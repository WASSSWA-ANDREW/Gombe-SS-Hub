<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentOptionalSubject extends Model
{
    protected $fillable = [
        'student_id',
        'olevel_subject_id',
        'alevel_subject_id',
        'level',
        'stream',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function olevelSubject()
    {
        return $this->belongsTo(OLevelSubject::class);
    }

    public function alevelSubject()
    {
        return $this->belongsTo(ALevelSubject::class);
    }
}
