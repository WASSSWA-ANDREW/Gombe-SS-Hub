<?php

namespace Database\Seeders;

use App\Models\OLevelSubject;
use Illuminate\Database\Seeder;

class OLevelSubjectsSeeder extends Seeder
{
    public function run(): void
    {
        $generalSubjects = [
            ['subject_name' => 'English Language', 'subject_code' => 'ENG', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Mathematics', 'subject_code' => 'MATH', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'History', 'subject_code' => 'HIST', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Geography', 'subject_code' => 'GEO', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Physics', 'subject_code' => 'PHY', 'requires_practical' => true, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Biology', 'subject_code' => 'BIO', 'requires_practical' => true, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Chemistry', 'subject_code' => 'CHEM', 'requires_practical' => true, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Religious Education (CRE)', 'subject_code' => 'CRE', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Islamic Religious Education (IRE)', 'subject_code' => 'IRE', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Entrepreneurship Education', 'subject_code' => 'EED', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Kiswahili', 'subject_code' => 'KSW', 'requires_practical' => false, 'classes' => ['S1', 'S2', 'S3', 'S4']],
            ['subject_name' => 'Physical Education', 'subject_code' => 'PE', 'requires_practical' => true, 'classes' => ['S1', 'S2', 'S3', 'S4']],
        ];

        $optionalSubjects = [
            ['subject_name' => 'Agriculture', 'subject_code' => 'AGR', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Information and Communication Technology (ICT)', 'subject_code' => 'ICT', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Art and Design', 'subject_code' => 'ART', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Performing Arts', 'subject_code' => 'PA', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Technology and Design', 'subject_code' => 'TD', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Nutrition and Food Technology', 'subject_code' => 'NFT', 'requires_practical' => true, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'French', 'subject_code' => 'FRE', 'requires_practical' => false, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Luganda', 'subject_code' => 'LUG', 'requires_practical' => false, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Arabic', 'subject_code' => 'ARA', 'requires_practical' => false, 'classes' => ['S3', 'S4']],
            ['subject_name' => 'Literature in English', 'subject_code' => 'LIT', 'requires_practical' => false, 'classes' => ['S3', 'S4']],
        ];

        foreach ($generalSubjects as $subject) {
            OLevelSubject::firstOrCreate(
                ['subject_code' => $subject['subject_code']],
                array_merge($subject, ['category' => 'general'])
            );
        }

        foreach ($optionalSubjects as $subject) {
            OLevelSubject::firstOrCreate(
                ['subject_code' => $subject['subject_code']],
                array_merge($subject, ['category' => 'optional'])
            );
        }
    }
}
