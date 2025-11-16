<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Alumni;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class AlumniController extends Controller
{
    /**
     * Display a listing of alumni.
     */
    public function index()
    {
        $alumni = Alumni::orderBy('graduation_year', 'desc')
                       ->orderBy('graduation_class')
                       ->paginate(10);

        return view('admin.alumni.index', compact('alumni'));
    }

    /**
     * Display the specified alumni.
     */
    public function show($id)
    {
        $alumnus = Alumni::findOrFail($id);
        return view('admin.alumni.show', compact('alumnus'));
    }

    /**
     * Export alumni to Excel.
     */
    public function exportExcel()
    {
        return Excel::download(new \App\Exports\AlumniExport, 'alumni.xlsx');
    }

    /**
     * Export alumni to PDF.
     */
    public function exportPdf()
    {
        $alumni = Alumni::all();
        $pdf = Pdf::loadView('admin.alumni.pdf_export_template', compact('alumni'));
        return $pdf->download('alumni.pdf');
    }

    /**
     * Get alumni statistics for dashboard.
     */
    public function getStats()
    {
        $totalAlumni = Alumni::count();
        $olevelAlumni = Alumni::where('graduation_class', 'S4')->count();
        $alevelAlumni = Alumni::where('graduation_class', 'S6')->count();
        $currentYearAlumni = Alumni::where('graduation_year', now()->year)->count();

        return response()->json([
            'total_alumni' => $totalAlumni,
            'olevel_alumni' => $olevelAlumni,
            'alevel_alumni' => $alevelAlumni,
            'current_year_alumni' => $currentYearAlumni,
        ]);
    }

    /**
     * Search alumni.
     */
    public function search(Request $request)
    {
        $query = $request->get('query');

        $alumni = Alumni::where('student_name', 'like', "%{$query}%")
                       ->orWhere('learners_lin', 'like', "%{$query}%")
                       ->orWhere('learners_nin', 'like', "%{$query}%")
                       ->orWhere('graduation_class', 'like', "%{$query}%")
                       ->paginate(10);

        return view('admin.alumni.index', compact('alumni', 'query'));
    }

    /**
     * Show the form for creating a new alumni.
     */
    public function create()
    {
        return view('admin.alumni.create');
    }

    /**
     * Store a newly created alumni in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'student_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255',
            'learners_nin' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'district_of_birth' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'tribe' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'ple_index_number' => 'nullable|string|max:255',
            'uce_index_number' => 'nullable|string|max:255',
            'special_issue' => 'nullable|string',
            'ple_english' => 'nullable|numeric|min:0|max:100',
            'ple_mathematics' => 'nullable|numeric|min:0|max:100',
            'ple_sst' => 'nullable|numeric|min:0|max:100',
            'ple_science' => 'nullable|numeric|min:0|max:100',
            'ple_total' => 'nullable|numeric|min:0|max:500',
            'ple_aggregates' => 'nullable|string|max:10',
            'uce_english' => 'nullable|numeric|min:0|max:100',
            'uce_mathematics' => 'nullable|numeric|min:0|max:100',
            'uce_physics' => 'nullable|numeric|min:0|max:100',
            'uce_chemistry' => 'nullable|numeric|min:0|max:100',
            'uce_biology' => 'nullable|numeric|min:0|max:100',
            'uce_history' => 'nullable|numeric|min:0|max:100',
            'uce_geography' => 'nullable|numeric|min:0|max:100',
            'uce_economics' => 'nullable|numeric|min:0|max:100',
            'uce_literature' => 'nullable|numeric|min:0|max:100',
            'uce_other' => 'nullable|string|max:255',
            'combination' => 'nullable|string|max:255',
            'pass_slip_path' => 'nullable|string|max:255',
            'medical_status' => 'nullable|string|max:255',
            'physical_health' => 'nullable|string|max:255',
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:255',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:255',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'official_comment' => 'nullable|string',
            'level' => 'required|string|in:olevel,alevel',
            'graduation_class' => 'required|string|in:S4,S6',
            'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'stream' => 'nullable|string|max:10',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('alumni_photos', 'public');
            $validatedData['photo_path'] = $photoPath;
        }

        // Remove photo from validated data as it's handled separately
        unset($validatedData['photo']);

        Alumni::create($validatedData);

        return redirect()->route('admin.alumni.index')
                        ->with('success', 'Alumni record created successfully.');
    }

    /**
     * Show the form for editing the specified alumni.
     */
    public function edit($id)
    {
        $alumnus = Alumni::findOrFail($id);
        return view('admin.alumni.edit', compact('alumnus'));
    }

    /**
     * Update the specified alumni in storage.
     */
    public function update(Request $request, $id)
    {
        $alumnus = Alumni::findOrFail($id);

        $validatedData = $request->validate([
            'student_name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gender' => 'required|string|in:Male,Female',
            'learners_lin' => 'nullable|string|max:255',
            'learners_nin' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'district_of_birth' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'nationality' => 'nullable|string|max:255',
            'tribe' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'ple_index_number' => 'nullable|string|max:255',
            'uce_index_number' => 'nullable|string|max:255',
            'special_issue' => 'nullable|string',
            'ple_english' => 'nullable|numeric|min:0|max:100',
            'ple_mathematics' => 'nullable|numeric|min:0|max:100',
            'ple_sst' => 'nullable|numeric|min:0|max:100',
            'ple_science' => 'nullable|numeric|min:0|max:100',
            'ple_total' => 'nullable|numeric|min:0|max:500',
            'ple_aggregates' => 'nullable|string|max:10',
            'uce_english' => 'nullable|numeric|min:0|max:100',
            'uce_mathematics' => 'nullable|numeric|min:0|max:100',
            'uce_physics' => 'nullable|numeric|min:0|max:100',
            'uce_chemistry' => 'nullable|numeric|min:0|max:100',
            'uce_biology' => 'nullable|numeric|min:0|max:100',
            'uce_history' => 'nullable|numeric|min:0|max:100',
            'uce_geography' => 'nullable|numeric|min:0|max:100',
            'uce_economics' => 'nullable|numeric|min:0|max:100',
            'uce_literature' => 'nullable|numeric|min:0|max:100',
            'uce_other' => 'nullable|string|max:255',
            'combination' => 'nullable|string|max:255',
            'pass_slip_path' => 'nullable|string|max:255',
            'medical_status' => 'nullable|string|max:255',
            'physical_health' => 'nullable|string|max:255',
            'father_full_name' => 'nullable|string|max:255',
            'father_mobile_number' => 'nullable|string|max:255',
            'father_email' => 'nullable|email|max:255',
            'father_nin' => 'nullable|string|max:255',
            'father_physical_address' => 'nullable|string|max:255',
            'father_occupation' => 'nullable|string|max:255',
            'father_dead_alive' => 'nullable|string|in:Alive,Dead',
            'mother_full_name' => 'nullable|string|max:255',
            'mother_mobile_number' => 'nullable|string|max:255',
            'mother_email' => 'nullable|email|max:255',
            'mother_nin' => 'nullable|string|max:255',
            'mother_physical_address' => 'nullable|string|max:255',
            'mother_occupation' => 'nullable|string|max:255',
            'mother_dead_alive' => 'nullable|string|in:Alive,Dead',
            'guardian_full_name' => 'nullable|string|max:255',
            'guardian_mobile_number' => 'nullable|string|max:255',
            'guardian_email' => 'nullable|email|max:255',
            'guardian_nin' => 'nullable|string|max:255',
            'guardian_physical_address' => 'nullable|string|max:255',
            'guardian_occupation' => 'nullable|string|max:255',
            'guardian_relationship' => 'nullable|string|max:255',
            'official_comment' => 'nullable|string',
            'level' => 'required|string|in:olevel,alevel',
            'graduation_class' => 'required|string|in:S4,S6',
            'graduation_year' => 'required|integer|min:2000|max:' . (date('Y') + 1),
            'stream' => 'nullable|string|max:10',
        ]);

        // Handle file upload
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($alumnus->photo_path) {
                \Storage::disk('public')->delete($alumnus->photo_path);
            }
            $photoPath = $request->file('photo')->store('alumni_photos', 'public');
            $validatedData['photo_path'] = $photoPath;
        }

        // Remove photo from validated data as it's handled separately
        unset($validatedData['photo']);

        $alumnus->update($validatedData);

        return redirect()->route('admin.alumni.index')
                        ->with('success', 'Alumni record updated successfully.');
    }

    /**
     * Remove the specified alumni from storage.
     */
    public function destroy($id)
    {
        $alumnus = Alumni::findOrFail($id);

        // Delete photo if exists
        if ($alumnus->photo_path) {
            \Storage::disk('public')->delete($alumnus->photo_path);
        }

        $alumnus->delete();

        return redirect()->route('admin.alumni.index')
                        ->with('success', 'Alumni record deleted successfully.');
    }
}
