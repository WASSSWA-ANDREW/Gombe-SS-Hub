<?php

namespace App\Services;

use App\Models\Student;
use App\Models\MarksEntry;
use App\Models\Staff;
use App\Models\Attendance;
use Illuminate\Support\Collection;

class AnomalyDetectionService
{
    const ZSCORE_THRESHOLD = 2.5;
    const SUDDEN_CHANGE_THRESHOLD = 30;

    public function detectStudentPerformanceAnomalies(Student $student, string $level = 'olevel'): array
    {
        $marksEntries = MarksEntry::where('student_id', $student->id)
            ->where('level', $level)
            ->orderBy('created_at')
            ->limit(20)
            ->get();

        if ($marksEntries->count() < 2) {
            return [
                'student_id' => $student->id,
                'anomalies_detected' => false,
                'anomalies' => [],
            ];
        }

        $marks = $marksEntries->pluck('total_marks')->toArray();
        $anomalies = [];

        $zscore_anomalies = $this->detectZScoreAnomalies($marks);
        $anomalies = array_merge($anomalies, $zscore_anomalies);

        $sudden_change = $this->detectSuddenChange($marks);
        if ($sudden_change) {
            $anomalies[] = $sudden_change;
        }

        $consistency_anomaly = $this->detectInconsistency($marks);
        if ($consistency_anomaly) {
            $anomalies[] = $consistency_anomaly;
        }

        return [
            'student_id' => $student->id,
            'student_name' => $student->student_name,
            'level' => $level,
            'anomalies_detected' => !empty($anomalies),
            'anomaly_count' => count($anomalies),
            'anomalies' => $anomalies,
            'severity' => $this->calculateSeverity($anomalies),
        ];
    }

    public function detectGradingAnomalies(?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $marksData = MarksEntry::where('academic_year', $academicYear)
            ->groupBy('teacher_subject_id')
            ->selectRaw('teacher_subject_id, AVG(total_marks) as avg_marks, STDDEV(total_marks) as std_marks, COUNT(*) as mark_count')
            ->get();

        $anomalies = [];

        foreach ($marksData as $data) {
            if ($data->mark_count < 5) continue;

            if ($data->avg_marks > 95) {
                $anomalies[] = [
                    'type' => 'unusually_high_average',
                    'teacher_subject_id' => $data->teacher_subject_id,
                    'average_marks' => round($data->avg_marks, 2),
                    'count' => $data->mark_count,
                    'severity' => 'medium',
                    'description' => 'Grading average is unusually high. Please verify marking accuracy.',
                ];
            }

            if ($data->std_marks < 2) {
                $anomalies[] = [
                    'type' => 'low_marking_variance',
                    'teacher_subject_id' => $data->teacher_subject_id,
                    'standard_deviation' => round($data->std_marks, 2),
                    'count' => $data->mark_count,
                    'severity' => 'low',
                    'description' => 'Grading shows very low variance. Consider if assessment is too uniform.',
                ];
            }
        }

        return [
            'academic_year' => $academicYear,
            'anomalies_detected' => !empty($anomalies),
            'anomaly_count' => count($anomalies),
            'anomalies' => $anomalies,
        ];
    }

    public function detectStaffBehaviorAnomalies(Staff $staff, ?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $marksEntries = MarksEntry::where('created_by', $staff->id)
            ->where('academic_year', $academicYear)
            ->get();

        $anomalies = [];

        $bulkEntryTiming = $this->detectBulkDataEntry($marksEntries);
        if ($bulkEntryTiming) {
            $anomalies[] = $bulkEntryTiming;
        }

        if ($marksEntries->count() > 0) {
            $marksArray = $marksEntries->pluck('total_marks')->toArray();
            $gradeDistribution = $this->analyzeGradeDistribution($marksArray);
            if ($gradeDistribution['is_anomalous']) {
                $anomalies[] = [
                    'type' => 'unusual_grade_distribution',
                    'staff_id' => $staff->id,
                    'distribution' => $gradeDistribution['distribution'],
                    'severity' => 'medium',
                    'description' => 'Grade distribution is unusual compared to standards.',
                ];
            }
        }

        return [
            'staff_id' => $staff->id,
            'staff_name' => $staff->staff_name,
            'academic_year' => $academicYear,
            'anomalies_detected' => !empty($anomalies),
            'anomaly_count' => count($anomalies),
            'anomalies' => $anomalies,
        ];
    }

    public function detectDataEntryAnomalies(?int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $anomalies = [];

        $duplicateMarks = MarksEntry::where('academic_year', $academicYear)
            ->groupBy('student_id', 'teacher_subject_id', 'term', 'entry_type')
            ->havingRaw('COUNT(*) > 1')
            ->select('student_id', 'teacher_subject_id', 'term', 'entry_type')
            ->get();

        foreach ($duplicateMarks as $duplicate) {
            $anomalies[] = [
                'type' => 'duplicate_entry',
                'student_id' => $duplicate->student_id,
                'teacher_subject_id' => $duplicate->teacher_subject_id,
                'term' => $duplicate->term,
                'severity' => 'high',
                'description' => 'Duplicate mark entry detected for same student, subject, and term.',
            ];
        }

        $zeroMarksCount = MarksEntry::where('academic_year', $academicYear)
            ->where('total_marks', 0)
            ->count();

        if ($zeroMarksCount > (MarksEntry::where('academic_year', $academicYear)->count() * 0.1)) {
            $anomalies[] = [
                'type' => 'excessive_zero_marks',
                'zero_marks_count' => $zeroMarksCount,
                'percentage' => round(($zeroMarksCount / MarksEntry::where('academic_year', $academicYear)->count()) * 100, 2),
                'severity' => 'medium',
                'description' => 'Unusually high number of zero marks detected.',
            ];
        }

        return [
            'academic_year' => $academicYear,
            'anomalies_detected' => !empty($anomalies),
            'anomaly_count' => count($anomalies),
            'anomalies' => $anomalies,
        ];
    }

    private function detectZScoreAnomalies(array $marks): array
    {
        if (count($marks) < 3) {
            return [];
        }

        $mean = array_sum($marks) / count($marks);
        $variance = array_reduce($marks, fn ($carry, $mark) => $carry + pow($mark - $mean, 2), 0) / count($marks);
        $stdDev = sqrt($variance);

        if ($stdDev === 0) {
            return [];
        }

        $anomalies = [];
        foreach ($marks as $index => $mark) {
            $zscore = abs(($mark - $mean) / $stdDev);
            if ($zscore > self::ZSCORE_THRESHOLD) {
                $anomalies[] = [
                    'type' => 'outlier_mark',
                    'mark_value' => $mark,
                    'position' => $index + 1,
                    'zscore' => round($zscore, 2),
                    'severity' => 'medium',
                    'description' => 'Mark significantly deviates from student\'s normal performance.',
                ];
            }
        }

        return $anomalies;
    }

    private function detectSuddenChange(array $marks): ?array
    {
        if (count($marks) < 2) {
            return null;
        }

        foreach (array_slice($marks, 1) as $index => $mark) {
            $previousMark = $marks[$index];
            $changePercent = abs(($mark - $previousMark) / max($previousMark, 1)) * 100;

            if ($changePercent > self::SUDDEN_CHANGE_THRESHOLD) {
                return [
                    'type' => 'sudden_change',
                    'from_mark' => $previousMark,
                    'to_mark' => $mark,
                    'change_percent' => round($changePercent, 2),
                    'position' => $index + 2,
                    'severity' => 'high',
                    'description' => 'Sudden significant change in performance detected.',
                ];
            }
        }

        return null;
    }

    private function detectInconsistency(array $marks): ?array
    {
        if (count($marks) < 4) {
            return null;
        }

        $firstHalf = array_slice($marks, 0, (int)ceil(count($marks) / 2));
        $secondHalf = array_slice($marks, (int)ceil(count($marks) / 2));

        $firstAvg = array_sum($firstHalf) / count($firstHalf);
        $secondAvg = array_sum($secondHalf) / count($secondHalf);

        if ($firstAvg > 0) {
            $inconsistency = abs(($secondAvg - $firstAvg) / $firstAvg) * 100;

            if ($inconsistency > 40) {
                return [
                    'type' => 'performance_inconsistency',
                    'first_period_avg' => round($firstAvg, 2),
                    'second_period_avg' => round($secondAvg, 2),
                    'inconsistency_percent' => round($inconsistency, 2),
                    'severity' => 'medium',
                    'description' => 'Student\'s performance shows significant inconsistency over time.',
                ];
            }
        }

        return null;
    }

    private function detectBulkDataEntry(Collection $marksEntries): ?array
    {
        $entryTimes = $marksEntries->groupBy(fn ($entry) => $entry->created_at->format('Y-m-d H:00'))->map->count();

        $maxEntriesInHour = $entryTimes->max();
        $totalEntries = $marksEntries->count();

        if ($maxEntriesInHour > ($totalEntries * 0.3)) {
            return [
                'type' => 'bulk_data_entry',
                'entries_in_single_hour' => $maxEntriesInHour,
                'total_entries' => $totalEntries,
                'severity' => 'low',
                'description' => 'Large batch of marks entered in a short time period.',
            ];
        }

        return null;
    }

    private function analyzeGradeDistribution(array $marks): array
    {
        $gradeDistribution = [
            'A' => 0,
            'B' => 0,
            'C' => 0,
            'D' => 0,
            'E' => 0,
            'F' => 0,
        ];

        foreach ($marks as $mark) {
            if ($mark >= 90) $gradeDistribution['A']++;
            elseif ($mark >= 80) $gradeDistribution['B']++;
            elseif ($mark >= 70) $gradeDistribution['C']++;
            elseif ($mark >= 60) $gradeDistribution['D']++;
            elseif ($mark >= 50) $gradeDistribution['E']++;
            else $gradeDistribution['F']++;
        }

        $isAnomalous = ($gradeDistribution['A'] + $gradeDistribution['B']) > (count($marks) * 0.7);

        return [
            'distribution' => $gradeDistribution,
            'is_anomalous' => $isAnomalous,
        ];
    }

    private function calculateSeverity(array $anomalies): string
    {
        if (empty($anomalies)) {
            return 'none';
        }

        $severities = array_map(fn ($a) => $a['severity'] ?? 'low', $anomalies);

        if (in_array('high', $severities)) {
            return 'high';
        }

        if (in_array('medium', $severities)) {
            return 'medium';
        }

        return 'low';
    }
}
