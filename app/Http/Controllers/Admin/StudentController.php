<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage; // Added for file handling
// Assuming you will create a Student model, e.g., App\Models\OlevelStudent or a generic Student model
// For now, we'll use a placeholder. If you have a Student model, uncomment the line below.
use App\Models\Student; // Assuming Student model exists or will be created
use App\Models\StudentOptionalSubject;
use App\Models\OLevelSubject;
use App\Models\ALevelSubject;
use App\Exports\OlevelStudentsExport;
use App\Exports\AlevelStudentsExport; // Will be created
use App\Imports\StudentsImport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf; // Or use PDF facade directly if aliased

class StudentController extends Controller
{
    /**
     * Display a listing of O'Level students.
     */
    public function indexOlevel()
    {
        // Fetch O'Level students from the database
        // Ensure your Student model has a 'level' column or similar to filter O'Level students.
        // If you are not using a 'level' column, adjust the query accordingly.
        // Example: $students = Student::all(); if you want all students for now.
        // Example: $students = Student::where('student_type', 'O-Level')->paginate(10);
        
        // Using the original placeholder logic:
        $students = Student::where('level', 'olevel')->paginate(10); // Assumes 'level' column and pagination
        
        return view('admin.students.olevel.index', compact('students'));
    }

    /**
     * Show the form for creating a new O'Level student.
     */
    public function createOlevel()
    {
        return view('admin.students.olevel.form');
    }

    /**
     * Store a newly created O'Level student in storage.
     */
    public function storeOlevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin', // Assuming 'students' table
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin',
            'admission_number' => 'nullable|string|max:255|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'ple_index_number' => 'nullable|string|max:255|unique:students,ple_index_number',
            'special_issue' => 'nullable|string',
            'ple_english' => 'nullable|string|max:10',
            'ple_mathematics' => 'nullable|string|max:10',
            'ple_sst' => 'nullable|string|max:10',
            'ple_science' => 'nullable|string|max:10',
            'ple_total' => 'nullable|numeric',
            'ple_aggregates' => 'nullable|numeric',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // Example validation for file
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'father_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'guardian_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'official_comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.olevel.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('pass_slip')) {
            // Create directory based on student name for organized storage
            $studentName = str_replace(' ', '_', $data['student_name']); // Replace spaces with underscores
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName); // Remove special characters
            $directory = 'pass_slips/' . $studentName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $studentName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Handle parent passport photos
        if ($request->hasFile('father_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('father_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'father_' . time() . '.' . $extension;
            $data['father_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('mother_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'mother_' . time() . '.' . $extension;
            $data['mother_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('guardian_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'guardian_' . time() . '.' . $extension;
            $data['guardian_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Set the level for the student
        $data['level'] = 'olevel';

        // Create student record using your Student model
        $student = Student::create($data);

        if ($request->has('optional_subjects') && is_array($request->input('optional_subjects'))) {
            foreach ($request->input('optional_subjects') as $subjectId) {
                if (!empty($subjectId)) {
                    $student->addOptionalSubject([
                        'olevel_subject_id' => $subjectId,
                        'level' => 'olevel',
                        'stream' => $data['stream'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.students.olevel.index')
                         ->with('success', "O'Level student created successfully!");
    }

    /**
     * Show the form for editing the specified O'Level student.
     */
    public function editOlevel(string $id) // Assuming $id is the student's ID
    {
        $student = Student::findOrFail($id);
        return view('admin.students.olevel.form', compact('student'));
    }

    /**
     * Update the specified O'Level student in storage.
     */
    public function updateOlevel(Request $request, string $id)
    {
        $student = Student::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin,' . $id, // Ignore current student
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin,' . $id,
            'admission_number' => 'nullable|string|max:255|unique:students,admission_number,' . $id,
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email,' . $id,
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'ple_index_number' => 'nullable|string|max:255|unique:students,ple_index_number,' . $id,
            'special_issue' => 'nullable|string',
            'ple_english' => 'nullable|string|max:10',
            'ple_mathematics' => 'nullable|string|max:10',
            'ple_sst' => 'nullable|string|max:10',
            'ple_science' => 'nullable|string|max:10',
            'ple_total' => 'nullable|numeric',
            'ple_aggregates' => 'nullable|numeric',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'father_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'guardian_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'official_comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.olevel.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        if ($request->hasFile('pass_slip')) {
            // Optionally, delete old file if it exists
            if ($student->pass_slip_path) {
                Storage::disk('public')->delete($student->pass_slip_path);
            }
            // Create directory based on student name for organized storage
            $studentName = str_replace(' ', '_', $data['student_name']); // Replace spaces with underscores
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName); // Remove special characters
            $directory = 'pass_slips/' . $studentName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $studentName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Handle parent passport photos
        if ($request->hasFile('father_passport_photo')) {
            if ($student->father_passport_photo_path) {
                Storage::disk('public')->delete($student->father_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('father_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'father_' . time() . '.' . $extension;
            $data['father_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            if ($student->mother_passport_photo_path) {
                Storage::disk('public')->delete($student->mother_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('mother_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'mother_' . time() . '.' . $extension;
            $data['mother_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            if ($student->guardian_passport_photo_path) {
                Storage::disk('public')->delete($student->guardian_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('guardian_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'guardian_' . time() . '.' . $extension;
            $data['guardian_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Ensure level is not accidentally changed if not part of the form
        // If 'level' can be edited, it should be in the validation rules and $data
        // For now, we assume 'level' is fixed for O'Level students during an update.
        // If you want to allow changing the level, add 'level' to the fillable array in Student model
        // and include it in the form and validation.

        $student->update($data);

        if ($request->has('optional_subjects') && is_array($request->input('optional_subjects'))) {
            $student->optionalSubjects()->where('level', 'olevel')->delete();
            foreach ($request->input('optional_subjects') as $subjectId) {
                if (!empty($subjectId)) {
                    $student->addOptionalSubject([
                        'olevel_subject_id' => $subjectId,
                        'level' => 'olevel',
                        'stream' => $data['stream'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.students.olevel.index')
                         ->with('success', "O'Level student data updated successfully!");
    }

    /**
     * Remove the specified O'Level student from storage.
     */
    public function destroyOlevel(string $id)
    {
        $student = Student::where('level', 'olevel')->findOrFail($id);
        if ($student->pass_slip_path) {
            Storage::disk('public')->delete($student->pass_slip_path);
        }
        $student->deleteStudent();

        return redirect()->route('admin.students.olevel.index')
                         ->with('success', "O'Level student deleted successfully!");
    }
    
    /**
     * Delete multiple O'Level students at once.
     */
    public function deleteSelectedOlevel(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.students.olevel.index')
                             ->with('error', "No O'Level students were selected for deletion.");
        }
        
        $students = Student::where('level', 'olevel')->whereIn('id', $selectedIds)->get();
        $count = $students->count();
        
        foreach ($students as $student) {
            if ($student->pass_slip_path) {
                Storage::disk('public')->delete($student->pass_slip_path);
            }
            $student->delete();
        }
        
        return redirect()->route('admin.students.olevel.index')
                         ->with('success', $count . " O'Level student(s) deleted successfully!");
    }


    // Generic methods (can be removed or adapted if only O'Level/A'Level specific methods are used)

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Default to O'Level create form or a selection page
        return redirect()->route('admin.students.olevel.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // This generic store might not be used if you have specific storeOlevel, storeAlevel.
        // If used, you'd need logic to determine student type.
        return $this->storeOlevel($request); // Example: defaults to O'Level
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // Placeholder: Show a specific student (could be O'Level or A'Level)
        // $student = Student::findOrFail($id);
        // return view('admin.students.show', compact('student'));
        return "Show student: " . $id . " (Placeholder)";
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Placeholder: Determine student type and redirect to specific edit form
        // $student = Student::findOrFail($id);
        // if ($student->level === 'olevel') { // Assuming a 'level' attribute
        //     return redirect()->route('admin.students.olevel.edit', $id);
        // }
        // Handle other levels or show a generic edit form
        return redirect()->route('admin.students.olevel.edit', $id); // Example
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Placeholder: Determine student type and use specific update logic
        return $this->updateOlevel($request, $id); // Example
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Placeholder: Determine student type and use specific delete logic
        return $this->destroyOlevel($id); // Example
    }

    /**
     * Show form for importing O'Level students from Excel.
     */
    public function importOlevelForm()
    {
        return view('admin.students.olevel.import');
    }

    /**
     * Import O'Level students from Excel.
     */
    public function importOlevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.olevel.import')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            Excel::import(new StudentsImport('olevel'), $request->file('file'));
            
            return redirect()->route('admin.students.olevel.index')
                         ->with('success', "O'Level students imported successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.students.olevel.import')
                         ->with('error', "Error importing students: " . $e->getMessage());
        }
    }

    /**
     * Show form for importing A'Level students from Excel.
     */
    public function importAlevelForm()
    {
        return view('admin.students.alevel.import');
    }

    /**
     * Import A'Level students from Excel.
     */
    public function importAlevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.alevel.import')
                        ->withErrors($validator)
                        ->withInput();
        }

        try {
            Excel::import(new StudentsImport('alevel'), $request->file('file'));
            
            return redirect()->route('admin.students.alevel.index')
                         ->with('success', "A'Level students imported successfully!");
        } catch (\Exception $e) {
            return redirect()->route('admin.students.alevel.import')
                         ->with('error', "Error importing students: " . $e->getMessage());
        }
    }

    /**
     * Export O'Level students to Excel.
     */
    public function exportOlevelExcel()
    {
        return Excel::download(new OlevelStudentsExport, 'olevel_students.xlsx');
    }

    /**
     * Export O'Level students to PDF.
     */
    public function exportOlevelPdf()
    {
        $students = Student::where('level', 'olevel')->get();
        $pdf = Pdf::loadView('admin.students.olevel.pdf_export_template', compact('students'));
        return $pdf->download('olevel_students.pdf');
    }
    
    /**
     * Export selected O'Level students.
     */
    public function exportSelectedOlevel(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.students.olevel.index')
                             ->with('error', "No O'Level students were selected for export.");
        }
        
        $students = Student::where('level', 'olevel')->whereIn('id', $selectedIds)->get();
        
        // Determine export format (you could add a parameter to the request)
        $format = $request->input('format', 'excel'); // Default to Excel
        
        if ($format === 'excel') {
            // Create a custom export class or use a collection export
            return Excel::download(new OlevelStudentsExport($students), 'selected_olevel_students.xlsx');
        } else {
            // PDF export
            $pdf = Pdf::loadView('admin.students.olevel.pdf_export_template', compact('students'));
            return $pdf->download('selected_olevel_students.pdf');
        }
    }

    /**
     * View individual O'Level student details as PDF.
     */
    public function viewOlevelPdf(string $id)
    {
        $student = Student::where('level', 'olevel')->findOrFail($id);
        $pdf = Pdf::loadView('admin.students.olevel.student_pdf', compact('student'));
        return $pdf->stream('olevel_student_' . $student->learners_lin . '.pdf');
    }

    /**
     * Display individual O'Level student details (web view).
     */
    public function showOlevel(string $id)
    {
        $student = Student::where('level', 'olevel')->findOrFail($id);
        return view('admin.students.olevel.show', compact('student'));
    }

    // A-Level Student Methods
    // ==========================================================================================

    /**
     * Display a listing of A'Level students.
     */
    public function indexAlevel()
    {
        $students = Student::where('level', 'alevel')->paginate(10);
        return view('admin.students.alevel.index', compact('students')); // View to be created
    }

    /**
     * Show the form for creating a new A'Level student.
     */
    public function createAlevel()
    {
        return view('admin.students.alevel.form'); // View to be created
    }

    /**
     * Store a newly created A'Level student in storage.
     */
    public function storeAlevel(Request $request)
    {
        // Validation rules might differ for A-Level, adjust as needed
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin',
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin',
            'admission_number' => 'nullable|string|max:255|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255', // This might be UCE school
            'uce_index_number' => 'nullable|string|max:255|unique:students,uce_index_number',
            'class' => 'required|string|in:S.5,S.6',
            'stream' => 'required|string|in:A,B,C,D,E,G,H,T',
            'combination' => 'required|string|max:255',
            'other_combination' => 'nullable|string|max:255',
            'medical_status' => 'required|string|in:Healthy,Medical care',
            'physical_health' => 'required|string|in:Fit,Disabled',
            'special_issue' => 'nullable|string',
            'uce_english' => 'nullable|string|max:10',
            'uce_mathematics' => 'nullable|string|max:10',
            'uce_physics' => 'nullable|string|max:10',
            'uce_chemistry' => 'nullable|string|max:10',
            'uce_biology' => 'nullable|string|max:10',
            'uce_history' => 'nullable|string|max:10',
            'uce_geography' => 'nullable|string|max:10',
            'uce_economics' => 'nullable|string|max:10',
            'uce_literature' => 'nullable|string|max:10',
            'uce_other' => 'nullable|string|max:10',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048', // UCE pass slip?
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'father_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'guardian_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'official_comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.alevel.create')
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        // Handle combination
        if ($data['combination'] === 'Other') {
            $data['combination'] = $data['other_combination'];
            unset($data['other_combination']);
        } else {
            unset($data['other_combination']);
        }

        if ($request->hasFile('pass_slip')) {
            // Create directory based on student name for organized storage
            $studentName = str_replace(' ', '_', $data['student_name']); // Replace spaces with underscores
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName); // Remove special characters
            $directory = 'pass_slips/' . $studentName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $studentName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Handle parent passport photos
        if ($request->hasFile('father_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('father_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'father_' . time() . '.' . $extension;
            $data['father_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('mother_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'mother_' . time() . '.' . $extension;
            $data['mother_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('guardian_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'guardian_' . time() . '.' . $extension;
            $data['guardian_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        $data['level'] = 'alevel';
        $student = Student::create($data);

        if ($request->has('subsidiary_subjects') && is_array($request->input('subsidiary_subjects'))) {
            foreach ($request->input('subsidiary_subjects') as $subjectId) {
                if (!empty($subjectId)) {
                    $student->addOptionalSubject([
                        'alevel_subject_id' => $subjectId,
                        'level' => 'alevel',
                        'stream' => $data['stream'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.students.alevel.index')
                         ->with('success', "A'Level student created successfully!");
    }

    /**
     * Show the form for editing the specified A'Level student.
     */
    public function editAlevel(string $id)
    {
        $student = Student::where('level', 'alevel')->findOrFail($id);
        return view('admin.students.alevel.form', compact('student')); // View to be created
    }

    /**
     * Update the specified A'Level student in storage.
     */
    public function updateAlevel(Request $request, string $id)
    {
        $student = Student::where('level', 'alevel')->findOrFail($id);
        // Validation rules might differ for A-Level, adjust as needed
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin,' . $id,
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin,' . $id,
            'admission_number' => 'nullable|string|max:255|unique:students,admission_number,' . $id,
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email,' . $id,
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'uce_index_number' => 'nullable|string|max:255|unique:students,uce_index_number,' . $id,
            'class' => 'required|string|in:S.5,S.6',
            'stream' => 'required|string|in:A,B,C,D,E,G,H,T',
            'combination' => 'required|string|max:255',
            'other_combination' => 'nullable|string|max:255',
            'medical_status' => 'required|string|in:Healthy,Medical care',
            'physical_health' => 'required|string|in:Fit,Disabled',
            'special_issue' => 'nullable|string',
            'uce_english' => 'nullable|string|max:10',
            'uce_mathematics' => 'nullable|string|max:10',
            'uce_physics' => 'nullable|string|max:10',
            'uce_chemistry' => 'nullable|string|max:10',
            'uce_biology' => 'nullable|string|max:10',
            'uce_history' => 'nullable|string|max:10',
            'uce_geography' => 'nullable|string|max:10',
            'uce_economics' => 'nullable|string|max:10',
            'uce_literature' => 'nullable|string|max:10',
            'uce_other' => 'nullable|string|max:10',
            'pass_slip' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:20',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'father_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:20',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:20',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'guardian_passport_photo' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
            'official_comment' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.students.alevel.edit', $id)
                        ->withErrors($validator)
                        ->withInput();
        }

        $data = $validator->validated();

        // Handle combination
        if ($data['combination'] === 'Other') {
            $data['combination'] = $data['other_combination'];
            unset($data['other_combination']);
        } else {
            unset($data['other_combination']);
        }

        if ($request->hasFile('pass_slip')) {
            if ($student->pass_slip_path) {
                Storage::disk('public')->delete($student->pass_slip_path);
            }
            // Create directory based on student name for organized storage
            $studentName = str_replace(' ', '_', $data['student_name']); // Replace spaces with underscores
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName); // Remove special characters
            $directory = 'pass_slips/' . $studentName;
            
            // Store file with owner's name in the filename
            $file = $request->file('pass_slip');
            $extension = $file->getClientOriginalExtension();
            $filename = $studentName . '_' . time() . '.' . $extension;
            $data['pass_slip_path'] = $file->storeAs($directory, $filename, 'public');
        }

        // Handle parent passport photos
        if ($request->hasFile('father_passport_photo')) {
            if ($student->father_passport_photo_path) {
                Storage::disk('public')->delete($student->father_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('father_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'father_' . time() . '.' . $extension;
            $data['father_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            if ($student->mother_passport_photo_path) {
                Storage::disk('public')->delete($student->mother_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('mother_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'mother_' . time() . '.' . $extension;
            $data['mother_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            if ($student->guardian_passport_photo_path) {
                Storage::disk('public')->delete($student->guardian_passport_photo_path);
            }
            $studentName = str_replace(' ', '_', $data['student_name']);
            $studentName = preg_replace('/[^A-Za-z0-9_-]/', '', $studentName);
            $directory = 'parent_photos/' . $studentName;
            $file = $request->file('guardian_passport_photo');
            $extension = $file->getClientOriginalExtension();
            $filename = 'guardian_' . time() . '.' . $extension;
            $data['guardian_passport_photo_path'] = $file->storeAs($directory, $filename, 'public');
        }

        $student->update($data);

        if ($request->has('subsidiary_subjects') && is_array($request->input('subsidiary_subjects'))) {
            $student->optionalSubjects()->where('level', 'alevel')->delete();
            foreach ($request->input('subsidiary_subjects') as $subjectId) {
                if (!empty($subjectId)) {
                    $student->addOptionalSubject([
                        'alevel_subject_id' => $subjectId,
                        'level' => 'alevel',
                        'stream' => $data['stream'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('admin.students.alevel.index')
                         ->with('success', "A'Level student data updated successfully!");
    }

    /**
     * Remove the specified A'Level student from storage.
     */
    public function destroyAlevel(string $id)
    {
        $student = Student::where('level', 'alevel')->findOrFail($id);
        if ($student->pass_slip_path) {
            Storage::disk('public')->delete($student->pass_slip_path);
        }
        $student->deleteStudent();

        return redirect()->route('admin.students.alevel.index')
                         ->with('success', "A'Level student deleted successfully!");
    }
    
    /**
     * Delete multiple A'Level students at once.
     */
    public function deleteSelectedAlevel(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.students.alevel.index')
                             ->with('error', "No A'Level students were selected for deletion.");
        }
        
        $students = Student::where('level', 'alevel')->whereIn('id', $selectedIds)->get();
        $count = $students->count();
        
        foreach ($students as $student) {
            if ($student->pass_slip_path) {
                Storage::disk('public')->delete($student->pass_slip_path);
            }
            $student->delete();
        }
        
        return redirect()->route('admin.students.alevel.index')
                         ->with('success', $count . " A'Level student(s) deleted successfully!");
    }

    /**
     * Export A'Level students to Excel.
     */
    public function exportAlevelExcel()
    {
        return Excel::download(new AlevelStudentsExport, 'alevel_students.xlsx'); // Export class to be created
    }

    /**
     * Export A'Level students to PDF.
     */
    public function exportAlevelPdf()
    {
        $students = Student::where('level', 'alevel')->get();
        $pdf = Pdf::loadView('admin.students.alevel.pdf_export_template', compact('students')); // View to be created
        return $pdf->download('alevel_students.pdf');
    }

    /**
     * View individual A'Level student details as PDF.
     */
    public function viewAlevelPdf(string $id)
    {
        $student = Student::where('level', 'alevel')->findOrFail($id);
        $pdf = Pdf::loadView('admin.students.alevel.student_pdf', compact('student'));
        return $pdf->stream('alevel_student_' . $student->learners_lin . '.pdf');
    }

    /**
     * Display individual A'Level student details (web view).
     */
    public function showAlevel(string $id)
    {
        $student = Student::where('level', 'alevel')->findOrFail($id);
        return view('admin.students.alevel.show', compact('student'));
    }

    /**
     * Export selected A'Level students.
     */
    public function exportSelectedAlevel(Request $request)
    {
        $selectedIds = $request->input('selected_ids', []);
        
        if (empty($selectedIds)) {
            return redirect()->route('admin.students.alevel.index')
                             ->with('error', "No A'Level students were selected for export.");
        }
        
        $students = Student::where('level', 'alevel')->whereIn('id', $selectedIds)->get();
        
        // Determine export format (you could add a parameter to the request)
        $format = $request->input('format', 'excel'); // Default to Excel
        
        if ($format === 'excel') {
            // Create a custom export class or use a collection export
            return Excel::download(new AlevelStudentsExport($students), 'selected_alevel_students.xlsx');
        } else {
            // PDF export
            $pdf = Pdf::loadView('admin.students.alevel.pdf_export_template', compact('students'));
            return $pdf->download('selected_alevel_students.pdf');
        }
    }

    /**
     * Search O'Level students
     */
    public function searchOlevel(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $students = Student::where('level', 'olevel')
                ->where(function($q) use ($query) {
                    $q->where('student_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('learners_lin', 'LIKE', '%' . $query . '%')
                      ->orWhere('learners_nin', 'LIKE', '%' . $query . '%')
                      ->orWhere('previous_school', 'LIKE', '%' . $query . '%');
                })
                ->paginate(10);
        } else {
            $students = Student::where('level', 'olevel')->paginate(10);
        }

        return view('admin.students.olevel.index', compact('students', 'query'));
    }

    /**
     * Search A'Level students
     */
    public function searchAlevel(Request $request)
    {
        $query = $request->get('query');

        if ($query) {
            $students = Student::where('level', 'alevel')
                ->where(function($q) use ($query) {
                    $q->where('student_name', 'LIKE', '%' . $query . '%')
                      ->orWhere('learners_lin', 'LIKE', '%' . $query . '%')
                      ->orWhere('learners_nin', 'LIKE', '%' . $query . '%')
                      ->orWhere('combination', 'LIKE', '%' . $query . '%')
                      ->orWhere('previous_school', 'LIKE', '%' . $query . '%');
                })
                ->paginate(10);
        } else {
            $students = Student::where('level', 'alevel')->paginate(10);
        }

        return view('admin.students.alevel.index', compact('students', 'query'));
    }
}
