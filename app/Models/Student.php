<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_name',
        'photo_path', // Added for passport photo
        'gender',
        'learners_lin',
        'learners_nin',
        'admission_number',
        'date_of_birth',
        'religion',
        'mobile_number',
        'email',
        'district_of_birth',
        'district',
        'nationality',
        'tribe',
        'previous_school',
        'ple_index_number',
        'uce_index_number',
        'special_issue',
        'ple_english',
        'ple_mathematics',
        'ple_sst',
        'ple_science',
        'ple_total',
        'ple_aggregates',
        'uce_english',
        'uce_mathematics',
        'uce_physics',
        'uce_chemistry',
        'uce_biology',
        'uce_history',
        'uce_geography',
        'uce_economics',
        'uce_literature',
        'uce_other',
        'combination',
        'pass_slip_path', // Added to store path of uploaded pass slip
        'medical_status', // Health field
        'physical_health', // Health field
        'father_full_name',
        'father_mobile_number',
        'father_email',
        'father_nin',
        'father_physical_address',
        'father_occupation',
        'father_dead_alive',
        'father_passport_photo_path',
        'mother_full_name',
        'mother_mobile_number',
        'mother_email',
        'mother_nin',
        'mother_physical_address',
        'mother_occupation',
        'mother_dead_alive',
        'mother_passport_photo_path',
        'guardian_full_name',
        'guardian_mobile_number',
        'guardian_email',
        'guardian_nin',
        'guardian_physical_address',
        'guardian_occupation',
        'guardian_relationship',
        'guardian_passport_photo_path',
        'official_comment',
        'level', // Assuming you have a 'level' column to distinguish O'Level, A'Level, etc.
        'class',
        'stream',
        // Add any other fields that you expect to be mass assignable
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        // Add other casts if needed, e.g., for boolean or json fields
    ];

    // Relationships
    /**
     * Get the discipline track records for this student
     */
    public function disciplineTracks()
    {
        return $this->hasMany(DisciplineTrack::class);
    }

    /**
     * Get the counselling track records for this student
     */
    public function counsellingTracks()
    {
        return $this->hasMany(CounsellingTrack::class);
    }

    /**
     * Get the optional/subsidiary subjects for this student
     */
    public function optionalSubjects()
    {
        return $this->hasMany(StudentOptionalSubject::class);
    }

    /**
     * Get O'Level optional subjects
     */
    public function olevelOptionalSubjects()
    {
        return $this->optionalSubjects()
            ->where('level', 'olevel')
            ->whereNotNull('olevel_subject_id');
    }

    /**
     * Get A'Level subsidiary subjects
     */
    public function alevelSubsidiarySubjects()
    {
        return $this->optionalSubjects()
            ->where('level', 'alevel')
            ->whereNotNull('alevel_subject_id');
    }

    /**
     * Get marks entries for this student
     */
    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class);
    }

    /**
     * Get the display name for the student
     */
    public function getDisplayName()
    {
        return $this->student_name ?? 'Unknown Student';
    }

    /**
     * Get the sanitized owner name for file organization
     */
    public function getOwnerName()
    {
        $name = $this->student_name;
        $name = str_replace(' ', '_', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);
        return $name;
    }

    /**
     * Check if student has uploaded files
     */
    public function hasFiles()
    {
        return !is_null($this->pass_slip_path) && !empty($this->pass_slip_path);
    }

    /**
     * Get the file path for storage
     */
    public function getFilePath()
    {
        return $this->pass_slip_path;
    }

    /**
     * Update student data
     */
    public function updateStudent($data)
    {
        return $this->update($data);
    }

    /**
     * Delete student and related records
     */
    public function deleteStudent()
    {
        $this->optionalSubjects()->delete();
        $this->marksEntries()->delete();
        $this->disciplineTracks()->delete();
        $this->counsellingTracks()->delete();
        return $this->delete();
    }

    /**
     * Add optional subject
     */
    public function addOptionalSubject($subjectData)
    {
        return $this->optionalSubjects()->create($subjectData);
    }

    /**
     * Remove optional subject
     */
    public function removeOptionalSubject($subjectId)
    {
        return $this->optionalSubjects()->where('id', $subjectId)->delete();
    }
}