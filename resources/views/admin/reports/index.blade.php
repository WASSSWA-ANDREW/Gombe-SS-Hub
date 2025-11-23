@extends('layouts.admin')

@section('title', 'School Reports Dashboard')

@section('content')
<div class="container px-4 sm:px-6 mx-auto grid">
    <h2 class="my-4 sm:my-6 text-xl sm:text-2xl font-semibold text-gray-700 dark:text-gray-200">
        School Reports Dashboard
    </h2>
    
    <!-- Description -->
    <div class="px-3 sm:px-4 py-3 mb-6 sm:mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            View comprehensive reports about students and staff. Generate PDF reports or export data to Excel.
        </p>
    </div>

    <!-- Report Categories -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Student Distribution -->
        <a href="{{ route('admin.reports.student-distribution') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-blue-100 dark:bg-blue-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Student Distribution</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">View student distribution by class, stream, and level.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-blue-600 dark:text-blue-400 hover:underline text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
        
        <!-- Demographics -->
        <a href="{{ route('admin.reports.demographics') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-purple-100 dark:bg-purple-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Demographics</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">View student demographics by district, nationality, tribe, religion, and gender.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:underline text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
        
        <!-- Staff Reports -->
        <a href="{{ route('admin.reports.staff') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-green-100 dark:bg-green-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Staff Reports</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">View staff distribution by role, gender, and department.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-green-600 dark:text-green-400 hover:underline text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
        
        <!-- Academic Performance -->
        <a href="{{ route('admin.reports.academic-performance') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-amber-100 dark:bg-amber-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Academic Performance</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">View student academic performance and grades analysis.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-amber-600 dark:text-amber-400 hover:underline text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
        
        <!-- Attendance -->
        <a href="{{ route('admin.reports.attendance') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-red-100 dark:bg-red-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Attendance</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">View student and staff attendance records and trends.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-red-600 dark:text-red-400 hover:underline text-sm">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
        
        <!-- Custom Report -->
        <a href="{{ route('admin.reports.custom') }}" class="flex flex-col h-full p-4 bg-white rounded-lg border border-gray-200 shadow-md hover:bg-gray-100 dark:bg-gray-800 dark:border-gray-700 dark:hover:bg-gray-700 transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center mb-3">
                <div class="p-3 bg-indigo-100 dark:bg-indigo-900/30 rounded-full mr-3">
                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
                <h5 class="text-lg font-bold tracking-tight text-gray-900 dark:text-white">Custom Report</h5>
            </div>
            <p class="font-normal text-gray-700 dark:text-gray-400 mb-3 text-sm">Create custom reports with specific filters and parameters.</p>
            <div class="mt-auto flex justify-end">
                <span class="inline-flex items-center text-indigo-600 dark:text-indigo-400 hover:underline text-sm">
                    Create Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                    </svg>
                </span>
            </div>
        </a>
    </div>

    <!-- Quick Stats Cards -->
    <h3 class="my-4 sm:my-6 text-lg sm:text-xl font-semibold text-gray-700 dark:text-gray-200">
        Quick Statistics
    </h3>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
        <!-- Card 1: Total Students -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-blue-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Total Students</h4>
                        <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2 py-0.5 rounded">ALL</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalStudents'] }}</p>
                    <div class="text-blue-500 dark:text-blue-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Teachers -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-green-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-green-100 dark:bg-green-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Total Teachers</h4>
                        <span class="bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 text-xs font-semibold px-2 py-0.5 rounded">STAFF</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalTeachers'] }}</p>
                    <div class="text-green-500 dark:text-green-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: O'Level Students -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-red-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-red-100 dark:bg-red-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">O'Level</h4>
                        <span class="bg-red-100 dark:bg-red-900/50 text-red-800 dark:text-red-300 text-xs font-semibold px-2 py-0.5 rounded">STUDENTS</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalOLevelStudents'] }}</p>
                    <div class="text-red-500 dark:text-red-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 4: A'Level Students -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-amber-700"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-amber-100 dark:bg-amber-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-amber-700 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">A'Level</h4>
                        <span class="bg-amber-100 dark:bg-amber-900/50 text-amber-800 dark:text-amber-300 text-xs font-semibold px-2 py-0.5 rounded">STUDENTS</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalALevelStudents'] }}</p>
                    <div class="text-amber-700 dark:text-amber-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 5: Male Students -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-indigo-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-indigo-100 dark:bg-indigo-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Male Students</h4>
                        <span class="bg-indigo-100 dark:bg-indigo-900/50 text-indigo-800 dark:text-indigo-300 text-xs font-semibold px-2 py-0.5 rounded">MALE</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalMaleStudents'] }}</p>
                    <div class="text-indigo-500 dark:text-indigo-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 6: Female Students -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-pink-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-pink-100 dark:bg-pink-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-pink-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Female Students</h4>
                        <span class="bg-pink-100 dark:bg-pink-900/50 text-pink-800 dark:text-pink-300 text-xs font-semibold px-2 py-0.5 rounded">FEMALE</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalFemaleStudents'] }}</p>
                    <div class="text-pink-500 dark:text-pink-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Statistics -->
    <h3 class="my-4 sm:my-6 text-lg sm:text-xl font-semibold text-gray-700 dark:text-gray-200">
        Staff Statistics
    </h3>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 mb-8">
        <!-- Card 1: Total Male Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-blue-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Male Staff</h4>
                        <span class="bg-blue-100 dark:bg-blue-900/50 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2 py-0.5 rounded">STAFF</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalMaleStaff'] }}</p>
                        <div class="flex items-center mt-1">
                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? (($data['totalMaleStaff'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-800 dark:text-gray-400 ml-1">{{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? round(($data['totalMaleStaff'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%</span>
                        </div>
                    </div>
                    <div class="text-blue-500 dark:text-blue-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 2: Total Female Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-pink-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-pink-100 dark:bg-pink-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-pink-500 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Female Staff</h4>
                        <span class="bg-pink-100 dark:bg-pink-900/50 text-pink-800 dark:text-pink-300 text-xs font-semibold px-2 py-0.5 rounded">STAFF</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalFemaleStaff'] }}</p>
                        <div class="flex items-center mt-1">
                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-pink-600 h-1.5 rounded-full" style="width: {{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? (($data['totalFemaleStaff'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-800 dark:text-gray-400 ml-1">{{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? round(($data['totalFemaleStaff'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%</span>
                        </div>
                    </div>
                    <div class="text-pink-500 dark:text-pink-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Card 3: Total Administrators -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-purple-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-purple-100 dark:bg-purple-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Administrators</h4>
                        <span class="bg-purple-100 dark:bg-purple-900/50 text-purple-800 dark:text-purple-300 text-xs font-semibold px-2 py-0.5 rounded">ADMIN</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalAdministrators'] }}</p>
                        <div class="flex items-center mt-1">
                            <div class="w-full bg-gray-200 rounded-full h-1.5 dark:bg-gray-700">
                                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? (($data['totalAdministrators'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%"></div>
                            </div>
                            <span class="text-xs text-gray-800 dark:text-gray-400 ml-1">{{ ($data['totalMaleStaff'] + $data['totalFemaleStaff']) > 0 ? round(($data['totalAdministrators'] / ($data['totalMaleStaff'] + $data['totalFemaleStaff'])) * 100) : 0 }}%</span>
                        </div>
                    </div>
                    <div class="text-purple-500 dark:text-purple-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card 4: Regular Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-teal-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-teal-100 dark:bg-teal-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-teal-500 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Regular Staff</h4>
                        <span class="bg-teal-100 dark:bg-teal-900/50 text-teal-800 dark:text-teal-300 text-xs font-semibold px-2 py-0.5 rounded">PRIVATE</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalRegularStaff'] }}</p>
                    <div class="text-teal-500 dark:text-teal-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Card 5: Government Staff -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden transform transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
            <div class="h-1 bg-orange-500"></div>
            <div class="p-4">
                <div class="flex items-center mb-2">
                    <div class="bg-orange-100 dark:bg-orange-900/30 p-2 rounded-full mr-3">
                        <svg class="w-6 h-6 text-orange-500 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-white">Government Staff</h4>
                        <span class="bg-orange-100 dark:bg-orange-900/50 text-orange-800 dark:text-orange-300 text-xs font-semibold px-2 py-0.5 rounded">GOV'T</span>
                    </div>
                </div>
                <div class="flex items-end justify-between">
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $data['totalGovernmentStaff'] }}</p>
                    <div class="text-orange-500 dark:text-orange-400">
                        <svg class="w-8 h-8 opacity-20" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a1 1 0 110 2h-3a1 1 0 01-1-1v-2a1 1 0 00-1-1H9a1 1 0 00-1 1v2a1 1 0 01-1 1H4a1 1 0 110-2V4zm3 1h2v2H7V5zm2 4H7v2h2V9zm2-4h2v2h-2V5zm2 4h-2v2h2V9z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Generate Reports</h3>
        
        <form action="{{ route('admin.reports.generate-pdf') }}" method="POST" class="mb-4">
            @csrf
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-grow">
                    <select name="report_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option value="summary">School Summary Report</option>
                        <option value="student_distribution">Student Distribution Report</option>
                        <option value="demographics">Student Demographics Report</option>
                        <option value="staff">Staff Report</option>
                    </select>
                </div>
                <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Generate PDF
                </button>
                <button type="submit" formaction="{{ route('admin.reports.export-excel') }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                    Export to Excel
                </button>
            </div>
        </form>
    </div>
</div>
@endsection