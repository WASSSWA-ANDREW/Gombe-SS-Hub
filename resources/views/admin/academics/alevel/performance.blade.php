@extends('layouts.admin')

@section('title', 'A\'Level Performance')

@section('header', 'A\'Level Student Performance')

@section('content')
<div class="space-y-6">
    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Filter Performance Data</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Class</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- All Classes --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Term</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Term --</option>
                    <option value="term1">Term 1</option>
                    <option value="term2">Term 2</option>
                    <option value="term3">Term 3</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    <i class="fas fa-search"></i> Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Performance Summary -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">Total Students</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $students->count() }}</p>
                </div>
                <div class="bg-blue-100 dark:bg-blue-900 rounded-full p-3">
                    <i class="fas fa-users text-blue-600 dark:text-blue-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">Arts Students</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $students->where('stream', 'Arts')->count() }}</p>
                </div>
                <div class="bg-indigo-100 dark:bg-indigo-900 rounded-full p-3">
                    <i class="fas fa-book text-indigo-600 dark:text-indigo-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">Science Students</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $students->where('stream', 'Science')->count() }}</p>
                </div>
                <div class="bg-red-100 dark:bg-red-900 rounded-full p-3">
                    <i class="fas fa-flask text-red-600 dark:text-red-300 text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-800 dark:text-gray-400 text-sm font-medium">Avg. Score</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white mt-2">82%</p>
                </div>
                <div class="bg-orange-100 dark:bg-orange-900 rounded-full p-3">
                    <i class="fas fa-chart-line text-orange-600 dark:text-orange-300 text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Performance Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Student Performance Details</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Student Name</th>
                        <th class="px-4 py-2 text-left">Class</th>
                        <th class="px-4 py-2 text-left">Combination</th>
                        <th class="px-4 py-2 text-center">Average Score</th>
                        <th class="px-4 py-2 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @if($students->count() > 0)
                        @foreach($students->take(10) as $student)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $student->getDisplayName() }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $student->class }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $student->combination ?? '-' }}</td>
                                <td class="px-4 py-3 text-center text-gray-900 dark:text-white">82%</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs">A</span>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-800 dark:text-gray-400">
                                No performance data available
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
