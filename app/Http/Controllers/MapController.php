<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Student;
use App\Models\Staff;

class MapController extends Controller
{
    /**
     * Display map view
     */
    public function index()
    {
        return view('map.index');
    }

    /**
     * Get students location data for map
     */
    public function getStudentsLocationData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'level' => 'nullable|string|in:olevel,alevel',
            'gender' => 'nullable|string|in:male,female',
            'district' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Student::query();
        $limit = $request->input('limit', 500);

        // Apply filters
        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        if ($request->filled('district')) {
            $query->where('district_of_birth', $request->district);
        }

        // Get students with location data
        $students = $query->whereNotNull('latitude')
                         ->whereNotNull('longitude')
                         ->limit($limit)
                         ->get(['id', 'first_name', 'last_name', 'level', 'gender', 
                               'district_of_birth', 'latitude', 'longitude', 'address']);

        $locationData = $students->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->first_name . ' ' . $student->last_name,
                'type' => 'student',
                'level' => $student->level,
                'gender' => $student->gender,
                'district' => $student->district_of_birth,
                'address' => $student->address,
                'latitude' => (float) $student->latitude,
                'longitude' => (float) $student->longitude,
                'icon' => $student->level === 'olevel' ? 'student-olevel' : 'student-alevel',
                'color' => $student->gender === 'male' ? '#007bff' : '#e83e8c'
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $locationData,
            'count' => $locationData->count()
        ]);
    }

    /**
     * Get staff location data for map
     */
    public function getStaffLocationData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'staff_type' => 'nullable|string',
            'sex' => 'nullable|string|in:male,female',
            'district' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $query = Staff::query();
        $limit = $request->input('limit', 500);

        // Apply filters
        if ($request->filled('staff_type')) {
            $query->where('staff_type', $request->staff_type);
        }

        if ($request->filled('sex')) {
            $query->where('sex', $request->sex);
        }

        if ($request->filled('district')) {
            $query->where('district_of_birth', $request->district);
        }

        // Get staff with location data
        $staff = $query->whereNotNull('latitude')
                      ->whereNotNull('longitude')
                      ->limit($limit)
                      ->get(['id', 'first_name', 'last_name', 'staff_type', 'sex', 
                            'district_of_birth', 'latitude', 'longitude', 'address']);

        $locationData = $staff->map(function($staffMember) {
            return [
                'id' => $staffMember->id,
                'name' => $staffMember->first_name . ' ' . $staffMember->last_name,
                'type' => 'staff',
                'staff_type' => $staffMember->staff_type,
                'sex' => $staffMember->sex,
                'district' => $staffMember->district_of_birth,
                'address' => $staffMember->address,
                'latitude' => (float) $staffMember->latitude,
                'longitude' => (float) $staffMember->longitude,
                'icon' => $staffMember->staff_type === 'government' ? 'staff-government' : 'staff-private',
                'color' => $staffMember->sex === 'male' ? '#28a745' : '#fd7e14'
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $locationData,
            'count' => $locationData->count()
        ]);
    }

    /**
     * Get combined location data (students and staff)
     */
    public function getCombinedLocationData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'include_students' => 'boolean',
            'include_staff' => 'boolean',
            'student_level' => 'nullable|string|in:olevel,alevel',
            'student_gender' => 'nullable|string|in:male,female',
            'staff_type' => 'nullable|string',
            'staff_sex' => 'nullable|string|in:male,female',
            'district' => 'nullable|string',
            'limit' => 'nullable|integer|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $includeStudents = $request->input('include_students', true);
        $includeStaff = $request->input('include_staff', true);
        $limit = $request->input('limit', 500);
        $locationData = collect();

        // Get students data
        if ($includeStudents) {
            $studentsRequest = new Request([
                'level' => $request->input('student_level'),
                'gender' => $request->input('student_gender'),
                'district' => $request->input('district'),
                'limit' => $limit / 2
            ]);
            
            $studentsResponse = $this->getStudentsLocationData($studentsRequest);
            $studentsData = json_decode($studentsResponse->getContent(), true);
            
            if ($studentsData['success']) {
                $locationData = $locationData->merge($studentsData['data']);
            }
        }

        // Get staff data
        if ($includeStaff) {
            $staffRequest = new Request([
                'staff_type' => $request->input('staff_type'),
                'sex' => $request->input('staff_sex'),
                'district' => $request->input('district'),
                'limit' => $limit / 2
            ]);
            
            $staffResponse = $this->getStaffLocationData($staffRequest);
            $staffData = json_decode($staffResponse->getContent(), true);
            
            if ($staffData['success']) {
                $locationData = $locationData->merge($staffData['data']);
            }
        }

        return response()->json([
            'success' => true,
            'data' => $locationData->take($limit),
            'count' => $locationData->count()
        ]);
    }

    /**
     * Get location statistics by district
     */
    public function getLocationStatsByDistrict()
    {
        $studentStats = Student::selectRaw('district_of_birth as district, COUNT(*) as student_count')
                              ->whereNotNull('district_of_birth')
                              ->groupBy('district_of_birth')
                              ->get();

        $staffStats = Staff::selectRaw('district_of_birth as district, COUNT(*) as staff_count')
                          ->whereNotNull('district_of_birth')
                          ->groupBy('district_of_birth')
                          ->get();

        // Combine statistics
        $combinedStats = [];
        
        foreach ($studentStats as $stat) {
            $combinedStats[$stat->district] = [
                'district' => $stat->district,
                'student_count' => $stat->student_count,
                'staff_count' => 0,
                'total_count' => $stat->student_count
            ];
        }

        foreach ($staffStats as $stat) {
            if (isset($combinedStats[$stat->district])) {
                $combinedStats[$stat->district]['staff_count'] = $stat->staff_count;
                $combinedStats[$stat->district]['total_count'] += $stat->staff_count;
            } else {
                $combinedStats[$stat->district] = [
                    'district' => $stat->district,
                    'student_count' => 0,
                    'staff_count' => $stat->staff_count,
                    'total_count' => $stat->staff_count
                ];
            }
        }

        // Sort by total count
        uasort($combinedStats, function($a, $b) {
            return $b['total_count'] - $a['total_count'];
        });

        return response()->json([
            'success' => true,
            'data' => array_values($combinedStats)
        ]);
    }

    /**
     * Get heatmap data
     */
    public function getHeatmapData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required|string|in:students,staff,combined',
            'intensity_field' => 'nullable|string|in:count,density'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $type = $request->input('type');
        $intensityField = $request->input('intensity_field', 'count');
        $heatmapData = [];

        if ($type === 'students' || $type === 'combined') {
            $students = Student::whereNotNull('latitude')
                              ->whereNotNull('longitude')
                              ->get(['latitude', 'longitude']);

            foreach ($students as $student) {
                $heatmapData[] = [
                    'lat' => (float) $student->latitude,
                    'lng' => (float) $student->longitude,
                    'intensity' => 1
                ];
            }
        }

        if ($type === 'staff' || $type === 'combined') {
            $staff = Staff::whereNotNull('latitude')
                         ->whereNotNull('longitude')
                         ->get(['latitude', 'longitude']);

            foreach ($staff as $staffMember) {
                $heatmapData[] = [
                    'lat' => (float) $staffMember->latitude,
                    'lng' => (float) $staffMember->longitude,
                    'intensity' => $type === 'combined' ? 0.7 : 1
                ];
            }
        }

        return response()->json([
            'success' => true,
            'data' => $heatmapData,
            'count' => count($heatmapData)
        ]);
    }

    /**
     * Get nearby locations
     */
    public function getNearbyLocations(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius' => 'nullable|numeric|min:0.1|max:100',
            'type' => 'nullable|string|in:students,staff,both',
            'limit' => 'nullable|integer|min:1|max:100'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
        $radius = $request->input('radius', 5); // Default 5km radius
        $type = $request->input('type', 'both');
        $limit = $request->input('limit', 50);

        $nearbyData = collect();

        // Calculate distance using Haversine formula
        $distanceFormula = "
            (6371 * acos(
                cos(radians(?)) * 
                cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * 
                sin(radians(latitude))
            ))
        ";

        if ($type === 'students' || $type === 'both') {
            $nearbyStudents = Student::selectRaw("
                    *, 
                    {$distanceFormula} AS distance
                ", [$latitude, $longitude, $latitude])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->havingRaw('distance <= ?', [$radius])
                ->orderBy('distance')
                ->limit($limit / ($type === 'both' ? 2 : 1))
                ->get();

            foreach ($nearbyStudents as $student) {
                $nearbyData->push([
                    'id' => $student->id,
                    'name' => $student->first_name . ' ' . $student->last_name,
                    'type' => 'student',
                    'level' => $student->level,
                    'gender' => $student->gender,
                    'latitude' => (float) $student->latitude,
                    'longitude' => (float) $student->longitude,
                    'distance' => round($student->distance, 2),
                    'address' => $student->address
                ]);
            }
        }

        if ($type === 'staff' || $type === 'both') {
            $nearbyStaff = Staff::selectRaw("
                    *, 
                    {$distanceFormula} AS distance
                ", [$latitude, $longitude, $latitude])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->havingRaw('distance <= ?', [$radius])
                ->orderBy('distance')
                ->limit($limit / ($type === 'both' ? 2 : 1))
                ->get();

            foreach ($nearbyStaff as $staff) {
                $nearbyData->push([
                    'id' => $staff->id,
                    'name' => $staff->first_name . ' ' . $staff->last_name,
                    'type' => 'staff',
                    'staff_type' => $staff->staff_type,
                    'sex' => $staff->sex,
                    'latitude' => (float) $staff->latitude,
                    'longitude' => (float) $staff->longitude,
                    'distance' => round($staff->distance, 2),
                    'address' => $staff->address
                ]);
            }
        }

        // Sort by distance
        $nearbyData = $nearbyData->sortBy('distance')->take($limit);

        return response()->json([
            'success' => true,
            'data' => $nearbyData->values(),
            'count' => $nearbyData->count(),
            'search_center' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'radius' => $radius
            ]
        ]);
    }

    /**
     * Get map configuration
     */
    public function getMapConfig()
    {
        return response()->json([
            'success' => true,
            'config' => [
                'default_center' => [
                    'latitude' => 10.2897, // Gombe State coordinates
                    'longitude' => 11.1689
                ],
                'default_zoom' => 10,
                'max_zoom' => 18,
                'min_zoom' => 6,
                'map_styles' => [
                    'default' => 'Default',
                    'satellite' => 'Satellite',
                    'terrain' => 'Terrain',
                    'hybrid' => 'Hybrid'
                ],
                'marker_icons' => [
                    'student-olevel' => '/images/markers/student-olevel.png',
                    'student-alevel' => '/images/markers/student-alevel.png',
                    'staff-government' => '/images/markers/staff-government.png',
                    'staff-private' => '/images/markers/staff-private.png'
                ],
                'cluster_options' => [
                    'enabled' => true,
                    'max_zoom' => 15,
                    'grid_size' => 60
                ]
            ]
        ]);
    }
}