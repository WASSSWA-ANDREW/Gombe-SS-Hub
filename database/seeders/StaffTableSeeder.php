<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample staff data
        $departments = ['Mathematics', 'Science', 'Languages', 'Social Studies', 'Arts', 'Administration'];
        $roles = ['teacher', 'administrator', 'support'];
        $employmentTypes = ['Regular', 'Government'];
        
        for ($i = 1; $i <= 20; $i++) {
            $gender = $i % 2 == 0 ? 'Female' : 'Male';
            $role = $roles[array_rand($roles)];
            $department = $departments[array_rand($departments)];
            $employmentType = $employmentTypes[array_rand($employmentTypes)];
            
            // Calculate a random hire date between 1 and 15 years ago
            $yearsAgo = rand(1, 15);
            $hireDate = now()->subYears($yearsAgo)->subDays(rand(0, 365));
            
            \App\Models\Staff::create([
                'surname' => 'Staff',
                'first_name' => 'Sample' . $i,
                'other_name' => 'Test',
                'sex' => $gender == 'Male' ? 'M' : 'F',
                'gender' => $gender,
                'date_of_birth' => now()->subYears(rand(25, 60))->format('Y-m-d'),
                'hire_date' => $hireDate,
                'role' => $role,
                'employment_type' => $employmentType,
                'department' => $department,
                'telephone_contacts' => '077' . rand(1000000, 9999999),
                'email' => 'staff' . $i . '@example.com',
                'highest_level_of_education' => 'Bachelor\'s Degree',
            ]);
        }
    }
}
