<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class TeacherAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('teacher.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $staff = Staff::where(function($query) {
            $username = request('username');
            $query->whereRaw("CONCAT(surname, ' ', first_name) = ?", [$username])
                  ->orWhereRaw("CONCAT(first_name, ' ', surname) = ?", [$username])
                  ->orWhere('surname', $username)
                  ->orWhere('first_name', $username);
        })
        ->where('enable_teacher_login', true)
        ->first();

        if (!$staff) {
            return back()->withErrors(['username' => 'Teacher not found or not enabled for login.']);
        }

        if (!Hash::check($credentials['password'], $staff->password)) {
            return back()->withErrors(['password' => 'Invalid password.']);
        }

        Session::put('teacher_id', $staff->id);
        $staff->update(['last_teacher_login_at' => now()]);

        return redirect()->route('teacher.dashboard');
    }

    public function logout(Request $request)
    {
        Session::forget('teacher_id');
        return redirect()->route('teacher.login.form');
    }

    public function dashboard()
    {
        $teacherId = session('teacher_id');
        $teacher = Staff::find($teacherId);

        if (!$teacher) {
            return redirect()->route('teacher.login.form');
        }

        $teacherSubjects = $teacher->teacherSubjects()->with('olevelSubject', 'alevelSubject')->get();
        $olevelSubjects = $teacherSubjects->where('level', 'olevel');
        $alevelSubjects = $teacherSubjects->where('level', 'alevel');

        $olevelStudents = collect();
        $alevelStudents = collect();

        foreach ($olevelSubjects as $subject) {
            if ($subject->olevelSubject) {
                $students = \App\Models\Student::where('level', 'olevel')->get();
                $olevelStudents = $olevelStudents->merge($students);
            }
        }

        foreach ($alevelSubjects as $subject) {
            if ($subject->alevelSubject) {
                $students = \App\Models\Student::where('level', 'alevel')->get();
                $alevelStudents = $alevelStudents->merge($students);
            }
        }

        return view('teacher.dashboard', compact(
            'teacher',
            'teacherSubjects',
            'olevelSubjects',
            'alevelSubjects',
            'olevelStudents',
            'alevelStudents'
        ));
    }
}
