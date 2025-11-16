<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Alumni;
use App\Models\Report;
use App\Models\DisciplineTrack;
use App\Models\CounsellingTrack;
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
        $totalAlumni = Alumni::count();
        $totalOlevelStudents = Student::where('level', 'olevel')->count();
        $totalAlevelStudents = Student::where('level', 'alevel')->count();

        // Alumni statistics
        $olevelAlumni = Alumni::where('graduation_class', 'S4')->count();
        $alevelAlumni = Alumni::where('graduation_class', 'S6')->count();
        $currentYearAlumni = Alumni::where('graduation_year', now()->year)->count();
        
        // Count total file uploads (pass slips from students and staff)
        $totalFileUploads = Student::whereNotNull('pass_slip_path')->count() + Staff::whereNotNull('pass_slip_path')->count();
        
        // Get recent file uploads from students and staff
        $recentStudentUploads = Student::whereNotNull('pass_slip_path')
                                        ->orderBy('updated_at', 'desc')
                                        ->limit(5)
                                        ->get()
                                        ->map(function ($student) {
                                            return [
                                                'id' => $student->id,
                                                'type' => 'student',
                                                'name' => $student->getDisplayName(),
                                                'path' => $student->getFilePath(),
                                                'date' => $student->updated_at,
                                                'owner_name' => $student->getOwnerName(),
                                            ];
                                        });
        
        $recentStaffUploads = Staff::whereNotNull('pass_slip_path')
                                    ->orderBy('updated_at', 'desc')
                                    ->limit(5)
                                    ->get()
                                    ->map(function ($staff) {
                                        return [
                                            'id' => $staff->id,
                                            'type' => 'staff',
                                            'name' => $staff->getDisplayName(),
                                            'path' => $staff->getFilePath(),
                                            'date' => $staff->updated_at,
                                            'owner_name' => $staff->getOwnerName(),
                                        ];
                                    });
        
        // Combine and sort by date, take top 8
        $recentFileUploads = $recentStudentUploads->concat($recentStaffUploads)
                                                  ->sortByDesc('date')
                                                  ->take(8)
                                                  ->values();
        
        // Staff statistics
        $governmentStaff = Staff::where('staff_type', 'government')->count();
        $privateStaff = Staff::where('staff_type', '!=', 'government')->count();

        // Teaching and support staff statistics based on role
        $teachingStaff = Staff::where('role', 'teacher')->count();
        $supportStaff = Staff::where('role', 'support')->count();
        $nonTeachingStaff = Staff::where('role', '!=', 'teacher')->count();
        
        // Religion statistics
        $totalMuslimStudents = Student::where('religion', 'Muslim')->orWhere('religion', 'Islam')->count();
        $totalChristianStudents = Student::where(function($query) {
            $query->where('religion', 'Catholic')
                  ->orWhere('religion', 'Anglican')
                  ->orWhere('religion', 'Born Again')
                  ->orWhere('religion', 'Adventist')
                  ->orWhere('religion', 'Christian')
                  ->orWhere('religion', 'Protestant');
        })->count();

        // Discipline statistics
        $totalDisciplineRecords = DisciplineTrack::count();
        $pendingDisciplineCases = DisciplineTrack::where('case_status', 'pending')->count();
        $totalCounsellingRecords = CounsellingTrack::count();
        $ongoingCounsellingSessions = CounsellingTrack::where('status', 'ongoing')->count();
        
        // Student statistics by gender (using Report model)
        $maleStudents = Report::getTotalMaleStudents();
        $femaleStudents = Report::getTotalFemaleStudents();
        
        // Staff statistics by gender (using Report model)
        $maleStaff = Report::getTotalMaleStaff();
        $femaleStaff = Report::getTotalFemaleStaff();
        
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
        
        // Get data entry growth over time (last 6 months)
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        
        // Get student data entry by month - using date format compatible with MySQL and SQLite
        try {
            $studentGrowthData = Student::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();
        } catch (\Exception $e) {
            // Fallback to SQLite syntax if MySQL syntax fails
            try {
                $studentGrowthData = Student::select(
                    DB::raw('strftime("%Y-%m", created_at) as month'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', $sixMonthsAgo)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month')
                ->map(function ($item) {
                    return $item->count;
                })
                ->toArray();
            } catch (\Exception $e2) {
                // If both fail, return empty array
                $studentGrowthData = [];
            }
        }
        
        // Get staff data entry by month - using date format compatible with MySQL and SQLite
        try {
            $staffGrowthData = Staff::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', $sixMonthsAgo)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->keyBy('month')
            ->map(function ($item) {
                return $item->count;
            })
            ->toArray();
        } catch (\Exception $e) {
            // Fallback to SQLite syntax if MySQL syntax fails
            try {
                $staffGrowthData = Staff::select(
                    DB::raw('strftime("%Y-%m", created_at) as month'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', $sixMonthsAgo)
                ->groupBy('month')
                ->orderBy('month')
                ->get()
                ->keyBy('month')
                ->map(function ($item) {
                    return $item->count;
                })
                ->toArray();
            } catch (\Exception $e2) {
                // If both fail, return empty array
                $staffGrowthData = [];
            }
        }
        
        // Generate all months in the range
        $months = [];
        $labels = [];
        $currentDate = clone $sixMonthsAgo;
        $endDate = Carbon::now()->endOfMonth();
        
        while ($currentDate->lte($endDate)) {
            $monthKey = $currentDate->format('Y-m');
            $months[] = $monthKey;
            $labels[] = $currentDate->format('M Y'); // Format as "Jan 2023"
            $currentDate->addMonth();
        }
        
        // Fill in missing months with zeros
        $studentDataSeries = [];
        $staffDataSeries = [];
        
        foreach ($months as $month) {
            $studentDataSeries[] = $studentGrowthData[$month] ?? 0;
            $staffDataSeries[] = $staffGrowthData[$month] ?? 0;
        }
        
        // Prepare growth chart data
        $growthChartData = [
            'labels' => $labels,
            'studentData' => $studentDataSeries,
            'staffData' => $staffDataSeries
        ];
        
        // Get academic performance data
        $academicPerformance = $this->getAcademicPerformance();
        
        return view('admin.dashboard', compact(
            'totalUsers',
            'totalStudents',
            'totalStaff',
            'totalAlumni',
            'olevelAlumni',
            'alevelAlumni',
            'currentYearAlumni',
            'totalOlevelStudents',
            'totalAlevelStudents',
            'totalFileUploads',
            'recentFileUploads',
            'governmentStaff',
            'privateStaff',
            'teachingStaff',
            'supportStaff',
            'nonTeachingStaff',
            'maleStudents',
            'femaleStudents',
            'maleStaff',
            'femaleStaff',
            'studentsByLevel',
            'studentsByReligion',
            'studentsByDistrict',
            'staffByDepartment',
            'recentStudents',
            'recentStaff',
            'ageGroups',
            'growthChartData',
            'totalMuslimStudents',
            'totalChristianStudents',
            'totalDisciplineRecords',
            'pendingDisciplineCases',
            'totalCounsellingRecords',
            'ongoingCounsellingSessions',
            'academicPerformance'
        ));
    }

    /**
     * Get the best performing class streams for O'level and A'level
     */
    private function getAcademicPerformance()
    {
        $performance = [
            'olevel' => null,
            'alevel' => null,
            'olevel_score' => null,
            'alevel_score' => null
        ];

        try {
            $olevelPerformance = Student::where('level', 'olevel')
                ->join('grades', 'students.id', '=', 'grades.student_id')
                ->select('students.stream', DB::raw('AVG(grades.score) as average_score'))
                ->groupBy('students.stream')
                ->orderBy('average_score', 'desc')
                ->first();

            if ($olevelPerformance) {
                $performance['olevel'] = $olevelPerformance->stream ?? 'N/A';
                $performance['olevel_score'] = round($olevelPerformance->average_score, 2);
            }

            $alevelPerformance = Student::where('level', 'alevel')
                ->join('grades', 'students.id', '=', 'grades.student_id')
                ->select('students.stream', DB::raw('AVG(grades.score) as average_score'))
                ->groupBy('students.stream')
                ->orderBy('average_score', 'desc')
                ->first();

            if ($alevelPerformance) {
                $performance['alevel'] = $alevelPerformance->stream ?? 'N/A';
                $performance['alevel_score'] = round($alevelPerformance->average_score, 2);
            }
        } catch (\Exception $e) {
        }

        return $performance;
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

            case 'alumni_overview':
                return response()->json([
                    'total_alumni' => Alumni::count(),
                    'olevel_alumni' => Alumni::where('graduation_class', 'S4')->count(),
                    'alevel_alumni' => Alumni::where('graduation_class', 'S6')->count(),
                    'current_year_alumni' => Alumni::where('graduation_year', now()->year)->count()
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