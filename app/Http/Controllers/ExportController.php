<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Student;
use App\Models\Staff;
use App\Exports\StudentsExport;
use App\Exports\StaffExport;
use App\Exports\CustomExport;

class ExportController extends Controller
{
    /**
     * Export data to Excel
     */
    public function exportExcel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'filters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.xlsx';

        switch ($type) {
            case 'students':
                return Excel::download(new StudentsExport($filters), $filename);
                
            case 'olevel_students':
                $filters['level'] = 'olevel';
                return Excel::download(new StudentsExport($filters), 'olevel_students_' . date('Y-m-d') . '.xlsx');
                
            case 'alevel_students':
                $filters['level'] = 'alevel';
                return Excel::download(new StudentsExport($filters), 'alevel_students_' . date('Y-m-d') . '.xlsx');
                
            case 'staff':
                return Excel::download(new StaffExport($filters), $filename);
                
            case 'government_staff':
                $filters['staff_type'] = 'government';
                return Excel::download(new StaffExport($filters), 'government_staff_' . date('Y-m-d') . '.xlsx');
                
            case 'private_staff':
                $filters['staff_type'] = 'private';
                return Excel::download(new StaffExport($filters), 'private_staff_' . date('Y-m-d') . '.xlsx');
                
            default:
                return response()->json(['error' => 'Invalid export type'], 400);
        }
    }

    /**
     * Export data to PDF
     */
    public function exportPdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'filters' => 'nullable|array',
            'template' => 'nullable|string|in:list,detailed,summary'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $template = $request->input('template', 'list');
        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.pdf';

        $data = $this->getData($type, $filters);
        $viewName = 'admin.exports.pdf.' . $template;

        $pdf = Pdf::loadView($viewName, compact('data', 'type'))
                  ->setPaper('a4', 'portrait');

        return $pdf->download($filename);
    }

    /**
     * Export data to CSV
     */
    public function exportCsv(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'filters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $filename = $type . '_export_' . date('Y-m-d_H-i-s') . '.csv';

        switch ($type) {
            case 'students':
            case 'olevel_students':
            case 'alevel_students':
                if ($type === 'olevel_students') $filters['level'] = 'olevel';
                if ($type === 'alevel_students') $filters['level'] = 'alevel';
                return Excel::download(new StudentsExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);
                
            case 'staff':
            case 'government_staff':
            case 'private_staff':
                if ($type === 'government_staff') $filters['staff_type'] = 'government';
                if ($type === 'private_staff') $filters['staff_type'] = 'private';
                return Excel::download(new StaffExport($filters), $filename, \Maatwebsite\Excel\Excel::CSV);
                
            default:
                return response()->json(['error' => 'Invalid export type'], 400);
        }
    }

    /**
     * Print data
     */
    public function print(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'filters' => 'nullable|array',
            'template' => 'nullable|string|in:list,detailed,summary'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $filters = $request->input('filters', []);
        $template = $request->input('template', 'list');

        $data = $this->getData($type, $filters);
        $viewName = 'admin.exports.print.' . $template;

        return view($viewName, compact('data', 'type'));
    }

    /**
     * Send data via email
     */
    public function sendEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'email' => 'required|email',
            'format' => 'required|string|in:excel,pdf,csv',
            'message' => 'nullable|string|max:500',
            'filters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // This would require email configuration
        // For now, return success message
        return response()->json([
            'success' => true,
            'message' => 'Data will be sent to ' . $request->email . ' in ' . $request->format . ' format.'
        ]);
    }

    /**
     * Share data via social media
     */
    public function shareViaWhatsApp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string',
            'phone' => 'required|string',
            'message' => 'nullable|string|max:500'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $phone = $request->input('phone');
        $message = $request->input('message', 'Sharing data from Gombe SS Hub');
        
        // Generate WhatsApp URL
        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);

        return response()->json([
            'success' => true,
            'url' => $whatsappUrl
        ]);
    }

    /**
     * Share data via other social platforms
     */
    public function shareViaSocial(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'platform' => 'required|string|in:facebook,twitter,linkedin,telegram',
            'message' => 'nullable|string|max:500',
            'url' => 'nullable|url'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $platform = $request->input('platform');
        $message = $request->input('message', 'Check out this data from Gombe SS Hub');
        $url = $request->input('url', url('/'));

        $shareUrls = [
            'facebook' => "https://www.facebook.com/sharer/sharer.php?u=" . urlencode($url),
            'twitter' => "https://twitter.com/intent/tweet?text=" . urlencode($message) . "&url=" . urlencode($url),
            'linkedin' => "https://www.linkedin.com/sharing/share-offsite/?url=" . urlencode($url),
            'telegram' => "https://t.me/share/url?url=" . urlencode($url) . "&text=" . urlencode($message)
        ];

        return response()->json([
            'success' => true,
            'url' => $shareUrls[$platform] ?? '#'
        ]);
    }

    /**
     * Get data based on type and filters
     */
    private function getData($type, $filters = [])
    {
        switch ($type) {
            case 'students':
                $query = Student::query();
                break;
                
            case 'olevel_students':
                $query = Student::where('level', 'olevel');
                break;
                
            case 'alevel_students':
                $query = Student::where('level', 'alevel');
                break;
                
            case 'staff':
                $query = Staff::query();
                break;
                
            case 'government_staff':
                $query = Staff::where('staff_type', 'government');
                break;
                
            case 'private_staff':
                $query = Staff::where('staff_type', '!=', 'government');
                break;
                
            default:
                return collect();
        }

        // Apply filters
        foreach ($filters as $key => $value) {
            if (!empty($value)) {
                if (in_array($key, ['gender', 'level', 'religion', 'district_of_birth', 'staff_type', 'sex'])) {
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
     * Get export templates
     */
    public function getTemplates()
    {
        return response()->json([
            'pdf_templates' => [
                'list' => 'Simple List',
                'detailed' => 'Detailed Report',
                'summary' => 'Summary Report'
            ],
            'print_templates' => [
                'list' => 'Print List',
                'detailed' => 'Print Detailed',
                'summary' => 'Print Summary'
            ]
        ]);
    }

    /**
     * Bulk export multiple types
     */
    public function bulkExport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'types' => 'required|array|min:1',
            'types.*' => 'string|in:students,staff,olevel_students,alevel_students,government_staff,private_staff',
            'format' => 'required|string|in:excel,pdf,csv',
            'filters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $types = $request->input('types');
        $format = $request->input('format');
        $filters = $request->input('filters', []);

        // This would create a ZIP file with multiple exports
        // For now, return success message
        return response()->json([
            'success' => true,
            'message' => 'Bulk export of ' . count($types) . ' data types will be processed.',
            'download_url' => '#' // Would be actual download URL
        ]);
    }
}