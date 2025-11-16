<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * DisciplineRecord Model
 * 
 * Unified model for holding both discipline and counselling information
 * about students under disciplinary actions or counselling sessions
 */
class DisciplineRecord extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'student_id',
        'staff_id',
        'record_type',              // 'discipline' or 'counselling'
        'title',                    // Case name or counselling title
        'description',              // Detailed description
        'category',                 // Action/counselling type
        'sub_category',             // Sub-classification
        'severity_level',           // Low, Medium, High, Critical
        'status',                   // Pending, Resolved, Ongoing, Completed
        'date_recorded',
        'date_of_incident',
        'resolution_notes',
        'follow_up_date',
        'assigned_to',              // Staff ID who's handling it
        'outcome',
        'attachments',              // JSON field for file paths
        'tags',                     // JSON for categorization
        'is_confidential',
        'priority',                 // 1-5 (1 being lowest)
    ];

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'date_recorded' => 'datetime',
        'date_of_incident' => 'date',
        'follow_up_date' => 'date',
        'is_confidential' => 'boolean',
        'attachments' => 'array',
        'tags' => 'array',
    ];

    /**
     * Get the student associated with this record
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get the staff member who recorded this
     */
    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'staff_id');
    }

    /**
     * Get the staff member assigned to handle this
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(Staff::class, 'assigned_to');
    }

    /**
     * Get severity level badge
     */
    public function getSeverityBadgeAttribute(): array
    {
        return match($this->severity_level) {
            'low' => ['color' => 'green', 'icon' => '📊', 'label' => 'Low'],
            'medium' => ['color' => 'yellow', 'icon' => '⚠️', 'label' => 'Medium'],
            'high' => ['color' => 'orange', 'icon' => '🔴', 'label' => 'High'],
            'critical' => ['color' => 'red', 'icon' => '🚨', 'label' => 'Critical'],
            default => ['color' => 'gray', 'icon' => '◯', 'label' => 'Unknown'],
        };
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): array
    {
        return match($this->status) {
            'pending' => ['color' => 'yellow', 'icon' => '⏱️', 'label' => 'Pending'],
            'resolved' => ['color' => 'green', 'icon' => '✓', 'label' => 'Resolved'],
            'ongoing' => ['color' => 'blue', 'icon' => '⟳', 'label' => 'Ongoing'],
            'completed' => ['color' => 'emerald', 'icon' => '✔', 'label' => 'Completed'],
            'dismissed' => ['color' => 'gray', 'icon' => '✕', 'label' => 'Dismissed'],
            default => ['color' => 'gray', 'icon' => '•', 'label' => ucfirst($this->status)],
        };
    }

    /**
     * Check if record is overdue for follow-up
     */
    public function isOverdueAttribute(): bool
    {
        return $this->follow_up_date && $this->follow_up_date->isPast() && $this->status !== 'resolved';
    }

    /**
     * Get priority label
     */
    public function getPriorityLabelAttribute(): string
    {
        return match($this->priority) {
            1 => '⬜ Low',
            2 => '🟩 Normal',
            3 => '🟨 Medium',
            4 => '🟧 High',
            5 => '🟥 Critical',
            default => 'Unknown',
        };
    }

    /**
     * Scope: Get only discipline records
     */
    public function scopeDiscipline($query)
    {
        return $query->where('record_type', 'discipline');
    }

    /**
     * Scope: Get only counselling records
     */
    public function scopeCounselling($query)
    {
        return $query->where('record_type', 'counselling');
    }

    /**
     * Scope: Get only pending records
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope: Get overdue records
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotNull('follow_up_date')
                    ->where('follow_up_date', '<', now())
                    ->whereNotIn('status', ['resolved', 'completed']);
    }

    /**
     * Scope: Get critical records
     */
    public function scopeCritical($query)
    {
        return $query->where('severity_level', 'critical')->orWhere('priority', 5);
    }
}
