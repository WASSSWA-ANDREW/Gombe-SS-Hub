<?php

namespace App\Exports;

use App\Models\Alumni;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AlumniExport implements FromCollection, WithHeadings, WithMapping
{
    protected $alumni;

    /**
     * Constructor to allow passing a custom collection
     *
     * @param \Illuminate\Support\Collection|null $alumni
     */
    public function __construct($alumni = null)
    {
        $this->alumni = $alumni;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        if ($this->alumni) {
            return $this->alumni;
        }

        return Alumni::all();
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
            'District',
            'Nationality',
            'Tribe',
            'Previous School',
            'PLE Index Number',
            'UCE Index Number',
            'Special Issue',
            'PLE English',
            'PLE Mathematics',
            'PLE SST',
            'PLE Science',
            'PLE Total',
            'PLE Aggregates',
            'UCE English',
            'UCE Mathematics',
            'UCE Physics',
            'UCE Chemistry',
            'UCE Biology',
            'UCE History',
            'UCE Geography',
            'UCE Economics',
            'UCE Literature',
            'UCE Other',
            'Combination',
            'Medical Status',
            'Physical Health',
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
            'Level',
            'Graduation Class',
            'Graduation Year',
            'Stream',
            'Created At',
            'Updated At',
        ];
    }

    /**
    * @param mixed $alumnus
    * @return array
    */
    public function map($alumnus): array
    {
        return [
            $alumnus->id,
            $alumnus->student_name,
            $alumnus->gender,
            $alumnus->learners_lin,
            $alumnus->learners_nin,
            $alumnus->date_of_birth,
            $alumnus->religion,
            $alumnus->mobile_number,
            $alumnus->email,
            $alumnus->district_of_birth,
            $alumnus->district,
            $alumnus->nationality,
            $alumnus->tribe,
            $alumnus->previous_school,
            $alumnus->ple_index_number,
            $alumnus->uce_index_number,
            $alumnus->special_issue,
            $alumnus->ple_english,
            $alumnus->ple_mathematics,
            $alumnus->ple_sst,
            $alumnus->ple_science,
            $alumnus->ple_total,
            $alumnus->ple_aggregates,
            $alumnus->uce_english,
            $alumnus->uce_mathematics,
            $alumnus->uce_physics,
            $alumnus->uce_chemistry,
            $alumnus->uce_biology,
            $alumnus->uce_history,
            $alumnus->uce_geography,
            $alumnus->uce_economics,
            $alumnus->uce_literature,
            $alumnus->uce_other,
            $alumnus->combination,
            $alumnus->medical_status,
            $alumnus->physical_health,
            $alumnus->father_full_name,
            $alumnus->father_mobile_number,
            $alumnus->father_email,
            $alumnus->father_nin,
            $alumnus->father_physical_address,
            $alumnus->father_occupation,
            $alumnus->father_dead_alive,
            $alumnus->mother_full_name,
            $alumnus->mother_mobile_number,
            $alumnus->mother_email,
            $alumnus->mother_nin,
            $alumnus->mother_physical_address,
            $alumnus->mother_occupation,
            $alumnus->mother_dead_alive,
            $alumnus->guardian_full_name,
            $alumnus->guardian_mobile_number,
            $alumnus->guardian_email,
            $alumnus->guardian_nin,
            $alumnus->guardian_physical_address,
            $alumnus->guardian_occupation,
            $alumnus->guardian_relationship,
            $alumnus->official_comment,
            $alumnus->level,
            $alumnus->graduation_class,
            $alumnus->graduation_year,
            $alumnus->stream,
            $alumnus->created_at->toDateTimeString(),
            $alumnus->updated_at->toDateTimeString(),
        ];
    }
}
