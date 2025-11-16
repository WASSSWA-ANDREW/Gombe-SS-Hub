<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GradesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all student IDs
        $studentIds = \App\Models\Student::pluck('id')->toArray();
        
        if (empty($studentIds)) {
            // If no students exist, create a few
            for ($i = 1; $i <= 10; $i++) {
                \App\Models\Student::create([
                    'surname' => 'Student',
                    'first_name' => 'Test' . $i,
                    'other_name' => 'Sample',
                    'gender' => $i % 2 == 0 ? 'Female' : 'Male',
                    'date_of_birth' => now()->subYears(rand(13, 20))->format('Y-m-d'),
                    'class' => 'S.' . rand(1, 6),
                    'stream' => chr(64 + rand(1, 4)), // A, B, C, or D
                    'district' => 'District ' . rand(1, 5),
                    'nationality' => 'Ugandan',
                    'tribe' => 'Tribe ' . rand(1, 10),
                    'religion' => 'Religion ' . rand(1, 3),
                ]);
            }
            
            // Get the newly created student IDs
            $studentIds = \App\Models\Student::pluck('id')->toArray();
        }
        
        // Subjects
        $subjects = ['Mathematics', 'English', 'Science', 'Social Studies', 'Geography', 'History', 'Physics', 'Chemistry', 'Biology'];
        
        // Terms
        $terms = ['Term 1', 'Term 2', 'Term 3'];
        
        // Academic years
        $academicYears = ['2023/2024', '2024/2025'];
        
        // Create sample grades for each student
        foreach ($studentIds as $studentId) {
            // Create 5-10 grades per student
            $numGrades = rand(5, 10);
            
            for ($i = 0; $i < $numGrades; $i++) {
                $score = rand(40, 95);
                
                // Determine grade letter based on score
                $gradeLetter = 'F';
                if ($score >= 90) {
                    $gradeLetter = 'A+';
                } elseif ($score >= 80) {
                    $gradeLetter = 'A';
                } elseif ($score >= 70) {
                    $gradeLetter = 'B';
                } elseif ($score >= 60) {
                    $gradeLetter = 'C';
                } elseif ($score >= 50) {
                    $gradeLetter = 'D';
                }
                
                // Determine remarks based on grade
                $remarks = 'Needs improvement';
                if ($score >= 80) {
                    $remarks = 'Excellent';
                } elseif ($score >= 70) {
                    $remarks = 'Very good';
                } elseif ($score >= 60) {
                    $remarks = 'Good';
                } elseif ($score >= 50) {
                    $remarks = 'Fair';
                }
                
                \App\Models\Grade::create([
                    'student_id' => $studentId,
                    'subject' => $subjects[array_rand($subjects)],
                    'term' => $terms[array_rand($terms)],
                    'academic_year' => $academicYears[array_rand($academicYears)],
                    'score' => $score,
                    'grade_letter' => $gradeLetter,
                    'remarks' => $remarks,
                ]);
            }
        }
    }
}
