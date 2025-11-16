<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="theme-{{ auth()->check() ? (auth()->user()->preferences()->where('key', 'theme')->first()?->value ?? 'green') : 'green' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- PWA Meta Tags -->
    <meta name="theme-color" content="#3B82F6">
    <meta name="description" content="School Management System for data collection and management of students and teachers">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Gombe Hub">
    <link rel="icon" type="image/png" href="{{ asset('img/pwa/icon-192x192.png') }}" sizes="192x192">
    <link rel="apple-touch-icon" href="{{ asset('img/pwa/icon-192x192.png') }}">
    <link rel="manifest" href="{{ asset('manifest.json') }}">

    <title>@yield('title', 'Admin Dashboard') | Gombe SS Hub</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="{{ asset('css-manifest.php') }}"></script>
    <script src="{{ asset('css-check.js') }}"></script>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('build/assets/app-CnyAn2T9.css') }}" rel="stylesheet">
    
    <!-- Font Awesome - with integrity and crossorigin for better security -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
          integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
          crossorigin="anonymous" referrerpolicy="no-referrer">
    
    <!-- Font Awesome Fallback CSS -->
    <link rel="stylesheet" href="{{ asset('css/font-awesome-fix.css') }}">
    
    <!-- Text Contrast CSS - Ensure dark text on white backgrounds -->
    <link rel="stylesheet" href="{{ asset('css/text-contrast.css') }}">
    
    <!-- Theme Text Auto Contrast CSS - Automatic text color adjustment based on themes -->
    <link rel="stylesheet" href="{{ asset('css/theme-text-auto-contrast.css') }}">
    
    <!-- PWA Styles -->
    <link rel="stylesheet" href="{{ asset('css/pwa.css') }}">
    
    <!-- Icon Fix Script -->
    <script src="{{ asset('js/icon-fix.js') }}"></script>
    
    <!-- Google Fonts - Ubuntu (Default) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    
    <!-- Flatpickr Date Picker -->
    <link rel="stylesheet" href="{{ asset('css/flatpickr.min.css') }}">
    <script src="{{ asset('js/flatpickr.min.js') }}"></script>
    
    <!-- Chart.js - Simple and Direct Loading -->
    <script src="{{ asset('js/chart.min.js') }}"></script>
    <script>
        // Simple verification that Chart.js loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Chart.js available:', typeof Chart !== 'undefined');
            if (typeof Chart === 'undefined') {
                console.log('Chart.js not available - some visualizations may not appear');
                // Create a visible error message
                var errorDiv = document.createElement('div');
                errorDiv.style.position = 'fixed';
                errorDiv.style.top = '0';
                errorDiv.style.left = '0';
                errorDiv.style.right = '0';
                errorDiv.style.backgroundColor = 'red';
                errorDiv.style.color = 'white';
                errorDiv.style.padding = '10px';
                errorDiv.style.zIndex = '9999';
                errorDiv.textContent = 'Chart.js failed to load. Please check the console for errors.';
                document.body.appendChild(errorDiv);
            }
        });
    </script>
    
    <style>
        :root {
            --font-family: 'Ubuntu', sans-serif;
        }
        
        body {
            font-family: var(--font-family);
        }
        
        * {
            font-family: var(--font-family);
        }
        .sidebar-link {
            display: flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            transition: background-color 0.2s, color 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #4a5568; /* bg-gray-700 */
            color: #ffffff; /* text-white */
        }
        .sidebar-link svg {
            margin-right: 0.75rem;
            width: 1.25rem; /* w-5 */
            height: 1.25rem; /* h-5 */
        }
        /* Font Awesome icon sizing */
        .sidebar-link i {
            display: inline-block;
            text-align: center;
            min-width: 1.25rem;
        }
        .w-3 {
            width: 0.75rem;
            font-size: 0.75rem;
        }
        .h-3 {
            height: 0.75rem;
        }
        .w-4 {
            width: 1rem;
            font-size: 1rem;
        }
        .h-4 {
            height: 1rem;
        }
        .w-5 {
            width: 1.25rem;
            font-size: 1.25rem;
        }
        .h-5 {
            height: 1.25rem;
        }
        /* Margin utilities for icons */
        .mr-2 {
            margin-right: 0.5rem;
        }
        .mr-3 {
            margin-right: 0.75rem;
        }

        /* Sidebar Collapse Styles */
        .admin-sidebar {
            transition: width 0.3s ease-in-out;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100vh;
        }
        
        .admin-sidebar nav {
            flex: 1;
            overflow-y: auto;
        }

        .admin-sidebar.collapsed {
            width: 80px !important;
        }

        .admin-sidebar.collapsed .sidebar-text {
            opacity: 0;
            pointer-events: none;
            display: none;
        }

        .admin-sidebar.collapsed .sidebar-logo-text {
            display: none;
        }

        .admin-sidebar.collapsed .sidebar-logo-img {
            margin-right: 0;
        }

        .admin-sidebar.collapsed .sidebar-toggle-btn {
            justify-content: center;
        }

        .admin-sidebar.collapsed .sidebar-link {
            padding: 0.75rem;
            justify-content: center;
        }

        .admin-sidebar.collapsed .sidebar-link span {
            display: none;
        }

        .admin-sidebar.collapsed .sidebar-link svg {
            margin-right: 0;
        }

        .admin-sidebar.collapsed .sidebar-link i {
            margin-right: 0;
        }

        .admin-sidebar.collapsed details > summary .sidebar-text {
            display: none;
        }

        .admin-sidebar.collapsed details > summary i:last-child {
            display: none;
        }

        .admin-sidebar.collapsed details > ul {
            display: none;
        }

        .sidebar-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.75rem 1rem;
            cursor: pointer;
            transition: background-color 0.2s;
            border-radius: 0.375rem;
        }

        .sidebar-toggle-btn:hover {
            background-color: #4a5568;
        }

        .sidebar-toggle-btn i {
            width: 1.25rem;
            height: 1.25rem;
            text-align: center;
        }

        .admin-sidebar.collapsed .sidebar-toggle-btn {
            padding: 0.75rem 0;
        }

        .admin-sidebar.collapsed nav {
            padding: 0;
        }

        .admin-sidebar.collapsed details > summary {
            padding: 0.75rem;
            justify-content: center;
        }

        .admin-sidebar.collapsed details > summary i:first-child {
            margin: 0;
        }
        
        /* Profile Section Styles */
        .admin-sidebar .profile-section {
            transition: all 0.3s ease-in-out;
        }
        
        .admin-sidebar.collapsed .profile-info {
            display: none;
        }
        
        .admin-sidebar.collapsed .profile-section > div {
            justify-content: center;
            padding: 0.75rem;
        }
        
        /* Dashboard specific styles */
        .grid {
            display: grid;
        }
        .grid-cols-1 {
            grid-template-columns: repeat(1, minmax(0, 1fr));
        }
        @media (min-width: 768px) {
            .md\:grid-cols-2 {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .lg\:grid-cols-4 {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        .gap-6 {
            gap: 1.5rem;
        }
        .mb-8 {
            margin-bottom: 2rem;
        }
        .rounded-lg {
            border-radius: 0.5rem;
        }
        .shadow-lg {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        .p-6 {
            padding: 1.5rem;
        }
        .text-white {
            color: white;
        }
        .bg-blue-500 {
            background-color: #3b82f6;
        }
        .bg-green-500 {
            background-color: #10b981;
        }
        .bg-yellow-500 {
            background-color: #f59e0b;
        }
        .bg-red-500 {
            background-color: #ef4444;
        }
        .dark .dark\:bg-blue-700 {
            background-color: #1d4ed8;
        }
        .dark .dark\:bg-green-700 {
            background-color: #047857;
        }
        .dark .dark\:bg-yellow-600 {
            background-color: #d97706;
        }
        .dark .dark\:bg-red-700 {
            background-color: #b91c1c;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 antialiased">
    <div class="flex h-screen bg-gray-200 dark:bg-gray-800 admin-layout-main-bg">
        <!-- Sidebar -->
        <aside class="w-64 bg-gray-800 dark:bg-gray-900 text-gray-100 flex-shrink-0 admin-sidebar" id="adminSidebar">
            <div class="p-4 flex items-center justify-between">
                <div class="flex items-center flex-1">
                    <img src="{{ asset('assets/gombe_logo.png') }}" alt="Gombe SS Hub" class="h-10 w-auto mr-2 sidebar-logo-img">
                    <a href="{{ route('admin.dashboard') }}" class="text-white text-2xl font-semibold sidebar-logo-text">Gombe SS Hub</a>
                </div>
                <button id="sidebarToggle" class="sidebar-toggle-btn text-white">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <nav class="mt-4">
                <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="fas fa-chart-line mr-3 w-5 h-5"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>
                <details class="group" {{ request()->routeIs('admin.students.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.students.*') ? 'active' : '' }}" title="Student Details">
                        <span class="flex items-center">
                            <i class="fas fa-user-graduate mr-3 w-5 h-5"></i>
                            <span class="sidebar-text">Student Details</span>
                        </span>
                        <i class="fas fa-chevron-right w-4 h-4 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                    </summary>
                    <ul class="pl-4 pt-1 space-y-1">
                        <li>
                            <details class="group" {{ request()->routeIs('admin.students.olevel.*') ? 'open' : '' }}>
                                <summary class="sidebar-link text-sm py-1 cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.students.olevel.*') ? 'active bg-gray-700' : '' }}" title="O'Level Students">
                                    <span><i class="fas fa-book-reader mr-2"></i><span class="sidebar-text">O'Level Students</span></span>
                                    <i class="fas fa-chevron-right w-3 h-3 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                                </summary>
                                <ul class="pl-4 pt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.students.olevel.create') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.students.olevel.create') ? 'active bg-gray-600' : '' }}" title="Add New Student">
                                            <i class="fas fa-plus-circle mr-2"></i><span class="sidebar-text">Add New Student</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.students.olevel.index') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.students.olevel.index') ? 'active bg-gray-600' : '' }}" title="View O'Level Students">
                                            <i class="fas fa-list-ul mr-2"></i><span class="sidebar-text">View O'Level Students</span>
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                        <li>
                            <details class="group" {{ request()->routeIs('admin.students.alevel.*') ? 'open' : '' }}>
                                <summary class="sidebar-link text-sm py-1 cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.students.alevel.*') ? 'active bg-gray-700' : '' }}" title="A'Level Students">
                                    <span><i class="fas fa-graduation-cap mr-2"></i><span class="sidebar-text">A'Level Students</span></span>
                                    <i class="fas fa-chevron-right w-3 h-3 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                                </summary>
                                <ul class="pl-4 pt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.students.alevel.create') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.students.alevel.create') ? 'active bg-gray-600' : '' }}" title="Add New Student">
                                            <i class="fas fa-plus-circle mr-2"></i><span class="sidebar-text">Add New Student</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.students.alevel.index') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.students.alevel.index') ? 'active bg-gray-600' : '' }}" title="View A'Level Students">
                                            <i class="fas fa-list-ul mr-2"></i><span class="sidebar-text">View A'Level Students</span>
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                    </ul>
                </details>
                <details class="group" {{ request()->routeIs('admin.staff.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.staff.*') ? 'active' : '' }}" title="Staff">
                        <span class="flex items-center">
                            <i class="fas fa-chalkboard-teacher mr-3 w-5 h-5"></i>
                            <span class="sidebar-text">Staff</span>
                        </span>
                        <i class="fas fa-chevron-right w-4 h-4 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                    </summary>
                    <ul class="pl-4 pt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.staff.create') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.staff.create') ? 'active bg-gray-700' : '' }}" title="Add New Staff">
                                <i class="fas fa-user-plus mr-2"></i><span class="sidebar-text">Add New Staff</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.staff.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.staff.index') && !request()->routeIs('admin.staff.create') ? 'active bg-gray-700' : '' }}" title="View Staff">
                                <i class="fas fa-list-ul mr-2"></i><span class="sidebar-text">View Staff</span>
                            </a>
                        </li>
                    </ul>
                </details>

                <!-- Academics Section -->
                <details class="group" {{ request()->routeIs('admin.academics.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.academics.*') ? 'active' : '' }}" title="Academics">
                        <span class="flex items-center">
                            <i class="fas fa-book mr-3 w-5 h-5"></i>
                            <span class="sidebar-text">Academics</span>
                        </span>
                        <i class="fas fa-chevron-right w-4 h-4 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                    </summary>
                    <ul class="pl-4 pt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.academics.dashboard') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.academics.dashboard') ? 'active bg-gray-700' : '' }}" title="Dashboard">
                                <i class="fas fa-chart-line mr-2"></i><span class="sidebar-text">Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <details class="group" {{ request()->routeIs('admin.academics.olevel.*') ? 'open' : '' }}>
                                <summary class="sidebar-link text-sm py-1 cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.academics.olevel.*') ? 'active bg-gray-700' : '' }}" title="O'Level">
                                    <span><i class="fas fa-layer-group mr-2"></i><span class="sidebar-text">O'Level</span></span>
                                    <i class="fas fa-chevron-right w-3 h-3 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                                </summary>
                                <ul class="pl-4 pt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.academics.olevel.subjects') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.olevel.subjects') ? 'active bg-gray-600' : '' }}" title="Subjects">
                                            <i class="fas fa-book-open mr-2"></i><span class="sidebar-text">Subjects</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.academics.olevel.marks') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.olevel.marks') ? 'active bg-gray-600' : '' }}" title="Marks Entry">
                                            <i class="fas fa-pen-alt mr-2"></i><span class="sidebar-text">Marks Entry</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.academics.olevel.performance') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.olevel.performance') ? 'active bg-gray-600' : '' }}" title="Performance">
                                            <i class="fas fa-chart-bar mr-2"></i><span class="sidebar-text">Performance</span>
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                        <li>
                            <details class="group" {{ request()->routeIs('admin.academics.alevel.*') ? 'open' : '' }}>
                                <summary class="sidebar-link text-sm py-1 cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.academics.alevel.*') ? 'active bg-gray-700' : '' }}" title="A'Level">
                                    <span><i class="fas fa-graduation-cap mr-2"></i><span class="sidebar-text">A'Level</span></span>
                                    <i class="fas fa-chevron-right w-3 h-3 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                                </summary>
                                <ul class="pl-4 pt-1 space-y-1">
                                    <li>
                                        <a href="{{ route('admin.academics.alevel.subjects') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.alevel.subjects') ? 'active bg-gray-600' : '' }}" title="Subjects">
                                            <i class="fas fa-book-open mr-2"></i><span class="sidebar-text">Subjects</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.academics.alevel.marks') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.alevel.marks') ? 'active bg-gray-600' : '' }}" title="Marks Entry">
                                            <i class="fas fa-pen-alt mr-2"></i><span class="sidebar-text">Marks Entry</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('admin.academics.alevel.performance') }}" class="sidebar-link text-xs py-1 {{ request()->routeIs('admin.academics.alevel.performance') ? 'active bg-gray-600' : '' }}" title="Performance">
                                            <i class="fas fa-chart-bar mr-2"></i><span class="sidebar-text">Performance</span>
                                        </a>
                                    </li>
                                </ul>
                            </details>
                        </li>
                        <li>
                            <a href="{{ route('admin.academics.teachers') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.academics.teachers') ? 'active bg-gray-700' : '' }}" title="Teacher Assignments">
                                <i class="fas fa-chalkboard-teacher mr-2"></i><span class="sidebar-text">Teacher Assignments</span>
                            </a>
                        </li>
                    </ul>
                </details>

                <!-- Discipline Records Section -->
                <details class="group" {{ request()->routeIs('admin.discipline.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.discipline.*') ? 'active' : '' }}" title="Discipline Records">
                        <span class="flex items-center">
                            <i class="fas fa-scroll mr-3 w-5 h-5"></i>
                            <span class="sidebar-text">Discipline Records</span>
                        </span>
                        <i class="fas fa-chevron-right w-4 h-4 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                    </summary>
                    <ul class="pl-4 pt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.discipline.records.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.discipline.records.index') ? 'active bg-gray-700' : '' }}" title="All Records">
                                <i class="fas fa-list-ul mr-2"></i><span class="sidebar-text">All Records</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.discipline.records.create') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.discipline.records.create') ? 'active bg-gray-700' : '' }}" title="Add Record">
                                <i class="fas fa-plus-circle mr-2"></i><span class="sidebar-text">Add Record</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.discipline.tracks.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.discipline.tracks.index') ? 'active bg-gray-700' : '' }}" title="Discipline Tracks">
                                <i class="fas fa-tasks mr-2"></i><span class="sidebar-text">Discipline Tracks</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.counselling.tracks.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.counselling.tracks.index') ? 'active bg-gray-700' : '' }}" title="Counselling Tracks">
                                <i class="fas fa-comments mr-2"></i><span class="sidebar-text">Counselling Tracks</span>
                            </a>
                        </li>
                    </ul>
                </details>

                <!-- Reports Section -->
                <details class="group" {{ request()->routeIs('admin.reports.*') ? 'open' : '' }}>
                    <summary class="sidebar-link cursor-pointer flex justify-between items-center {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" title="Reports">
                        <span class="flex items-center">
                            <i class="fas fa-chart-bar mr-3 w-5 h-5"></i>
                            <span class="sidebar-text">Reports</span>
                        </span>
                        <i class="fas fa-chevron-right w-4 h-4 transform transition-transform duration-200 group-open:rotate-90 sidebar-text"></i>
                    </summary>
                    <ul class="pl-4 pt-1 space-y-1">
                        <li>
                            <a href="{{ route('admin.reports.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.reports.index') ? 'active bg-gray-700' : '' }}" title="Reports Dashboard">
                                <i class="fas fa-tachometer-alt mr-2"></i><span class="sidebar-text">Reports Dashboard</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.student-distribution') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.reports.student-distribution') ? 'active bg-gray-700' : '' }}" title="Student Distribution">
                                <i class="fas fa-users mr-2"></i><span class="sidebar-text">Student Distribution</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.demographics') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.reports.demographics') ? 'active bg-gray-700' : '' }}" title="Demographics">
                                <i class="fas fa-globe-africa mr-2"></i><span class="sidebar-text">Demographics</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.reports.staff') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.reports.staff') ? 'active bg-gray-700' : '' }}" title="Staff Reports">
                                <i class="fas fa-user-tie mr-2"></i><span class="sidebar-text">Staff Reports</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('admin.alumni.index') }}" class="sidebar-link text-sm py-1 {{ request()->routeIs('admin.alumni.*') ? 'active bg-gray-700' : '' }}" title="Alumni Management">
                                <i class="fas fa-user-graduate mr-2"></i><span class="sidebar-text">Alumni Management</span>
                            </a>
                        </li>

                    </ul>
                </details>
                

                @if(auth()->check() && (auth()->user()->role === 'super_admin' || auth()->user()->role === 'admin'))
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" title="Settings">
                    <i class="fas fa-cogs mr-3 w-5 h-5"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
                @endif
                
                <a href="#" class="sidebar-link mt-auto" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" title="Logout">
                    <i class="fas fa-sign-out-alt mr-3 w-5 h-5"></i>
                    <span class="sidebar-text">Logout</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </nav>
            
            <!-- Profile Section -->
            @if(auth()->check())
            <div class="profile-section mt-auto pt-4 px-4 border-t border-gray-700">
                <div class="flex items-center p-3 rounded-lg hover:bg-gray-700 transition-all duration-300 cursor-pointer group" onclick="document.location.href='{{ route('profile') }}'">
                    <div class="flex-shrink-0">
                        <img src="{{ auth()->user()->avatar ?? asset('assets/default-avatar.png') }}" 
                             alt="{{ auth()->user()->name }}" 
                             class="h-12 w-12 rounded-full object-cover border-2 border-gray-600 group-hover:border-blue-500 transition-colors">
                    </div>
                    <div class="profile-info ml-3 flex-1">
                        <p class="text-sm font-semibold text-gray-100 group-hover:text-blue-400 transition-colors">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-400 group-hover:text-blue-300 transition-colors">{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</p>
                    </div>
                    <button type="button" 
                            class="p-1 text-gray-400 hover:text-blue-400 hover:bg-gray-700 rounded transition-all duration-300" 
                            title="Profile Settings"
                            onclick="event.stopPropagation(); document.location.href='{{ route('profile') }}'">
                        <i class="fas fa-cog w-4 h-4"></i>
                    </button>
                </div>
            </div>
            @endif
        </aside>

        <!-- Main content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Navbar -->
            <header class="bg-white dark:bg-gray-800 shadow-md p-4 admin-header">
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <h1 class="text-xl font-semibold text-gray-700 dark:text-gray-200 admin-header-title">@yield('header', 'Dashboard')</h1>
                    
                    <!-- Global Search Bar -->
                    <div class="w-full md:w-1/3 relative">
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                                <i class="fas fa-search h-5 w-5 text-gray-800 dark:text-gray-300 group-hover:scale-110 transition-transform"></i>
                            </div>
                            <form action="{{ route('search.submit') }}" method="POST" class="flex search-form">
                                @csrf
                                <input type="text" name="query" id="global-search"
                                    class="w-full px-4 py-3 pl-10 pr-4 rounded-lg border border-gray-300
                                    shadow-sm transition-all duration-300
                                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                                    hover:border-indigo-400 hover:shadow-md
                                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                                    dark:hover:border-indigo-500 dark:hover:shadow-indigo-700/30
                                    bg-white dark:bg-gray-700"
                                    placeholder="Search students, staff, users..."
                                    value="{{ old('query', request('query')) }}"
                                    autocomplete="off">
                                <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                                    Search
                                </button>
                            </form>
                            <div id="search-suggestions" class="absolute z-50 w-full mt-1 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 hidden"></div>
                        </div>
                    </div>
                    
                    <div class="flex items-center">
                        <span class="text-gray-800 dark:text-gray-300 mr-4 admin-header-welcome">Welcome, Admin!</span> {{-- Replace with Auth::user()->name if auth is set up --}}
                        <div id="theme-selector" class="flex items-center space-x-2">
                            <span class="text-sm theme-selector-heading">Theme:</span>
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="theme" value="green" class="form-radio h-4 w-4 text-green-600 focus:ring-green-500">
                                <span class="text-sm theme-label-text">Green</span>
                            </label>
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="theme" value="cream" class="form-radio h-4 w-4 text-yellow-600 focus:ring-yellow-500">
                                <span class="text-sm theme-label-text">Cream</span>
                            </label>
                            <label class="flex items-center space-x-1 cursor-pointer">
                                <input type="radio" name="theme" value="brown" class="form-radio h-4 w-4 text-yellow-700 focus:ring-yellow-600">
                                <span class="text-sm theme-label-text">Brown</span>
                            </label>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200 dark:bg-gray-700 p-6 admin-content-area-bg">
                @yield('content')
                
                <!-- Footer -->
                <footer class="mt-8 pt-4 border-t border-gray-300 dark:border-gray-600">
                    <div class="text-center text-sm text-gray-800 dark:text-gray-400">
                        <div class="mb-2">
                            <a href="/legal/about" class="hover:text-blue-600 dark:hover:text-blue-400 mx-2">About Us</a>
                            <a href="/legal/privacy-policy" class="hover:text-blue-600 dark:hover:text-blue-400 mx-2">Privacy Policy</a>
                            <a href="/legal/terms-of-service" class="hover:text-blue-600 dark:hover:text-blue-400 mx-2">Terms of Service</a>
                        </div>
                        <p>&copy; {{ date('Y') }} Gombe Secondary School Hub. All rights reserved.</p>
                    </div>
                </footer>
            </main>
        </div>
    </div>
    <script>
        // Sidebar Collapse/Expand Functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('adminSidebar');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const SIDEBAR_STATE_KEY = 'sidebar-collapsed';
            let autoCollapseTimeout;

            // Load saved state
            const isSidebarCollapsed = localStorage.getItem(SIDEBAR_STATE_KEY) === 'true';
            if (isSidebarCollapsed) {
                sidebar.classList.add('collapsed');
                sidebarToggle.innerHTML = '<i class="fas fa-bars"></i>';
            }

            // Toggle button click
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');
                const isNowCollapsed = sidebar.classList.contains('collapsed');
                localStorage.setItem(SIDEBAR_STATE_KEY, isNowCollapsed);
                clearTimeout(autoCollapseTimeout);
            });

            // Auto-collapse on mouse leave (after 5 seconds of inactivity)
            sidebar.addEventListener('mouseleave', function() {
                autoCollapseTimeout = setTimeout(function() {
                    if (!sidebar.classList.contains('collapsed')) {
                        sidebar.classList.add('collapsed');
                        localStorage.setItem(SIDEBAR_STATE_KEY, 'true');
                    }
                }, 5000);
            });

            // Auto-expand on mouse enter
            sidebar.addEventListener('mouseenter', function() {
                clearTimeout(autoCollapseTimeout);
                if (sidebar.classList.contains('collapsed')) {
                    sidebar.classList.remove('collapsed');
                    localStorage.setItem(SIDEBAR_STATE_KEY, 'false');
                }
            });

            // Prevent auto-collapse when interacting with sidebar
            sidebar.addEventListener('click', function(e) {
                clearTimeout(autoCollapseTimeout);
                if (!sidebar.classList.contains('collapsed')) {
                    autoCollapseTimeout = setTimeout(function() {
                        sidebar.classList.add('collapsed');
                        localStorage.setItem(SIDEBAR_STATE_KEY, 'true');
                    }, 5000);
                }
            });
        });
    </script>

    <script>
        const THEMES = ['green', 'cream', 'brown'];
        const themeRadios = document.querySelectorAll('#theme-selector input[name="theme"]');
        const htmlElement = document.documentElement;

        function applyThemePreference(theme) {
            // Remove all theme classes from html element
            THEMES.forEach(t => htmlElement.classList.remove(`theme-${t}`));

            if (theme && THEMES.includes(theme)) {
                htmlElement.classList.add(`theme-${theme}`);
                localStorage.setItem('color-theme', theme);

                // Check the corresponding radio button
                const radioToCheck = document.querySelector(`#theme-selector input[name="theme"][value="${theme}"]`);
                if (radioToCheck) {
                    radioToCheck.checked = true;
                }
            } else {
                 // Fallback if theme is invalid or not in THEMES
                htmlElement.classList.add('theme-green'); // Default to green
                localStorage.setItem('color-theme', 'green');
                const greenRadio = document.querySelector(`#theme-selector input[name="theme"][value="green"]`);
                if (greenRadio) greenRadio.checked = true;
            }
        }

        // Set initial theme on page load
        let savedTheme = localStorage.getItem('color-theme');
        if (savedTheme && THEMES.includes(savedTheme)) {
            applyThemePreference(savedTheme);
        } else {
            // Default to green theme
            applyThemePreference('green');
        }

        // Event listener for radio buttons
        
        // Global Search Functionality
        const globalSearch = document.getElementById('global-search');
        const searchSuggestions = document.getElementById('search-suggestions');
        
        if (globalSearch && searchSuggestions) {
            let searchTimeout;
            
            globalSearch.addEventListener('focus', function() {
                if (searchSuggestions.children.length > 0) {
                    searchSuggestions.classList.remove('hidden');
                }
            });
            
            globalSearch.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                const query = this.value.trim();
                
                if (query.length < 2) {
                    searchSuggestions.innerHTML = '';
                    searchSuggestions.classList.add('hidden');
                    return;
                }
                
                searchTimeout = setTimeout(() => {
                    fetch(`{{ route('search.suggestions') }}?query=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            searchSuggestions.innerHTML = '';
                            
                            if (data.length === 0) {
                                searchSuggestions.classList.add('hidden');
                                return;
                            }
                            
                            data.forEach(item => {
                                const div = document.createElement('div');
                                div.className = 'p-3 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer border-b border-gray-200 dark:border-gray-700 last:border-b-0';
                                
                                let icon = '';
                                switch(item.type) {
                                    case 'student': icon = '<i class="fas fa-user-graduate text-blue-500 mr-2"></i>'; break;
                                    case 'staff': icon = '<i class="fas fa-chalkboard-teacher text-green-500 mr-2"></i>'; break;
                                    case 'user': icon = '<i class="fas fa-user text-purple-500 mr-2"></i>'; break;
                                }
                                
                                div.innerHTML = `
                                    <a href="${item.url}" class="flex items-center">
                                        ${icon}
                                        <div>
                                            <div class="font-medium text-gray-800 dark:text-gray-200">${item.name}</div>
                                            <div class="text-xs text-gray-800 dark:text-gray-400">${item.subtitle}</div>
                                        </div>
                                    </a>
                                `;
                                
                                searchSuggestions.appendChild(div);
                            });
                            
                            searchSuggestions.classList.remove('hidden');
                        })
                        .catch(error => {
                            console.log('Could not fetch search suggestions:', error);
                        });
                }, 300);
            });
            
            // Close suggestions when clicking outside
            document.addEventListener('click', function(e) {
                if (!globalSearch.contains(e.target) && !searchSuggestions.contains(e.target)) {
                    searchSuggestions.classList.add('hidden');
                }
            });
        }
        themeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.checked) {
                    applyThemePreference(this.value);
                }
            });
        });
    </script>
    
    <!-- Emergency Contact Floating Button -->
    <div id="emergencyContact" class="fixed bottom-4 right-4 z-50">
        <div class="relative">
            <button id="emergencyBtn" class="bg-red-600 hover:bg-red-700 text-white rounded-full p-3 shadow-lg transition-all duration-300 animate-pulse">
                <i class="fas fa-exclamation-triangle text-xl"></i>
            </button>
            
            <!-- Emergency Options Menu -->
            <div id="emergencyMenu" class="absolute bottom-16 right-0 bg-white dark:bg-gray-800 rounded-lg shadow-xl p-4 min-w-64 hidden">
                <h6 class="font-semibold text-gray-800 dark:text-gray-200 mb-3">Emergency Contact</h6>
                <div class="space-y-2">
                    <button onclick="emergencyCall()" class="w-full flex items-center p-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-phone text-green-600 mr-3"></i>
                        <span class="text-gray-700 dark:text-gray-300">Call Administration</span>
                    </button>
                    <button onclick="emergencyWhatsApp()" class="w-full flex items-center p-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fab fa-whatsapp text-green-600 mr-3"></i>
                        <span class="text-gray-700 dark:text-gray-300">WhatsApp Support</span>
                    </button>
                    <button onclick="emergencyEmail()" class="w-full flex items-center p-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                        <i class="fas fa-envelope text-blue-600 mr-3"></i>
                        <span class="text-gray-700 dark:text-gray-300">Send Email</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Emergency Contact Functionality
        document.getElementById('emergencyBtn').addEventListener('click', function() {
            const menu = document.getElementById('emergencyMenu');
            menu.classList.toggle('hidden');
        });

        // Close menu when clicking outside
        document.addEventListener('click', function(event) {
            const emergencyContact = document.getElementById('emergencyContact');
            if (!emergencyContact.contains(event.target)) {
                document.getElementById('emergencyMenu').classList.add('hidden');
            }
        });

        function emergencyCall() {
            // Log the emergency contact attempt
            fetch('/api/emergency-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    contact_type: 'call',
                    contact_value: '+234XXXXXXXXX'
                })
            });
            
            // Initiate call
            window.location.href = 'tel:+234XXXXXXXXX';
            document.getElementById('emergencyMenu').classList.add('hidden');
        }

        function emergencyWhatsApp() {
            // Log the emergency contact attempt
            fetch('/api/emergency-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    contact_type: 'whatsapp',
                    contact_value: '+234XXXXXXXXX'
                })
            });
            
            // Open WhatsApp
            window.open('https://wa.me/234XXXXXXXXX?text=Emergency%20assistance%20needed%20from%20Gombe%20School%20Hub', '_blank');
            document.getElementById('emergencyMenu').classList.add('hidden');
        }

        function emergencyEmail() {
            // Log the emergency contact attempt
            fetch('/api/emergency-contact', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    contact_type: 'email',
                    contact_value: 'emergency@gombeschoolhub.edu.ng'
                })
            });
            
            // Open email client
            window.location.href = 'mailto:emergency@gombeschoolhub.edu.ng?subject=Emergency%20Assistance%20Needed&body=Please%20describe%20your%20emergency%20situation...';
            document.getElementById('emergencyMenu').classList.add('hidden');
        }
    </script>
    
    <!-- Global Font Application Script -->
    <script>
        // Load and apply saved font preference
        document.addEventListener('DOMContentLoaded', function() {
            const savedFont = localStorage.getItem('app_font_family') || 'Ubuntu';
            applyFontGlobally(savedFont);
        });
        
        function applyFontGlobally(fontFamily) {
            document.documentElement.style.setProperty('--font-family', fontFamily);
            document.body.style.fontFamily = fontFamily;
            
            // Apply to all elements
            const allElements = document.querySelectorAll('*');
            allElements.forEach(element => {
                element.style.fontFamily = fontFamily;
            });
        }
    </script>
    
    <!-- Initialize Flatpickr Date Pickers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all date inputs with Flatpickr
            const dateInputs = document.querySelectorAll('input[type="date"]');
            
            if (dateInputs.length > 0) {
                dateInputs.forEach(function(input) {
                    flatpickr(input, {
                        dateFormat: "Y-m-d",
                        allowInput: true,
                        altInput: true,
                        altFormat: "F j, Y",
                        monthSelectorType: "static",
                        showMonths: 1,
                        animate: true,
                        position: "auto",
                        disableMobile: false,
                        locale: {
                            firstDayOfWeek: 1 // Monday as first day
                        }
                    });
                });
            }
            
            // Special handling for government staff form with DD/MM/YYYY format
            const govtDateInputs = document.querySelectorAll('input[placeholder="DD/MM/YYYY"]');
            
            if (govtDateInputs.length > 0) {
                govtDateInputs.forEach(function(input) {
                    flatpickr(input, {
                        dateFormat: "d/m/Y",
                        allowInput: true,
                        altInput: true,
                        altFormat: "j F, Y",
                        monthSelectorType: "static",
                        showMonths: 1,
                        animate: true,
                        position: "auto",
                        disableMobile: false,
                        locale: {
                            firstDayOfWeek: 1 // Monday as first day
                        }
                    });
                });
            }
        });
    </script>

    <!-- Page-specific scripts -->
    @stack('scripts')
    
    <!-- PWA Installer -->
    <script src="{{ asset('js/pwa-installer.js') }}"></script>
    
    <!-- PWA Service Worker Registration -->
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/js/service-worker.js')
                    .then((registration) => {
                        console.log('Service Worker registered successfully:', registration.scope);
                        
                        // Check for updates periodically
                        setInterval(() => {
                            registration.update();
                        }, 60000); // Check every minute
                    })
                    .catch((error) => {
                        console.log('Service Worker registration failed:', error);
                    });
                
                // Handle service worker updates
                navigator.serviceWorker.addEventListener('controllerchange', () => {
                    console.log('Service Worker updated');
                    // You can reload the page or show a notification here
                });
            });
            
            // Listen for install prompt
            let deferredPrompt;
            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                
                // Show install button (you can customize this)
                const installButton = document.getElementById('install-app-btn');
                if (installButton) {
                    installButton.style.display = 'block';
                    installButton.addEventListener('click', async () => {
                        if (deferredPrompt) {
                            deferredPrompt.prompt();
                            const { outcome } = await deferredPrompt.userChoice;
                            console.log(`User response to the install prompt: ${outcome}`);
                            deferredPrompt = null;
                        }
                    });
                }
            });
            
            // Handle app installation
            window.addEventListener('appinstalled', () => {
                console.log('App was installed');
                // You can track this event for analytics
                if (window.gtag) {
                    gtag('event', 'app_installed');
                }
            });
        }
    </script>
</body>
</html>