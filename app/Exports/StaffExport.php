<?php

namespace App\Exports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class StaffExport implements FromCollection, WithHeadings, WithMapping, WithStyles, ShouldAutoSize
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
        $query = Staff::query();

        // Apply filters
        foreach ($this->filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ['staff_type', 'sex', 'religion', 'district_of_birth'])) {
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
            'Staff ID',
            'First Name',
            'Last Name',
            'Middle Name',
            'Staff Type',
            'Sex',
            'Date of Birth',
            'Religion',
            'District of Birth',
            'Address',
            'Phone',
            'Email',
            'Department',
            'Position',
            'Qualification',
            'Created At'
        ];
    }

    /**
     * @param mixed $staff
     * @return array
     */
    public function map($staff): array
    {
        return [
            $staff->id,
            $staff->staff_id,
            $staff->first_name,
            $staff->last_name,
            $staff->middle_name,
            ucfirst($staff->staff_type),
            ucfirst($staff->sex),
            $staff->date_of_birth ? $staff->date_of_birth->format('Y-m-d') : '',
            $staff->religion,
            $staff->district_of_birth,
            $staff->address,
            $staff->phone,
            $staff->email,
            $staff->department,
            $staff->position,
            $staff->qualification,
            $staff->created_at->format('Y-m-d H:i:s')
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