<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use \Illuminate\Database\Eloquent\Factories\HasFactory;
    
    protected $fillable = [
        'student_id',
        'subject',
        'term',
        'academic_year',
        'score',
        'grade_letter',
        'remarks',
    ];
    
    protected $casts = [
        'score' => 'decimal:2',
    ];
    
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
