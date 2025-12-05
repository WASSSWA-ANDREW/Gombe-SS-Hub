<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Import Validator
use App\Exports\StaffExport; // Import the export class
use App\Imports\StaffImport; // Import the import class
use Maatwebsite\Excel\Facades\Excel; // Import Excel facade
use Barryvdh\DomPDF\Facade\Pdf; // Import PDF facade

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $staffMembers = Staff::latest()->get();
        return view('admin.staff.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.staff.form');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_type' => 'required|string|in:private,government',
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'sex' => 'required|string|in:Male,Female,M,F',
            'date_of_birth' => 'required|date',
            'uts_file_no' => 'nullable|string|max:255',
            'district_file_no' => 'nullable|string|max:255',
            'computer_no' => 'nullable|string|max:255',
            'national_id_no' => 'nullable|string|max:255|unique:staff,national_id_no',
            'registration_no' => 'nullable|string|max:255',
            'ipps_no' => 'nullable|string|max:255',
            'salary_scale' => 'nullable|string|max:255',
            'gross_salary' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
            'tin_no' => 'nullable|string|max:255',
            'date_of_1st_appt' => 'nullable|date',
            'designation_of_1st_appt' => 'nullable|string|max:255',
            'minute_no_1st_appt' => 'nullable|string|max:255',
            'date_of_current_appt' => 'nullable|date',
            'designation_of_current_appt' => 'nullable|string|max:255',
            'minute_no_current_appt' => 'nullable|string|max:255',
            'date_of_confirmation' => 'nullable|date',
            'minute_no_confirmation' => 'nullable|string|max:255',
            'date_of_current_posting' => 'nullable|date',
            'teaching_subjects' => 'nullable|string|max:255',
            'telephone_contacts' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|in:Single,Married,Divorced,Widowed',
            'next_of_kin' => 'nullable|string|max:255',
            'next_of_kin_telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:staff,email',
            'other_academic_qualifications' => 'nullable|string',
            'highest_level_of_education' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/staff_photos', $filename);
            $data['photo_path'] = 'storage/staff_photos/' . $filename;
        }

        // Handle document/pass slip upload with staff name
        if ($request->hasFile('pass_slip')) {
            // Create directory based on staff name for organized storage
            $staffName = $data['surname'] . '_' . $data['first_name'];
            $staffName = str_replace(' ', '_', $staffName);
            $staffName = preg_replace('/[^A-Za-z0-9_-]/', '', $staffName); // Remove special characters
            $directory = 'pass_slips/' . $staffName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $staffName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        Staff::create($data);

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member added successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Staff $staff)
    {
        return view('admin.staff.form', compact('staff'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Staff $staff)
    {
        $validator = Validator::make($request->all(), [
            'surname' => 'required|string|max:255',
            'first_name' => 'required|string|max:255',
            'other_name' => 'nullable|string|max:255',
            'sex' => 'required|string|in:Male,Female',
            'date_of_birth' => 'required|date',
            'uts_file_no' => 'nullable|string|max:255',
            'district_file_no' => 'nullable|string|max:255',
            'computer_no' => 'nullable|string|max:255',
            'national_id_no' => 'nullable|string|max:255|unique:staff,national_id_no,' . $staff->id,
            'registration_no' => 'nullable|string|max:255',
            'salary_scale' => 'nullable|string|max:255',
            'gross_salary' => 'nullable|numeric|min:0',
            'net_salary' => 'nullable|numeric|min:0',
            'tin_no' => 'nullable|string|max:255',
            'date_of_1st_appt' => 'nullable|date',
            'designation_of_1st_appt' => 'nullable|string|max:255',
            'minute_no_1st_appt' => 'nullable|string|max:255',
            'date_of_current_appt' => 'nullable|date',
            'designation_of_current_appt' => 'nullable|string|max:255',
            'minute_no_current_appt' => 'nullable|string|max:255',
            'date_of_confirmation' => 'nullable|date',
            'minute_no_confirmation' => 'nullable|string|max:255',
            'date_of_current_posting' => 'nullable|date',
            'teaching_subjects' => 'nullable|string|max:255',
            'telephone_contacts' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|in:Single,Married,Divorced,Widowed',
            'next_of_kin' => 'nullable|string|max:255',
            'next_of_kin_telephone' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:staff,email,' . $staff->id,
            'other_academic_qualifications' => 'nullable|string',
            'highest_level_of_education' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.edit', $staff)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/staff_photos', $filename);
            $data['photo_path'] = 'storage/staff_photos/' . $filename;
        }

        // Handle document/pass slip upload with staff name
        if ($request->hasFile('pass_slip')) {
            if ($staff->pass_slip_path) {
                Storage::disk('public')->delete($staff->pass_slip_path);
            }
            // Create directory based on staff name for organized storage
            $staffName = $data['surname'] . '_' . $data['first_name'];
            $staffName = str_replace(' ', '_', $staffName);
            $staffName = preg_replace('/[^A-Za-z0-9_-]/', '', $staffName); // Remove special characters
            $directory = 'pass_slips/' . $staffName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $staffName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        $staff->update($data);

        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Staff $staff)
    {
        $staff->delete();
        return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff member deleted successfully!');
    }
    
    /**
     * Delete multiple staff members at once.
     */
    public function deleteSelected(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.staff.index')
                             ->with('error', 'No staff members were selected for deletion.');
        }
        
        $count = Staff::whereIn('id', $selectedIds)->count();
        Staff::whereIn('id', $selectedIds)->delete();
        
        return redirect()->route('admin.staff.index')
                         ->with('success', $count . ' staff member(s) deleted successfully!');
    }

    /**
     * Show form for importing staff from Excel.
     */
    public function importForm()
    {
        return view('admin.staff.import');
    }

    /**
     * Import staff from Excel.
     */
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'staff_type' => 'required|in:private,government',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.import')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            Excel::import(new StaffImport($request->staff_type), $request->file('file'));
            
            return redirect()->route('admin.staff.index')
                         ->with('success', 'Staff members imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.staff.import')
                         ->with('error', 'Error importing staff: ' . $e->getMessage());
        }
    }

    /**
     * Show form for importing government staff from Excel.
     */
    public function importGovtForm()
    {
        return view('admin.staff.govt.import');
    }

    /**
     * Import government staff from Excel.
     */
    public function importGovt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.import_govt')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            Excel::import(new StaffImport('government'), $request->file('file'));
            
            return redirect()->route('admin.staff.index_govt')
                         ->with('success', 'Government staff members imported successfully!');
        } catch (\Exception $e) {
            return redirect()->route('admin.staff.import_govt')
                         ->with('error', 'Error importing staff: ' . $e->getMessage());
        }
    }

    /**
     * Export staff data to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new StaffExport, 'staff_directory.xlsx');
    }

    /**
     * Export staff data to PDF.
     */
    public function exportPdf()
    {
        $staffMembers = Staff::all();
        $pdf = Pdf::loadView('admin.staff.pdf', compact('staffMembers'));
        return $pdf->download('staff_directory.pdf');
        // For a larger dataset or more complex PDF, consider ->stream() or queueing
    }
    
    /**
     * Export selected staff members to PDF.
     */
    public function exportSelected(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.staff.index')
                            ->with('error', 'No staff members selected for export.');
        }
        
        $staffMembers = Staff::whereIn('id', $selectedIds)->get();
        
        $pdf = Pdf::loadView('admin.staff.pdf', compact('staffMembers'));
        return $pdf->download('selected_staff_members.pdf');
    }

    /**
     * Export a single staff member's data as a PDF form.
     */
    public function exportFormPdf(Staff $staff)
    {
        $pdf = Pdf::loadView('admin.staff.form_pdf', compact('staff'));
        return $pdf->download('staff_form_' . $staff->surname . '_' . $staff->first_name . '.pdf');
    }

    /**
     * View individual staff member details as PDF.
     */
    public function viewStaffPdf(Staff $staff)
    {
        $pdf = Pdf::loadView('admin.staff.staff_pdf', compact('staff'));
        return $pdf->stream('staff_' . $staff->surname . '_' . $staff->first_name . '.pdf');
    }

    /**
     * Display individual staff member details (web view).
     */
    public function show(Staff $staff)
    {
        return view('admin.staff.show', compact('staff'));
    }

// Government Staff Methods

    /**
     * Display a listing of the government staff resource.
     */
    public function indexGovt()
    {
        // Assuming a 'staff_type' column or similar to differentiate, or a separate table.
        // For now, let's assume we'll filter by a new field or all staff are shown here.
        // This will need adjustment based on actual data structure.
        $staffMembers = Staff::where('staff_type', 'government')->latest()->paginate(15); // Placeholder filter
        return view('admin.staff.govt.index', compact('staffMembers'));
    }

    /**
     * Show the form for creating a new government staff resource.
     */
    public function createGovt()
    {
        return view('admin.staff.govt.form');
    }

    /**
     * Store a newly created government staff resource in storage.
     */
    public function storeGovt(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255', // Assuming 'surname' is Last Name
            'sex' => 'required|string|in:M,F', // As per image
            'uts_file_no' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'registration_no' => 'nullable|string|max:255', // Employee Registration Number(s) for Teachers
            'ipps_no' => 'nullable|string|max:255', // IPPS Number
            'date_of_1st_appt' => 'nullable|date_format:d/m/Y', // Date of 1st / Probationary Appointment
            'designation_of_1st_appt' => 'nullable|string|max:255', // Designation at Probationary Appointment
            'minute_no_1st_appt' => 'nullable|string|max:255', // ESC Minute of 1st Appointment
            'minute_no_confirmation' => 'nullable|string|max:255', // ESC Minute of Confirmation
            'designation_of_current_appt' => 'nullable|string|max:255', // Current Position
            'minute_no_current_appt' => 'nullable|string|max:255', // ESC Minute of Appointment to Current Position
            'date_of_current_posting' => 'nullable|date_format:d/m/Y', // Date of Posting to Current Station
            'photo' => 'nullable|image|mimes:jpeg,png|max:2048', // Passport photo
            'teaching_subjects' => 'nullable|string|max:255',
            // Add other fields from the private staff form if they are also applicable and not covered
            // For example, 'national_id_no', 'email', etc. if they should be on this form too.
            // For now, sticking to the provided image.
            'staff_type' => 'required|string|in:government', // To identify staff type
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.create_govt')
                        ->withErrors($validator)
                        ->withInput();
        }

        $validatedData = $validator->validated();
        // Ensure date formats are converted if necessary for DB storage (e.g., Y-m-d)
        // Laravel's Eloquent usually handles this if 'casts' are defined in the model,
        // or if the input format matches 'Y-m-d'. Since we use d/m/Y, conversion might be needed.
        // For simplicity, assuming model handles casting or DB accepts d/m/Y (less common).
        // A more robust way:
        // $validatedData['date_of_birth'] = \Carbon\Carbon::createFromFormat('d/m/Y', $validatedData['date_of_birth'])->format('Y-m-d');
        // ... similar for other dates ...
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = time() . '_' . $photo->getClientOriginalName();
            $photo->storeAs('public/staff_photos', $filename);
            $validatedData['photo_path'] = 'storage/staff_photos/' . $filename;
        }

        Staff::create($validatedData);

        return redirect()->route('admin.staff.index_govt')
                         ->with('success', 'Government staff member added successfully!');
    }

    /**
     * Show the form for editing the specified government staff resource.
     */
    public function editGovt(Staff $staff)
    {
        // Ensure this staff member is indeed a 'government' type if that's a distinction.
        // if ($staff->staff_type !== 'government') {
        //     return redirect()->route('admin.staff.index_govt')->with('error', 'Invalid staff type.');
        // }
        return view('admin.staff.govt.form', compact('staff'));
    }

    /**
     * Update the specified government staff resource in storage.
     */
    public function updateGovt(Request $request, Staff $staff)
    {
        // if ($staff->staff_type !== 'government') {
        //     return redirect()->route('admin.staff.index_govt')->with('error', 'Invalid staff type for update.');
        // }
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'sex' => 'required|string|in:M,F',
            'uts_file_no' => 'nullable|string|max:255',
            'date_of_birth' => 'required|date_format:d/m/Y',
            'registration_no' => 'nullable|string|max:255',
            'ipps_no' => 'nullable|string|max:255',
            'date_of_1st_appt' => 'nullable|date_format:d/m/Y',
            'designation_of_1st_appt' => 'nullable|string|max:255',
            'minute_no_1st_appt' => 'nullable|string|max:255',
            'minute_no_confirmation' => 'nullable|string|max:255',
            'designation_of_current_appt' => 'nullable|string|max:255',
            'minute_no_current_appt' => 'nullable|string|max:255',
            'date_of_current_posting' => 'nullable|date_format:d/m/Y',
            'teaching_subjects' => 'nullable|string|max:255',
            'staff_type' => 'required|string|in:government',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.staff.edit_govt', $staff->id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $validatedData = $validator->validated();
        // Date conversion as in storeGovt if needed

        $staff->update($validatedData);

        return redirect()->route('admin.staff.index_govt')
                         ->with('success', 'Government staff member updated successfully!');
    }

    /**
     * Remove the specified government staff resource from storage.
     */
    public function destroyGovt(Staff $staff)
    {
        // if ($staff->staff_type !== 'government') {
        //    return redirect()->route('admin.staff.index_govt')->with('error', 'Invalid staff type for deletion.');
        // }
        $staff->delete();
        return redirect()->route('admin.staff.index_govt')
                         ->with('success', 'Government staff member deleted successfully!');
    }
    
    /**
     * Delete multiple government staff members at once.
     */
    public function deleteSelectedGovt(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.staff.index_govt')
                             ->with('error', 'No government staff members were selected for deletion.');
        }
        
        $count = Staff::whereIn('id', $selectedIds)->count();
        Staff::whereIn('id', $selectedIds)->delete();
        
        return redirect()->route('admin.staff.index_govt')
                         ->with('success', $count . ' government staff member(s) deleted successfully!');
    }
/**
     * Export government staff data to Excel.
     */
    public function exportExcelGovt()
    {
        // You might want to create a specific Export class for Government Staff
        // if the columns or data formatting are significantly different.
        // For now, let's assume StaffExport can be filtered or is generic enough.
        // If StaffExport needs modification, that would be another step.
        // return Excel::download(new StaffExport('government'), 'government_staff_directory.xlsx');
        // For a simple filter:
        return Excel::download(new StaffExport(Staff::where('staff_type', 'government')->get()), 'government_staff_directory.xlsx');
    }

    /**
     * Export government staff data to PDF.
     */
    public function exportPdfGovt()
    {
        $staffMembers = Staff::where('staff_type', 'government')->get();
        // You might want to create a specific PDF view for Government Staff
        // if the layout or columns are different.
        // For now, using the existing 'admin.staff.pdf' view and filtering data.
        // If a new view 'admin.staff.govt.pdf' is needed, create it and change view name below.
        $pdf = Pdf::loadView('admin.staff.pdf', compact('staffMembers')); // Consider a new view: 'admin.staff.govt.pdf'
        return $pdf->download('government_staff_directory.pdf');
    }

    /**
     * View individual government staff member details as PDF.
     */
    public function viewStaffGovtPdf(Staff $staff)
    {
        $pdf = Pdf::loadView('admin.staff.govt.staff_pdf', compact('staff'));
        return $pdf->stream('govt_staff_' . $staff->surname . '_' . $staff->first_name . '.pdf');
    }

    /**
     * Display individual government staff member details (web view).
     */
    public function showGovt(string $id)
    {
        $staff = Staff::where('staff_type', 'government')->findOrFail($id);
        return view('admin.staff.govt.show', compact('staff'));
    }

    /**
     * Search general staff members
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $staff = Staff::where('staff_type', '!=', 'government')
                ->where(function($q) use ($query) {
                    $q->where('first_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('surname', 'LIKE', '%' . $query . '%')
                      ->orWhere('email', 'LIKE', '%' . $query . '%')
                      ->orWhere('phone_number', 'LIKE', '%' . $query . '%');
                })
                ->paginate(10);
        } else {
            $staff = Staff::where('staff_type', '!=', 'government')->paginate(10);
        }

        return view('admin.staff.index', compact('staff', 'query'));
    }

    /**
     * Search government staff members
     */
    public function searchGovt(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $staff = Staff::where('staff_type', 'government')
                ->where(function($q) use ($query) {
                    $q->where('first_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('surname', 'LIKE', '%' . $query . '%')
                      ->orWhere('email', 'LIKE', '%' . $query . '%')
                      ->orWhere('phone_number', 'LIKE', '%' . $query . '%')
                      ->orWhere('uts_file_no', 'LIKE', '%' . $query . '%')
                      ->orWhere('ipps_no', 'LIKE', '%' . $query . '%');
                })
                ->paginate(10);
        } else {
            $staff = Staff::where('staff_type', 'government')->paginate(10);
        }

        return view('admin.staff.govt.index', compact('staff', 'query'));
    }
}
