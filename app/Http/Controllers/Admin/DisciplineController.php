<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DisciplineTrack;
use App\Models\CounsellingTrack;
use App\Models\Student;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DisciplineController extends Controller
{
    /**
     * Display the discipline records dashboard
     */
    public function index()
    {
        // Statistics
        $totalDisciplineRecords = DisciplineTrack::count();
        $pendingCases = DisciplineTrack::where('case_status', 'pending')->count();
        $sortedCases = DisciplineTrack::where('case_status', 'sorted')->count();

        $totalCounsellingRecords = CounsellingTrack::count();
        $ongoingCounselling = CounsellingTrack::where('status', 'ongoing')->count();
        $completedCounselling = CounsellingTrack::where('status', 'completed')->count();

        // Recent discipline records
        $recentDisciplineRecords = DisciplineTrack::with(['student', 'recordedBy'])
            ->latest()
            ->limit(10)
            ->get();

        // Recent counselling records
        $recentCounsellingRecords = CounsellingTrack::with(['student', 'counsellor'])
            ->latest()
            ->limit(10)
            ->get();

        // Students with most discipline issues
        $studentsWithMostIssues = Student::withCount('disciplineTracks')
            ->having('discipline_tracks_count', '>', 0)
            ->orderBy('discipline_tracks_count', 'desc')
            ->limit(10)
            ->get();

        return view('admin.discipline.index', compact(
            'totalDisciplineRecords',
            'pendingCases',
            'sortedCases',
            'totalCounsellingRecords',
            'ongoingCounselling',
            'completedCounselling',
            'recentDisciplineRecords',
            'recentCounsellingRecords',
            'studentsWithMostIssues'
        ));
    }

    /**
     * Show discipline track records
     */
    public function disciplineTracks(Request $request)
    {
        $query = DisciplineTrack::with(['student', 'recordedBy']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('case_status', $request->status);
        }

        // Filter by disciplinary action
        if ($request->has('action') && $request->action !== '') {
            $query->where('disciplinary_action', $request->action);
        }

        // Search by student name or case name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('student', function($studentQuery) use ($search) {
                    $studentQuery->where('student_name', 'like', "%{$search}%");
                })
                ->orWhere('case_name', 'like', "%{$search}%");
            });
        }

        $disciplineTracks = $query->get();

        return view('admin.discipline.discipline-tracks', compact('disciplineTracks'));
    }

    /**
     * Show counselling track records
     */
    public function counsellingTracks(Request $request)
    {
        $query = CounsellingTrack::with(['student', 'counsellor']);

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by counselling type
        if ($request->has('type') && $request->type !== '') {
            $query->where('counselling_type', $request->type);
        }

        // Search by student name
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('student', function($studentQuery) use ($search) {
                $studentQuery->where('student_name', 'like', "%{$search}%");
            });
        }

        $counsellingTracks = $query->get();

        return view('admin.discipline.counselling-tracks', compact('counsellingTracks'));
    }

    /**
     * Show form to create a new discipline track record
     */
    public function createDisciplineTrack()
    {
        $students = Student::orderBy('student_name')->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('admin.discipline.create-discipline-track', compact('students', 'staff'));
    }

    /**
     * Store a new discipline track record
     */
    public function storeDisciplineTrack(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,admission_number',
            'case_name' => 'required|string|max:255',
            'disciplinary_action' => 'required|in:statement_letter,cautions,active_punishment',
            'resolution' => 'nullable|in:suspension,expulsion',
            'case_status' => 'required|in:pending,sorted',
            'date_of_incident' => 'nullable|date',
            'description' => 'nullable|string',
            'statement_written' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'caution_document' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'counselling_agreement' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
        ]);

        $attachments = [];

        if ($request->hasFile('statement_written')) {
            $file = $request->file('statement_written');
            $path = $file->store('discipline/statements', 'public');
            $attachments['statement_written'] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        if ($request->hasFile('caution_document')) {
            $file = $request->file('caution_document');
            $path = $file->store('discipline/cautions', 'public');
            $attachments['caution_document'] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        if ($request->hasFile('counselling_agreement')) {
            $file = $request->file('counselling_agreement');
            $path = $file->store('discipline/agreements', 'public');
            $attachments['counselling_agreement'] = [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'uploaded_at' => now()->toDateTimeString()
            ];
        }

        DisciplineTrack::create([
            'student_id' => $request->student_id,
            'case_name' => $request->case_name,
            'disciplinary_action' => $request->disciplinary_action,
            'resolution' => $request->resolution,
            'case_status' => $request->case_status,
            'date_of_incident' => $request->date_of_incident,
            'description' => $request->description,
            'recorded_by' => Auth::id(),
            'attachments' => !empty($attachments) ? $attachments : null,
        ]);

        return redirect()->route('admin.discipline.discipline-tracks')
            ->with('success', 'Discipline track record created successfully.');
    }

    /**
     * Show form to create a new counselling track record
     */
    public function createCounsellingTrack()
    {
        $students = Student::orderBy('student_name')->get();
        $staff = Staff::orderBy('first_name')->get();

        return view('admin.discipline.create-counselling-track', compact('students', 'staff'));
    }

    /**
     * Store a new counselling track record
     */
    public function storeCounsellingTrack(Request $request)
    {
        $request->validate([
            'student_id' => 'required|exists:students,admission_number',
            'counselling_type' => 'required|in:life,academic,behavior,gender,character,sex',
            'date_of_session' => 'required|date',
            'notes' => 'nullable|string',
            'outcome' => 'nullable|string',
            'status' => 'required|in:ongoing,completed',
            'counsellor_id' => 'nullable|exists:staff,id',
        ]);

        CounsellingTrack::create([
            'student_id' => $request->student_id,
            'counselling_type' => $request->counselling_type,
            'date_of_session' => $request->date_of_session,
            'notes' => $request->notes,
            'outcome' => $request->outcome,
            'status' => $request->status,
            'counsellor_id' => $request->counsellor_id,
        ]);

        return redirect()->route('admin.discipline.counselling-tracks')
            ->with('success', 'Counselling track record created successfully.');
    }

    /**
     * Show a student's discipline and counselling records
     */
    public function studentRecords($studentId)
    {
        $student = Student::with(['disciplineTracks.recordedBy', 'counsellingTracks.counsellor'])
            ->findOrFail($studentId);

        return view('admin.discipline.student-records', compact('student'));
    }
}
