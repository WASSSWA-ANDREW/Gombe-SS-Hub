<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnomalyDetection extends Model
{
    use HasFactory;

    protected $table = 'anomaly_detections';

    protected $fillable = [
        'student_id',
        'staff_id',
        'anomaly_type',
        'description',
        'severity',
        'anomaly_data',
        'resolved',
        'resolution_notes',
        'academic_year',
    ];

    protected $casts = [
        'anomaly_data' => 'array',
        'resolved' => 'boolean',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('resolved', false);
    }

    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('anomaly_type', $type);
    }
}
