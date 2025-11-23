<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Staff;
use App\Models\MarksEntry;
use App\Models\StudentPerformance;

class RecommendationService
{
    protected PredictiveAnalyticsService $analyticsService;
    protected AnomalyDetectionService $anomalyService;

    public function __construct(PredictiveAnalyticsService $analyticsService, AnomalyDetectionService $anomalyService)
    {
        $this->analyticsService = $analyticsService;
        $this->anomalyService = $anomalyService;
    }

    public function getStudentRecommendations(Student $student, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;
        $recommendations = [];

        $performancePrediction = $this->analyticsService->predictStudentGradeTrend($student, 'olevel', $academicYear);
        if ($performancePrediction['prediction_available']) {
            $recommendations = array_merge($recommendations, $this->generatePerformanceRecommendations($performancePrediction));
        }

        $riskAssessment = $this->analyticsService->predictStudentDropoutRisk($student);
        if ($riskAssessment['risk_level'] !== 'low') {
            $recommendations = array_merge($recommendations, $this->generateRiskMitigationRecommendations($riskAssessment));
        }

        $anomalies = $this->anomalyService->detectStudentPerformanceAnomalies($student);
        if ($anomalies['anomalies_detected']) {
            $recommendations = array_merge($recommendations, $this->generateAnomalyRecommendations($anomalies));
        }

        return [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'academic_year' => $academicYear,
            'total_recommendations' => count($recommendations),
            'recommendations' => array_values($recommendations),
            'priority_level' => $this->calculateOverallPriority($recommendations),
        ];
    }

    public function getClassRecommendations(string $level, string $class, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;
        $recommendations = [];

        $classPerformance = $this->analyticsService->predictClassPerformance($level, $class, $academicYear);
        
        if ($classPerformance['students_at_risk'] > 0) {
            $recommendations[] = [
                'type' => 'intervention_required',
                'priority' => 'high',
                'description' => "Class has {$classPerformance['students_at_risk']} students at risk. Implement targeted intervention program.",
                'action' => 'Schedule remedial sessions, contact parents, assign mentors',
                'affected_students' => $classPerformance['students_at_risk'],
            ];
        }

        if ($classPerformance['class_average'] < 60) {
            $recommendations[] = [
                'type' => 'curriculum_review',
                'priority' => 'high',
                'description' => "Class average is {$classPerformance['class_average']}%. Consider curriculum review.",
                'action' => 'Meet with subject teachers, identify difficult topics, adjust teaching methods',
                'gap_percentage' => 60 - $classPerformance['class_average'],
            ];
        }

        if ($classPerformance['performance_distribution']['high_performers'] < $classPerformance['total_students'] * 0.2) {
            $recommendations[] = [
                'type' => 'gifted_program',
                'priority' => 'medium',
                'description' => 'Low percentage of high performers. Consider enrichment programs.',
                'action' => 'Identify advanced learners, provide extension activities, advanced materials',
                'estimated_high_performers' => ceil($classPerformance['total_students'] * 0.2),
            ];
        }

        $resourceAllocation = $this->analyticsService->predictResourceAllocation($level, $class, $academicYear);
        $recommendations[] = [
            'type' => 'resource_allocation',
            'priority' => 'medium',
            'description' => 'Resource allocation recommendation for optimal support.',
            'allocation' => $resourceAllocation['recommended_allocation'],
            'estimated_budget' => $resourceAllocation['estimated_budget_impact']['total_estimated_cost'],
        ];

        return [
            'level' => $level,
            'class' => $class,
            'academic_year' => $academicYear,
            'total_recommendations' => count($recommendations),
            'recommendations' => $recommendations,
        ];
    }

    public function getStaffRecommendations(Staff $staff, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;
        $recommendations = [];

        $performanceAnalysis = $this->analyticsService->predictStaffPerformance($staff, $academicYear);
        
        if ($performanceAnalysis['performance_level'] === 'needs_improvement') {
            $recommendations[] = [
                'type' => 'professional_development',
                'priority' => 'high',
                'description' => 'Staff performance is below expected standards.',
                'action' => 'Provide training, mentoring, and performance support',
                'performance_gap' => 50 - $performanceAnalysis['performance_score'],
            ];
        }

        $anomalies = $this->anomalyService->detectStaffBehaviorAnomalies($staff, $academicYear);
        if ($anomalies['anomalies_detected']) {
            foreach ($anomalies['anomalies'] as $anomaly) {
                $recommendations[] = [
                    'type' => 'behavior_investigation',
                    'priority' => $anomaly['severity'] === 'high' ? 'high' : 'medium',
                    'description' => $anomaly['description'],
                    'anomaly_type' => $anomaly['type'],
                    'action' => 'Review marking patterns, provide guidance, monitor closely',
                ];
            }
        }

        if ($performanceAnalysis['performance_level'] === 'excellent') {
            $recommendations[] = [
                'type' => 'recognition_and_mentorship',
                'priority' => 'low',
                'description' => 'Staff demonstrates excellent performance.',
                'action' => 'Consider for advanced roles, pair with struggling teachers as mentor',
            ];
        }

        return [
            'staff_id' => $staff->id,
            'staff_name' => $staff->staff_name,
            'academic_year' => $academicYear,
            'performance_level' => $performanceAnalysis['performance_level'],
            'total_recommendations' => count($recommendations),
            'recommendations' => $recommendations,
        ];
    }

    public function getSystemWideRecommendations(?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;
        $recommendations = [];

        $gradingAnomalies = $this->anomalyService->detectGradingAnomalies($academicYear);
        if ($gradingAnomalies['anomalies_detected']) {
            $recommendations[] = [
                'type' => 'grading_quality_audit',
                'priority' => 'high',
                'description' => "Found {$gradingAnomalies['anomaly_count']} grading anomalies.",
                'action' => 'Conduct audit of marking patterns, provide teacher training',
                'anomaly_details' => $gradingAnomalies['anomalies'],
            ];
        }

        $dataAnomalies = $this->anomalyService->detectDataEntryAnomalies($academicYear);
        if ($dataAnomalies['anomalies_detected']) {
            $recommendations[] = [
                'type' => 'data_quality_improvement',
                'priority' => 'high',
                'description' => "Found {$dataAnomalies['anomaly_count']} data entry issues.",
                'action' => 'Resolve duplicate entries, implement data validation, retrain staff',
                'anomaly_count' => $dataAnomalies['anomaly_count'],
            ];
        }

        $students = Student::all();
        $highRiskCount = 0;
        foreach ($students as $student) {
            $risk = $this->analyticsService->predictStudentDropoutRisk($student);
            if ($risk['risk_level'] === 'high') {
                $highRiskCount++;
            }
        }

        if ($highRiskCount > (count($students) * 0.1)) {
            $recommendations[] = [
                'type' => 'student_retention_program',
                'priority' => 'high',
                'description' => "High number of at-risk students ({$highRiskCount}).",
                'action' => 'Implement comprehensive student support program, counseling services',
                'at_risk_percentage' => round(($highRiskCount / count($students)) * 100, 2),
            ];
        }

        return [
            'academic_year' => $academicYear,
            'total_recommendations' => count($recommendations),
            'recommendations' => $recommendations,
        ];
    }

    private function generatePerformanceRecommendations(array $prediction): array
    {
        $recommendations = [];

        if ($prediction['trend_direction'] === 'declining') {
            $recommendations[] = [
                'type' => 'academic_support',
                'priority' => 'high',
                'description' => 'Student performance is declining. Immediate intervention needed.',
                'action' => 'Arrange tutoring sessions, identify learning difficulties, provide resources',
            ];
        }

        if ($prediction['trend_direction'] === 'improving') {
            $recommendations[] = [
                'type' => 'positive_reinforcement',
                'priority' => 'low',
                'description' => 'Student is showing improvement. Continue current support.',
                'action' => 'Recognize progress, maintain current strategies, encourage consistency',
            ];
        }

        if ($prediction['risk_level'] === 'high') {
            $recommendations[] = [
                'type' => 'grade_intervention',
                'priority' => 'high',
                'description' => 'Current grade trajectory indicates possible failure.',
                'action' => 'Set realistic goals, break down topics, provide additional practice',
            ];
        }

        return $recommendations;
    }

    private function generateRiskMitigationRecommendations(array $risk): array
    {
        $recommendations = [];

        foreach ($risk['factors'] as $factor) {
            $recommendations[] = [
                'type' => 'risk_mitigation',
                'priority' => $risk['risk_level'] === 'high' ? 'high' : 'medium',
                'description' => "Address: {$factor}",
                'action' => $risk['recommendation'],
                'risk_factor' => $factor,
            ];
        }

        return $recommendations;
    }

    private function generateAnomalyRecommendations(array $anomalies): array
    {
        $recommendations = [];

        foreach ($anomalies['anomalies'] as $anomaly) {
            $recommendations[] = [
                'type' => 'performance_anomaly',
                'priority' => $anomaly['severity'] ?? 'medium',
                'description' => $anomaly['description'] ?? 'Unusual performance pattern detected',
                'action' => 'Investigate cause, provide targeted support, monitor closely',
                'anomaly_type' => $anomaly['type'] ?? 'unknown',
            ];
        }

        return $recommendations;
    }

    private function calculateOverallPriority(array $recommendations): string
    {
        $priorities = array_map(fn ($r) => $r['priority'] ?? 'low', $recommendations);

        if (in_array('high', $priorities)) {
            return 'high';
        }

        if (in_array('medium', $priorities)) {
            return 'medium';
        }

        return 'low';
    }
}
