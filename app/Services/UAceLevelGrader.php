<?php

namespace App\Services;

use App\Models\Student;
use App\Models\MarksEntry;
use App\Models\StudentPerformance;

class UAceLevelGrader
{
    public static function getUaceGrades(Student $student, int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $performances = StudentPerformance::where('student_id', $student->admission_number)
            ->where('level', 'alevel')
            ->where('academic_year', $academicYear)
            ->with(['alevelSubject'])
            ->get();

        $grades = [];
        foreach ($performances as $performance) {
            $subject = $performance->alevelSubject;
            if ($subject) {
                $grades[] = [
                    'subject_name' => $subject->subject_name,
                    'subject_code' => $subject->subject_code,
                    'category' => $subject->category,
                    'grade' => $performance->grade,
                    'uace_points' => $performance->uace_points,
                    'average_marks' => $performance->average_marks,
                ];
            }
        }

        return $grades;
    }

    public static function calculateUaceScore(Student $student, int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $performances = StudentPerformance::where('student_id', $student->admission_number)
            ->where('level', 'alevel')
            ->where('academic_year', $academicYear)
            ->with(['alevelSubject'])
            ->get();

        if ($performances->isEmpty()) {
            return [
                'student_id' => $student->admission_number,
                'student_name' => $student->student_name,
                'academic_year' => $academicYear,
                'total_score' => 0,
                'principal_passes' => 0,
                'subsidiary_passes' => 0,
                'qualifies_for_uace' => false,
                'all_grades' => [],
                'best_three' => [],
                'message' => 'No grades recorded',
            ];
        }

        $gradesSorted = $performances->map(function ($perf) {
            return [
                'subject_name' => $perf->alevelSubject?->subject_name ?? 'Unknown',
                'subject_code' => $perf->alevelSubject?->subject_code ?? '',
                'category' => $perf->alevelSubject?->category ?? 'general',
                'grade' => $perf->grade,
                'points' => self::gradeToPoints($perf->grade),
                'average_marks' => $perf->average_marks,
            ];
        })->sortByDesc('points');

        $principalPasses = $gradesSorted->filter(fn ($g) => in_array($g['category'], ['principal', 'general']) && self::gradeToPoints($g['grade']) > 0)->count();

        $bestThree = $gradesSorted->take(3)->toArray();
        $totalScore = collect($bestThree)->sum('points');

        $subsidiaryPasses = $gradesSorted->filter(fn ($g) => $g['category'] === 'subsidiary' && self::gradeToPoints($g['grade']) > 0)->count();

        $qualifiesForUace = $principalPasses >= 1;

        return [
            'student_id' => $student->admission_number,
            'student_name' => $student->student_name,
            'academic_year' => $academicYear,
            'total_score' => $totalScore,
            'principal_passes' => $principalPasses,
            'subsidiary_passes' => $subsidiaryPasses,
            'qualifies_for_uace' => $qualifiesForUace,
            'all_grades' => $gradesSorted->toArray(),
            'best_three' => $bestThree,
            'message' => $qualifiesForUace ? 'Qualifies for UACE certificate' : 'Does not qualify - must have at least one principal subject pass',
        ];
    }

    public static function gradeToPoints(string $grade): int
    {
        $pointMap = [
            'A' => 6,
            'B' => 5,
            'C' => 4,
            'D' => 3,
            'E' => 2,
            'O' => 1,
            'F' => 0,
        ];

        return $pointMap[$grade] ?? 0;
    }

    public static function pointsToGrade(int $points): string
    {
        return match ($points) {
            6 => 'A',
            5 => 'B',
            4 => 'C',
            3 => 'D',
            2 => 'E',
            1 => 'O',
            default => 'F',
        };
    }

    public static function generateUaceTranscript(Student $student, int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $uaceData = self::calculateUaceScore($student, $academicYear);

        return [
            'header' => [
                'school_name' => config('app.name'),
                'certificate_type' => 'Uganda Advanced Certificate of Education (UACE)',
                'student_name' => $student->student_name,
                'admission_number' => $student->admission_number,
                'academic_year' => $academicYear,
                'date_issued' => now()->format('Y-m-d'),
            ],
            'grades' => $uaceData['all_grades'],
            'summary' => [
                'total_subjects' => count($uaceData['all_grades']),
                'best_three_subjects' => $uaceData['best_three'],
                'total_score' => $uaceData['total_score'],
                'principal_passes' => $uaceData['principal_passes'],
                'subsidiary_passes' => $uaceData['subsidiary_passes'],
                'qualifies_for_certificate' => $uaceData['qualifies_for_uace'],
            ],
            'remarks' => $uaceData['message'],
        ];
    }

    public static function bulkCalculateUaceScores(array $studentIds, int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $results = [];
        foreach ($studentIds as $studentId) {
            $student = Student::find($studentId);
            if ($student) {
                $results[] = self::calculateUaceScore($student, $academicYear);
            }
        }

        return $results;
    }

    public static function getALevelClassStats(string $class, int $academicYear = null): array
    {
        $academicYear = $academicYear ?? now()->year;

        $students = Student::where('class', $class)
            ->where('level', 'alevel')
            ->get();

        $stats = [
            'total_students' => $students->count(),
            'students_with_complete_grades' => 0,
            'students_qualified_for_uace' => 0,
            'average_total_score' => 0,
            'students_data' => [],
        ];

        $totalScores = [];

        foreach ($students as $student) {
            $uaceData = self::calculateUaceScore($student, $academicYear);

            if (!empty($uaceData['all_grades'])) {
                $stats['students_with_complete_grades']++;

                if ($uaceData['qualifies_for_uace']) {
                    $stats['students_qualified_for_uace']++;
                }

                $totalScores[] = $uaceData['total_score'];

                $stats['students_data'][] = [
                    'student_name' => $student->student_name,
                    'admission_number' => $student->admission_number,
                    'total_score' => $uaceData['total_score'],
                    'principal_passes' => $uaceData['principal_passes'],
                    'qualifies' => $uaceData['qualifies_for_uace'],
                ];
            }
        }

        if (!empty($totalScores)) {
            $stats['average_total_score'] = round(array_sum($totalScores) / count($totalScores), 2);
        }

        return $stats;
    }
}
