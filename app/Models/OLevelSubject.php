<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OLevelSubject extends Model
{
    use HasFactory;

    protected $table = 'olevel_subjects';

    protected $fillable = [
        'academics_id',
        'subject_name',
        'subject_code',
        'category', // 'general' or 'optional'
        'classes', // JSON array of classes (S1, S2, S3, S4)
        'requires_practical', // for subjects like Biology, Chemistry, Physics
    ];

    protected $casts = [
        'classes' => 'array',
        'requires_practical' => 'boolean',
    ];

    public function academics()
    {
        return $this->belongsTo(Academics::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class, 'olevel_subject_id');
    }

    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class, 'olevel_subject_id');
    }
}
