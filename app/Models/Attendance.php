<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attendance';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'attendance_date',
        'class',
        'stream',
        'present_count',
        'absent_count',
        'total_students',
        'term',
        'academic_year',
        'remarks'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'attendance_date' => 'date',
        'present_count' => 'integer',
        'absent_count' => 'integer',
        'total_students' => 'integer'
    ];
}
