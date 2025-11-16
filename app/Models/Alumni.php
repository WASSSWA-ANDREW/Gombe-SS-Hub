<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'student_name',
        'photo_path',
        'gender',
        'learners_lin',
        'learners_nin',
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
        'pass_slip_path',
        'medical_status',
        'physical_health',
        'father_full_name',
        'father_mobile_number',
        'father_email',
        'father_nin',
        'father_physical_address',
        'father_occupation',
        'father_dead_alive',
        'mother_full_name',
        'mother_mobile_number',
        'mother_email',
        'mother_nin',
        'mother_physical_address',
        'mother_occupation',
        'mother_dead_alive',
        'guardian_full_name',
        'guardian_mobile_number',
        'guardian_email',
        'guardian_nin',
        'guardian_physical_address',
        'guardian_occupation',
        'guardian_relationship',
        'official_comment',
        'level',
        'graduation_class',
        'graduation_year',
        'stream',
        'student_id', // Reference to original student record
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_of_birth' => 'date',
        'graduation_year' => 'integer',
    ];

    /**
     * Get the display name for the alumni
     */
    public function getDisplayName()
    {
        return $this->student_name ?? 'Unknown Alumni';
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
     * Check if alumni has uploaded files
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
     * Get the original student record
     */
    public function originalStudent()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
