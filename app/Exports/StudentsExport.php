<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StudentsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
{
    protected $filters;

    public function __construct($filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = Student::query();

        // Apply filters
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ['level', 'gender', 'religion', 'district_of_birth'])) {
                    $query->where($key, $value);
                } elseif ($key === 'date_from') {
                    $query->where('created_at', '>=', $value);
                } elseif ($key === 'date_to') {
                    $query->where('created_at', '<=', $value);
                }
            }
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Student ID',
            'First Name',
            'Last Name',
            'Middle Name',
            'Level',
            'Gender',
            'Date of Birth',
            'Religion',
            'District of Birth',
            'Address',
            'Phone',
            'Email',
            'Guardian Name',
            'Guardian Phone',
            'Created At'
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
            $student->student_id,
            $student->first_name,
            $student->last_name,
            $student->middle_name,
            ucfirst($student->level),
            ucfirst($student->gender),
            $student->date_of_birth ? $student->date_of_birth->format('Y-m-d') : '',
            $student->religion,
            $student->district_of_birth,
            $student->address,
            $student->phone,
            $student->email,
            $student->guardian_name,
            $student->guardian_phone,
            $student->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            // Style the first row as bold text
            1 => ['font' => ['bold' => true]],
        ];
    }
}