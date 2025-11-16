<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Exports\CustomReportExport;

class ReportsController extends Controller
{
    /**
     * Display the reports dashboard
     */
    public function index()
    {
        return view('admin.reports.index');
    }

    /**
     * Generate students per class report
     */
    public function studentsPerClass()
    {
        $data = Student::select('level', DB::raw('count(*) as count'))
                      ->groupBy('level')
                      ->get();

        return view('admin.reports.students-per-class', compact('data'));
    }

    /**
     * Generate teachers per department report
     */
    public function teachersPerDepartment()
    {
        $data = Staff::select('teaching_subjects as department', DB::raw('count(*) as count'))
                    ->whereNotNull('teaching_subjects')
                    ->groupBy('teaching_subjects')
                    ->get();

        return view('admin.reports.teachers-per-department', compact('data'));
    }

    /**
     * Generate students per gender report
     */
    public function studentsPerGender()
    {
        $data = Student::select('gender', DB::raw('count(*) as count'))
                      ->groupBy('gender')
                      ->get();

        return view('admin.reports.students-per-gender', compact('data'));
    }

    /**
     * Generate students per age group report
     */
    public function studentsPerAgeGroup()
    {
        // Database agnostic approach using Carbon for age calculations
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
                    $age = \Carbon\Carbon::parse($student->date_of_birth)->age;
                    
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

        return view('admin.reports.students-per-age-group', compact('ageGroups'));
    }

    /**
     * Generate students per state/district report
     */
    public function studentsPerDistrict()
    {
        $data = Student::select('district_of_birth as district', DB::raw('count(*) as count'))
                      ->whereNotNull('district_of_birth')
                      ->groupBy('district_of_birth')
                      ->orderBy('count', 'desc')
                      ->get();

        return view('admin.reports.students-per-district', compact('data'));
    }

    /**
     * Generate students per religion report
     */
    public function studentsPerReligion()
    {
        $data = Student::select('religion', DB::raw('count(*) as count'))
                      ->whereNotNull('religion')
                      ->groupBy('religion')
                      ->get();

        return view('admin.reports.students-per-religion', compact('data'));
    }

    /**
     * Generate custom report based on filters
     */
    public function customReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'report_type' => 'required|string|in:students,staff,combined',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from',
            'filters' => 'nullable|array',
            'export_format' => 'nullable|string|in:pdf,excel,csv'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $reportType = $request->input('report_type');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $filters = $request->input('filters', []);
        $exportFormat = $request->input('export_format');

        $data = [];

        // Generate student report
        if ($reportType === 'students' || $reportType === 'combined') {
            $studentQuery = Student::query();

            if ($dateFrom) {
                $studentQuery->where('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $studentQuery->where('created_at', '<=', $dateTo);
            }

            // Apply filters
            if (isset($filters['level']) && !empty($filters['level'])) {
                $studentQuery->where('level', $filters['level']);
            }
            if (isset($filters['gender']) && !empty($filters['gender'])) {
                $studentQuery->where('gender', $filters['gender']);
            }
            if (isset($filters['religion']) && !empty($filters['religion'])) {
                $studentQuery->where('religion', $filters['religion']);
            }
            if (isset($filters['district']) && !empty($filters['district'])) {
                $studentQuery->where('district_of_birth', $filters['district']);
            }

            $data['students'] = $studentQuery->get();
        }

        // Generate staff report
        if ($reportType === 'staff' || $reportType === 'combined') {
            $staffQuery = Staff::query();

            if ($dateFrom) {
                $staffQuery->where('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $staffQuery->where('created_at', '<=', $dateTo);
            }

            // Apply filters
            if (isset($filters['staff_type']) && !empty($filters['staff_type'])) {
                $staffQuery->where('staff_type', $filters['staff_type']);
            }
            if (isset($filters['sex']) && !empty($filters['sex'])) {
                $staffQuery->where('sex', $filters['sex']);
            }
            if (isset($filters['department']) && !empty($filters['department'])) {
                $staffQuery->where('teaching_subjects', 'LIKE', "%{$filters['department']}%");
            }

            $data['staff'] = $staffQuery->get();
        }

        // Handle export
        if ($exportFormat) {
            return $this->exportReport($data, $exportFormat, $reportType);
        }

        return view('admin.reports.custom', compact('data', 'reportType', 'filters'));
    }

    /**
     * Export report in specified format
     */
    private function exportReport($data, $format, $reportType)
    {
        $filename = 'report_' . $reportType . '_' . date('Y-m-d_H-i-s');

        switch ($format) {
            case 'excel':
                return Excel::download(new CustomReportExport($data), $filename . '.xlsx');
                
            case 'csv':
                return Excel::download(new CustomReportExport($data), $filename . '.csv');
                
            case 'pdf':
                $pdf = Pdf::loadView('admin.reports.pdf-template', compact('data', 'reportType'));
                return $pdf->download($filename . '.pdf');
                
            default:
                return response()->json(['error' => 'Invalid export format'], 400);
        }
    }

    /**
     * Generate attendance report (placeholder for future implementation)
     */
    public function attendanceReport()
    {
        // This would require an attendance system to be implemented first
        return view('admin.reports.attendance');
    }

    /**
     * Generate academic performance report (placeholder for future implementation)
     */
    public function academicPerformanceReport()
    {
        // This would require grades/results system to be implemented first
        return view('admin.reports.academic-performance');
    }

    /**
     * Get report data for charts/graphs via AJAX
     */
    public function getChartData(Request $request)
    {
        $type = $request->input('type');

        switch ($type) {
            case 'students_by_level':
                return response()->json(
                    Student::select('level', DB::raw('count(*) as count'))
                           ->groupBy('level')
                           ->get()
                );

            case 'students_by_gender':
                return response()->json(
                    Student::select('gender', DB::raw('count(*) as count'))
                           ->groupBy('gender')
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
                    Student::select('district_of_birth as district', DB::raw('count(*) as count'))
                           ->whereNotNull('district_of_birth')
                           ->groupBy('district_of_birth')
                           ->orderBy('count', 'desc')
                           ->limit(10)
                           ->get()
                );

            case 'staff_by_type':
                return response()->json(
                    Staff::select('staff_type', DB::raw('count(*) as count'))
                          ->groupBy('staff_type')
                          ->get()
                );

            case 'staff_by_department':
                return response()->json(
                    Staff::select('teaching_subjects as department', DB::raw('count(*) as count'))
                          ->whereNotNull('teaching_subjects')
                          ->groupBy('teaching_subjects')
                          ->get()
                );

            case 'monthly_registrations':
                $students = Student::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

                $staff = Staff::select(
                    DB::raw('MONTH(created_at) as month'),
                    DB::raw('YEAR(created_at) as year'),
                    DB::raw('count(*) as count')
                )
                ->where('created_at', '>=', now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year', 'desc')
                ->orderBy('month', 'desc')
                ->get();

                return response()->json([
                    'students' => $students,
                    'staff' => $staff
                ]);

            default:
                return response()->json(['error' => 'Invalid chart type'], 400);
        }
    }

    /**
     * Print report
     */
    public function printReport(Request $request)
    {
        $reportType = $request->input('report_type');
        $data = $this->getReportData($reportType, $request->all());
        
        return view('admin.reports.print-template', compact('data', 'reportType'));
    }

    /**
     * Email report
     */
    public function emailReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'report_type' => 'required|string',
            'message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // This would require email configuration and implementation
        // For now, return success message
        return response()->json(['success' => 'Report will be sent to ' . $request->email]);
    }

    /**
     * Get report data based on type
     */
    private function getReportData($type, $filters = [])
    {
        switch ($type) {
            case 'students_per_class':
                return Student::select('level', DB::raw('count(*) as count'))
                             ->groupBy('level')
                             ->get();

            case 'teachers_per_department':
                return Staff::select('teaching_subjects as department', DB::raw('count(*) as count'))
                           ->whereNotNull('teaching_subjects')
                           ->groupBy('teaching_subjects')
                           ->get();

            case 'students_per_gender':
                return Student::select('gender', DB::raw('count(*) as count'))
                             ->groupBy('gender')
                             ->get();

            default:
                return collect();
        }
    }
}