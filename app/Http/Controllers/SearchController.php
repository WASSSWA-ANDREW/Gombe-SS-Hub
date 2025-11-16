<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
    /**
     * Display the search page
     */
    public function index()
    {
        return view('admin.search.index');
    }

    /**
     * Perform search across students, staff, and users
     */
    public function search(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'query' => 'required|string|min:2|max:255',
            'type' => 'nullable|string|in:all,students,staff,users',
            'filters' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.search.index')
                        ->withErrors($validator)
                        ->withInput();
        }

        $query = $request->input('query');
        $type = $request->input('type', 'all');
        $filters = $request->input('filters', []);

        $results = [
            'students' => collect(),
            'staff' => collect(),
            'users' => collect()
        ];

        // Search students
        if ($type === 'all' || $type === 'students') {
            $studentQuery = Student::where(function($q) use ($query) {
                $q->where('student_name', 'LIKE', "%{$query}%")
                  ->orWhere('learners_lin', 'LIKE', "%{$query}%")
                  ->orWhere('learners_nin', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('mobile_number', 'LIKE', "%{$query}%")
                  ->orWhere('ple_index_number', 'LIKE', "%{$query}%");
            });

            // Apply filters for students
            if (isset($filters['level']) && !empty($filters['level'])) {
                $studentQuery->where('level', $filters['level']);
            }
            if (isset($filters['gender']) && !empty($filters['gender'])) {
                $studentQuery->where('gender', $filters['gender']);
            }
            if (isset($filters['religion']) && !empty($filters['religion'])) {
                $studentQuery->where('religion', $filters['religion']);
            }
            if (isset($filters['district']) && !empty($filters['district'])) {
                $studentQuery->where('district_of_birth', $filters['district']);
            }

            $results['students'] = $studentQuery->limit(50)->get();
        }

        // Search staff
        if ($type === 'all' || $type === 'staff') {
            $staffQuery = Staff::where(function($q) use ($query) {
                $q->where('first_name', 'LIKE', "%{$query}%")
                  ->orWhere('surname', 'LIKE', "%{$query}%")
                  ->orWhere('other_name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('national_id_no', 'LIKE', "%{$query}%")
                  ->orWhere('registration_no', 'LIKE', "%{$query}%")
                  ->orWhere('telephone_contacts', 'LIKE', "%{$query}%")
                  ->orWhere('teaching_subjects', 'LIKE', "%{$query}%");
            });

            // Apply filters for staff
            if (isset($filters['staff_type']) && !empty($filters['staff_type'])) {
                $staffQuery->where('staff_type', $filters['staff_type']);
            }
            if (isset($filters['sex']) && !empty($filters['sex'])) {
                $staffQuery->where('sex', $filters['sex']);
            }
            if (isset($filters['designation']) && !empty($filters['designation'])) {
                $staffQuery->where('designation_of_current_appt', 'LIKE', "%{$filters['designation']}%");
            }

            $results['staff'] = $staffQuery->limit(50)->get();
        }

        // Search users
        if ($type === 'all' || $type === 'users') {
            $userQuery = User::where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('email', 'LIKE', "%{$query}%")
                  ->orWhere('phone', 'LIKE', "%{$query}%");
            });

            // Apply filters for users
            if (isset($filters['role']) && !empty($filters['role'])) {
                $userQuery->where('role', $filters['role']);
            }

            $results['users'] = $userQuery->limit(50)->get();
        }

        // Save search history
        $this->saveSearchHistory($query, $type, auth()->id());

        $totalResults = $results['students']->count() + $results['staff']->count() + $results['users']->count();

        return view('admin.search.results', compact('results', 'query', 'type', 'filters', 'totalResults'));
    }

    /**
     * Get search suggestions via AJAX
     */
    public function getSuggestions(Request $request)
    {
        $query = $request->input('query');
        
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = [];

        // Student suggestions
        $students = Student::where('student_name', 'LIKE', "%{$query}%")
                          ->limit(5)
                          ->get(['id', 'student_name', 'level']);
        
        foreach ($students as $student) {
            $suggestions[] = [
                'type' => 'student',
                'id' => $student->id,
                'name' => $student->student_name,
                'subtitle' => ucfirst($student->level) . ' Student',
                'url' => route('admin.students.' . $student->level . '.edit', $student->id)
            ];
        }

        // Staff suggestions
        $staff = Staff::where(function($q) use ($query) {
                    $q->where('first_name', 'LIKE', "%{$query}%")
                      ->orWhere('surname', 'LIKE', "%{$query}%");
                })
                ->limit(5)
                ->get(['id', 'first_name', 'surname', 'staff_type']);
        
        foreach ($staff as $member) {
            $suggestions[] = [
                'type' => 'staff',
                'id' => $member->id,
                'name' => $member->first_name . ' ' . $member->surname,
                'subtitle' => ucfirst($member->staff_type ?? 'Private') . ' Staff',
                'url' => route('admin.staff.edit', $member->id)
            ];
        }

        return response()->json($suggestions);
    }

    /**
     * Get search history for current user
     */
    public function getSearchHistory()
    {
        $history = session()->get('search_history', []);
        return response()->json(array_slice($history, -10)); // Last 10 searches
    }

    /**
     * Delete a specific search history item
     */
    public function deleteSearchHistory($id)
    {
        $history = session()->get('search_history', []);
        
        $history = array_filter($history, function($item) use ($id) {
            return $item['query'] !== $id;
        });
        
        session()->put('search_history', $history);
        
        return response()->json(['success' => true]);
    }

    /**
     * Clear search history
     */
    public function clearSearchHistory()
    {
        session()->forget('search_history');
        return response()->json(['success' => true]);
    }

    /**
     * Save search to history
     */
    private function saveSearchHistory($query, $type, $userId)
    {
        $history = session()->get('search_history', []);
        
        $searchEntry = [
            'query' => $query,
            'type' => $type,
            'timestamp' => now()->toDateTimeString(),
            'user_id' => $userId
        ];

        // Remove duplicate if exists
        $history = array_filter($history, function($item) use ($query, $type) {
            return !($item['query'] === $query && $item['type'] === $type);
        });

        // Add new search to beginning
        array_unshift($history, $searchEntry);

        // Keep only last 20 searches
        $history = array_slice($history, 0, 20);

        session()->put('search_history', $history);
    }

    /**
     * Advanced search with multiple criteria
     */
    public function advancedSearch(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_name' => 'nullable|string|max:255',
            'staff_name' => 'nullable|string|max:255',
            'level' => 'nullable|string|in:olevel,alevel',
            'gender' => 'nullable|string|in:Male,Female',
            'staff_type' => 'nullable|string|in:government,private',
            'religion' => 'nullable|string|max:255',
            'district' => 'nullable|string|max:255',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date|after_or_equal:date_from'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $criteria = $validator->validated();
        $results = ['students' => collect(), 'staff' => collect()];

        // Advanced student search
        if (!empty($criteria['student_name']) || !empty($criteria['level']) || !empty($criteria['gender']) || !empty($criteria['religion']) || !empty($criteria['district'])) {
            $studentQuery = Student::query();

            if (!empty($criteria['student_name'])) {
                $studentQuery->where('student_name', 'LIKE', "%{$criteria['student_name']}%");
            }
            if (!empty($criteria['level'])) {
                $studentQuery->where('level', $criteria['level']);
            }
            if (!empty($criteria['gender'])) {
                $studentQuery->where('gender', $criteria['gender']);
            }
            if (!empty($criteria['religion'])) {
                $studentQuery->where('religion', 'LIKE', "%{$criteria['religion']}%");
            }
            if (!empty($criteria['district'])) {
                $studentQuery->where('district_of_birth', 'LIKE', "%{$criteria['district']}%");
            }
            if (!empty($criteria['date_from'])) {
                $studentQuery->where('created_at', '>=', $criteria['date_from']);
            }
            if (!empty($criteria['date_to'])) {
                $studentQuery->where('created_at', '<=', $criteria['date_to']);
            }

            $results['students'] = $studentQuery->limit(100)->get();
        }

        // Advanced staff search
        if (!empty($criteria['staff_name']) || !empty($criteria['staff_type'])) {
            $staffQuery = Staff::query();

            if (!empty($criteria['staff_name'])) {
                $staffQuery->where(function($q) use ($criteria) {
                    $q->where('first_name', 'LIKE', "%{$criteria['staff_name']}%")
                      ->orWhere('surname', 'LIKE', "%{$criteria['staff_name']}%")
                      ->orWhere('other_name', 'LIKE', "%{$criteria['staff_name']}%");
                });
            }
            if (!empty($criteria['staff_type'])) {
                $staffQuery->where('staff_type', $criteria['staff_type']);
            }
            if (!empty($criteria['date_from'])) {
                $staffQuery->where('created_at', '>=', $criteria['date_from']);
            }
            if (!empty($criteria['date_to'])) {
                $staffQuery->where('created_at', '<=', $criteria['date_to']);
            }

            $results['staff'] = $staffQuery->limit(100)->get();
        }

        return response()->json($results);
    }
}