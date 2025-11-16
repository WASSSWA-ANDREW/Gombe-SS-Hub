@extends('layouts.admin')

@section('title', 'Attendance Reports')

@section('content')
<div class="container px-4 sm:px-6 mx-auto grid">
    <h2 class="my-4 sm:my-6 text-xl sm:text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Attendance Reports
    </h2>
    
    <!-- Description -->
    <div class="px-3 sm:px-4 py-3 mb-6 sm:mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            View attendance records and trends for students and staff. Track attendance patterns and identify areas for improvement.
        </p>
    </div>

    <!-- Coming Soon Message -->
    <div class="flex p-4 mb-6 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
        <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <span class="font-medium">Coming Soon!</span> Attendance reports are currently under development. Check back soon for detailed attendance tracking, trends analysis, and more.
        </div>
    </div>

    <!-- Placeholder Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <!-- Student Attendance Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Student Attendance</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-400">Student attendance data will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Staff Attendance Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Staff Attendance</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-400">Staff attendance data will be displayed here</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Attendance Trends Section -->
    <h3 class="my-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
        Attendance Trends
    </h3>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-4">
            <div class="flex items-center justify-center h-64 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-800 dark:text-gray-400">Attendance trend charts will be displayed here</p>
            </div>
        </div>
    </div>

    <!-- Filter Options -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Filter Options</h3>
        </div>
        <div class="p-4">
            <form class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="date-range" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Date Range</label>
                    <select id="date-range" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>Choose a date range</option>
                        <option value="today">Today</option>
                        <option value="this-week">This Week</option>
                        <option value="this-month">This Month</option>
                        <option value="this-term">This Term</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>
                <div>
                    <label for="class" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class</label>
                    <select id="class" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                        <option selected>All Classes</option>
                        <option value="s1">S1</option>
                        <option value="s2">S2</option>
                        <option value="s3">S3</option>
                        <option value="s4">S4</option>
                        <option value="s5">S5</option>
                        <option value="s6">S6</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Export Options -->
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
        <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
            Export to PDF
        </button>
        <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
            Export to Excel
        </button>
    </div>
</div>
@endsection