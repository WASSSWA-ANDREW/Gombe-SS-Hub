<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Staff;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class SmartNotificationService
{
    protected PredictiveAnalyticsService $analyticsService;
    protected AnomalyDetectionService $anomalyService;
    protected RecommendationService $recommendationService;

    public function __construct(
        PredictiveAnalyticsService $analyticsService,
        AnomalyDetectionService $anomalyService,
        RecommendationService $recommendationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->anomalyService = $anomalyService;
        $this->recommendationService = $recommendationService;
    }

    public function generateStudentRiskNotifications(?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $students = Student::all();

        foreach ($students as $student) {
            $riskAssessment = $this->analyticsService->predictStudentDropoutRisk($student);

            if ($riskAssessment['risk_level'] === 'high') {
                $this->createNotification(
                    'high_risk_student',
                    "Student {$student->student_name} is at high risk of academic failure.",
                    'high',
                    ['student_id' => $student->id, 'risk_score' => $riskAssessment['risk_score']],
                    'admin'
                );
                $notificationsCreated++;
            } elseif ($riskAssessment['risk_level'] === 'medium') {
                $this->createNotification(
                    'medium_risk_student',
                    "Student {$student->student_name} requires monitoring.",
                    'medium',
                    ['student_id' => $student->id, 'risk_score' => $riskAssessment['risk_score']],
                    'teacher'
                );
                $notificationsCreated++;
            }
        }

        return $notificationsCreated;
    }

    public function generatePerformanceTrendNotifications(?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $students = Student::all();

        foreach ($students as $student) {
            $prediction = $this->analyticsService->predictStudentGradeTrend($student, 'olevel', $academicYear);

            if ($prediction['prediction_available'] && $prediction['trend_direction'] === 'declining') {
                $this->createNotification(
                    'performance_decline',
                    "Student {$student->student_name}'s performance is declining (Trend: {$prediction['trend']}%).",
                    'high',
                    ['student_id' => $student->id, 'trend' => $prediction['trend']],
                    'teacher'
                );
                $notificationsCreated++;
            } elseif ($prediction['prediction_available'] && $prediction['trend_direction'] === 'improving') {
                $this->createNotification(
                    'performance_improvement',
                    "Great! Student {$student->student_name}'s performance is improving.",
                    'low',
                    ['student_id' => $student->id, 'trend' => $prediction['trend']],
                    'admin'
                );
                $notificationsCreated++;
            }
        }

        return $notificationsCreated;
    }

    public function generateAnomalyAlerts(?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $gradingAnomalies = $this->anomalyService->detectGradingAnomalies($academicYear);
        if ($gradingAnomalies['anomalies_detected']) {
            foreach ($gradingAnomalies['anomalies'] as $anomaly) {
                $this->createNotification(
                    'grading_anomaly',
                    "Grading anomaly detected: {$anomaly['description']}",
                    'medium',
                    ['anomaly_type' => $anomaly['type']],
                    'admin'
                );
                $notificationsCreated++;
            }
        }

        $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies($academicYear);
        if ($dataAnomalies['anomalies_detected']) {
            foreach ($dataAnomalies['anomalies'] as $anomaly) {
                $this->createNotification(
                    'data_anomaly',
                    "Data entry anomaly: {$anomaly['description']}",
                    'high',
                    ['anomaly_type' => $anomaly['type']],
                    'admin'
                );
                $notificationsCreated++;
            }
        }

        return $notificationsCreated;
    }

    public function generateRecommendationNotifications(?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $students = Student::all();
        foreach ($students as $student) {
            $recommendations = $this->recommendationService->getStudentRecommendations($student, $academicYear);
            
            if ($recommendations['priority_level'] === 'high' && !empty($recommendations['recommendations'])) {
                $this->createNotification(
                    'urgent_recommendations',
                    "Urgent recommendations for student {$student->student_name}. Review dashboard for details.",
                    'high',
                    ['student_id' => $student->id, 'recommendation_count' => count($recommendations['recommendations'])],
                    'teacher'
                );
                $notificationsCreated++;
            }
        }

        return $notificationsCreated;
    }

    public function sendClassNotifications(string $level, string $class, ?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $classRecommendations = $this->recommendationService->getClassRecommendations($level, $class, $academicYear);
        
        foreach ($classRecommendations['recommendations'] as $recommendation) {
            $priority = $recommendation['priority'] === 'high' ? 'high' : 'medium';
            
            $this->createNotification(
                'class_recommendation',
                "Class {$class} ({$level}): {$recommendation['description']}",
                $priority,
                ['class' => $class, 'level' => $level, 'recommendation_type' => $recommendation['type']],
                'admin'
            );
            $notificationsCreated++;
        }

        return $notificationsCreated;
    }

    public function sendStaffPerformanceNotifications(?int $academicYear = null): int
    {
        $academicYear = $academicYear ?? now()->year;
        $notificationsCreated = 0;

        $staff = Staff::all();
        foreach ($staff as $staffMember) {
            $recommendations = $this->recommendationService->getStaffRecommendations($staffMember, $academicYear);
            
            if (!empty($recommendations['recommendations'])) {
                foreach ($recommendations['recommendations'] as $recommendation) {
                    $priority = $recommendation['priority'] === 'high' ? 'high' : 'medium';
                    
                    $this->createNotification(
                        'staff_performance_update',
                        "Staff {$staffMember->staff_name}: {$recommendation['description']}",
                        $priority,
                        ['staff_id' => $staffMember->id, 'recommendation_type' => $recommendation['type']],
                        'admin'
                    );
                    $notificationsCreated++;
                }
            }
        }

        return $notificationsCreated;
    }

    public function getNotificationsForUser(User $user): array
    {
        $userRole = $user->role ?? 'user';
        
        $notifications = Notification::where('recipient_role', $userRole)
            ->orWhere('recipient_role', 'all')
            ->where('read', false)
            ->orderBy('priority')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();

        return [
            'user_id' => $user->id,
            'total_unread' => $notifications->count(),
            'by_priority' => [
                'high' => $notifications->where('priority', 'high')->count(),
                'medium' => $notifications->where('priority', 'medium')->count(),
                'low' => $notifications->where('priority', 'low')->count(),
            ],
            'notifications' => $notifications->map(fn ($n) => [
                'id' => $n->id,
                'type' => $n->notification_type,
                'title' => $this->getTitleForNotificationType($n->notification_type),
                'message' => $n->message,
                'priority' => $n->priority,
                'data' => json_decode($n->data, true),
                'created_at' => $n->created_at,
                'read' => $n->read,
            ])->toArray(),
        ];
    }

    public function markNotificationAsRead(int $notificationId): bool
    {
        $notification = Notification::find($notificationId);
        if ($notification) {
            $notification->update(['read' => true]);
            return true;
        }
        return false;
    }

    public function clearOldNotifications(int $daysOld = 30): int
    {
        return Notification::where('created_at', '<', now()->subDays($daysOld))
            ->where('read', true)
            ->delete();
    }

    private function createNotification(string $type, string $message, string $priority, array $data, string $recipientRole): void
    {
        Notification::create([
            'notification_type' => $type,
            'message' => $message,
            'priority' => $priority,
            'data' => json_encode($data),
            'recipient_role' => $recipientRole,
            'read' => false,
        ]);
    }

    private function getTitleForNotificationType(string $type): string
    {
        $titles = [
            'high_risk_student' => 'High Risk Student Alert',
            'medium_risk_student' => 'Student Monitoring Required',
            'performance_decline' => 'Performance Decline Alert',
            'performance_improvement' => 'Positive Performance Update',
            'grading_anomaly' => 'Grading Anomaly Detected',
            'data_anomaly' => 'Data Entry Anomaly',
            'urgent_recommendations' => 'Urgent Recommendations',
            'class_recommendation' => 'Class-Level Recommendation',
            'staff_performance_update' => 'Staff Performance Update',
        ];

        return $titles[$type] ?? ucwords(str_replace('_', ' ', $type));
    }
}
