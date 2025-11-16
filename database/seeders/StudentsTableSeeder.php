<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define classes and streams
        $classes = ['S1', 'S2', 'S3', 'S4', 'S5', 'S6'];
        $streams = ['A', 'B', 'C', 'D'];
        $districts = ['Kampala', 'Wakiso', 'Mukono', 'Jinja', 'Mbale', 'Gulu', 'Mbarara'];
        $nationalities = ['Ugandan', 'Kenyan', 'Tanzanian', 'Rwandan', 'South Sudanese'];
        $tribes = ['Baganda', 'Banyankole', 'Basoga', 'Bakiga', 'Iteso', 'Langi', 'Acholi', 'Lugbara'];
        $religions = ['Christian', 'Muslim', 'Hindu', 'Traditional', 'Other'];
        $levels = ['O-Level', 'A-Level'];

        // Create 100 sample students
        for ($i = 0; $i < 100; $i++) {
            $gender = rand(0, 1) ? 'Male' : 'Female';
            $class = $classes[array_rand($classes)];
            $stream = $streams[array_rand($streams)];
            $district = $districts[array_rand($districts)];
            $nationality = $nationalities[array_rand($nationalities)];
            $tribe = $tribes[array_rand($tribes)];
            $religion = $religions[array_rand($religions)];
            $level = ($class == 'S5' || $class == 'S6') ? 'A-Level' : 'O-Level';

            \App\Models\Student::create([
                'student_name' => $gender == 'Male' ? 'John Doe ' . ($i + 1) : 'Jane Doe ' . ($i + 1),
                'gender' => $gender,
                'date_of_birth' => now()->subYears(rand(13, 20))->subMonths(rand(1, 12))->subDays(rand(1, 28)),
                'religion' => $religion,
                'district_of_birth' => $district,
                'district' => $district,
                'nationality' => $nationality,
                'tribe' => $tribe,
                'class' => $class,
                'stream' => $stream,
                'level' => $level,
            ]);
        }
    }
}
