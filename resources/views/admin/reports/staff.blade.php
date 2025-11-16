@extends('layouts.admin')

@section('title', 'Staff Reports')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Reports
    </h2>
    
    <!-- Description -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            Comprehensive reports about staff distribution, roles, and demographics.
        </p>
    </div>

    <!-- Export Options -->
    <div class="flex justify-end mb-6">
        <form action="{{ route('admin.reports.generate-pdf') }}" method="POST" class="mr-2">
            @csrf
            <input type="hidden" name="report_type" value="staff">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Export as PDF
            </button>
        </form>
        <form action="{{ route('admin.reports.export-excel') }}" method="POST">
            @csrf
            <input type="hidden" name="report_type" value="staff">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                Export as Excel
            </button>
        </form>
    </div>

    <!-- Staff Overview Cards -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Overview
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-8">
        <!-- Card 1: Total Teachers -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-green-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs font-semibold px-2.5 py-1 rounded">TEACHERS</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalTeachers'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Total Teachers</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Administrators -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-purple-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-purple-100 dark:bg-purple-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 text-xs font-semibold px-2.5 py-1 rounded">ADMIN</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalAdministrators'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Administrators</p>
                </div>
            </div>
        </div>

        <!-- Card 3: Support Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2" style="background-color: #008080;"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-3 rounded-full" style="background-color: rgba(0, 128, 128, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="color: #008080;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 105.25 18.75 9.75 9.75 0 000-18.75zM12 12a3 3 0 100-6 3 3 0 000 6z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded" style="background-color: rgba(0, 128, 128, 0.1); color: #008080;">SUPPORT</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalSupportStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Support Staff</p>
                </div>
            </div>
        </div>

        <!-- Card 4: Non-Teaching Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2" style="background-color: #4B0082;"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="p-3 rounded-full" style="background-color: rgba(75, 0, 130, 0.1);">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" style="color: #4B0082;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded" style="background-color: rgba(75, 0, 130, 0.1); color: #4B0082;">NON-TEACHING</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalNonTeachingStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Non-Teaching Staff</p>
                </div>
            </div>
        </div>

        <!-- Card 5: Total Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2.5 py-1 rounded">TOTAL</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalMaleStaff'] + $data['totalFemaleStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Total Staff Members</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Gender Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Gender Distribution
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Card 1: Male Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2.5 py-1 rounded">MALE</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalMaleStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Male Staff Members</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $totalStaff = $data['totalMaleStaff'] + $data['totalFemaleStaff'];
                            $malePercentage = $totalStaff > 0 ? round(($data['totalMaleStaff'] / $totalStaff) * 100) : 0;
                        @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $malePercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $malePercentage }}% of total staff</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Female Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-pink-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-pink-100 dark:bg-pink-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-pink-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <span class="bg-pink-100 dark:bg-pink-900 text-pink-800 dark:text-pink-300 text-xs font-semibold px-2.5 py-1 rounded">FEMALE</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalFemaleStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Female Staff Members</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $femalePercentage = $totalStaff > 0 ? round(($data['totalFemaleStaff'] / $totalStaff) * 100) : 0;
                        @endphp
                        <div class="bg-pink-600 h-2.5 rounded-full" style="width: {{ $femalePercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $femalePercentage }}% of total staff</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Employment Type -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Employment Type
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Card 1: Regular Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-yellow-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </div>
                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2.5 py-1 rounded">REGULAR</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalRegularStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Regular Staff Members</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $regularPercentage = $totalStaff > 0 ? round(($data['totalRegularStaff'] / $totalStaff) * 100) : 0;
                        @endphp
                        <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $regularPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $regularPercentage }}% of total staff</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Government Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-teal-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-teal-100 dark:bg-teal-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <span class="bg-teal-100 dark:bg-teal-900 text-teal-800 dark:text-teal-300 text-xs font-semibold px-2.5 py-1 rounded">GOVERNMENT</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['totalGovernmentStaff'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Government Staff Members</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $governmentPercentage = $totalStaff > 0 ? round(($data['totalGovernmentStaff'] / $totalStaff) * 100) : 0;
                        @endphp
                        <div class="bg-teal-600 h-2.5 rounded-full" style="width: {{ $governmentPercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $governmentPercentage }}% of total staff</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Distribution by Role -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Distribution by Role
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Role</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['staffByRole'] as $role)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ ucfirst($role->role) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $role->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStaff > 0 ? round(($role->total / $totalStaff) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Staff Distribution by Department -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Distribution by Department
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Department</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['staffByDepartment'] as $dept)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $dept->department ?: 'Not Assigned' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $dept->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStaff > 0 ? round(($dept->total / $totalStaff) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Staff by Years of Service -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff by Years of Service
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Less than 1 year -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2.5 py-1 rounded">NEW</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['staffByYearsOfService']['less_than_1'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Less than 1 year</p>
                </div>
            </div>
        </div>

        <!-- 1-5 years -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-green-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs font-semibold px-2.5 py-1 rounded">JUNIOR</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['staffByYearsOfService']['1_to_5'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">1-5 years</p>
                </div>
            </div>
        </div>

        <!-- 6-10 years -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-yellow-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2.5 py-1 rounded">EXPERIENCED</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['staffByYearsOfService']['6_to_10'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">6-10 years</p>
                </div>
            </div>
        </div>

        <!-- More than 10 years -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-red-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 text-xs font-semibold px-2.5 py-1 rounded">SENIOR</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['staffByYearsOfService']['more_than_10'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">More than 10 years</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection