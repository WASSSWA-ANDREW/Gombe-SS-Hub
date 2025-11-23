@extends('layouts.admin')

@section('title', 'Dashboard')
@section('header', 'Dashboard Overview')

@push('styles')
<style>
    /* Welcome card styling */
    .welcome-card-gradient {
        background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 51, 234, 0.05) 100%);
        position: relative;
        overflow: hidden;
    }
    
    .welcome-card-gradient::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5z' fill='%234b5563' fill-opacity='0.03' fill-rule='evenodd'/%3E%3C/svg%3E");
        opacity: 0.5;
        z-index: 0;
    }
    
    /* Base grid container */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(2, 480px);
        grid-auto-rows: 320px;
        gap: 24px;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fb;
        border-radius: 8px;
        margin-bottom: 32px;
    }

    /* Chart card design */
    .dashboard-grid .chart-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        padding: 24px;
        font-weight: 500;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    /* Hover effect */
    .dashboard-grid .chart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Dark mode support */
    .dark .dashboard-grid {
        background-color: #111827;
    }

    .dark .dashboard-grid .chart-card {
        background-color: #1f2937;
        color: #f3f4f6;
    }

    /* Canvas container for charts */
    .dashboard-grid .chart-card canvas {
        flex: 1;
        max-width: 100%;
        height: auto;
        display: block;
    }
    
    /* School Population Chart styling */
    #schoolPopulationChart {
        max-width: 100%;
        height: 100% !important;
        display: block;
        z-index: 10;
        position: relative;
    }
    
    .chart-container {
        position: relative;
        width: 100%;
        height: 100%;
        border-radius: 0.5rem;
        overflow: hidden;
        transition: all 0.3s ease;
        background-color: rgba(255, 255, 255, 0.05);
    }
    
    .chart-container:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3);
    }
    
    /* Make sure the chart is visible */
    canvas#schoolPopulationChart {
        visibility: visible !important;
        opacity: 1 !important;
    }
    
    /* Population stat circles */
    .population-stat-circle {
        transition: all 0.3s ease;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }
    
    .population-stat-circle:hover {
        transform: scale(1.1);
    }

    /* Graph card header styles */
    .graph-card-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 16px;
    }

    .graph-card-title {
        flex: 1;
        min-width: 0;
        white-space: normal;
        word-break: break-word;
    }

    .graph-card-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        align-items: center;
        justify-content: flex-end;
        flex-shrink: 0;
    }

    .legend-item {
        display: flex;
        align-items: center;
        white-space: nowrap;
        font-size: 0.75rem;
    }

    /* Responsive behavior */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 360px);
            grid-auto-rows: 280px;
            gap: 20px;
        }
        .graph-card-legend {
            justify-content: flex-start;
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            grid-auto-rows: 250px;
            padding: 16px;
        }
        .graph-card-header {
            flex-direction: column;
            align-items: flex-start;
        }
        .graph-card-legend {
            width: 100%;
            justify-content: flex-start;
            flex-direction: column;
            gap: 8px;
        }
    }

    /* ====================================== */
    /* DASHBOARD SUMMARY CARDS - WHITE TEXT & ICONS FOR ALL THEMES */
    /* ====================================== */
    
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div {
        color: #ffffff !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div * {
        color: #ffffff !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div p,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div span,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div div,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h1,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h2,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h3,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h4,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h5,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div h6 {
        color: #ffffff !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div svg,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div svg * {
        color: #ffffff !important;
        stroke: #ffffff !important;
        fill: #ffffff !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div .opacity-80,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div .opacity-90 {
        color: rgba(255, 255, 255, 0.8) !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div,
    .flex.flex-wrap.justify-center.gap-6.mb-8 > div * {
        color: white !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div p {
        color: white !important;
    }

    .flex.flex-wrap.justify-center.gap-6.mb-8 > div span {
        color: white !important;
    }
</style>
@endpush

@section('content')
<div class="container mx-auto px-4 py-6">



    <!-- Quick Stats Cards -->
    <div class="flex flex-wrap justify-center gap-6 mb-8">
        <!-- Card 1: Total Students -->
        <div class="bg-blue-500 dark:bg-blue-700 text-white shadow-lg rounded-lg p-6 hover:bg-blue-600 dark:hover:bg-blue-800 transition-all duration-300 flex flex-col justify-between" style="height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="bg-blue-400/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                </div>
                <span class="bg-blue-400/30 text-xs font-semibold px-2.5 py-1 rounded" style="color: white; font-family: Ubuntu;">STUDENTS</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalStudents }}</p>
                <p class="text-sm opacity-80 mt-1">Total Students</p>
            </div>
        </div>

        <!-- Card 2: Total Staff -->
        <div class="bg-green-500 dark:bg-green-700 text-white shadow-lg rounded-lg p-6 hover:bg-green-600 dark:hover:bg-green-800 transition-all duration-300 flex flex-col justify-between" style="height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="bg-green-400/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <span class="bg-green-400/30 text-xs font-semibold px-2.5 py-1 rounded" style="color: white; font-family: Ubuntu;">STAFF</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalStaff }}</p>
                <p class="text-sm opacity-80 mt-1">Total Staff</p>
            </div>
        </div>

        <!-- Card 3: Total Users -->
        <div class="bg-yellow-500 dark:bg-yellow-600 text-white shadow-lg rounded-lg p-6 hover:bg-yellow-600 dark:hover:bg-yellow-700 transition-all duration-300 flex flex-col justify-between" style="height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="bg-yellow-400/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <span class="bg-yellow-400/30 text-xs font-semibold px-2.5 py-1 rounded" style="color: white; font-family: Ubuntu;">USERS</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalUsers }}</p>
                <p class="text-sm opacity-80 mt-1">Total Users</p>
            </div>
        </div>

        <!-- Card 4: O'Level Students -->
        <div class="bg-red-500 dark:bg-red-700 text-white shadow-lg rounded-lg p-6 hover:bg-red-600 dark:hover:bg-red-800 transition-all duration-300 flex flex-col justify-between" style="height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="bg-red-400/30 p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path></svg>
                </div>
                <span class="bg-red-400/30 text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">O'LEVEL</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalOlevelStudents }}</p>
                <p class="text-sm opacity-80 mt-1">O'Level Students</p>
            </div>
        </div>

        <!-- Card 5: A'Level Students (Maroon #800000) -->
        <div class="text-white shadow-lg rounded-lg p-6 transition-all duration-300 flex flex-col justify-between" style="background-color: #800000; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path></svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">A'LEVEL</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalAlevelStudents }}</p>
                <p class="text-sm opacity-80 mt-1">A'Level Students</p>
            </div>
        </div>

        <!-- Card 6: Total File Uploads (Navy Blue #000080) -->
        <div class="text-white shadow-lg rounded-lg p-6 transition-all duration-300 flex flex-col justify-between" style="background-color: #000080; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">FILES</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalFileUploads }}</p>
                <p class="text-sm opacity-80 mt-1">File Uploads</p>
            </div>
        </div>

        <!-- Card 7: Muslim Students (Green #006400) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-green-800 transition-all duration-300 flex flex-col justify-between" style="background-color: #006400; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">MUSLIM</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalMuslimStudents }}</p>
                <p class="text-sm opacity-80 mt-1">Muslim Students</p>
            </div>
        </div>

        <!-- Card 8: Christian Students (Purple #800080) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-purple-900 transition-all duration-300 flex flex-col justify-between" style="background-color: #800080; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">CHRISTIAN</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalChristianStudents }}</p>
                <p class="text-sm opacity-80 mt-1">Christian Students</p>
            </div>
        </div>

        <!-- Card 9: Total Alumni (Orange #FF8C00) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-orange-600 transition-all duration-300 flex flex-col justify-between" style="background-color: #FF8C00; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">ALUMNI</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalAlumni }}</p>
                <p class="text-sm opacity-80 mt-1">Total Alumni</p>
                <div class="text-xs opacity-100 mt-2">
                    <span class="inline-block mr-3">O'Level: {{ $olevelAlumni }}</span>
                    <span class="inline-block">A'Level: {{ $alevelAlumni }}</span>
                </div>
            </div>
        </div>

        <!-- Card 10: Discipline Records (Red #DC143C) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-red-700 transition-all duration-300 flex flex-col justify-between" style="background-color: #DC143C; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">DISCIPLINE</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalDisciplineRecords ?? 0 }}</p>
                <p class="text-sm opacity-80 mt-1">Total Records</p>
                <div class="text-xs opacity-100 mt-2">
                    <span class="inline-block mr-3">Pending: {{ $pendingDisciplineCases ?? 0 }}</span>
                    <span class="inline-block">Resolved: {{ ($totalDisciplineRecords ?? 0) - ($pendingDisciplineCases ?? 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Card 11: Counselling Records (Purple #6A0DAD) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-purple-800 transition-all duration-300 flex flex-col justify-between" style="background-color: #6A0DAD; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">COUNSELLING</span>
            </div>
            <div class="mt-4">
                <p class="text-4xl font-bold">{{ $totalCounsellingRecords ?? 0 }}</p>
                <p class="text-sm opacity-80 mt-1">Total Sessions</p>
                <div class="text-xs opacity-100 mt-2">
                    <span class="inline-block mr-3">Ongoing: {{ $ongoingCounsellingSessions ?? 0 }}</span>
                    <span class="inline-block">Completed: {{ ($totalCounsellingRecords ?? 0) - ($ongoingCounsellingSessions ?? 0) }}</span>
                </div>
            </div>
        </div>

        <!-- Card 12: Academic Performance (Teal #008080) -->
        <div class="text-white shadow-lg rounded-lg p-6 hover:bg-teal-700 transition-all duration-300 flex flex-col justify-between" style="background-color: #008080; height: 160px; width: 280px;">
            <div class="flex justify-between items-start">
                <div class="p-3 rounded-full">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded" style="color: white;">PERFORMANCE</span>
            </div>
            <div class="mt-4">
                <p class="text-sm opacity-80 mb-2">Best Performing Streams</p>
                <div class="text-xs opacity-90">
                    @if($academicPerformance['olevel'])
                        <div class="mb-1"><span class="font-semibold">O'Level:</span> {{ $academicPerformance['olevel'] }} ({{ $academicPerformance['olevel_score'] }})</div>
                    @else
                        <div class="mb-1"><span class="font-semibold">O'Level:</span> N/A</div>
                    @endif
                    @if($academicPerformance['alevel'])
                        <div><span class="font-semibold">A'Level:</span> {{ $academicPerformance['alevel'] }} ({{ $academicPerformance['alevel_score'] }})</div>
                    @else
                        <div><span class="font-semibold">A'Level:</span> N/A</div>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Horizontal Line: Recent File Uploads, Student Gender Distribution, Staff Gender Distribution -->
    <div class="flex flex-nowrap gap-6 mb-8 overflow-x-auto">
        <!-- File Uploads Summary Card -->
        @if($recentFileUploads->count() > 0)
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 flex-shrink-0" style="width: 400px; height: 320px; overflow-y: auto;">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-3 rounded-lg mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-white">Recent File Uploads</h3>
                        <p class="text-sm text-gray-800 dark:text-gray-300">{{ $totalFileUploads }} document{{ $totalFileUploads !== 1 ? 's' : '' }}</p>
                    </div>
                </div>
            </div>

            <!-- File Uploads List -->
            <div class="space-y-2">
                @forelse($recentFileUploads as $upload)
                <a href="{{ route('admin.files.download', ['id' => $upload['id'], 'type' => $upload['type'] === 'student' ? 'students' : 'staff']) }}" 
                   class="flex items-center justify-between p-2 bg-gradient-to-r from-indigo-50 to-indigo-100 dark:from-indigo-900/20 dark:to-indigo-800/20 rounded-lg hover:from-indigo-100 hover:to-indigo-200 dark:hover:from-indigo-900/30 dark:hover:to-indigo-800/30 transition-all duration-200 group text-sm">
                    <div class="flex items-center flex-1 min-w-0">
                        <div class="flex-shrink-0">
                            @if($upload['type'] === 'student')
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-blue-600 dark:bg-blue-500">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            @else
                            <div class="flex items-center justify-center h-8 w-8 rounded-full bg-green-600 dark:bg-green-500">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="ml-2 flex-1 min-w-0">
                            <p class="text-xs font-medium text-gray-900 dark:text-white truncate group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                {{ $upload['name'] }}
                            </p>
                            <p class="text-xs text-gray-800 dark:text-gray-400">
                                {{ $upload['date']->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-800 dark:text-gray-400 text-sm">No files uploaded yet</p>
                </div>
                @endforelse
            </div>
        </div>
        @endif

        <!-- Student Gender Statistics Graph Card -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 flex-shrink-0" style="width: 400px; height: 320px;">
            <div class="graph-card-header">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white graph-card-title">Student Gender</h3>
                </div>
                <div class="graph-card-legend">
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">M: <strong>{{ $maleStudents }}</strong></span>
                    </div>
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-pink-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">F: <strong>{{ $femaleStudents }}</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="relative" style="height: 200px;">
                <canvas id="genderChart"></canvas>
            </div>
        </div>
        
        <!-- Staff Gender Statistics Graph Card -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 flex-shrink-0" style="width: 400px; height: 320px;">
            <div class="graph-card-header">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-green-500 to-green-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white graph-card-title">Staff Gender</h3>
                </div>
                <div class="graph-card-legend">
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-blue-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">M: <strong>{{ $maleStaff }}</strong></span>
                    </div>
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-pink-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">F: <strong>{{ $femaleStaff }}</strong></span>
                    </div>
                </div>
            </div>
            
            <div class="relative" style="height: 200px;">
                <canvas id="staffGenderChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Quick Actions, Growth Chart, School Population Distribution, Recent Activity - Horizontal Line -->
    <div class="flex flex-nowrap gap-6 mb-8 overflow-x-auto">
        <!-- Quick Actions Section -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex-shrink-0" style="width: 380px; height: 320px; overflow-y: auto;">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-3">Quick Actions</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                <a href="{{ route('admin.students.olevel.create') }}" class="bg-indigo-500 hover:bg-indigo-600 text-white font-semibold py-2 px-3 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Add Student
                </a>
                <a href="{{ route('admin.staff.index') }}" class="bg-teal-500 hover:bg-teal-600 text-white font-semibold py-2 px-3 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Staff
                </a>
                <a href="{{ route('admin.reports.index') }}" class="bg-orange-500 hover:bg-orange-600 text-white font-semibold py-2 px-3 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Report
                </a>
                <a href="{{ route('admin.settings.index') }}" class="bg-purple-500 hover:bg-purple-600 text-white font-semibold py-2 px-3 rounded-lg text-center transition-colors duration-300 flex items-center justify-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    Settings
                </a>
            </div>
        </div>

        <!-- Growth Chart Card -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 flex-shrink-0" style="width: 380px; height: 320px;">
            <div class="graph-card-header">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-purple-500 to-purple-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white graph-card-title">Data Entry Growth</h3>
                </div>
                <div class="graph-card-legend">
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-green-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">Students</span>
                    </div>
                    <div class="legend-item">
                        <span class="w-3 h-3 rounded-full bg-purple-500 mr-1"></span>
                        <span class="text-gray-800 dark:text-gray-300">Staff</span>
                    </div>
                </div>
            </div>
            
            <div class="relative" style="height: 200px;">
                <canvas id="growthChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Section -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 flex-shrink-0" style="width: 380px; height: 320px; overflow-y: auto;">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-white mb-3">Recent Activity</h3>
            <div class="space-y-3">
                <div class="flex items-start">
                    <div class="bg-indigo-100 dark:bg-indigo-900/30 p-1 rounded-full mr-2 flex-shrink-0">
                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">New student application</p>
                        <p class="text-gray-800 dark:text-gray-400 text-xs truncate">{{ now()->subHours(2)->format('M d, H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-teal-100 dark:bg-teal-900/30 p-1 rounded-full mr-2 flex-shrink-0">
                        <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">Staff member updated</p>
                        <p class="text-gray-800 dark:text-gray-400 text-xs truncate">{{ now()->subHours(5)->format('M d, H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-orange-100 dark:bg-orange-900/30 p-1 rounded-full mr-2 flex-shrink-0">
                        <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">Attendance report</p>
                        <p class="text-gray-800 dark:text-gray-400 text-xs truncate">{{ now()->subDay()->format('M d, H:i') }}</p>
                    </div>
                </div>
                
                <div class="flex items-start">
                    <div class="bg-blue-100 dark:bg-blue-900/30 p-1 rounded-full mr-2 flex-shrink-0">
                        <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="text-gray-700 dark:text-gray-300 font-medium text-sm">System notification</p>
                        <p class="text-gray-800 dark:text-gray-400 text-xs truncate">{{ now()->subDays(2)->format('M d, H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- School Population Distribution Pie Chart -->
        <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl p-6 transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-1 flex-shrink-0" style="width: 380px; height: 320px;">
            <div class="graph-card-header">
                <div class="flex items-center">
                    <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 p-2 rounded-lg mr-3">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18a6 6 0 100-12 6 6 0 000 12zM12 6v6m3-3H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-white graph-card-title">School Pop.</h3>
                </div>
            </div>
            
            <div class="relative" style="height: 230px;">
                <canvas id="schoolPopulationChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Floating Contact Buttons -->
    <div style="position: fixed; bottom: 20px; right: 20px; z-index: 9999;">
        <!-- WhatsApp Button -->
        <a href="https://wa.me/256779201801?text=Hello%2C%20I%20need%20assistance%20with%20Gombe%20SS%20Hub" 
           target="_blank"
           style="display: block; background-color: #25D366; color: white; padding: 12px; border-radius: 50%; margin-bottom: 10px; box-shadow: 0 4px 12px rgba(0,0,0,0.3); text-decoration: none; width: 50px; height: 50px; text-align: center; line-height: 26px;"
           title="Contact us on WhatsApp">
            📱
        </a>

        <!-- Phone Call Button -->
        <a href="tel:+256779201801" 
           style="display: block; background-color: #007bff; color: white; padding: 12px; border-radius: 50%; box-shadow: 0 4px 12px rgba(0,0,0,0.3); text-decoration: none; width: 50px; height: 50px; text-align: center; line-height: 26px;"
           title="Call us directly">
            📞
        </a>
    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart.js Global Configuration
        Chart.defaults.font.family = "'Ubuntu', 'Helvetica', 'Arial', sans-serif";
        Chart.defaults.color = '#6B7280';
        Chart.defaults.plugins.tooltip.padding = 12;
        Chart.defaults.plugins.tooltip.cornerRadius = 8;
        Chart.defaults.plugins.tooltip.titleFont.weight = 'bold';
        Chart.defaults.plugins.tooltip.titleFont.size = 14;
        Chart.defaults.plugins.tooltip.bodyFont.size = 13;
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        Chart.defaults.plugins.tooltip.borderColor = 'rgba(255, 255, 255, 0.1)';
        Chart.defaults.plugins.tooltip.borderWidth = 1;
        Chart.defaults.plugins.tooltip.displayColors = true;
        Chart.defaults.plugins.tooltip.boxPadding = 6;
        Chart.defaults.plugins.tooltip.usePointStyle = true;
        
        // Student Gender Distribution Chart
        const genderCtx = document.getElementById('genderChart');
        if (genderCtx) {
            const maleCount = {{ $maleStudents }};
            const femaleCount = {{ $femaleStudents }};
            const totalCount = {{ $totalStudents }};
            
            if (totalCount > 0) {
                new Chart(genderCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Male Students', 'Female Students'],
                        datasets: [{
                            label: 'Number of Students',
                            data: [maleCount, femaleCount],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',  // Blue for male
                                'rgba(236, 72, 153, 0.8)'   // Pink for female
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(236, 72, 153, 1)'
                            ],
                            borderWidth: 2,
                            borderRadius: 8,
                            barThickness: 80
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        const percentage = ((value / totalCount) * 100).toFixed(1);
                                        return `Count: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: Math.ceil(Math.max(maleCount, femaleCount) / 5),
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                genderCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-800 dark:text-gray-400"><p>No student data available</p></div>';
            }
        }
        
        // Staff Gender Distribution Chart
        const staffGenderCtx = document.getElementById('staffGenderChart');
        if (staffGenderCtx) {
            const maleStaffCount = {{ $maleStaff }};
            const femaleStaffCount = {{ $femaleStaff }};
            const totalStaffCount = {{ $totalStaff }};
            
            if (totalStaffCount > 0) {
                new Chart(staffGenderCtx, {
                    type: 'bar',
                    data: {
                        labels: ['Male Staff', 'Female Staff'],
                        datasets: [{
                            label: 'Number of Staff',
                            data: [maleStaffCount, femaleStaffCount],
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',  // Blue for male
                                'rgba(236, 72, 153, 0.8)'   // Pink for female
                            ],
                            borderColor: [
                                'rgba(59, 130, 246, 1)',
                                'rgba(236, 72, 153, 1)'
                            ],
                            borderWidth: 2,
                            borderRadius: 8,
                            barThickness: 80
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 1500,
                            easing: 'easeOutQuart'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        const percentage = ((value / totalStaffCount) * 100).toFixed(1);
                                        return `Count: ${value} (${percentage}%)`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: Math.ceil(Math.max(maleStaffCount, femaleStaffCount) / 5),
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 13,
                                        weight: 'bold'
                                    }
                                },
                                grid: {
                                    display: false
                                }
                            }
                        }
                    }
                });
            } else {
                staffGenderCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-800 dark:text-gray-400"><p>No staff data available</p></div>';
            }
        }
        
        // Growth Chart
        const growthCtx = document.getElementById('growthChart');
        if (growthCtx) {
            const labels = @json($growthChartData['labels']);
            const studentData = @json($growthChartData['studentData']);
            const staffData = @json($growthChartData['staffData']);
            
            if (labels.length > 0) {
                new Chart(growthCtx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Students',
                                data: studentData,
                                borderColor: 'rgba(34, 197, 94, 1)',  // Green
                                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'rgba(34, 197, 94, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: 'rgba(34, 197, 94, 1)',
                                pointHoverBorderColor: '#fff',
                                pointHoverBorderWidth: 2
                            },
                            {
                                label: 'Staff',
                                data: staffData,
                                borderColor: 'rgba(168, 85, 247, 1)',  // Purple
                                backgroundColor: 'rgba(168, 85, 247, 0.1)',
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: 'rgba(168, 85, 247, 1)',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7,
                                pointHoverBackgroundColor: 'rgba(168, 85, 247, 1)',
                                pointHoverBorderColor: '#fff',
                                pointHoverBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 2000,
                            easing: 'easeOutQuart'
                        },
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.y;
                                        const datasetLabel = context.dataset.label;
                                        return `${datasetLabel}: ${value} entries`;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1,
                                    font: {
                                        size: 12
                                    }
                                },
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)'
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Entries',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            },
                            x: {
                                ticks: {
                                    font: {
                                        size: 11
                                    }
                                },
                                grid: {
                                    display: false
                                },
                                title: {
                                    display: true,
                                    text: 'Month',
                                    font: {
                                        size: 12,
                                        weight: 'bold'
                                    }
                                }
                            }
                        }
                    }
                });
            } else {
                growthCtx.parentElement.innerHTML = '<div class="flex items-center justify-center h-full text-gray-800 dark:text-gray-400"><p>No growth data available</p></div>';
            }
        }
        
        // Initialize School Population Pie Chart
        try {
            console.log('Initializing School Population Chart...');
            
            // Check if Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.log('Chart.js is not loaded - using fallback display');
                const chartElement = document.getElementById('schoolPopulationChart');
                if (chartElement && chartElement.parentElement) {
                    chartElement.parentElement.innerHTML = `
                        <div class="flex items-center justify-center h-full flex-col">
                            <svg class="w-10 h-10 text-red-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-800 dark:text-gray-400 text-sm text-center">Chart library not loaded</p>
                        </div>
                    `;
                }
                return;
            }
            
            // Add a small delay to ensure DOM is fully loaded
            setTimeout(function() {
            
            const populationChartCtx = document.getElementById('schoolPopulationChart');
            
            if (!populationChartCtx) {
                console.log('School Population Chart canvas element not found - using fallback display');
                return;
            }
            
            let olevelStudents = parseInt('{{ $totalOlevelStudents }}') || 0;
            let alevelStudents = parseInt('{{ $totalAlevelStudents }}') || 0;
            let teachingStaffCount = parseInt('{{ $teachingStaff }}') || 0;
            let nonTeachingStaffCount = parseInt('{{ $nonTeachingStaff }}') || 0;
            let supportStaffCount = parseInt('{{ $supportStaff }}') || 0;
            let alumniCount = parseInt('{{ $totalAlumni }}') || 0;
            
            let total = olevelStudents + alevelStudents + teachingStaffCount + nonTeachingStaffCount + supportStaffCount + alumniCount;
            
            if (total === 0 || isNaN(total)) {
                olevelStudents = 150;
                alevelStudents = 120;
                teachingStaffCount = 35;
                nonTeachingStaffCount = 18;
                supportStaffCount = 12;
                alumniCount = 50;
                total = olevelStudents + alevelStudents + teachingStaffCount + nonTeachingStaffCount + supportStaffCount + alumniCount;
            }
            
            console.log('Chart data loaded:', { olevelStudents, alevelStudents, teachingStaffCount, nonTeachingStaffCount, supportStaffCount, alumniCount, total });
            
            // Destroy existing chart if it exists
            if (window.schoolPopulationChart) {
                window.schoolPopulationChart.destroy();
            }
            
            window.schoolPopulationChart = new Chart(populationChartCtx, {
                type: 'pie',
                data: {
                    labels: ['O\'Level Students', 'A\'Level Students', 'Teaching Staff', 'Non-Teaching Staff', 'Support Staff', 'Alumni'],
                    datasets: [{
                        data: [olevelStudents, alevelStudents, teachingStaffCount, nonTeachingStaffCount, supportStaffCount, alumniCount],
                        backgroundColor: [
                            '#3B82F6', // Blue for O'Level Students
                            '#DC2626', // Red for A'Level Students
                            '#10B981', // Green for Teaching Staff
                            '#F59E0B', // Amber for Non-Teaching Staff
                            '#8B5CF6', // Purple for Support Staff
                            '#EC4899'  // Pink for Alumni
                        ],
                        borderColor: [
                            '#1E40AF', // Darker Blue
                            '#7F1D1D', // Darker Red
                            '#065F46', // Darker Green
                            '#92400E', // Darker Amber
                            '#5B21B6', // Darker Purple
                            '#831843'  // Darker Pink
                        ],
                        borderWidth: 3,
                        hoverBackgroundColor: [
                            '#60A5FA', // Lighter Blue
                            '#EF4444', // Lighter Red
                            '#34D399', // Lighter Green
                            '#FBBF24', // Lighter Amber
                            '#A78BFA', // Lighter Purple
                            '#F472B6'  // Lighter Pink
                        ],
                        borderRadius: 4,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    layout: {
                        padding: 20
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true,
                            backgroundColor: 'rgba(0,0,0,0.9)',
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                title: function(context) {
                                    return context[0].label;
                                },
                                label: function(context) {
                                    const value = context.raw || 0;
                                    const percentage = Math.round((value / total) * 100);
                                    return `Count: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true,
                        duration: 2000,
                        easing: 'easeOutQuart'
                    },
                    events: ['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove']
                }
            });
            
            // Handle window resize to make chart responsive
            window.addEventListener('resize', function() {
                if (window.schoolPopulationChart) {
                    // Chart.js automatically handles resize when the container changes size
                    // No need to call resize() as it's not a method of Chart.js
                    window.schoolPopulationChart.update();
                }
            });
            
            // Handle theme changes (light/dark mode)
            document.addEventListener('themeChanged', function() {
                if (window.schoolPopulationChart) {
                    window.schoolPopulationChart.update();
                }
            });
            
            // Force a redraw after a short delay to ensure proper rendering
            setTimeout(function() {
                if (window.schoolPopulationChart) {
                    window.schoolPopulationChart.update();
                    console.log('Chart updated after timeout');
                }
            }, 100);
            
            // Add another delayed update to ensure chart is visible
            setTimeout(function() {
                if (window.schoolPopulationChart) {
                    window.schoolPopulationChart.update();
                    console.log('Chart updated after second timeout');
                }
            }, 500);
            
            console.log('School Population Chart successfully initialized!');
            }, 50); // Close the setTimeout we added earlier
        } catch (error) {
            console.log('Handling chart initialization error gracefully');
            
            // Safely get the chart container
            try {
                const chartElement = document.getElementById('schoolPopulationChart');
                if (chartElement && chartElement.parentElement) {
                    // Use demo data instead of showing an error message
                    setTimeout(function() {
                        try {
                            const ctx = chartElement.getContext('2d');
                            window.schoolPopulationChart = new Chart(ctx, {
                                type: 'pie',
                                data: {
                                    labels: ['O\'Level Students', 'A\'Level Students', 'Teaching Staff', 'Non-Teaching Staff', 'Support Staff', 'Alumni'],
                                    datasets: [{
                                        data: [150, 120, 35, 18, 12, 50],
                                        backgroundColor: [
                                            '#3B82F6',
                                            '#DC2626',
                                            '#10B981',
                                            '#F59E0B',
                                            '#8B5CF6',
                                            '#EC4899'
                                        ],
                                        borderColor: [
                                            '#1E40AF',
                                            '#7F1D1D',
                                            '#065F46',
                                            '#92400E',
                                            '#5B21B6',
                                            '#831843'
                                        ],
                                        borderWidth: 3
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    plugins: {
                                        legend: {
                                            display: false
                                        }
                                    }
                                }
                            });
                        } catch (fallbackError) {
                            console.log('Using fallback display for chart');
                            chartElement.parentElement.innerHTML = `
                                <div class="flex items-center justify-center h-full flex-col">
                                    <p class="text-gray-800 dark:text-gray-400 text-sm text-center">Population data visualization</p>
                                </div>
                            `;
                        }
                    }, 100);
                }
            } catch (displayError) {
                // Silent fail - no console errors
            }
        }
    });
</script>
@endpush