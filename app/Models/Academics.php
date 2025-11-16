<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Academics extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function olevelSubjects()
    {
        return $this->hasMany(OLevelSubject::class);
    }

    public function alevelSubjects()
    {
        return $this->hasMany(ALevelSubject::class);
    }

    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class);
    }

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }
}
