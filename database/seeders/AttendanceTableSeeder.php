<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AttendanceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get the classes and streams
        $classes = ['S.1', 'S.2', 'S.3', 'S.4', 'S.5', 'S.6'];
        $streams = ['A', 'B', 'C', 'D', 'E', 'G', 'H', 'T'];
        $terms = ['Term 1', 'Term 2', 'Term 3'];
        $academicYears = ['2023', '2024', '2025'];
        
        // Create attendance records for each class and stream
        foreach ($classes as $class) {
            foreach ($streams as $stream) {
                foreach ($terms as $term) {
                    foreach ($academicYears as $year) {
                        // Generate random attendance data for 10 days
                        for ($day = 1; $day <= 10; $day++) {
                            $date = date('Y-m-d', strtotime("$year-01-01 +$day days"));
                            $totalStudents = rand(30, 50);
                            $absentCount = rand(0, 10);
                            $presentCount = $totalStudents - $absentCount;
                            
                            \App\Models\Attendance::create([
                                'attendance_date' => $date,
                                'class' => $class,
                                'stream' => $stream,
                                'present_count' => $presentCount,
                                'absent_count' => $absentCount,
                                'total_students' => $totalStudents,
                                'term' => $term,
                                'academic_year' => $year,
                                'remarks' => 'Regular school day'
                            ]);
                        }
                    }
                }
            }
        }
    }
}
