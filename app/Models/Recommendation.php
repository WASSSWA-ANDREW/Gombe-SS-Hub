<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'staff_id',
        'class',
        'level',
        'recommendation_type',
        'description',
        'recommended_action',
        'priority',
        'implemented',
        'implementation_notes',
        'academic_year',
    ];

    protected $casts = [
        'implemented' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function scopePending($query)
    {
        return $query->where('implemented', false);
    }

    public function scopeByPriority($query, string $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('recommendation_type', $type);
    }
}
