<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\StudentOptionalSubject;
use App\Models\OLevelSubject;
use App\Models\ALevelSubject;
use App\Models\Alumni;
use App\Exports\OlevelStudentsExport;
use App\Exports\AlevelStudentsExport;
use App\Imports\StudentsImport;
use App\Services\NotificationService;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    /**
     * Class progression mapping for O'Level and A'Level
     */
    private const CLASS_PROGRESSION = [
        'S.1' => 'S.2',
        'S.2' => 'S.3',
        'S.3' => 'S.4',
        'S.4' => null, // Graduates to Alumni
        'S.5' => 'S.6',
        'S.6' => null, // Graduates to Alumni
    ];

    /**
     * Display a listing of O'Level students.
     */
    public function indexOlevel()
    {
        $students = Student::where('level', 'olevel')->paginate(10);
        
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
            'class' => 'required|string|in:S.1,S.2,S.3,S.4',
            'stream' => 'required|string|in:A,B,C,D,E,G,H,T',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin',
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin',
            'admission_number' => 'required|string|max:255|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'ple_index_number' => 'nullable|string|max:255|unique:students,ple_index_number',
            'medical_status' => 'required|string|in:Healthy,Medical care',
            'physical_health' => 'required|string|in:Fit,Disabled',
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
            'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['level'] = 'olevel';

        // Handle file uploads
        if ($request->hasFile('pass_slip')) {
            $data['pass_slip_path'] = $request->file('pass_slip')->store('students/olevel/pass_slips', 'public');
        }

        if ($request->hasFile('father_passport_photo')) {
            $data['father_passport_photo_path'] = $request->file('father_passport_photo')->store('students/olevel/parents', 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            $data['mother_passport_photo_path'] = $request->file('mother_passport_photo')->store('students/olevel/parents', 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            $data['guardian_passport_photo_path'] = $request->file('guardian_passport_photo')->store('students/olevel/guardians', 'public');
        }

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('students/olevel/photos', 'public');
        }

        $student = Student::create($data);
        NotificationService::notifyStudentDataEntered($student, 'created');

        return redirect()->route('admin.students.olevel.index')->with('success', 'O\'Level student created successfully');
    }

    /**
     * Display all A'Level students
     */
    public function indexAlevel()
    {
        $students = Student::where('level', 'alevel')->paginate(10);
        
        return view('admin.students.alevel.index', compact('students'));
    }

    /**
     * Show form for creating A'Level student
     */
    public function createAlevel()
    {
        return view('admin.students.alevel.form');
    }

    /**
     * Store a newly created A'Level student
     */
    public function storeAlevel(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'class' => 'required|string|in:S.5,S.6',
            'stream' => 'required|string|in:A,B,C,D',
            'learners_lin' => 'nullable|string|max:255|unique:students,learners_lin',
            'learners_nin' => 'nullable|string|max:255|unique:students,learners_nin',
            'admission_number' => 'required|string|max:255|unique:students,admission_number',
            'date_of_birth' => 'required|date',
            'religion' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255|unique:students,email',
            'district_of_birth' => 'nullable|string|max:255',
            'previous_school' => 'nullable|string|max:255',
            'uce_index_number' => 'nullable|string|max:255|unique:students,uce_index_number',
            'medical_status' => 'required|string|in:Healthy,Medical care',
            'physical_health' => 'required|string|in:Fit,Disabled',
            'special_issue' => 'nullable|string',
            'combination' => 'required|string|max:255',
            'uce_english' => 'nullable|string|max:10',
            'uce_mathematics' => 'nullable|string|max:10',
            'uce_physics' => 'nullable|string|max:10',
            'uce_chemistry' => 'nullable|string|max:10',
            'uce_biology' => 'nullable|string|max:10',
            'uce_history' => 'nullable|string|max:10',
            'uce_geography' => 'nullable|string|max:10',
            'uce_economics' => 'nullable|string|max:10',
            'uce_literature' => 'nullable|string|max:10',
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
            'photo_path' => 'nullable|file|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();
        $data['level'] = 'alevel';

        // Handle file uploads
        if ($request->hasFile('father_passport_photo')) {
            $data['father_passport_photo_path'] = $request->file('father_passport_photo')->store('students/alevel/parents', 'public');
        }

        if ($request->hasFile('mother_passport_photo')) {
            $data['mother_passport_photo_path'] = $request->file('mother_passport_photo')->store('students/alevel/parents', 'public');
        }

        if ($request->hasFile('guardian_passport_photo')) {
            $data['guardian_passport_photo_path'] = $request->file('guardian_passport_photo')->store('students/alevel/guardians', 'public');
        }

        if ($request->hasFile('photo_path')) {
            $data['photo_path'] = $request->file('photo_path')->store('students/alevel/photos', 'public');
        }

        $student = Student::create($data);
        NotificationService::notifyStudentDataEntered($student, 'created');

        return redirect()->route('admin.students.alevel.index')->with('success', 'A\'Level student created successfully');
    }

    /**
     * Edit O'Level student
     */
    public function editOlevel(Student $student)
    {
        return view('admin.students.olevel.form', compact('student'));
    }

    /**
     * Update O'Level student
     */
    public function updateOlevel(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'class' => 'required|string|in:S.1,S.2,S.3,S.4',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student->update($validator->validated());

        return redirect()->route('admin.students.olevel.index')->with('success', 'Student updated successfully');
    }

    /**
     * Delete O'Level student
     */
    public function destroyOlevel(Student $student)
    {
        $student->deleteStudent();

        return redirect()->route('admin.students.olevel.index')->with('success', 'Student deleted successfully');
    }

    /**
     * Export O'Level students to Excel
     */
    public function exportOlevelExcel()
    {
        return Excel::download(new OlevelStudentsExport, 'olevel_students.xlsx');
    }

    /**
     * Export O'Level students to PDF
     */
    public function exportOlevelPdf()
    {
        $students = Student::where('level', 'olevel')->get();
        $pdf = Pdf::loadView('admin.students.olevel.pdf', compact('students'));
        return $pdf->download('olevel_students.pdf');
    }

    /**
     * Export selected O'Level students
     */
    public function exportSelectedOlevel(Request $request)
    {
        $ids = $request->input('ids', []);
        $students = Student::whereIn('id', $ids)->where('level', 'olevel')->get();

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.students.olevel.pdf', compact('students'));
            return $pdf->download('selected_students.pdf');
        }

        return Excel::download(new OlevelStudentsExport($students), 'selected_students.xlsx');
    }

    /**
     * View O'Level student PDF
     */
    public function viewOlevelPdf(Student $student)
    {
        $pdf = Pdf::loadView('admin.students.olevel.pdf-single', compact('student'));
        return $pdf->stream('student.pdf');
    }

    /**
     * Show O'Level student details
     */
    public function showOlevel(Student $student)
    {
        return view('admin.students.olevel.show', compact('student'));
    }

    /**
     * Delete selected O'Level students
     */
    public function deleteSelectedOlevel(Request $request)
    {
        $ids = $request->input('ids', []);
        Student::whereIn('id', $ids)->where('level', 'olevel')->delete();

        return redirect()->route('admin.students.olevel.index')->with('success', 'Selected students deleted');
    }

    /**
     * Import O'Level students form
     */
    public function importOlevelForm()
    {
        return view('admin.students.olevel.import');
    }

    /**
     * Import O'Level students
     */
    public function importOlevel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);

        Excel::import(new StudentsImport('olevel'), $request->file('file'));

        return redirect()->route('admin.students.olevel.index')->with('success', 'Students imported successfully');
    }

    /**
     * Edit A'Level student
     */
    public function editAlevel(Student $student)
    {
        return view('admin.students.alevel.form', compact('student'));
    }

    /**
     * Update A'Level student
     */
    public function updateAlevel(Request $request, Student $student)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'required|string|max:255',
            'gender' => 'required|string|in:Male,Female',
            'class' => 'required|string|in:S.5,S.6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $student->update($validator->validated());

        return redirect()->route('admin.students.alevel.index')->with('success', 'Student updated successfully');
    }

    /**
     * Delete A'Level student
     */
    public function destroyAlevel(Student $student)
    {
        $student->deleteStudent();

        return redirect()->route('admin.students.alevel.index')->with('success', 'Student deleted successfully');
    }

    /**
     * Export A'Level students to Excel
     */
    public function exportAlevelExcel()
    {
        return Excel::download(new AlevelStudentsExport, 'alevel_students.xlsx');
    }

    /**
     * Export A'Level students to PDF
     */
    public function exportAlevelPdf()
    {
        $students = Student::where('level', 'alevel')->get();
        $pdf = Pdf::loadView('admin.students.alevel.pdf', compact('students'));
        return $pdf->download('alevel_students.pdf');
    }

    /**
     * Export selected A'Level students
     */
    public function exportSelectedAlevel(Request $request)
    {
        $ids = $request->input('ids', []);
        $students = Student::whereIn('id', $ids)->where('level', 'alevel')->get();

        if ($request->input('format') === 'pdf') {
            $pdf = Pdf::loadView('admin.students.alevel.pdf', compact('students'));
            return $pdf->download('selected_students.pdf');
        }

        return Excel::download(new AlevelStudentsExport($students), 'selected_students.xlsx');
    }

    /**
     * View A'Level student PDF
     */
    public function viewAlevelPdf(Student $student)
    {
        $pdf = Pdf::loadView('admin.students.alevel.pdf-single', compact('student'));
        return $pdf->stream('student.pdf');
    }

    /**
     * Show A'Level student details
     */
    public function showAlevel(Student $student)
    {
        return view('admin.students.alevel.show', compact('student'));
    }

    /**
     * Delete selected A'Level students
     */
    public function deleteSelectedAlevel(Request $request)
    {
        $ids = $request->input('ids', []);
        Student::whereIn('id', $ids)->where('level', 'alevel')->delete();

        return redirect()->route('admin.students.alevel.index')->with('success', 'Selected students deleted');
    }

    /**
     * Import A'Level students form
     */
    public function importAlevelForm()
    {
        return view('admin.students.alevel.import');
    }

    /**
     * Import A'Level students
     */
    public function importAlevel(Request $request)
    {
        $request->validate(['file' => 'required|file|mimes:xlsx,csv']);

        Excel::import(new StudentsImport('alevel'), $request->file('file'));

        return redirect()->route('admin.students.alevel.index')->with('success', 'Students imported successfully');
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

    /**
     * Show the form for bulk student promotion
     */
    public function promotionForm()
    {
        $students = Student::whereIn('class', array_keys(self::CLASS_PROGRESSION))
            ->orderBy('level')
            ->orderBy('class')
            ->paginate(20);
        
        return view('admin.students.promotion.form', compact('students'));
    }

    /**
     * Promote all eligible students to next class
     */
    public function promoteStudents(Request $request)
    {
        $academicYear = $request->input('academic_year', now()->year);

        DB::beginTransaction();
        try {
            $promotedCount = 0;
            $graduatedCount = 0;

            // Archive S.4 (O-Level) and S.6 (A-Level) students as alumni
            $olevelGraduates = Student::where('class', 'S.4')->where('level', 'olevel')->get();
            $alevelGraduates = Student::where('class', 'S.6')->where('level', 'alevel')->get();

            $graduatedCount += $this->archiveAsAlumni($olevelGraduates, 'S.4', $academicYear);
            $graduatedCount += $this->archiveAsAlumni($alevelGraduates, 'S.6', $academicYear);

            // Promote remaining students
            foreach (self::CLASS_PROGRESSION as $currentClass => $nextClass) {
                if ($nextClass) {
                    $count = Student::where('class', $currentClass)
                        ->whereNotIn('class', ['S.4', 'S.6'])
                        ->update([
                            'class' => $nextClass,
                            'promoted_at' => now(),
                            'promotion_count' => DB::raw('promotion_count + 1')
                        ]);
                    $promotedCount += $count;
                }
            }

            DB::commit();

            return redirect()->route('admin.students.olevel.index')
                ->with('success', "Promotion completed! Promoted: $promotedCount students, Graduated: $graduatedCount students");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Promotion failed: ' . $e->getMessage());
        }
    }

    /**
     * Archive students as alumni
     */
    private function archiveAsAlumni($students, $graduationClass, $academicYear)
    {
        $count = 0;

        foreach ($students as $student) {
            if (!Alumni::where('student_id', $student->id)->exists()) {
                Alumni::create([
                    'student_name' => $student->student_name,
                    'photo_path' => $student->photo_path,
                    'gender' => $student->gender,
                    'learners_lin' => $student->learners_lin,
                    'learners_nin' => $student->learners_nin,
                    'date_of_birth' => $student->date_of_birth,
                    'religion' => $student->religion,
                    'mobile_number' => $student->mobile_number,
                    'email' => $student->email,
                    'district_of_birth' => $student->district_of_birth,
                    'district' => $student->district,
                    'nationality' => $student->nationality,
                    'tribe' => $student->tribe,
                    'previous_school' => $student->previous_school,
                    'ple_index_number' => $student->ple_index_number,
                    'uce_index_number' => $student->uce_index_number,
                    'special_issue' => $student->special_issue,
                    'ple_english' => $student->ple_english,
                    'ple_mathematics' => $student->ple_mathematics,
                    'ple_sst' => $student->ple_sst,
                    'ple_science' => $student->ple_science,
                    'ple_total' => $student->ple_total,
                    'ple_aggregates' => $student->ple_aggregates,
                    'uce_english' => $student->uce_english,
                    'uce_mathematics' => $student->uce_mathematics,
                    'uce_physics' => $student->uce_physics,
                    'uce_chemistry' => $student->uce_chemistry,
                    'uce_biology' => $student->uce_biology,
                    'uce_history' => $student->uce_history,
                    'uce_geography' => $student->uce_geography,
                    'uce_economics' => $student->uce_economics,
                    'uce_literature' => $student->uce_literature,
                    'uce_other' => $student->uce_other,
                    'combination' => $student->combination,
                    'pass_slip_path' => $student->pass_slip_path,
                    'medical_status' => $student->medical_status,
                    'physical_health' => $student->physical_health,
                    'father_full_name' => $student->father_full_name,
                    'father_mobile_number' => $student->father_mobile_number,
                    'father_email' => $student->father_email,
                    'father_nin' => $student->father_nin,
                    'father_physical_address' => $student->father_physical_address,
                    'father_occupation' => $student->father_occupation,
                    'father_dead_alive' => $student->father_dead_alive,
                    'mother_full_name' => $student->mother_full_name,
                    'mother_mobile_number' => $student->mother_mobile_number,
                    'mother_email' => $student->mother_email,
                    'mother_nin' => $student->mother_nin,
                    'mother_physical_address' => $student->mother_physical_address,
                    'mother_occupation' => $student->mother_occupation,
                    'mother_dead_alive' => $student->mother_dead_alive,
                    'guardian_full_name' => $student->guardian_full_name,
                    'guardian_mobile_number' => $student->guardian_mobile_number,
                    'guardian_email' => $student->guardian_email,
                    'guardian_nin' => $student->guardian_nin,
                    'guardian_physical_address' => $student->guardian_physical_address,
                    'guardian_occupation' => $student->guardian_occupation,
                    'guardian_relationship' => $student->guardian_relationship,
                    'official_comment' => $student->official_comment,
                    'level' => $student->level,
                    'graduation_class' => $graduationClass,
                    'graduation_year' => $academicYear,
                    'stream' => $student->stream,
                    'student_id' => $student->id,
                ]);
                $count++;
            }

            $student->delete();
        }

        return $count;
    }
}
