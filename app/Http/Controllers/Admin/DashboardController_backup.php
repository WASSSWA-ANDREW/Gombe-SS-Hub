<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Display the admin dashboard with statistics
     */
    public function index()
    {
        // Basic counts
        $totalUsers = User::count();
        $totalStudents = Student::count();
        $totalStaff = Staff::count();
        $totalOlevelStudents = Student::where('level', 'olevel')->count();
        $totalAlevelStudents = Student::where('level', 'alevel')->count();
        
        // Staff statistics
        $governmentStaff = Staff::where('staff_type', 'government')->count();
        $privateStaff = Staff::where('staff_type', '!=', 'government')->count();

        // Teaching and support staff statistics
        $teachingStaff = Staff::where('role', 'teacher')->count();
        $supportStaff = Staff::where('role', 'support')->count();
        $nonTeachingStaff = Staff::where('role', '!=', 'teacher')->count();
        
        // Student statistics by gender
        $maleStudents = Student::where('gender', 'Male')->count();
        $femaleStudents = Student::where('gender', 'Female')->count();
        
        // Students by class/level
        $studentsByLevel = Student::select('level', DB::raw('count(*) as count'))
                                ->groupBy('level')
                                ->get();
        
        // Students by religion
        $studentsByReligion = Student::select('religion', DB::raw('count(*) as count'))
                                   ->whereNotNull('religion')
                                   ->groupBy('religion')
                                   ->get();
        
        // Students by district
        $studentsByDistrict = Student::select('district_of_birth', DB::raw('count(*) as count'))
                                   ->whereNotNull('district_of_birth')
                                   ->groupBy('district_of_birth')
                                   ->limit(10)
                                   ->get();
        
        // Staff by department (using teaching_subjects as department indicator)
        $staffByDepartment = Staff::select('teaching_subjects', DB::raw('count(*) as count'))
                                ->whereNotNull('teaching_subjects')
                                ->groupBy('teaching_subjects')
                                ->get();
        
        // Recent activities (last 10 students and staff added)
        $recentStudents = Student::latest()->limit(5)->get();
        $recentStaff = Staff::latest()->limit(5)->get();
        
        // Age group statistics for students (Database agnostic approach using Carbon)
        $students = Student::whereNotNull('date_of_birth')->get();
        
        $ageGroups = [
            '10-15' => 0,
            '16-20' => 0,
            '21-25' => 0,
            '26+' => 0,
        ];
        
        foreach ($students as $student) {
            if ($student->date_of_birth) {
                try {
                    $age = Carbon::parse($student->date_of_birth)->age;
                    
                    if ($age >= 10 && $age <= 15) {
                        $ageGroups['10-15']++;
                    } elseif ($age >= 16 && $age <= 20) {
                        $ageGroups['16-20']++;
                    } elseif ($age >= 21 && $age <= 25) {
                        $ageGroups['21-25']++;
                    } elseif ($age > 25) {
                        $ageGroups['26+']++;
                    }
                } catch (\Exception $e) {
                    // Skip invalid dates
                    continue;
                }
            }
        }
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalStaff',
            'totalOlevelStudents',
            'totalAlevelStudents',
            'governmentStaff',
            'privateStaff',
            'teachingStaff',
            'supportStaff',
            'nonTeachingStaff',
            'maleStudents',
            'femaleStudents',
            'studentsByLevel',
            'studentsByReligion',
            'studentsByDistrict',
            'staffByDepartment',
            'recentStudents',
            'recentStaff',
            'ageGroups'
        ));
    }

    /**
     * Get statistics data for AJAX requests
     */
    public function getStatistics(Request $request)
    {
        $type = $request->get('type', 'overview');
        
        switch ($type) {
            case 'students_by_gender':
                return response()->json([
                    'male' => Student::where('gender', 'Male')->count(),
                    'female' => Student::where('gender', 'Female')->count()
                ]);
                
            case 'students_by_level':
                return response()->json(
                    Student::select('level', DB::raw('count(*) as count'))
                           ->groupBy('level')
                           ->get()
                );
                
            case 'students_by_religion':
                return response()->json(
                    Student::select('religion', DB::raw('count(*) as count'))
                           ->whereNotNull('religion')
                           ->groupBy('religion')
                           ->get()
                );
                
            case 'students_by_district':
                return response()->json(
                    Student::select('district_of_birth', DB::raw('count(*) as count'))
                           ->whereNotNull('district_of_birth')
                           ->groupBy('district_of_birth')
                           ->orderBy('count', 'desc')
                           ->limit(10)
                           ->get()
                );
                
            case 'staff_by_type':
                return response()->json([
                    'government' => Staff::where('staff_type', 'government')->count(),
                    'private' => Staff::where('staff_type', '!=', 'government')->count()
                ]);
                
            default:
                return response()->json([
                    'total_users' => User::count(),
                    'total_students' => Student::count(),
                    'total_staff' => Staff::count()
                ]);
        }
    }
}