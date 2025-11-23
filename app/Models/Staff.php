<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    protected $fillable = [
        'surname',
        'first_name',
        'photo_path', // Added for passport photo
        'pass_slip_path', // Added for file uploads (documents)
        'other_name',
        'sex',
        'gender',
        'date_of_birth',
        'hire_date',
        'uts_file_no',
        'district_file_no',
        'computer_no',
        'national_id_no',
        'registration_no',
        'salary_scale',
        'gross_salary',
        'net_salary',
        'tin_no',
        'date_of_1st_appt',
        'designation_of_1st_appt',
        'minute_no_1st_appt',
        'date_of_current_appt',
        'designation_of_current_appt',
        'minute_no_current_appt',
        'date_of_confirmation',
        'minute_no_confirmation',
        'date_of_current_posting',
        'teaching_subjects',
        'telephone_contacts',
        'password',
        'enable_teacher_login',
        'last_teacher_login_at',
        'marital_status',
        'religion', // Religion field
        'next_of_kin',
        'next_of_kin_telephone',
        'email',
        'other_academic_qualifications',
        'highest_level_of_education',
        'ipps_no',
        'medical_status', // Health field
        'physical_health', // Health field
        'staff_designation',
        'staff_type',
        'role',
        'employment_type',
        'department',
    ];

/**
 * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'hire_date' => 'date',
        'date_of_1st_appt' => 'date',
        'date_of_current_appt' => 'date',
        'date_of_confirmation' => 'date',
        'date_of_current_posting' => 'date',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    public function teacherSubjects()
    {
        return $this->hasMany(TeacherSubject::class);
    }

    public function marksEntries()
    {
        return $this->hasMany(MarksEntry::class, 'created_by');
    }

    public function academics()
    {
        return $this->belongsToMany(Academics::class, 'teacher_subjects', 'staff_id', 'academics_id');
    }

    public function olevelSubjects()
    {
        return $this->belongsToMany(OLevelSubject::class, 'teacher_subjects', 'staff_id', 'olevel_subject_id');
    }

    public function alevelSubjects()
    {
        return $this->belongsToMany(ALevelSubject::class, 'teacher_subjects', 'staff_id', 'alevel_subject_id');
    }

    /**
     * Get the display name for the staff member
     */
    public function getDisplayName()
    {
        $name = trim($this->first_name . ' ' . ($this->other_name ?? '') . ' ' . $this->surname);
        return !empty($name) ? $name : 'Unknown Staff';
    }

    /**
     * Get the sanitized owner name for file organization
     */
    public function getOwnerName()
    {
        $name = $this->surname . '_' . $this->first_name;
        $name = str_replace(' ', '_', $name);
        $name = preg_replace('/[^A-Za-z0-9_-]/', '', $name);
        return $name;
    }

    /**
     * Check if staff member has uploaded files
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
     * Update staff data
     */
    public function updateStaff($data)
    {
        return $this->update($data);
    }

    /**
     * Delete staff and related records
     */
    public function deleteStaff()
    {
        $this->teacherSubjects()->delete();
        $this->marksEntries()->delete();
        return $this->delete();
    }

    /**
     * Assign subject to staff
     */
    public function assignSubject($subjectData)
    {
        return $this->teacherSubjects()->create($subjectData);
    }

    /**
     * Remove subject assignment
     */
    public function removeSubject($subjectId)
    {
        return $this->teacherSubjects()->where('id', $subjectId)->delete();
    }
}
