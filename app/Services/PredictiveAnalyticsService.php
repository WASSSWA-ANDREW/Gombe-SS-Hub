<?php

namespace App\Services;

use App\Models\Student;
use App\Models\MarksEntry;
use App\Models\StudentPerformance;
use App\Models\Staff;
use Illuminate\Support\Collection;

class PredictiveAnalyticsService
{
    const HIGH_PERFORMER_THRESHOLD = 75;
    const LOW_PERFORMER_THRESHOLD = 50;
    const IMPROVEMENT_THRESHOLD = 10;
    const DECLINE_THRESHOLD = -10;

    public function predictStudentGradeTrend(Student $student, string $level = 'olevel', ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;
        
        $performances = StudentPerformance::where('student_id', $student->id)
            ->where('level', $level)
            ->where('academic_year', $academicYear)
            ->orderBy('created_at')
            ->get();

        if ($performances->isEmpty()) {
            return [
                'student_id' => $student->id,
                'student_name' => $student->student_name,
                'level' => $level,
                'academic_year' => $academicYear,
                'prediction_available' => false,
                'message' => 'Insufficient data for prediction',
            ];
        }

        $marks = $performances->pluck('average_marks')->toArray();
        $trend = $this->calculateTrend($marks);
        $predictedGrade = $this->predictNextGrade($marks, $trend);
        $confidenceScore = $this->calculateConfidenceScore($marks, $performances->count());

        $riskLevel = $this->assessRiskLevel($marks, $trend);

        return [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'level' => $level,
            'academic_year' => $academicYear,
            'current_average' => end($marks) ?? 0,
            'trend' => $trend,
            'trend_direction' => $trend > self::IMPROVEMENT_THRESHOLD ? 'improving' : ($trend < self::DECLINE_THRESHOLD ? 'declining' : 'stable'),
            'predicted_next_grade' => $predictedGrade,
            'confidence_score' => $confidenceScore,
            'risk_level' => $riskLevel,
            'prediction_available' => true,
        ];
    }

    public function predictClassPerformance(string $level, string $class, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $students = Student::where('level', $level)
            ->where('class', $class)
            ->get();

        $predictions = [];
        $totalAverage = 0;
        $performanceDistribution = [
            'high_performers' => 0,
            'average_performers' => 0,
            'low_performers' => 0,
        ];

        foreach ($students as $student) {
            $prediction = $this->predictStudentGradeTrend($student, $level, $academicYear);
            if ($prediction['prediction_available']) {
                $predictions[] = $prediction;
                $totalAverage += $prediction['current_average'];

                if ($prediction['current_average'] >= self::HIGH_PERFORMER_THRESHOLD) {
                    $performanceDistribution['high_performers']++;
                } elseif ($prediction['current_average'] >= self::LOW_PERFORMER_THRESHOLD) {
                    $performanceDistribution['average_performers']++;
                } else {
                    $performanceDistribution['low_performers']++;
                }
            }
        }

        $classAverage = count($predictions) > 0 ? $totalAverage / count($predictions) : 0;

        return [
            'level' => $level,
            'class' => $class,
            'academic_year' => $academicYear,
            'total_students' => $students->count(),
            'students_with_predictions' => count($predictions),
            'class_average' => round($classAverage, 2),
            'performance_distribution' => $performanceDistribution,
            'students_at_risk' => count(array_filter($predictions, fn ($p) => $p['risk_level'] === 'high')),
            'high_performers' => count(array_filter($predictions, fn ($p) => $p['risk_level'] === 'low')),
            'detailed_predictions' => $predictions,
        ];
    }

    public function predictStudentDropoutRisk(Student $student): array
    {
        $marksEntries = MarksEntry::where('student_id', $student->id)
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        if ($marksEntries->isEmpty()) {
            return [
                'student_id' => $student->id,
                'risk_score' => 0,
                'risk_level' => 'low',
                'factors' => [],
            ];
        }

        $factors = [];
        $riskScore = 0;

        $avgMarks = $marksEntries->avg('total_marks');
        if ($avgMarks < self::LOW_PERFORMER_THRESHOLD) {
            $riskScore += 25;
            $factors[] = 'Low academic performance';
        }

        $recentMarks = $marksEntries->slice(-5);
        $trendPercentage = $this->calculateTrend($recentMarks->pluck('total_marks')->toArray());
        if ($trendPercentage < self::DECLINE_THRESHOLD) {
            $riskScore += 20;
            $factors[] = 'Declining performance trend';
        }

        $absentCount = 0;
        if ($riskScore > 0) {
            $riskScore = min($riskScore, 100);
        }

        $riskLevel = $riskScore >= 70 ? 'high' : ($riskScore >= 40 ? 'medium' : 'low');

        return [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'risk_score' => $riskScore,
            'risk_level' => $riskLevel,
            'factors' => $factors,
            'recommendation' => $this->generateRiskMitigation($riskLevel, $factors),
        ];
    }

    public function predictStaffPerformance(Staff $staff, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $marksEntered = MarksEntry::where('created_by', $staff->id)
            ->where('academic_year', $academicYear)
            ->count();

        $studentsServed = MarksEntry::where('created_by', $staff->id)
            ->where('academic_year', $academicYear)
            ->distinct('student_id')
            ->count('student_id');

        $avgStudentPerformance = MarksEntry::where('created_by', $staff->id)
            ->where('academic_year', $academicYear)
            ->avg('total_marks') ?? 0;

        $performanceScore = min(($marksEntered / max($studentsServed, 1)) * 50 + ($avgStudentPerformance / 2), 100);

        return [
            'staff_id' => $staff->id,
            'staff_name' => $staff->staff_name,
            'academic_year' => $academicYear,
            'marks_entered' => $marksEntered,
            'students_served' => $studentsServed,
            'average_student_performance' => round($avgStudentPerformance, 2),
            'performance_score' => round($performanceScore, 2),
            'performance_level' => $performanceScore >= 75 ? 'excellent' : ($performanceScore >= 50 ? 'good' : 'needs_improvement'),
        ];
    }

    public function predictResourceAllocation(string $level, string $class, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $classPerformance = $this->predictClassPerformance($level, $class, $academicYear);
        $studentsAtRisk = $classPerformance['students_at_risk'];
        $totalStudents = $classPerformance['total_students'];

        $resourceAllocation = [
            'tutorial_sessions' => max(2, ceil($studentsAtRisk / 5)),
            'remedial_classes' => max(1, ceil($studentsAtRisk / 10)),
            'mentorship_pairs' => ceil($studentsAtRisk / 3),
            'additional_teaching_materials' => ceil($totalStudents / 10),
            'priority_subjects' => $this->identifyPrioritySubjects($level, $class, $academicYear),
        ];

        return [
            'level' => $level,
            'class' => $class,
            'academic_year' => $academicYear,
            'students_at_risk' => $studentsAtRisk,
            'recommended_allocation' => $resourceAllocation,
            'estimated_budget_impact' => $this->estimateBudgetImpact($resourceAllocation),
        ];
    }

    private function calculateTrend(array $marks): float
    {
        if (count($marks) < 2) {
            return 0;
        }

        $first = reset($marks);
        $last = end($marks);

        if ($first == 0) {
            return $last > 0 ? 100 : 0;
        }

        return (($last - $first) / $first) * 100;
    }

    private function predictNextGrade(array $marks, float $trend): string
    {
        $currentAvg = end($marks) ?? 0;
        $predictedAvg = $currentAvg + ($currentAvg * $trend / 100);

        if ($predictedAvg >= 90) return 'A';
        if ($predictedAvg >= 80) return 'B';
        if ($predictedAvg >= 70) return 'C';
        if ($predictedAvg >= 60) return 'D';
        if ($predictedAvg >= 50) return 'E';
        return 'F';
    }

    private function calculateConfidenceScore(array $marks, int $dataPoints): float
    {
        $baseConfidence = min(($dataPoints / 10) * 100, 95);
        $variance = $this->calculateVariance($marks);
        $stabilityBonus = $variance < 10 ? 5 : 0;

        return min($baseConfidence + $stabilityBonus, 100);
    }

    private function calculateVariance(array $marks): float
    {
        if (empty($marks)) return 0;
        
        $avg = array_sum($marks) / count($marks);
        $sumSquaredDiff = array_reduce($marks, fn ($sum, $mark) => $sum + pow($mark - $avg, 2), 0);
        
        return sqrt($sumSquaredDiff / count($marks));
    }

    private function assessRiskLevel(array $marks, float $trend): string
    {
        $currentAvg = end($marks) ?? 0;

        if ($currentAvg < self::LOW_PERFORMER_THRESHOLD && $trend < self::DECLINE_THRESHOLD) {
            return 'high';
        }

        if ($currentAvg < self::LOW_PERFORMER_THRESHOLD || $trend < self::DECLINE_THRESHOLD) {
            return 'medium';
        }

        return 'low';
    }

    private function generateRiskMitigation(string $riskLevel, array $factors): string
    {
        if ($riskLevel === 'high') {
            return 'Urgent intervention required. Contact student and parents immediately. Enroll in remedial program.';
        }

        if ($riskLevel === 'medium') {
            return 'Provide additional tutoring and monitoring. Schedule regular check-ins with student.';
        }

        return 'Continue regular academic monitoring and encouragement.';
    }

    private function identifyPrioritySubjects(string $level, string $class, int $academicYear): array
    {
        $marks = MarksEntry::where('level', $level)
            ->where('class', $class)
            ->where('academic_year', $academicYear)
            ->groupBy($level === 'olevel' ? 'olevel_subject_id' : 'alevel_subject_id')
            ->selectRaw('AVG(total_marks) as avg_marks, ' . ($level === 'olevel' ? 'olevel_subject_id' : 'alevel_subject_id') . ' as subject_id')
            ->orderBy('avg_marks')
            ->limit(3)
            ->get();

        return $marks->pluck('subject_id')->toArray();
    }

    private function estimateBudgetImpact(array $allocation): array
    {
        return [
            'tutorial_sessions_cost' => $allocation['tutorial_sessions'] * 5000,
            'remedial_classes_cost' => $allocation['remedial_classes'] * 10000,
            'mentorship_cost' => $allocation['mentorship_pairs'] * 2000,
            'materials_cost' => $allocation['additional_teaching_materials'] * 3000,
            'total_estimated_cost' => ($allocation['tutorial_sessions'] * 5000) + ($allocation['remedial_classes'] * 10000) + ($allocation['mentorship_pairs'] * 2000) + ($allocation['additional_teaching_materials'] * 3000),
        ];
    }
}
