<?php

namespace App\Models;

use App\Services\UAceLevelGrader;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarksEntry extends Model
{
    use HasFactory;

    protected $table = 'marks_entries';

    protected $fillable = [
        'student_id',
        'teacher_subject_id',
        'academics_id',
        'olevel_subject_id',
        'alevel_subject_id',
        'level', // 'olevel' or 'alevel'
        'class',
        'stream',
        'term',
        'academic_year',
        'entry_type', // 'beginning_of_term', 'activities_of_integration', 'test', 'end_of_term'
        'activity_number', // For activities of integration (1-4)
        'test_number', // For tests (1-2)
        'theory_marks', // For theory-based subjects
        'practical_marks', // For practical-based subjects
        'total_marks',
        'grade',
        'uace_points',
        'is_principal_subject',
        'created_by', // Staff ID of the teacher who entered
        'entered_at',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function teacherSubject()
    {
        return $this->belongsTo(TeacherSubject::class);
    }

    public function academics()
    {
        return $this->belongsTo(Academics::class);
    }

    public function olevelSubject()
    {
        return $this->belongsTo(OLevelSubject::class);
    }

    public function alevelSubject()
    {
        return $this->belongsTo(ALevelSubject::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(Staff::class, 'created_by');
    }

    public static function boot()
    {
        parent::boot();

        static::creating(function ($marksEntry) {
            $marksEntry->assignUacePoints();
        });

        static::updating(function ($marksEntry) {
            $marksEntry->assignUacePoints();
        });

        static::created(function ($marksEntry) {
            $marksEntry->updateStudentPerformance();
        });

        static::updated(function ($marksEntry) {
            $marksEntry->updateStudentPerformance();
        });
    }

    public function updateStudentPerformance()
    {
        $subjectId = $this->olevel_subject_id ?? $this->alevel_subject_id;
        $level = $this->level;

        $allMarks = MarksEntry::where('student_id', $this->student_id)
            ->where('level', $level)
            ->when($this->olevel_subject_id, function ($q) use ($subjectId) {
                $q->where('olevel_subject_id', $subjectId);
            })
            ->when($this->alevel_subject_id, function ($q) use ($subjectId) {
                $q->where('alevel_subject_id', $subjectId);
            })
            ->orderBy('created_at')
            ->get();

        if ($allMarks->isEmpty()) {
            return;
        }

        $averageMarks = $allMarks->avg('total_marks');
        $highestMarks = $allMarks->max('total_marks');
        $lowestMarks = $allMarks->min('total_marks');

        $performanceTrend = 0;
        if ($allMarks->count() > 1) {
            $marks = $allMarks->pluck('total_marks')->toArray();
            $firstMarks = reset($marks);
            $lastMarks = end($marks);
            if ($firstMarks > 0) {
                $performanceTrend = (($lastMarks - $firstMarks) / $firstMarks) * 100;
            }
        }

        $grade = $this->calculateGrade($averageMarks);
        $uacePoints = null;
        $subjectCategory = null;

        if ($level === 'alevel' && $this->alevel_subject_id) {
            $subject = ALevelSubject::find($this->alevel_subject_id);
            if ($subject) {
                $subjectCategory = $subject->category;
                $uacePoints = self::gradeToUacePoints($grade);
            }
        }

        StudentPerformance::updateOrCreate(
            [
                'student_id' => $this->student_id,
                'olevel_subject_id' => $this->olevel_subject_id,
                'alevel_subject_id' => $this->alevel_subject_id,
                'level' => $level,
                'academic_year' => $this->academic_year,
            ],
            [
                'class' => $this->class,
                'stream' => $this->stream,
                'term' => $this->term,
                'average_marks' => $averageMarks,
                'highest_marks' => $highestMarks,
                'lowest_marks' => $lowestMarks,
                'performance_trend' => $performanceTrend,
                'grade' => $grade,
                'uace_points' => $uacePoints,
                'subject_category' => $subjectCategory,
            ]
        );
    }

    private function calculateGrade($marks)
    {
        if ($this->level === 'alevel') {
            return $this->calculateALevelGrade($marks);
        }

        if ($marks >= 90) {
            return 'A';
        }
        if ($marks >= 80) {
            return 'B';
        }
        if ($marks >= 70) {
            return 'C';
        }
        if ($marks >= 60) {
            return 'D';
        }
        if ($marks >= 50) {
            return 'E';
        }

        return 'F';
    }

    public function calculateALevelGrade($marks)
    {
        if ($marks >= 90) {
            return 'A';
        }
        if ($marks >= 80) {
            return 'B';
        }
        if ($marks >= 70) {
            return 'C';
        }
        if ($marks >= 60) {
            return 'D';
        }
        if ($marks >= 50) {
            return 'E';
        }

        return 'F';
    }

    public static function gradeToUacePoints($grade)
    {
        $gradePoints = [
            'A' => 6,
            'B' => 5,
            'C' => 4,
            'D' => 3,
            'E' => 2,
            'O' => 1,
            'F' => 0,
        ];

        return $gradePoints[$grade] ?? 0;
    }

    public function assignUacePoints()
    {
        if ($this->level === 'alevel' && $this->grade) {
            $this->uace_points = self::gradeToUacePoints($this->grade);
            
            if ($this->alevel_subject_id) {
                $subject = ALevelSubject::find($this->alevel_subject_id);
                if ($subject) {
                    $this->is_principal_subject = $subject->category === 'principal' || $subject->category === 'general';
                }
            }
            
            return $this->uace_points;
        }

        return 0;
    }

    public static function getMarksForStudentSubject($studentId, $subjectId, $level, $academicYear = null)
    {
        $academicYear = $academicYear ?? now()->year;

        return self::where('student_id', $studentId)
            ->where('level', $level)
            ->where('academic_year', $academicYear)
            ->when($level === 'olevel', function ($q) use ($subjectId) {
                $q->where('olevel_subject_id', $subjectId);
            })
            ->when($level === 'alevel', function ($q) use ($subjectId) {
                $q->where('alevel_subject_id', $subjectId);
            })
            ->get();
    }

    public static function checkS3S4StudentMarksCompletion($studentId, $academicYear = null)
    {
        $academicYear = $academicYear ?? now()->year;

        $student = Student::find($studentId);

        if (! $student || $student->level !== 'olevel' || ! in_array($student->class, ['S3', 'S4'])) {
            return null;
        }

        $academics = new Academics;
        $generalSubjects = $academics->getRequiredGeneralSubjectsForClass($student->class);
        $optionalSubjects = $student->optionalSubjects()
            ->where('level', 'olevel')
            ->get();

        $results = [];

        foreach ($generalSubjects as $subject) {
            $marks = self::getMarksForStudentSubject($studentId, $subject->id, 'olevel', $academicYear);
            $results[$subject->subject_name] = [
                'has_marks' => $marks->isNotEmpty(),
                'marks_count' => $marks->count(),
                'subject_id' => $subject->id,
                'is_optional' => false,
            ];
        }

        foreach ($optionalSubjects as $optSub) {
            if ($optSub->olevel_subject_id) {
                $subject = OLevelSubject::find($optSub->olevel_subject_id);
                if ($subject) {
                    $marks = self::getMarksForStudentSubject($studentId, $subject->id, 'olevel', $academicYear);
                    $results[$subject->subject_name] = [
                        'has_marks' => $marks->isNotEmpty(),
                        'marks_count' => $marks->count(),
                        'subject_id' => $subject->id,
                        'is_optional' => true,
                    ];
                }
            }
        }

        $allComplete = array_every($results, fn ($result) => $result['has_marks']);

        return [
            'student_id' => $studentId,
            'student_name' => $student->student_name,
            'class' => $student->class,
            'academic_year' => $academicYear,
            'subjects_status' => $results,
            'total_subjects' => count($results),
            'subjects_with_marks' => count(array_filter($results, fn ($r) => $r['has_marks'])),
            'is_complete' => $allComplete,
        ];
    }

    public static function getS3S4StudentsMarksStatus($academicYear = null)
    {
        $academicYear = $academicYear ?? now()->year;

        $s3s4Students = Student::whereIn('class', ['S3', 'S4'])
            ->where('level', 'olevel')
            ->get();

        $statusReport = [];
        foreach ($s3s4Students as $student) {
            $status = self::checkS3S4StudentMarksCompletion($student->admission_number, $academicYear);
            if ($status) {
                $statusReport[] = $status;
            }
        }

        return [
            'academic_year' => $academicYear,
            'total_s3s4_students' => count($s3s4Students),
            'students_with_complete_marks' => count(array_filter($statusReport, fn ($s) => $s['is_complete'])),
            'students_with_incomplete_marks' => count(array_filter($statusReport, fn ($s) => ! $s['is_complete'])),
            'student_details' => $statusReport,
        ];
    }
}
