<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeacherSubject extends Model
{
    use HasFactory;

    protected $table = 'teacher_subjects';

    protected $fillable = [
        'staff_id',
        'academics_id',
        'olevel_subject_id',
        'alevel_subject_id',
        'level', // 'olevel' or 'alevel'
        'specialty', // 'arts' or 'science' (for A'Level)
        'classes', // JSON array of classes they teach
    ];

    protected $casts = [
        'classes' => 'array',
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
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

    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class);
    }
}
