<?php

namespace App\Imports;

use App\Models\Staff;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StaffImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;
    
    protected $staffType;
    
    public function __construct($staffType = 'private')
    {
        $this->staffType = $staffType;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Staff([
            'surname' => $row['surname'],
            'first_name' => $row['first_name'],
            'other_name' => $row['other_name'] ?? null,
            'sex' => $row['sex'],
            'gender' => $row['gender'] ?? $row['sex'],
            'date_of_birth' => $this->transformDate($row['date_of_birth']),
            'hire_date' => isset($row['hire_date']) ? $this->transformDate($row['hire_date']) : null,
            'uts_file_no' => $row['uts_file_no'] ?? null,
            'district_file_no' => $row['district_file_no'] ?? null,
            'computer_no' => $row['computer_no'] ?? null,
            'national_id_no' => $row['national_id_no'] ?? null,
            'registration_no' => $row['registration_no'] ?? null,
            'salary_scale' => $row['salary_scale'] ?? null,
            'gross_salary' => $row['gross_salary'] ?? null,
            'net_salary' => $row['net_salary'] ?? null,
            'tin_no' => $row['tin_no'] ?? null,
            'date_of_1st_appt' => isset($row['date_of_1st_appt']) ? $this->transformDate($row['date_of_1st_appt']) : null,
            'designation_of_1st_appt' => $row['designation_of_1st_appt'] ?? null,
            'minute_no_1st_appt' => $row['minute_no_1st_appt'] ?? null,
            'date_of_current_appt' => isset($row['date_of_current_appt']) ? $this->transformDate($row['date_of_current_appt']) : null,
            'designation_of_current_appt' => $row['designation_of_current_appt'] ?? null,
            'minute_no_current_appt' => $row['minute_no_current_appt'] ?? null,
            'date_of_confirmation' => isset($row['date_of_confirmation']) ? $this->transformDate($row['date_of_confirmation']) : null,
            'minute_no_confirmation' => $row['minute_no_confirmation'] ?? null,
            'date_of_current_posting' => isset($row['date_of_current_posting']) ? $this->transformDate($row['date_of_current_posting']) : null,
            'teaching_subjects' => $row['teaching_subjects'] ?? null,
            'telephone_contacts' => $row['telephone_contacts'] ?? null,
            'marital_status' => $row['marital_status'] ?? null,
            'next_of_kin' => $row['next_of_kin'] ?? null,
            'next_of_kin_telephone' => $row['next_of_kin_telephone'] ?? null,
            'email' => $row['email'] ?? null,
            'other_academic_qualifications' => $row['other_academic_qualifications'] ?? null,
            'highest_level_of_education' => $row['highest_level_of_education'] ?? null,
            'ipps_no' => $row['ipps_no'] ?? null,
            'medical_status' => $row['medical_status'] ?? 'Healthy', // Added health field with default value
            'physical_health' => $row['physical_health'] ?? 'Fit', // Added health field with default value
            'staff_type' => $this->staffType,
            'role' => $row['role'] ?? null,
            'employment_type' => $row['employment_type'] ?? null,
            'department' => $row['department'] ?? null,
        ]);
    }
    
    /**
     * Transform date value from excel
     */
    public function transformDate($value)
    {
        try {
            if (is_numeric($value)) {
                return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value);
            }
            return $value;
        } catch (\Exception $e) {
            return $value;
        }
    }
    
    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'surname' => 'required',
            'first_name' => 'required',
            'sex' => 'required|in:Male,Female,M,F',
            'date_of_birth' => 'required',
            'email' => 'nullable|email|unique:staff,email',
            'national_id_no' => 'nullable|unique:staff,national_id_no',
            'medical_status' => 'nullable|in:Healthy,Medical care',
            'physical_health' => 'nullable|in:Fit,Disabled',
        ];
    }
    
    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }
    
    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }
}