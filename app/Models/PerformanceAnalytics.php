<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerformanceAnalytics extends Model
{
    use HasFactory;

    protected $table = 'performance_analytics';

    protected $fillable = [
        'student_id',
        'level',
        'class',
        'stream',
        'average_performance',
        'trend_percentage',
        'subjects_passed',
        'subjects_failed',
        'performance_grade',
        'academic_year',
    ];

    protected $casts = [
        'average_performance' => 'float',
        'trend_percentage' => 'float',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function scopeByYear($query, int $year)
    {
        return $query->where('academic_year', $year);
    }

    public function scopeByClass($query, string $class, string $level = null)
    {
        return $query->where('class', $class)
            ->when($level, fn ($q) => $q->where('level', $level));
    }

    public function scopeHighPerformers($query)
    {
        return $query->where('performance_grade', 'A')
            ->orWhere('performance_grade', 'B');
    }

    public function scopeLowPerformers($query)
    {
        return $query->where('performance_grade', 'E')
            ->orWhere('performance_grade', 'F');
    }
}
