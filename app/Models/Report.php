<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class Report extends Model
{
    use HasFactory;

    /**
     * Get the number of students per class
     * 
     * @return array
     */
    public static function getStudentsPerClass()
    {
        return DB::table('students')
            ->select('class', DB::raw('count(*) as total'))
            ->groupBy('class')
            ->get();
    }

    /**
     * Get the number of students per stream
     * 
     * @return array
     */
    public static function getStudentsPerStream()
    {
        return DB::table('students')
            ->select('stream', DB::raw('count(*) as total'))
            ->groupBy('stream')
            ->get();
    }

    /**
     * Get the number of students per class and stream
     * 
     * @return array
     */
    public static function getStudentsPerClassAndStream()
    {
        return DB::table('students')
            ->select('class', 'stream', DB::raw('count(*) as total'))
            ->groupBy('class', 'stream')
            ->get();
    }

    /**
     * Get total number of students in the school
     * 
     * @return int
     */
    public static function getTotalStudents()
    {
        return Student::count();
    }

    /**
     * Get total number of teachers in the school
     * 
     * @return int
     */
    public static function getTotalTeachers()
    {
        return Staff::where('role', 'teacher')->count();
    }

    /**
     * Get total number of O'Level students
     * 
     * @return int
     */
    public static function getTotalOLevelStudents()
    {
        return Student::where('level', 'O-Level')->count();
    }

    /**
     * Get total number of A'Level students
     * 
     * @return int
     */
    public static function getTotalALevelStudents()
    {
        return Student::where('level', 'A-Level')->count();
    }

    /**
     * Get number of students per district of origin
     * 
     * @return array
     */
    public static function getStudentsPerDistrict()
    {
        return DB::table('students')
            ->select('district', DB::raw('count(*) as total'))
            ->groupBy('district')
            ->get();
    }

    /**
     * Get number of students per nationality
     * 
     * @return array
     */
    public static function getStudentsPerNationality()
    {
        return DB::table('students')
            ->select('nationality', DB::raw('count(*) as total'))
            ->groupBy('nationality')
            ->get();
    }

    /**
     * Get number of students per tribe
     * 
     * @return array
     */
    public static function getStudentsPerTribe()
    {
        return DB::table('students')
            ->select('tribe', DB::raw('count(*) as total'))
            ->groupBy('tribe')
            ->get();
    }

    /**
     * Get number of students per religion
     * 
     * @return array
     */
    public static function getStudentsPerReligion()
    {
        return DB::table('students')
            ->select('religion', DB::raw('count(*) as total'))
            ->groupBy('religion')
            ->get();
    }

    /**
     * Get total number of male students
     * 
     * @return int
     */
    public static function getTotalMaleStudents()
    {
        return Student::where('gender', 'Male')->count();
    }

    /**
     * Get total number of female students
     * 
     * @return int
     */
    public static function getTotalFemaleStudents()
    {
        return Student::where('gender', 'Female')->count();
    }

    /**
     * Get total number of male staff
     * 
     * @return int
     */
    public static function getTotalMaleStaff()
    {
        return Staff::where('sex', 'Male')->count();
    }

    /**
     * Get total number of female staff
     * 
     * @return int
     */
    public static function getTotalFemaleStaff()
    {
        return Staff::where('sex', 'Female')->count();
    }

    /**
     * Get total number of administrators
     * 
     * @return int
     */
    public static function getTotalAdministrators()
    {
        return Staff::whereIn('staff_designation', [
            'HEADMASTER',
            'DEPUTY HEADMASTER-ADMINISTRATION',
            'DEPUTY HEADMASTER-ACADEMICS',
            'DEPUTY HEADMASTER-DISCIPLINE',
            'DIRECTOR OF STUDIES',
            'DEAN OF STUDENTS',
            'WARDEN',
            'HEAD OF DEPARTMENT',
            'MATRON',
            'STAFF SECRETARY'
        ])->count();
    }

    /**
     * Get staff distribution by role
     * 
     * @return array
     */
    public static function getStaffByRole()
    {
        return DB::table('staff')
            ->select('role', DB::raw('count(*) as total'))
            ->groupBy('role')
            ->get();
    }



    /**
     * Get total number of regular staff
     * 
     * @return int
     */
    public static function getTotalRegularStaff()
    {
        return Staff::where('employment_type', 'Regular')->count();
    }

    /**
     * Get total number of government staff
     *
     * @return int
     */
    public static function getTotalGovernmentStaff()
    {
        return Staff::where('employment_type', 'Government')->count();
    }

    /**
     * Get total number of support staff
     *
     * @return int
     */
    public static function getTotalSupportStaff()
    {
        return Staff::where('role', 'support')->count();
    }

    /**
     * Get total number of non-teaching staff
     *
     * @return int
     */
    public static function getTotalNonTeachingStaff()
    {
        return Staff::where('role', '!=', 'teacher')->count();
    }

    /**
     * Get staff distribution by department
     * 
     * @return array
     */
    public static function getStaffByDepartment()
    {
        return DB::table('staff')
            ->select('department', DB::raw('count(*) as total'))
            ->groupBy('department')
            ->get();
    }



    /**
     * Get students by age group
     * 
     * @return array
     */
    public static function getStudentsByAgeGroup()
    {
        return [
            'under_13' => DB::table('students')
                ->whereRaw("TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) < 13")
                ->count(),
            '13_to_15' => DB::table('students')
                ->whereRaw("TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 13 AND 15")
                ->count(),
            '16_to_18' => DB::table('students')
                ->whereRaw("TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) BETWEEN 16 AND 18")
                ->count(),
            'over_18' => DB::table('students')
                ->whereRaw("TIMESTAMPDIFF(YEAR, date_of_birth, CURDATE()) > 18")
                ->count(),
        ];
    }

    /**
     * Get staff by years of service
     * 
     * @return array
     */
    public static function getStaffByYearsOfService()
    {
        if (Schema::hasColumn('staff', 'hire_date')) {
            return [
                'less_than_1' => DB::table('staff')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) < 1")
                    ->count(),
                '1_to_5' => DB::table('staff')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 1 AND 5")
                    ->count(),
                '6_to_10' => DB::table('staff')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) BETWEEN 6 AND 10")
                    ->count(),
                'more_than_10' => DB::table('staff')
                    ->whereRaw("TIMESTAMPDIFF(YEAR, hire_date, CURDATE()) > 10")
                    ->count(),
            ];
        } else {
            return [
                'less_than_1' => 0,
                '1_to_5' => 0,
                '6_to_10' => 0,
                'more_than_10' => 0,
            ];
        }
    }
}