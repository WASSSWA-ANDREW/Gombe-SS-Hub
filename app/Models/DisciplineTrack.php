<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplineTrack extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'case_name',
        'disciplinary_action',
        'resolution',
        'case_status',
        'date_of_incident',
        'description',
        'recorded_by',
        'attachments',
        'statement_type'
    ];

    protected $casts = [
        'date_of_incident' => 'date',
        'attachments' => 'array',
    ];

    /**
     * Get the student that owns this discipline track record
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    /**
     * Get the staff member who recorded this discipline track record
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'recorded_by');
    }

    /**
     * Get the display name for the discipline action
     */
    public function getDisciplinaryActionDisplayAttribute(): string
    {
        return match($this->disciplinary_action) {
            'statement_letter' => 'Statement Letter',
            'cautions' => 'Cautions',
            'active_punishment' => 'Active Punishment',
            default => ucfirst(str_replace('_', ' ', $this->disciplinary_action))
        };
    }

    /**
     * Get the display name for the resolution
     */
    public function getResolutionDisplayAttribute(): string
    {
        return match($this->resolution) {
            'suspension' => 'Suspension',
            'expulsion' => 'Expulsion',
            default => $this->resolution ? ucfirst($this->resolution) : 'N/A'
        };
    }

    /**
     * Get the display name for the case status
     */
    public function getCaseStatusDisplayAttribute(): string
    {
        return match($this->case_status) {
            'pending' => 'Pending',
            'sorted' => 'Sorted',
            default => ucfirst($this->case_status)
        };
    }
}
