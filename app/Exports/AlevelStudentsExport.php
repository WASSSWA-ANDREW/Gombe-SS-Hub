<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlevelStudentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $students;

    /**
     * Constructor to allow passing a custom collection
     * 
     * @param \Illuminate\Support\Collection|null $students
     */
    public function __construct($students = null)
    {
        $this->students = $students;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->students) {
            return $this->students;
        }
        
        return Student::where('level', 'alevel')->get();
    }

    /**
    * @return array
    */
    public function headings(): array
    {
        return [
            'ID',
            'Student Name',
            'Gender',
            'Learners LIN',
            'Learners NIN',
            'Date of Birth',
            'Religion',
            'Mobile Number',
            'Email',
            'District of Birth',
            'Previous School',
            'Subject Combination',
            'UCE Index Number',
            'Special Issue',
            'Father Full Name',
            'Father Mobile',
            'Father Email',
            'Father NIN',
            'Father Address',
            'Father Occupation',
            'Father Status',
            'Mother Full Name',
            'Mother Mobile',
            'Mother Email',
            'Mother NIN',
            'Mother Address',
            'Mother Occupation',
            'Mother Status',
            'Guardian Full Name',
            'Guardian Mobile',
            'Guardian Email',
            'Guardian NIN',
            'Guardian Address',
            'Guardian Occupation',
            'Guardian Relationship',
            'Official Comment',
            'Created At',
            'Updated At',
        ];
    }

    /**
    * @param mixed $student
    * @return array
    */
    public function map($student): array
    {
        return [
            $student->id,
            $student->student_name,
            $student->gender,
            $student->learners_lin,
            $student->learners_nin,
            $student->date_of_birth,
            $student->religion,
            $student->mobile_number,
            $student->email,
            $student->district_of_birth,
            $student->previous_school,
            $student->subject_combination,
            $student->uce_index_number,
            $student->special_issue,
            $student->father_full_name,
            $student->father_mobile_number,
            $student->father_email,
            $student->father_nin,
            $student->father_physical_address,
            $student->father_occupation,
            $student->father_dead_alive,
            $student->mother_full_name,
            $student->mother_mobile_number,
            $student->mother_email,
            $student->mother_nin,
            $student->mother_physical_address,
            $student->mother_occupation,
            $student->mother_dead_alive,
            $student->guardian_full_name,
            $student->guardian_mobile_number,
            $student->guardian_email,
            $student->guardian_nin,
            $student->guardian_physical_address,
            $student->guardian_occupation,
            $student->guardian_relationship,
            $student->official_comment,
            $student->created_at->toDateTimeString(),
            $student->updated_at->toDateTimeString(),
        ];
    }
}