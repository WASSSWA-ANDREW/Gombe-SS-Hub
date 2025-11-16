@extends('layouts.admin')

@section('title', 'Academic Performance Reports')

@section('content')
<div class="container px-4 sm:px-6 mx-auto grid">
    <h2 class="my-4 sm:my-6 text-xl sm:text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Academic Performance Reports
    </h2>
    
    <!-- Description -->
    <div class="px-3 sm:px-4 py-3 mb-6 sm:mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            View comprehensive academic performance reports for students. Analyze grades, subject performance, and identify top performers.
        </p>
    </div>

    <!-- Coming Soon Message -->
    <div class="flex p-4 mb-6 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
        <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <span class="font-medium">Coming Soon!</span> Academic performance reports are currently under development. Check back soon for detailed grade analysis, subject performance trends, and more.
        </div>
    </div>

    <!-- Placeholder Content -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-8">
        <!-- Average Grades Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Average Grades</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-400">Grade data will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Subject Performance Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Subject Performance</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-400">Subject performance data will be displayed here</p>
                </div>
            </div>
        </div>

        <!-- Class Performance Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Class Performance</h3>
            </div>
            <div class="p-4">
                <div class="flex items-center justify-center h-48 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <p class="text-gray-800 dark:text-gray-400">Class performance data will be displayed here</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Performers Section -->
    <h3 class="my-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
        Top Performers
    </h3>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-4">
            <div class="flex items-center justify-center h-24 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-800 dark:text-gray-400">Top performing students will be listed here</p>
            </div>
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