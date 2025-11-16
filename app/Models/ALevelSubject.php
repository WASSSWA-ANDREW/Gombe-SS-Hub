<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ALevelSubject extends Model
{
    use HasFactory;

    protected $table = 'alevel_subjects';

    protected $fillable = [
        'academics_id',
        'subject_name',
        'subject_code',
        'stream', // 'arts', 'science', or 'general'
        'category', // 'principal', 'subsidiary', or 'general'
        'classes', // JSON array of classes (S5, S6)
    ];

    protected $casts = [
        'classes' => 'array',
    ];

    public function academics()
    {
        return $this->belongsTo(Academics::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'alevel_subject_id');
    }

    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class, 'alevel_subject_id');
    }
}
