<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounsellingTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'counselling_type',
        'date_of_session',
        'notes',
        'outcome',
        'status',
        'counsellor_id'
    ];

    protected $casts = [
        'date_of_session' => 'date',
    ];

    /**
     * Get the student that owns this counselling track record
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the counsellor (staff member) for this counselling session
     */
    public function counsellor(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'counsellor_id');
    }

    /**
     * Get the display name for the counselling type
     */
    public function getCounsellingTypeDisplayAttribute(): string
    {
        return match($this->counselling_type) {
            'life' => 'Life Counselling',
            'academic' => 'Academic Counselling',
            'behavior' => 'Behavioral Counselling',
            'gender' => 'Gender Counselling',
            'character' => 'Character Development',
            'sex' => 'Sexual Health Education',
            default => ucfirst($this->counselling_type)
        };
    }

    /**
     * Get the display name for the status
     */
    public function getStatusDisplayAttribute(): string
    {
        return match($this->status) {
            'ongoing' => 'Ongoing',
            'completed' => 'Completed',
            default => ucfirst($this->status)
        };
    }
}
