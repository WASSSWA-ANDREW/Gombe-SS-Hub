@extends('layouts.admin')

@section('title', 'Academics Dashboard')

@section('header', 'Academics Dashboard')

@section('content')
<style>
    /* Base grid container */
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(3, 370px);
        grid-auto-rows: 280px;
        gap: 20px;
        justify-content: center;
        padding: 20px;
        background-color: #f8f9fb;
        border-radius: 8px;
    }

    /* Chart card design */
    .chart-card {
        background-color: #ffffff;
        border-radius: 16px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        flex-direction: column;
        padding: 20px;
        font-weight: 500;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
        overflow: hidden;
    }

    /* Hover effect */
    .chart-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    /* Chart title styling */
    .chart-card h3 {
        font-size: 16px;
        font-weight: 600;
        margin: 0 0 12px 0;
        color: #1f2937;
        flex-shrink: 0;
    }

    /* Dark mode support */
    .dark .chart-card {
        background-color: #1f2937;
        color: #f3f4f6;
    }

    .dark .chart-card h3 {
        color: #f3f4f6;
    }

    /* Canvas container for charts */
    .chart-card canvas {
        flex: 1;
        max-width: 100%;
        height: auto;
        display: block;
    }

    /* Responsive behavior */
    @media (max-width: 1024px) {
        .dashboard-grid {
            grid-template-columns: repeat(2, 360px);
            grid-auto-rows: 280px;
        }
    }

    @media (max-width: 768px) {
        .dashboard-grid {
            grid-template-columns: 1fr;
            grid-auto-rows: 250px;
            padding: 16px;
        }
    }
</style>

<div class="space-y-6">
    <!-- Summary Cards (KPI Cards: 2:1 Aspect Ratio) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- O'Level Students Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">O'Level Students</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $olevelStudents }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                    <i class="fas fa-book-reader text-blue-600 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- A'Level Students Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">A'Level Students</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $alevelStudents }}</p>
                </div>
                <div class="bg-green-100 dark:bg-green-900 rounded-full p-3">
                    <i class="fas fa-graduation-cap text-green-600 dark:text-green-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- O'Level General Subjects Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">O'Level General Subjects</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $olevelGeneralSubjects }}</p>
                </div>
                <div class="bg-purple-100 dark:bg-purple-900 rounded-full p-3">
                    <i class="fas fa-book-open text-purple-600 dark:text-purple-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- O'Level Optional Subjects Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">O'Level Optional Subjects</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $olevelOptionalSubjects }}</p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 rounded-full p-3">
                    <i class="fas fa-layer-group text-orange-600 dark:text-orange-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- A'Level Arts Subjects Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">A'Level Arts Subjects</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $alevelArtsSubjects }}</p>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-3">
                    <i class="fas fa-palette text-indigo-600 dark:text-indigo-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- A'Level Science Subjects Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">A'Level Science Subjects</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $alevelScienceSubjects }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 rounded-full p-3">
                    <i class="fas fa-flask text-red-600 dark:text-red-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- A'Level Subsidiary Subjects Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">A'Level Subsidiary Subjects</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $alevelSubsidiarySubjects }}</p>
                </div>
                <div class="bg-pink-100 dark:bg-pink-900 rounded-full p-3">
                    <i class="fas fa-book text-pink-600 dark:text-pink-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <!-- Total Teachers Card -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 hover:shadow-lg transition" style="aspect-ratio: 2 / 1;">
            <div class="flex items-center justify-between h-full">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">Teaching Staff</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $totalTeachers }}</p>
                </div>
                <div class="bg-yellow-100 dark:bg-yellow-900 rounded-full p-3">
                    <i class="fas fa-chalkboard-teacher text-yellow-600 dark:text-yellow-300 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section (Standard 3-column grid: 370×280 px per chart) -->
    <div class="dashboard-grid">
        <!-- Stream Distribution Chart (Donut: 1:1) -->
        <div class="chart-card">
            <h3>Stream Distribution</h3>
            <canvas id="streamDistributionChart"></canvas>
        </div>

        <!-- Class Distribution Chart (Bar Chart: 4:3) -->
        <div class="chart-card">
            <h3>Class Distribution</h3>
            <canvas id="classDistributionChart"></canvas>
        </div>

        <!-- O'Level Performance by Class (Bar Chart: 4:3) -->
        <div class="chart-card">
            <h3>O'Level Students by Class</h3>
            <canvas id="olevelPerformanceChart"></canvas>
        </div>

        <!-- A'Level Performance by Class (Bar Chart: 4:3) -->
        <div class="chart-card">
            <h3>A'Level Students by Class</h3>
            <canvas id="alevelPerformanceChart"></canvas>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('admin.academics.olevel.subjects') }}" class="bg-blue-50 dark:bg-blue-900 hover:bg-blue-100 dark:hover:bg-blue-800 rounded-lg p-4 text-center transition">
                <i class="fas fa-book-open text-blue-600 dark:text-blue-300 text-2xl mb-2"></i>
                <p class="text-gray-900 dark:text-white font-medium">O'Level Subjects</p>
            </a>
            <a href="{{ route('admin.academics.alevel.subjects') }}" class="bg-green-50 dark:bg-green-900 hover:bg-green-100 dark:hover:bg-green-800 rounded-lg p-4 text-center transition">
                <i class="fas fa-graduation-cap text-green-600 dark:text-green-300 text-2xl mb-2"></i>
                <p class="text-gray-900 dark:text-white font-medium">A'Level Subjects</p>
            </a>
            <a href="{{ route('admin.academics.olevel.marks') }}" class="bg-purple-50 dark:bg-purple-900 hover:bg-purple-100 dark:hover:bg-purple-800 rounded-lg p-4 text-center transition">
                <i class="fas fa-pen-alt text-purple-600 dark:text-purple-300 text-2xl mb-2"></i>
                <p class="text-gray-900 dark:text-white font-medium">O'Level Marks</p>
            </a>
            <a href="{{ route('admin.academics.teachers') }}" class="bg-orange-50 dark:bg-orange-900 hover:bg-orange-100 dark:hover:bg-orange-800 rounded-lg p-4 text-center transition">
                <i class="fas fa-chalkboard-teacher text-orange-600 dark:text-orange-300 text-2xl mb-2"></i>
                <p class="text-gray-900 dark:text-white font-medium">Teacher Assignments</p>
            </a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Class Distribution Chart (Bar Chart: 4:3 aspect ratio)
        const classDistCtx = document.getElementById('classDistributionChart');
        if (classDistCtx && typeof Chart !== 'undefined') {
            new Chart(classDistCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($classDistribution->pluck('class')->toArray()) !!},
                    datasets: [{
                        label: 'Students',
                        data: {!! json_encode($classDistribution->pluck('count')->toArray()) !!},
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // Stream Distribution Chart (Donut: 1:1 aspect ratio)
        const streamDistCtx = document.getElementById('streamDistributionChart');
        if (streamDistCtx && typeof Chart !== 'undefined') {
            new Chart(streamDistCtx, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($streamDistribution->pluck('stream')->filter(function($s) { return $s !== null; })->toArray()) !!},
                    datasets: [{
                        data: {!! json_encode($streamDistribution->where('stream', '!=', null)->pluck('count')->toArray()) !!},
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        }

        // O'Level Performance Chart (Bar Chart: 4:3 aspect ratio)
        const olevelCtx = document.getElementById('olevelPerformanceChart');
        if (olevelCtx && typeof Chart !== 'undefined') {
            new Chart(olevelCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($olevelPerformanceByClass->pluck('class')->toArray()) !!},
                    datasets: [{
                        label: 'O\'Level Students',
                        data: {!! json_encode($olevelPerformanceByClass->pluck('count')->toArray()) !!},
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        // A'Level Performance Chart (Bar Chart: 4:3 aspect ratio)
        const alevelCtx = document.getElementById('alevelPerformanceChart');
        if (alevelCtx && typeof Chart !== 'undefined') {
            new Chart(alevelCtx, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($alevelPerformanceByClass->pluck('class')->toArray()) !!},
                    datasets: [{
                        label: 'A\'Level Students',
                        data: {!! json_encode($alevelPerformanceByClass->pluck('count')->toArray()) !!},
                        backgroundColor: 'rgba(153, 102, 255, 0.6)',
                        borderColor: 'rgba(153, 102, 255, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    });
</script>
@endsection
