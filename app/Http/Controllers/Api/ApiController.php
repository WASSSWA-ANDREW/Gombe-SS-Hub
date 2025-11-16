<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;

class ApiController extends Controller
{
    /**
     * API Login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('API Token')->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => $user,
                    'token' => $token
                ]
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * API Logout
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }

    /**
     * Get user profile
     */
    public function profile(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $request->user()
        ]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'nullable|string|max:20'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $user->update($request->only(['name', 'email', 'phone']));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    /**
     * Change password
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Current password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Password changed successfully'
        ]);
    }

    /**
     * Get dashboard statistics
     */
    public function dashboardStats()
    {
        $stats = [
            'total_students' => Student::count(),
            'total_staff' => Staff::count(),
            'total_users' => User::count(),
            'olevel_students' => Student::where('level', 'olevel')->count(),
            'alevel_students' => Student::where('level', 'alevel')->count(),
            'government_staff' => Staff::where('staff_type', 'government')->count(),
            'private_staff' => Staff::where('staff_type', '!=', 'government')->count(),
            'recent_students' => Student::latest()->take(5)->get(),
            'recent_staff' => Staff::latest()->take(5)->get()
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Search students
     */
    public function searchStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
            'level' => 'nullable|string|in:olevel,alevel',
            'gender' => 'nullable|string|in:male,female',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Student::query();
        $searchTerm = $request->input('query');
        $limit = $request->input('limit', 20);

        // Search in multiple fields
        $query->where(function($q) use ($searchTerm) {
            $q->where('first_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('student_id', 'LIKE', "%{$searchTerm}%");
        });

        // Apply filters
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $students = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $students,
            'count' => $students->count()
        ]);
    }

    /**
     * Search staff
     */
    public function searchStaff(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2',
            'staff_type' => 'nullable|string',
            'sex' => 'nullable|string|in:male,female',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Staff::query();
        $searchTerm = $request->input('query');
        $limit = $request->input('limit', 20);

        // Search in multiple fields
        $query->where(function($q) use ($searchTerm) {
            $q->where('first_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('last_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('middle_name', 'LIKE', "%{$searchTerm}%")
              ->orWhere('staff_id', 'LIKE', "%{$searchTerm}%");
        });

        // Apply filters
        if ($request->filled('staff_type')) {
            $query->where('staff_type', $request->staff_type);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        $staff = $query->limit($limit)->get();

        return response()->json([
            'success' => true,
            'data' => $staff,
            'count' => $staff->count()
        ]);
    }

    /**
     * Get student details
     */
    public function getStudent($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Get staff details
     */
    public function getStaff($id)
    {
        $staff = Staff::find($id);

        if (!$staff) {
            return response()->json([
                'success' => false,
                'message' => 'Staff not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $staff
        ]);
    }

    /**
     * Get students list with pagination
     */
    public function getStudents(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'level' => 'nullable|string|in:olevel,alevel',
            'gender' => 'nullable|string|in:male,female',
            'sort_by' => 'nullable|string|in:first_name,last_name,created_at',
            'sort_order' => 'nullable|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Student::query();
        $perPage = $request->input('per_page', 20);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Apply filters
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        $students = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $students->items(),
            'pagination' => [
                'current_page' => $students->currentPage(),
                'last_page' => $students->lastPage(),
                'per_page' => $students->perPage(),
                'total' => $students->total()
            ]
        ]);
    }

    /**
     * Get staff list with pagination
     */
    public function getStaffList(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'staff_type' => 'nullable|string',
            'sex' => 'nullable|string|in:male,female',
            'sort_by' => 'nullable|string|in:first_name,last_name,created_at',
            'sort_order' => 'nullable|string|in:asc,desc'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $query = Staff::query();
        $perPage = $request->input('per_page', 20);
        $sortBy = $request->input('sort_by', 'created_at');
        $sortOrder = $request->input('sort_order', 'desc');

        // Apply filters
        if ($request->filled('staff_type')) {
            $query->where('staff_type', $request->staff_type);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortOrder);

        $staff = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $staff->items(),
            'pagination' => [
                'current_page' => $staff->currentPage(),
                'last_page' => $staff->lastPage(),
                'per_page' => $staff->perPage(),
                'total' => $staff->total()
            ]
        ]);
    }

    /**
     * Get system statistics
     */
    public function getSystemStats()
    {
        $stats = [
            'students' => [
                'total' => Student::count(),
                'olevel' => Student::where('level', 'olevel')->count(),
                'alevel' => Student::where('level', 'alevel')->count(),
                'male' => Student::where('gender', 'male')->count(),
                'female' => Student::where('gender', 'female')->count(),
                'recent' => Student::where('created_at', '>=', now()->subDays(30))->count()
            ],
            'staff' => [
                'total' => Staff::count(),
                'government' => Staff::where('staff_type', 'government')->count(),
                'private' => Staff::where('staff_type', '!=', 'government')->count(),
                'male' => Staff::where('sex', 'male')->count(),
                'female' => Staff::where('sex', 'female')->count(),
                'recent' => Staff::where('created_at', '>=', now()->subDays(30))->count()
            ],
            'users' => [
                'total' => User::count(),
                'active' => User::where('status', 'active')->count(),
                'inactive' => User::where('status', 'inactive')->count(),
                'recent' => User::where('created_at', '>=', now()->subDays(30))->count()
            ]
        ];

        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }

    /**
     * Get app version and info
     */
    public function getAppInfo()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'app_name' => config('app.name', 'Gombe SS Hub'),
                'app_version' => '1.0.0',
                'api_version' => '1.0',
                'laravel_version' => app()->version(),
                'php_version' => PHP_VERSION,
                'server_time' => now()->toISOString(),
                'timezone' => config('app.timezone')
            ]
        ]);
    }
}