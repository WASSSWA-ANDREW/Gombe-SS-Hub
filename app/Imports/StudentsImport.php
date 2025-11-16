<?php

namespace App\Imports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class StudentsImport implements ToModel, WithHeadingRow, WithValidation, SkipsOnError, WithBatchInserts, WithChunkReading
{
    use SkipsErrors;
    
    protected $level;
    
    public function __construct($level = 'olevel')
    {
        $this->level = $level;
    }
    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'student_name' => $row['student_name'],
            'gender' => $row['gender'],
            'learners_lin' => $row['learners_lin'] ?? null,
            'learners_nin' => $row['learners_nin'] ?? null,
            'date_of_birth' => $this->transformDate($row['date_of_birth']),
            'religion' => $row['religion'] ?? null,
            'mobile_number' => $row['mobile_number'] ?? null,
            'email' => $row['email'] ?? null,
            'district_of_birth' => $row['district_of_birth'] ?? null,
            'district' => $row['district'] ?? null,
            'nationality' => $row['nationality'] ?? null,
            'tribe' => $row['tribe'] ?? null,
            'previous_school' => $row['previous_school'] ?? null,
            'ple_index_number' => $row['ple_index_number'] ?? null,
            'special_issue' => $row['special_issue'] ?? null,
            'ple_english' => $row['ple_english'] ?? null,
            'ple_mathematics' => $row['ple_mathematics'] ?? null,
            'ple_sst' => $row['ple_sst'] ?? null,
            'ple_science' => $row['ple_science'] ?? null,
            'ple_total' => $row['ple_total'] ?? null,
            'ple_aggregates' => $row['ple_aggregates'] ?? null,
            'medical_status' => $row['medical_status'] ?? 'Healthy', // Added health field with default value
            'physical_health' => $row['physical_health'] ?? 'Fit', // Added health field with default value
            'father_full_name' => $row['father_full_name'] ?? null,
            'father_mobile_number' => $row['father_mobile_number'] ?? null,
            'father_email' => $row['father_email'] ?? null,
            'father_nin' => $row['father_nin'] ?? null,
            'father_physical_address' => $row['father_physical_address'] ?? null,
            'father_occupation' => $row['father_occupation'] ?? null,
            'father_dead_alive' => $row['father_dead_alive'] ?? null,
            'mother_full_name' => $row['mother_full_name'] ?? null,
            'mother_mobile_number' => $row['mother_mobile_number'] ?? null,
            'mother_email' => $row['mother_email'] ?? null,
            'mother_nin' => $row['mother_nin'] ?? null,
            'mother_physical_address' => $row['mother_physical_address'] ?? null,
            'mother_occupation' => $row['mother_occupation'] ?? null,
            'mother_dead_alive' => $row['mother_dead_alive'] ?? null,
            'guardian_full_name' => $row['guardian_full_name'] ?? null,
            'guardian_mobile_number' => $row['guardian_mobile_number'] ?? null,
            'guardian_email' => $row['guardian_email'] ?? null,
            'guardian_nin' => $row['guardian_nin'] ?? null,
            'guardian_physical_address' => $row['guardian_physical_address'] ?? null,
            'guardian_occupation' => $row['guardian_occupation'] ?? null,
            'guardian_relationship' => $row['guardian_relationship'] ?? null,
            'official_comment' => $row['official_comment'] ?? null,
            'level' => $this->level,
            'class' => $row['class'] ?? null,
            'stream' => $row['stream'] ?? null,
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
            'student_name' => 'required',
            'gender' => 'required|in:Male,Female',
            'date_of_birth' => 'required',
            'email' => 'nullable|email|unique:students,email',
            'learners_lin' => 'nullable|unique:students,learners_lin',
            'learners_nin' => 'nullable|unique:students,learners_nin',
            'ple_index_number' => 'nullable|unique:students,ple_index_number',
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