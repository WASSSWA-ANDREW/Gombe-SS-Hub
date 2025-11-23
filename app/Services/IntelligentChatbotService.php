<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Staff;
use App\Models\MarksEntry;
use App\Models\StudentPerformance;

class IntelligentChatbotService
{
    protected PredictiveAnalyticsService $analyticsService;
    protected RecommendationService $recommendationService;
    protected AnomalyDetectionService $anomalyService;

    public function __construct(
        PredictiveAnalyticsService $analyticsService,
        RecommendationService $recommendationService,
        AnomalyDetectionService $anomalyService
    ) {
        $this->analyticsService = $analyticsService;
        $this->recommendationService = $recommendationService;
        $this->anomalyService = $anomalyService;
    }

    public function handleQuery(string $query, ?int $userId = null): array
    {
        $query = strtolower(trim($query));
        
        if ($this->isStudentPerformanceQuery($query)) {
            return $this->handleStudentPerformanceQuery($query, $userId);
        }
        
        if ($this->isRecommendationQuery($query)) {
            return $this->handleRecommendationQuery($query, $userId);
        }
        
        if ($this->isRiskAssessmentQuery($query)) {
            return $this->handleRiskAssessmentQuery($query);
        }
        
        if ($this->isClassAnalysisQuery($query)) {
            return $this->handleClassAnalysisQuery($query);
        }
        
        if ($this->isStaffAnalysisQuery($query)) {
            return $this->handleStaffAnalysisQuery($query);
        }

        if ($this->isAnomalyQuery($query)) {
            return $this->handleAnomalyQuery($query);
        }
        
        return $this->handleGeneralQuery($query);
    }

    private function handleStudentPerformanceQuery(string $query, ?int $userId): array
    {
        preg_match('/student|name\s+(\w+(?:\s+\w+)?)/i', $query, $matches);
        
        $students = Student::all();
        $results = [];

        foreach ($students as $student) {
            $prediction = $this->analyticsService->predictStudentGradeTrend($student);
            
            if ($prediction['prediction_available']) {
                $results[] = [
                    'student' => $student->student_name,
                    'current_average' => $prediction['current_average'],
                    'predicted_grade' => $prediction['predicted_next_grade'],
                    'trend' => $prediction['trend_direction'],
                    'confidence' => $prediction['confidence_score'],
                ];
            }
        }

        if (empty($results)) {
            return [
                'type' => 'student_performance',
                'response' => 'No student performance data available at the moment.',
                'data' => [],
            ];
        }

        return [
            'type' => 'student_performance',
            'response' => $this->formatPerformanceResponse($results),
            'data' => $results,
        ];
    }

    private function handleRecommendationQuery(string $query, ?int $userId): array
    {
        preg_match('/student|name\s+(\w+(?:\s+\w+)?)/i', $query, $matches);
        
        $students = Student::all();
        $allRecommendations = [];

        foreach ($students as $student) {
            $recommendations = $this->recommendationService->getStudentRecommendations($student);
            if ($recommendations['priority_level'] === 'high') {
                $allRecommendations[] = [
                    'student' => $student->student_name,
                    'recommendations' => $recommendations['recommendations'],
                    'priority' => $recommendations['priority_level'],
                ];
            }
        }

        if (empty($allRecommendations)) {
            return [
                'type' => 'recommendations',
                'response' => 'No urgent recommendations at this time. All students are performing within acceptable ranges.',
                'data' => [],
            ];
        }

        return [
            'type' => 'recommendations',
            'response' => $this->formatRecommendationResponse($allRecommendations),
            'data' => $allRecommendations,
        ];
    }

    private function handleRiskAssessmentQuery(string $query): array
    {
        $students = Student::all();
        $riskAnalysis = [
            'high_risk' => [],
            'medium_risk' => [],
            'low_risk' => [],
        ];

        foreach ($students as $student) {
            $risk = $this->analyticsService->predictStudentDropoutRisk($student);
            
            if ($risk['risk_level'] === 'high') {
                $riskAnalysis['high_risk'][] = [
                    'student' => $student->student_name,
                    'risk_score' => $risk['risk_score'],
                    'factors' => $risk['factors'],
                ];
            } elseif ($risk['risk_level'] === 'medium') {
                $riskAnalysis['medium_risk'][] = $student->student_name;
            }
        }

        return [
            'type' => 'risk_assessment',
            'response' => $this->formatRiskResponse($riskAnalysis),
            'data' => $riskAnalysis,
            'summary' => [
                'total_high_risk' => count($riskAnalysis['high_risk']),
                'total_medium_risk' => count($riskAnalysis['medium_risk']),
            ],
        ];
    }

    private function handleClassAnalysisQuery(string $query): array
    {
        preg_match('/class\s*([^\s]*)/i', $query, $classMatch);
        preg_match('/level|o.level|a.level/i', $query, $levelMatch);
        
        $level = $levelMatch ? (strpos($levelMatch[0], 'a') === 0 ? 'alevel' : 'olevel') : 'olevel';
        $class = $classMatch[1] ?? 'S3';

        $classPerformance = $this->analyticsService->predictClassPerformance($level, $class);
        
        return [
            'type' => 'class_analysis',
            'response' => $this->formatClassAnalysisResponse($classPerformance),
            'data' => $classPerformance,
        ];
    }

    private function handleStaffAnalysisQuery(string $query): array
    {
        $staff = Staff::all();
        $staffAnalysis = [];

        foreach ($staff as $staffMember) {
            $performance = $this->analyticsService->predictStaffPerformance($staffMember);
            $staffAnalysis[] = [
                'name' => $staffMember->staff_name,
                'performance_score' => $performance['performance_score'],
                'performance_level' => $performance['performance_level'],
                'students_served' => $performance['students_served'],
            ];
        }

        usort($staffAnalysis, fn ($a, $b) => $b['performance_score'] <=> $a['performance_score']);

        return [
            'type' => 'staff_analysis',
            'response' => $this->formatStaffAnalysisResponse($staffAnalysis),
            'data' => $staffAnalysis,
        ];
    }

    private function handleAnomalyQuery(string $query): array
    {
        $gradingAnomalies = $this->anomalyService->detectGradingAnomalies();
        $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies();
        
        $allAnomalies = [
            'grading_anomalies' => $gradingAnomalies['anomalies'],
            'data_entry_anomalies' => $dataAnomalies['anomalies'],
        ];

        if (!$gradingAnomalies['anomalies_detected'] && !$dataAnomalies['anomalies_detected']) {
            return [
                'type' => 'anomaly_report',
                'response' => 'System is operating normally. No anomalies detected.',
                'data' => [],
            ];
        }

        return [
            'type' => 'anomaly_report',
            'response' => $this->formatAnomalyResponse($allAnomalies),
            'data' => $allAnomalies,
        ];
    }

    private function handleGeneralQuery(string $query): array
    {
        $responses = [
            'help' => 'I can help you with: student performance analysis, recommendations, risk assessment, class analysis, staff analysis, and anomaly detection. What would you like to know?',
            'hello' => 'Hello! I\'m the Gombe SS Hub Pro intelligent assistant. How can I help you today?',
            'hi' => 'Hi there! I can provide analysis on student performance, recommendations, risks, class statistics, and staff performance.',
            'status' => 'System is operating normally. All services are available.',
        ];

        foreach ($responses as $keyword => $response) {
            if (strpos($query, $keyword) !== false) {
                return [
                    'type' => 'general_response',
                    'response' => $response,
                    'data' => [],
                ];
            }
        }

        return [
            'type' => 'general_response',
            'response' => 'I didn\'t quite understand that. Try asking about student performance, recommendations, risk assessment, class analysis, staff analysis, or anomalies.',
            'data' => [],
        ];
    }

    private function isStudentPerformanceQuery(string $query): bool
    {
        return (bool) preg_match('/(student|performance|grades?|marks?)/i', $query);
    }

    private function isRecommendationQuery(string $query): bool
    {
        return (bool) preg_match('/(recommend|suggestion|advice|what should)/i', $query);
    }

    private function isRiskAssessmentQuery(string $query): bool
    {
        return (bool) preg_match('/(risk|at risk|dropout|danger|failure)/i', $query);
    }

    private function isClassAnalysisQuery(string $query): bool
    {
        return (bool) preg_match('/(class|s[0-9]|s[0-9][a-z]?)/i', $query);
    }

    private function isStaffAnalysisQuery(string $query): bool
    {
        return (bool) preg_match('/(staff|teacher|teaching|performance)/i', $query);
    }

    private function isAnomalyQuery(string $query): bool
    {
        return (bool) preg_match('/(anomal|unusual|suspicious|problem|issue)/i', $query);
    }

    private function formatPerformanceResponse(array $results): string
    {
        $response = "Here's the current performance summary:\n\n";
        
        foreach (array_slice($results, 0, 5) as $result) {
            $response .= "📊 {$result['student']}\n";
            $response .= "  • Current Average: {$result['current_average']}/100\n";
            $response .= "  • Predicted Grade: {$result['predicted_grade']}\n";
            $response .= "  • Trend: {$result['trend']}\n";
            $response .= "  • Confidence: {$result['confidence']}%\n\n";
        }

        if (count($results) > 5) {
            $response .= "... and " . (count($results) - 5) . " more students.\n";
        }

        return $response;
    }

    private function formatRecommendationResponse(array $recommendations): string
    {
        $response = "⚠️ Urgent Recommendations:\n\n";
        
        foreach (array_slice($recommendations, 0, 3) as $rec) {
            $response .= "👤 {$rec['student']}\n";
            foreach (array_slice($rec['recommendations'], 0, 2) as $r) {
                $response .= "  • {$r['description']}\n";
            }
            $response .= "\n";
        }

        return $response;
    }

    private function formatRiskResponse(array $riskAnalysis): string
    {
        $response = "🚨 Risk Assessment Report:\n\n";
        $response .= "HIGH RISK (" . count($riskAnalysis['high_risk']) . "):\n";
        
        foreach (array_slice($riskAnalysis['high_risk'], 0, 5) as $student) {
            $response .= "  ❌ {$student['student']} (Score: {$student['risk_score']})\n";
        }

        $response .= "\nMEDIUM RISK (" . count($riskAnalysis['medium_risk']) . ")\n";
        $response .= "INTERVENTION RECOMMENDED\n";

        return $response;
    }

    private function formatClassAnalysisResponse(array $classPerformance): string
    {
        $response = "📚 Class Analysis: {$classPerformance['level']} - {$classPerformance['class']}\n\n";
        $response .= "Class Average: {$classPerformance['class_average']}/100\n";
        $response .= "Total Students: {$classPerformance['total_students']}\n";
        $response .= "High Performers: {$classPerformance['performance_distribution']['high_performers']}\n";
        $response .= "Average Performers: {$classPerformance['performance_distribution']['average_performers']}\n";
        $response .= "Low Performers: {$classPerformance['performance_distribution']['low_performers']}\n";
        $response .= "Students at Risk: {$classPerformance['students_at_risk']}\n";

        return $response;
    }

    private function formatStaffAnalysisResponse(array $staffAnalysis): string
    {
        $response = "👨‍🏫 Staff Performance Analysis:\n\n";
        
        foreach (array_slice($staffAnalysis, 0, 5) as $staff) {
            $emoji = $staff['performance_level'] === 'excellent' ? '⭐' : ($staff['performance_level'] === 'good' ? '✅' : '⚠️');
            $response .= "{$emoji} {$staff['name']}\n";
            $response .= "  Score: {$staff['performance_score']}/100\n";
            $response .= "  Students: {$staff['students_served']}\n\n";
        }

        return $response;
    }

    private function formatAnomalyResponse(array $anomalies): string
    {
        $response = "🔍 Anomaly Report:\n\n";
        
        if (!empty($anomalies['grading_anomalies'])) {
            $response .= "Grading Anomalies: " . count($anomalies['grading_anomalies']) . "\n";
        }
        
        if (!empty($anomalies['data_entry_anomalies'])) {
            $response .= "Data Entry Anomalies: " . count($anomalies['data_entry_anomalies']) . "\n";
        }

        $response .= "\n⚠️ Review admin panel for detailed investigation.\n";

        return $response;
    }
}
