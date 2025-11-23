<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AIPrediction extends Model
{
    use HasFactory;

    protected $table = 'ai_predictions';

    protected $fillable = [
        'student_id',
        'prediction_type',
        'level',
        'class',
        'confidence_score',
        'prediction_data',
        'risk_level',
        'academic_year',
    ];

    protected $casts = [
        'prediction_data' => 'array',
        'confidence_score' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeByType($query, string $type)
    {
        return $query->where('prediction_type', $type);
    }

    public function scopeByYear($query, int $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeHighRisk($query)
    {
        return $query->where('risk_level', 'high');
    }
}
