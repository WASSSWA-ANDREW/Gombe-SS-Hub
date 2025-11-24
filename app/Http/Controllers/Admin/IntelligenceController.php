<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\PredictiveAnalyticsService;
use App\Services\AnomalyDetectionService;
use App\Services\RecommendationService;
use App\Services\SmartNotificationService;
use App\Models\Student;
use App\Models\Attendance;
use App\Models\PerformanceAnalytics;
use App\Models\AIPrediction;
use Illuminate\Http\Request;

class IntelligenceController extends Controller
{
    protected PredictiveAnalyticsService $analyticsService;
    protected AnomalyDetectionService $anomalyService;
    protected RecommendationService $recommendationService;
    protected SmartNotificationService $notificationService;

    public function __construct(
        PredictiveAnalyticsService $analyticsService,
        AnomalyDetectionService $anomalyService,
        RecommendationService $recommendationService,
        SmartNotificationService $notificationService
    ) {
        $this->analyticsService = $analyticsService;
        $this->anomalyService = $anomalyService;
        $this->recommendationService = $recommendationService;
        $this->notificationService = $notificationService;
    }

    public function dashboard()
    {
        $academicYear = now()->year;
        
        $studentCount = Student::count();
        $highRiskCount = AIPrediction::where('risk_level', 'high')->where('academic_year', $academicYear)->count();
        $avgAttendance = Attendance::where('academic_year', $academicYear)
            ->avg(\DB::raw('(present_count / total_students) * 100'));
        
        $predictions = AIPrediction::where('academic_year', $academicYear)->latest()->limit(10)->get();
        $recentNotifications = \App\Models\Notification::latest()->limit(5)->get();
        
        return view('admin.intelligence-dashboard', [
            'studentCount' => $studentCount,
            'highRiskCount' => $highRiskCount,
            'avgAttendance' => round($avgAttendance ?? 0, 2),
            'predictions' => $predictions,
            'recentNotifications' => $recentNotifications,
        ]);
    }

    public function studentPredictions(Request $request)
    {
        $level = $request->input('level', 'olevel');
        $class = $request->input('class', 'S3');

        $predictions = $this->analyticsService->predictClassPerformance($level, $class);
        
        $students = Student::where('level', $level)->where('class', $class)->get();
        $studentRisks = [];
        foreach ($students as $student) {
            $risk = $this->analyticsService->predictStudentDropoutRisk($student);
            $studentRisks[$student->id] = $risk['risk_level'];
        }

        return view('admin.intelligence.student-predictions', [
            'predictions' => $predictions,
            'studentRisks' => $studentRisks,
            'level' => $level,
            'class' => $class,
        ]);
    }

    public function performanceAnalytics(Request $request)
    {
        $level = $request->input('level', 'olevel');
        $class = $request->input('class', 'S3');
        $academicYear = $request->input('academic_year', now()->year);
        
        $analytics = PerformanceAnalytics::where('level', $level)
            ->where('class', $class)
            ->where('academic_year', $academicYear)
            ->get();
        
        $highPerformers = $analytics->where('performance_grade', 'A')->merge($analytics->where('performance_grade', 'B'));
        $lowPerformers = $analytics->where('performance_grade', 'E')->merge($analytics->where('performance_grade', 'F'));
        
        return view('admin.intelligence.performance-analytics', [
            'analytics' => $analytics,
            'highPerformers' => $highPerformers,
            'lowPerformers' => $lowPerformers,
            'level' => $level,
            'class' => $class,
        ]);
    }

    public function attendanceAnalysis(Request $request)
    {
        $level = $request->input('level', 'olevel');
        $class = $request->input('class', 'S3');
        $academicYear = $request->input('academic_year', now()->year);
        
        $classAttendances = Attendance::where('level', $level)
            ->where('class', $class)
            ->where('academic_year', $academicYear)
            ->orderBy('attendance_date', 'desc')
            ->get();
        
        $avgAttendanceRate = $classAttendances->avg(function ($record) {
            return $record->total_students > 0 ? ($record->present_count / $record->total_students) * 100 : 0;
        });
        
        $criticalDays = $classAttendances->filter(function ($record) {
            $rate = $record->total_students > 0 ? ($record->present_count / $record->total_students) * 100 : 0;
            return $rate < 70;
        })->take(10);
        
        return view('admin.intelligence.attendance-analysis', [
            'classAttendances' => $classAttendances,
            'avgAttendanceRate' => round($avgAttendanceRate ?? 0, 2),
            'criticalDays' => $criticalDays,
            'level' => $level,
            'class' => $class,
        ]);
    }

    public function anomalyReport()
    {
        $gradingAnomalies = $this->anomalyService->detectGradingAnomalies();
        $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies();
        $staffAnomalies = [];
        
        $staffMembers = \App\Models\Staff::all();
        foreach ($staffMembers as $staff) {
            $anomalies = $this->anomalyService->detectStaffBehaviorAnomalies($staff);
            if ($anomalies['anomalies_detected']) {
                $staffAnomalies[$staff->id] = $anomalies;
            }
        }

        return view('admin.intelligence.anomaly-report', [
            'gradingAnomalies' => $gradingAnomalies,
            'dataAnomalies' => $dataAnomalies,
            'staffAnomalies' => $staffAnomalies,
        ]);
    }

    public function recommendations()
    {
        $systemRecommendations = $this->recommendationService->getSystemWideRecommendations();
        
        $students = Student::limit(20)->get();
        $studentRecommendations = [];
        foreach ($students as $student) {
            $recs = $this->recommendationService->getStudentRecommendations($student);
            if ($recs['total_recommendations'] > 0) {
                $studentRecommendations[$student->id] = $recs;
            }
        }

        return view('admin.intelligence.recommendations', [
            'systemRecommendations' => $systemRecommendations,
            'studentRecommendations' => $studentRecommendations,
        ]);
    }

    public function notifications()
    {
        $notifications = \App\Models\Notification::orderBy('created_at', 'desc')
            ->limit(100)
            ->get();
        
        $unreadCount = \App\Models\Notification::where('read', false)->count();
        $highPriority = \App\Models\Notification::where('priority', 'high')->where('read', false)->get();

        return view('admin.intelligence.notifications', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
            'highPriority' => $highPriority,
        ]);
    }

    public function generateNotifications(Request $request)
    {
        $notificationsCreated = 0;
        
        $notificationsCreated += $this->notificationService->generateStudentRiskNotifications();
        $notificationsCreated += $this->notificationService->generatePerformanceTrendNotifications();
        $notificationsCreated += $this->notificationService->generateAnomalyAlerts();
        $notificationsCreated += $this->notificationService->generateRecommendationNotifications();
        
        return redirect()->back()->with('success', "Generated {$notificationsCreated} intelligent notifications");
    }

    public function systemHealth()
    {
        $academicYear = now()->year;
        
        $totalStudents = Student::count();
        $studentsWithPredictions = AIPrediction::where('academic_year', $academicYear)->distinct('student_id')->count('student_id');
        $highRiskStudents = AIPrediction::where('risk_level', 'high')->where('academic_year', $academicYear)->distinct('student_id')->count('student_id');
        
        $avgAttendance = Attendance::where('academic_year', $academicYear)
            ->avg(\DB::raw('(present_count / total_students) * 100'));
        
        $anomalyCount = \App\Models\AnomalyDetection::where('academic_year', $academicYear)->where('resolved', false)->count();
        
        $pendingRecommendations = \App\Models\Recommendation::where('implemented', false)->count();
        
        return view('admin.intelligence.system-health', [
            'totalStudents' => $totalStudents,
            'studentsWithPredictions' => $studentsWithPredictions,
            'highRiskStudents' => $highRiskStudents,
            'avgAttendance' => round($avgAttendance ?? 0, 2),
            'anomalyCount' => $anomalyCount,
            'pendingRecommendations' => $pendingRecommendations,
            'dataHealth' => round(($studentsWithPredictions / max($totalStudents, 1)) * 100, 2),
        ]);
    }
}
