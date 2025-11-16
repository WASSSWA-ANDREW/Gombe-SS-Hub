<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ReportController extends Controller
{
    /**
     * Display the reports dashboard
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Basic statistics for dashboard
        $data = [
            'totalStudents' => Report::getTotalStudents(),
            'totalTeachers' => Report::getTotalTeachers(),
            'totalOLevelStudents' => Report::getTotalOLevelStudents(),
            'totalALevelStudents' => Report::getTotalALevelStudents(),
            'totalMaleStudents' => Report::getTotalMaleStudents(),
            'totalFemaleStudents' => Report::getTotalFemaleStudents(),
            'totalMaleStaff' => Report::getTotalMaleStaff(),
            'totalFemaleStaff' => Report::getTotalFemaleStaff(),
            'totalAdministrators' => Report::getTotalAdministrators(),
            'totalRegularStaff' => Report::getTotalRegularStaff(),
            'totalGovernmentStaff' => Report::getTotalGovernmentStaff(),
        ];

        return view('admin.reports.index', compact('data'));
    }

    /**
     * Display student distribution reports
     *
     * @return \Illuminate\Http\Response
     */
    public function studentDistribution()
    {
        $data = [
            'studentsPerClass' => Report::getStudentsPerClass(),
            'studentsPerStream' => Report::getStudentsPerStream(),
            'studentsPerClassAndStream' => Report::getStudentsPerClassAndStream(),
        ];

        return view('admin.reports.student-distribution', compact('data'));
    }

    /**
     * Display demographic reports
     *
     * @return \Illuminate\Http\Response
     */
    public function demographics()
    {
        $data = [
            'studentsPerDistrict' => Report::getStudentsPerDistrict(),
            'studentsPerNationality' => Report::getStudentsPerNationality(),
            'studentsPerTribe' => Report::getStudentsPerTribe(),
            'studentsPerReligion' => Report::getStudentsPerReligion(),
            'genderDistribution' => [
                'male' => Report::getTotalMaleStudents(),
                'female' => Report::getTotalFemaleStudents(),
            ],
            'ageGroups' => Report::getStudentsByAgeGroup(),
        ];

        return view('admin.reports.demographics', compact('data'));
    }

    /**
     * Display staff reports
     *
     * @return \Illuminate\Http\Response
     */
    public function staffReports()
    {
        try {
            $data = [
                'totalTeachers' => Report::getTotalTeachers(),
                'totalMaleStaff' => Report::getTotalMaleStaff(),
                'totalFemaleStaff' => Report::getTotalFemaleStaff(),
                'totalAdministrators' => Report::getTotalAdministrators(),
                'totalRegularStaff' => Report::getTotalRegularStaff(),
                'totalGovernmentStaff' => Report::getTotalGovernmentStaff(),
                'totalSupportStaff' => Report::getTotalSupportStaff(),
                'totalNonTeachingStaff' => Report::getTotalNonTeachingStaff(),
                'staffByRole' => Report::getStaffByRole(),
                'staffByDepartment' => Report::getStaffByDepartment(),
            ];
            
            // Only include years of service if the hire_date column exists
            if (Schema::hasColumn('staff', 'hire_date')) {
                $data['staffByYearsOfService'] = Report::getStaffByYearsOfService();
            } else {
                $data['staffByYearsOfService'] = [
                    'less_than_1' => 0,
                    '1_to_5' => 0,
                    '6_to_10' => 0,
                    'more_than_10' => 0,
                ];
            }
            
            return view('admin.reports.staff', compact('data'));
        } catch (\Exception $e) {
            // Log the error
            \Log::error('Error in staff reports: ' . $e->getMessage());
            
            // Return a view with basic data
            $data = [
                'totalTeachers' => 0,
                'totalMaleStaff' => 0,
                'totalFemaleStaff' => 0,
                'totalAdministrators' => 0,
                'totalRegularStaff' => 0,
                'totalGovernmentStaff' => 0,
                'staffByRole' => [],
                'staffByDepartment' => [],
                'staffByYearsOfService' => [
                    'less_than_1' => 0,
                    '1_to_5' => 0,
                    '6_to_10' => 0,
                    'more_than_10' => 0,
                ],
            ];
            
            return view('admin.reports.staff', compact('data'));
        }
    }
    
    /**
     * Display academic performance reports
     *
     * @return \Illuminate\Http\Response
     */
    public function academicPerformance()
    {
        // Placeholder data - replace with actual data when available
        $data = [
            'averageGrades' => [],
            'subjectPerformance' => [],
            'classPerformance' => [],
            'topPerformers' => [],
        ];
        
        return view('admin.reports.academic-performance', compact('data'));
    }
    
    /**
     * Display attendance reports
     *
     * @return \Illuminate\Http\Response
     */
    public function attendance()
    {
        // Placeholder data - replace with actual data when available
        $data = [
            'studentAttendance' => [],
            'staffAttendance' => [],
            'attendanceTrends' => [],
        ];
        
        return view('admin.reports.attendance', compact('data'));
    }
    
    /**
     * Display custom report builder
     *
     * @return \Illuminate\Http\Response
     */
    public function custom()
    {
        $data = [
            'availableFields' => [
                'students' => Schema::getColumnListing('students'),
                'staff' => Schema::getColumnListing('staff'),
            ],
        ];
        
        return view('admin.reports.custom', compact('data'));
    }



    /**
     * Generate a PDF report
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function generatePdf(Request $request)
    {
        $reportType = $request->input('report_type');
        
        // Logic to generate different PDF reports based on report_type
        switch ($reportType) {
            case 'student_distribution':
                $data = [
                    'studentsPerClass' => Report::getStudentsPerClass(),
                    'studentsPerStream' => Report::getStudentsPerStream(),
                    'studentsPerClassAndStream' => Report::getStudentsPerClassAndStream(),
                ];
                $view = 'admin.reports.pdf.student-distribution';
                $title = 'Student Distribution Report';
                break;
                
            case 'demographics':
                $data = [
                    'studentsPerDistrict' => Report::getStudentsPerDistrict(),
                    'studentsPerNationality' => Report::getStudentsPerNationality(),
                    'studentsPerTribe' => Report::getStudentsPerTribe(),
                    'studentsPerReligion' => Report::getStudentsPerReligion(),
                    'genderDistribution' => [
                        'male' => Report::getTotalMaleStudents(),
                        'female' => Report::getTotalFemaleStudents(),
                    ],
                    'ageGroups' => Report::getStudentsByAgeGroup(),
                ];
                $view = 'admin.reports.pdf.demographics';
                $title = 'Student Demographics Report';
                break;
                
            case 'staff':
                $data = [
                    'totalTeachers' => Report::getTotalTeachers(),
                    'totalMaleStaff' => Report::getTotalMaleStaff(),
                    'totalFemaleStaff' => Report::getTotalFemaleStaff(),
                    'totalAdministrators' => Report::getTotalAdministrators(),
                    'totalRegularStaff' => Report::getTotalRegularStaff(),
                    'totalGovernmentStaff' => Report::getTotalGovernmentStaff(),
                    'staffByRole' => Report::getStaffByRole(),
                    'staffByDepartment' => Report::getStaffByDepartment(),
                    'staffByYearsOfService' => Report::getStaffByYearsOfService(),
                ];
                $view = 'admin.reports.pdf.staff';
                $title = 'Staff Report';
                break;
                
            default:
                $data = [
                    'totalStudents' => Report::getTotalStudents(),
                    'totalTeachers' => Report::getTotalTeachers(),
                    'totalOLevelStudents' => Report::getTotalOLevelStudents(),
                    'totalALevelStudents' => Report::getTotalALevelStudents(),
                ];
                $view = 'admin.reports.pdf.summary';
                $title = 'School Summary Report';
                break;
        }
        
        // Generate PDF using a package like dompdf or barryvdh/laravel-dompdf
        // This is a placeholder - implement based on your PDF generation setup
        // return PDF::loadView($view, compact('data'))->download($title . '.pdf');
        
        // For now, just return a view
        return view($view, compact('data', 'title'));
    }

    /**
     * Export report data to Excel
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function exportExcel(Request $request)
    {
        $reportType = $request->input('report_type');
        
        // Logic to generate different Excel reports based on report_type
        // This is a placeholder - implement based on your Excel export setup
        // using a package like maatwebsite/excel
        
        return redirect()->back()->with('success', 'Excel export functionality will be implemented soon.');
    }
}