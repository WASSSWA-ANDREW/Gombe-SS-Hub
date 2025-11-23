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

    public function studentPerformances()
    {
        return $this->hasManyThrough(
            StudentPerformance::class,
            MarksEntry::class,
            'academics_id',
            'id'
        );
    }

    public function getRequiredGeneralSubjectsForClass($class)
    {
        return $this->olevelSubjects()
            ->where('category', 'general')
            ->whereJsonContains('classes', $class)
            ->get();
    }

    public function getRequiredOptionalSubjectsForClass($class)
    {
        return $this->olevelSubjects()
            ->where('category', 'optional')
            ->whereJsonContains('classes', $class)
            ->get();
    }

    public function getS3S4GeneralSubjects()
    {
        return $this->olevelSubjects()
            ->where('category', 'general')
            ->whereJsonContains('classes', 'S3')
            ->orWhere(function ($query) {
                $query->where('category', 'general')
                    ->whereJsonContains('classes', 'S4');
            })
            ->get();
    }

    public function getS3S4OptionalSubjects()
    {
        return $this->olevelSubjects()
            ->where('category', 'optional')
            ->where(function ($query) {
                $query->whereJsonContains('classes', 'S3')
                    ->orWhereJsonContains('classes', 'S4');
            })
            ->get();
    }

    public function ensureMarksEntriesExist($student, $academicYear = null)
    {
        $academicYear = $academicYear ?? now()->year;

        if ($student->level !== 'olevel') {
            return false;
        }

        $class = $student->class;

        $generalSubjects = $this->getRequiredGeneralSubjectsForClass($class);
        $optionalSubjects = $student->optionalSubjects()
            ->where('level', 'olevel')
            ->with('olevelSubject')
            ->get();

        $allSubjectsIds = $generalSubjects->pluck('id')->toArray();
        foreach ($optionalSubjects as $optSub) {
            if ($optSub->olevel_subject_id) {
                $allSubjectsIds[] = $optSub->olevel_subject_id;
            }
        }

        $existingMarks = MarksEntry::where('student_id', $student->admission_number)
            ->where('level', 'olevel')
            ->where('academic_year', $academicYear)
            ->get();

        return [
            'required_subjects_count' => count($allSubjectsIds),
            'marks_entry_count' => $existingMarks->count(),
            'missing_subjects' => count($allSubjectsIds) - $existingMarks->count(),
            'is_complete' => count($allSubjectsIds) === $existingMarks->count(),
        ];
    }

    public function getALevelSubjects($class = null)
    {
        return $this->alevelSubjects()
            ->when($class, function ($q) use ($class) {
                $q->whereJsonContains('classes', $class);
            })
            ->get();
    }

    public function getALevelPrincipalSubjects($class = null)
    {
        return $this->alevelSubjects()
            ->where('category', 'principal')
            ->when($class, function ($q) use ($class) {
                $q->whereJsonContains('classes', $class);
            })
            ->get();
    }

    public function getALevelSubsidiarySubjects($class = null)
    {
        return $this->alevelSubjects()
            ->where('category', 'subsidiary')
            ->when($class, function ($q) use ($class) {
                $q->whereJsonContains('classes', $class);
            })
            ->get();
    }
}
