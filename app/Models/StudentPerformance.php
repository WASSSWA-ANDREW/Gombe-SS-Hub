<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentPerformance extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'olevel_subject_id',
        'alevel_subject_id',
        'level',
        'class',
        'stream',
        'academic_year',
        'term',
        'average_marks',
        'highest_marks',
        'lowest_marks',
        'performance_trend',
        'grade',
        'uace_points',
        'subject_category',
    ];

    protected $casts = [
        'average_marks' => 'decimal:2',
        'highest_marks' => 'decimal:2',
        'lowest_marks' => 'decimal:2',
        'performance_trend' => 'decimal:2',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function olevelSubject()
    {
        return $this->belongsTo(OLevelSubject::class);
    }

    public function alevelSubject()
    {
        return $this->belongsTo(ALevelSubject::class);
    }

    public function getSubjectNameAttribute()
    {
        if ($this->level === 'olevel') {
            return $this->olevelSubject->subject_name ?? 'Unknown Subject';
        }

        return $this->alevelSubject->subject_name ?? 'Unknown Subject';
    }

    public function calculatePerformanceTrend($previousMarks, $currentMarks)
    {
        if ($previousMarks == 0) {
            return 0;
        }

        return (($currentMarks - $previousMarks) / $previousMarks) * 100;
    }
}
