<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileUploadController extends Controller
{
    /**
     * Display all uploaded files (pass slips and documents)
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'students'); // 'students' or 'staff'
        
        $files = [];
        
        if ($type === 'students') {
            // Get all students with uploaded pass slips
            $students = Student::whereNotNull('pass_slip_path')->where('pass_slip_path', '!=', '')->get();

            foreach ($students as $student) {
                if ($student->pass_slip_path && Storage::disk('public')->exists($student->pass_slip_path)) {
                    $fileInfo = [
                        'id' => $student->id,
                        'owner_name' => $student->student_name,
                        'owner_type' => 'Student',
                        'file_path' => $student->pass_slip_path,
                        'file_name' => basename($student->pass_slip_path),
                        'file_size' => Storage::disk('public')->size($student->pass_slip_path),
                        'upload_date' => Storage::disk('public')->lastModified($student->pass_slip_path),
                        'url' => Storage::url($student->pass_slip_path),
                        'download_url' => route('admin.files.download', ['id' => $student->id, 'type' => 'students'])
                    ];
                    $files[] = $fileInfo;
                }
            }
        } else {
            // Get all staff with uploaded documents
            $staffMembers = Staff::whereNotNull('pass_slip_path')->where('pass_slip_path', '!=', '')->get();

            foreach ($staffMembers as $staff) {
                if ($staff->pass_slip_path && Storage::disk('public')->exists($staff->pass_slip_path)) {
                    $fileInfo = [
                        'id' => $staff->id,
                        'owner_name' => $staff->surname . ' ' . $staff->first_name,
                        'owner_type' => 'Staff',
                        'file_path' => $staff->pass_slip_path,
                        'file_name' => basename($staff->pass_slip_path),
                        'file_size' => Storage::disk('public')->size($staff->pass_slip_path),
                        'upload_date' => Storage::disk('public')->lastModified($staff->pass_slip_path),
                        'url' => Storage::url($staff->pass_slip_path),
                        'download_url' => route('admin.files.download', ['id' => $staff->id, 'type' => 'staff'])
                    ];
                    $files[] = $fileInfo;
                }
            }
        }
        
        // Sort by upload date (newest first)
        usort($files, function ($a, $b) {
            return $b['upload_date'] <=> $a['upload_date'];
        });
        
        return view('admin.files.index', compact('files', 'type'));
    }

    /**
     * Download a specific file
     */
    public function download(Request $request, $id, $type)
    {
        if ($type === 'students') {
            $owner = Student::findOrFail($id);
            $filePath = $owner->pass_slip_path;
        } else {
            $owner = Staff::findOrFail($id);
            $filePath = $owner->pass_slip_path;
        }
        
        if (!$filePath || !Storage::disk('public')->exists($filePath)) {
            return abort(404, 'File not found');
        }
        
        return Storage::disk('public')->download($filePath);
    }

    /**
     * Delete a file
     */
    public function delete(Request $request, $id, $type)
    {
        if ($type === 'students') {
            $owner = Student::findOrFail($id);
            if ($owner->pass_slip_path && Storage::disk('public')->exists($owner->pass_slip_path)) {
                Storage::disk('public')->delete($owner->pass_slip_path);
                $owner->update(['pass_slip_path' => null]);
            }
        } else {
            $owner = Staff::findOrFail($id);
            if ($owner->pass_slip_path && Storage::disk('public')->exists($owner->pass_slip_path)) {
                Storage::disk('public')->delete($owner->pass_slip_path);
                $owner->update(['pass_slip_path' => null]);
            }
        }
        
        return redirect()->back()->with('success', 'File deleted successfully!');
    }
}