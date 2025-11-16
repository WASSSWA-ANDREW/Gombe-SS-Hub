<?php

namespace App\Models;

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
        'created_by', // Staff ID of the teacher who entered
        'entered_at',
    ];

    protected $casts = [
        'entered_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
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
}
