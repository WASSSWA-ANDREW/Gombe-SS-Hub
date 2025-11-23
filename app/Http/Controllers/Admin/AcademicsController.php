<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ALevelSubject;
use App\Models\MarksEntry;
use App\Models\OLevelSubject;
use App\Models\Staff;
use App\Models\Student;
use App\Models\StudentPerformance;
use App\Models\TeacherSubject;
use Illuminate\Http\Request;

class AcademicsController extends Controller
{
    public function dashboard()
    {
        $olevelStudents = Student::where('level', 'olevel')->count();
        $alevelStudents = Student::where('level', 'alevel')->count();
        $olevelGeneralSubjects = OLevelSubject::where('category', 'general')->count();
        $olevelOptionalSubjects = OLevelSubject::where('category', 'optional')->count();
        $alevelArtsSubjects = ALevelSubject::where('stream', 'arts')->count();
        $alevelScienceSubjects = ALevelSubject::where('stream', 'science')->count();
        $alevelSubsidiarySubjects = ALevelSubject::where('category', 'subsidiary')->count();
        $totalTeachers = Staff::whereNotNull('teaching_subjects')->count();

        $olevelPerformanceByClass = Student::where('level', 'olevel')
            ->selectRaw('class, COUNT(*) as count')
            ->groupBy('class')
            ->get();

        $alevelPerformanceByClass = Student::where('level', 'alevel')
            ->selectRaw('class, COUNT(*) as count')
            ->groupBy('class')
            ->get();

        $classDistribution = Student::selectRaw('class, COUNT(*) as count')
            ->groupBy('class')
            ->get();

        $streamDistribution = Student::selectRaw('stream, COUNT(*) as count')
            ->where('stream', '!=', null)
            ->groupBy('stream')
            ->get();

        return view('admin.academics.dashboard', compact(
            'olevelStudents',
            'alevelStudents',
            'olevelGeneralSubjects',
            'olevelOptionalSubjects',
            'alevelArtsSubjects',
            'alevelScienceSubjects',
            'alevelSubsidiarySubjects',
            'totalTeachers',
            'olevelPerformanceByClass',
            'alevelPerformanceByClass',
            'classDistribution',
            'streamDistribution'
        ));
    }

    public function olevelSubjects()
    {
        $generalSubjects = OLevelSubject::where('category', 'general')->get();
        $optionalSubjects = OLevelSubject::where('category', 'optional')->get();

        return view('admin.academics.olevel.subjects', compact('generalSubjects', 'optionalSubjects'));
    }

    public function alevelSubjects()
    {
        $artsSubjects = ALevelSubject::where('stream', 'arts')->get();
        $scienceSubjects = ALevelSubject::where('stream', 'science')->get();
        $subsidiarySubjects = ALevelSubject::where('category', 'subsidiary')->get();
        $generalSubjects = ALevelSubject::where('stream', 'general')->get();

        return view('admin.academics.alevel.subjects', compact(
            'artsSubjects',
            'scienceSubjects',
            'subsidiarySubjects',
            'generalSubjects'
        ));
    }

    public function olevelMarks()
    {
        $olevelStudents = Student::where('level', 'olevel')->with('disciplineTracks')->get();
        $subjects = OLevelSubject::all();
        $classes = Student::where('level', 'olevel')->distinct()->pluck('class');

        return view('admin.academics.olevel.marks', compact('olevelStudents', 'subjects', 'classes'));
    }

    public function alevelMarks()
    {
        $alevelStudents = Student::where('level', 'alevel')->with('disciplineTracks')->get();
        $subjects = ALevelSubject::all();
        $classes = Student::where('level', 'alevel')->distinct()->pluck('class');

        return view('admin.academics.alevel.marks', compact('alevelStudents', 'subjects', 'classes'));
    }

    public function olevelPerformance()
    {
        $students = Student::where('level', 'olevel')->get();
        $classes = Student::where('level', 'olevel')->distinct()->pluck('class');
        $marks = MarksEntry::where('level', 'olevel')->get();

        return view('admin.academics.olevel.performance', compact('students', 'classes', 'marks'));
    }

    public function alevelPerformance()
    {
        $students = Student::where('level', 'alevel')->get();
        $classes = Student::where('level', 'alevel')->distinct()->pluck('class');
        $marks = MarksEntry::where('level', 'alevel')->get();

        return view('admin.academics.alevel.performance', compact('students', 'classes', 'marks'));
    }

    public function teacherAssignments()
    {
        $teachers = Staff::get();
        $teacherSubjects = TeacherSubject::with('staff', 'olevelSubject', 'alevelSubject')->get();
        $olevelSubjects = OLevelSubject::get();
        $alevelSubjects = ALevelSubject::get();

        return view('admin.academics.teachers', compact('teachers', 'teacherSubjects', 'olevelSubjects', 'alevelSubjects'));
    }

    public function assignTeacherSubjects(Request $request)
    {
        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'olevel_subject_id' => 'nullable|exists:olevel_subjects,id',
            'alevel_subject_id' => 'nullable|exists:alevel_subjects,id',
            'level' => 'required|in:olevel,alevel',
            'specialty' => 'nullable|in:arts,science',
            'classes' => 'nullable|string',
            'streams' => 'nullable|string',
        ]);

        if ($validated['classes']) {
            $validated['classes'] = array_map('trim', explode(',', $validated['classes']));
        }

        if ($validated['streams']) {
            $validated['streams'] = array_map('trim', explode(',', $validated['streams']));
        }

        $teacherSubject = TeacherSubject::create($validated);

        return redirect()->route('admin.academics.teachers')->with('success', 'Teacher subject assigned successfully');
    }

    public function editTeacherSubject($id)
    {
        $assignment = TeacherSubject::findOrFail($id);

        return response()->json([
            'id' => $assignment->id,
            'staff_id' => $assignment->staff_id,
            'level' => $assignment->level,
            'olevel_subject_id' => $assignment->olevel_subject_id,
            'alevel_subject_id' => $assignment->alevel_subject_id,
            'specialty' => $assignment->specialty,
            'classes' => $assignment->classes,
            'streams' => $assignment->streams,
        ]);
    }

    public function updateTeacherSubject(Request $request, $id)
    {
        $assignment = TeacherSubject::findOrFail($id);

        $validated = $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'olevel_subject_id' => 'nullable|exists:olevel_subjects,id',
            'alevel_subject_id' => 'nullable|exists:alevel_subjects,id',
            'level' => 'required|in:olevel,alevel',
            'specialty' => 'nullable|in:arts,science',
            'classes' => 'nullable|string',
            'streams' => 'nullable|string',
        ]);

        if ($validated['classes']) {
            $validated['classes'] = array_map('trim', explode(',', $validated['classes']));
        }

        if ($validated['streams']) {
            $validated['streams'] = array_map('trim', explode(',', $validated['streams']));
        }

        $assignment->update($validated);

        return redirect()->route('admin.academics.teachers')->with('success', 'Teacher subject assignment updated successfully');
    }

    public function destroyTeacherSubject($id)
    {
        $assignment = TeacherSubject::findOrFail($id);
        $assignment->delete();

        return redirect()->route('admin.academics.teachers')->with('success', 'Teacher subject assignment deleted successfully');
    }

    public function storeOlevelGeneralSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:olevel_subjects',
            'requires_practical' => 'nullable|boolean',
            'classes' => 'nullable|array',
        ]);

        $validated['category'] = 'general';
        OLevelSubject::create($validated);

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'General subject added successfully');
    }

    public function updateOlevelGeneralSubject(Request $request, $id)
    {
        $subject = OLevelSubject::findOrFail($id);

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:olevel_subjects,subject_code,'.$id,
            'requires_practical' => 'nullable|boolean',
            'classes' => 'nullable|array',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'General subject updated successfully');
    }

    public function destroyOlevelGeneralSubject($id)
    {
        $subject = OLevelSubject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'General subject deleted successfully');
    }

    public function storeOlevelOptionalSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:olevel_subjects',
            'requires_practical' => 'nullable|boolean',
            'classes' => 'nullable|array',
        ]);

        $validated['category'] = 'optional';
        OLevelSubject::create($validated);

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'Optional subject added successfully');
    }

    public function updateOlevelOptionalSubject(Request $request, $id)
    {
        $subject = OLevelSubject::findOrFail($id);

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:olevel_subjects,subject_code,'.$id,
            'requires_practical' => 'nullable|boolean',
            'classes' => 'nullable|array',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'Optional subject updated successfully');
    }

    public function destroyOlevelOptionalSubject($id)
    {
        $subject = OLevelSubject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.academics.olevel.subjects')->with('success', 'Optional subject deleted successfully');
    }

    public function storeAlevelArtsSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects',
            'classes' => 'nullable|array',
        ]);

        $validated['stream'] = 'arts';
        $validated['category'] = 'main';
        ALevelSubject::create($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Arts subject added successfully');
    }

    public function updateAlevelArtsSubject(Request $request, $id)
    {
        $subject = ALevelSubject::findOrFail($id);

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects,subject_code,'.$id,
            'classes' => 'nullable|array',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Arts subject updated successfully');
    }

    public function destroyAlevelArtsSubject($id)
    {
        $subject = ALevelSubject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Arts subject deleted successfully');
    }

    public function storeAlevelScienceSubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects',
            'classes' => 'nullable|array',
        ]);

        $validated['stream'] = 'science';
        $validated['category'] = 'main';
        ALevelSubject::create($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Science subject added successfully');
    }

    public function updateAlevelScienceSubject(Request $request, $id)
    {
        $subject = ALevelSubject::findOrFail($id);

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects,subject_code,'.$id,
            'classes' => 'nullable|array',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Science subject updated successfully');
    }

    public function destroyAlevelScienceSubject($id)
    {
        $subject = ALevelSubject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Science subject deleted successfully');
    }

    public function storeAlevelSubsidiarySubject(Request $request)
    {
        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects',
            'stream' => 'required|in:arts,science',
            'classes' => 'nullable|array',
        ]);

        $validated['category'] = 'subsidiary';
        ALevelSubject::create($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Subsidiary subject added successfully');
    }

    public function updateAlevelSubsidiarySubject(Request $request, $id)
    {
        $subject = ALevelSubject::findOrFail($id);

        $validated = $request->validate([
            'subject_name' => 'required|string|max:255',
            'subject_code' => 'nullable|string|max:50|unique:alevel_subjects,subject_code,'.$id,
            'stream' => 'required|in:arts,science',
            'classes' => 'nullable|array',
        ]);

        $subject->update($validated);

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Subsidiary subject updated successfully');
    }

    public function destroyAlevelSubsidiarySubject($id)
    {
        $subject = ALevelSubject::findOrFail($id);
        $subject->delete();

        return redirect()->route('admin.academics.alevel.subjects')->with('success', 'Subsidiary subject deleted successfully');
    }

    public function studentPerformance()
    {
        $students = Student::with('performances')->get();
        $olevelStudents = Student::where('level', 'olevel')->get();
        $alevelStudents = Student::where('level', 'alevel')->get();

        return view('admin.academics.student-performance', compact('students', 'olevelStudents', 'alevelStudents'));
    }

    public function getStudentPerformanceData($studentId)
    {
        $student = Student::findOrFail($studentId);
        $performances = StudentPerformance::where('student_id', $student->admission_number)
            ->with(['olevelSubject', 'alevelSubject'])
            ->orderBy('academic_year', 'desc')
            ->orderBy('term', 'desc')
            ->get();

        $performanceBySubject = $performances->groupBy(function ($item) {
            return $item->level === 'olevel'
                ? $item->olevelSubject?->subject_name
                : $item->alevelSubject?->subject_name;
        })->map(function ($subjectPerformances) {
            return $subjectPerformances->map(function ($perf) {
                return [
                    'academic_year' => $perf->academic_year,
                    'term' => $perf->term,
                    'average_marks' => (float) $perf->average_marks,
                    'grade' => $perf->grade,
                    'performance_trend' => (float) $perf->performance_trend,
                    'highest_marks' => (float) $perf->highest_marks,
                    'lowest_marks' => (float) $perf->lowest_marks,
                ];
            })->sortBy(function ($item) {
                return [$item['academic_year'], $item['term']];
            })->values()->all();
        });

        $overallPerformance = [
            'average_marks' => round($performances->avg('average_marks'), 2),
            'highest_marks' => $performances->max('highest_marks'),
            'lowest_marks' => $performances->min('lowest_marks'),
            'total_subjects' => $performances->groupBy(function ($item) {
                return $item->level === 'olevel'
                    ? $item->olevel_subject_id
                    : $item->alevel_subject_id;
            })->count(),
        ];

        $chartLabels = $performances
            ->map(function ($item) {
                return $item->academic_year.' - '.($item->term ?? 'Full Year');
            })
            ->unique()
            ->values()
            ->all();

        $chartData = [];
        foreach ($performanceBySubject as $subject => $marks) {
            $chartData[] = [
                'label' => $subject,
                'data' => array_map(function ($item) {
                    return $item['average_marks'];
                }, $marks),
            ];
        }

        return response()->json([
            'success' => true,
            'student' => [
                'id' => $student->admission_number,
                'name' => $student->student_name,
                'class' => $student->class,
                'stream' => $student->stream,
                'level' => $student->level,
            ],
            'performances' => $performances,
            'performanceBySubject' => $performanceBySubject,
            'overallPerformance' => $overallPerformance,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}
