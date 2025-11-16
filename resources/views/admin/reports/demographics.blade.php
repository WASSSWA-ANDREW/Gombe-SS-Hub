@extends('layouts.admin')

@section('title', 'Student Demographics Reports')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Student Demographics Reports
    </h2>
    
    <!-- Description -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            Comprehensive reports about student demographics including district, nationality, tribe, religion, and gender distribution.
        </p>
    </div>

    <!-- Export Options -->
    <div class="flex justify-end mb-6">
        <form action="{{ route('admin.reports.generate-pdf') }}" method="POST" class="mr-2">
            @csrf
            <input type="hidden" name="report_type" value="demographics">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-purple-600 border border-transparent rounded-lg active:bg-purple-600 hover:bg-purple-700 focus:outline-none focus:shadow-outline-purple">
                Export as PDF
            </button>
        </form>
        <form action="{{ route('admin.reports.export-excel') }}" method="POST">
            @csrf
            <input type="hidden" name="report_type" value="demographics">
            <button type="submit" class="px-4 py-2 text-sm font-medium leading-5 text-white transition-colors duration-150 bg-green-600 border border-transparent rounded-lg active:bg-green-600 hover:bg-green-700 focus:outline-none focus:shadow-outline-green">
                Export as Excel
            </button>
        </form>
    </div>

    <!-- Gender Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Gender Distribution
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Card 1: Male Students -->
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
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['genderDistribution']['male'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Male Students</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $totalStudents = $data['genderDistribution']['male'] + $data['genderDistribution']['female'];
                            $malePercentage = $totalStudents > 0 ? round(($data['genderDistribution']['male'] / $totalStudents) * 100) : 0;
                        @endphp
                        <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $malePercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $malePercentage }}% of total students</p>
                </div>
            </div>
        </div>

        <!-- Card 2: Female Students -->
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
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['genderDistribution']['female'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Female Students</p>
                    <div class="w-full bg-gray-200 rounded-full h-2.5 mt-3 dark:bg-gray-700">
                        @php
                            $femalePercentage = $totalStudents > 0 ? round(($data['genderDistribution']['female'] / $totalStudents) * 100) : 0;
                        @endphp
                        <div class="bg-pink-600 h-2.5 rounded-full" style="width: {{ $femalePercentage }}%"></div>
                    </div>
                    <p class="text-xs text-gray-800 dark:text-gray-400 mt-1">{{ $femalePercentage }}% of total students</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Age Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Age Distribution
    </h3>
    
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Under 13 -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-green-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-green-100 dark:bg-green-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 text-xs font-semibold px-2.5 py-1 rounded">UNDER 13</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['ageGroups']['under_13'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Under 13 years</p>
                </div>
            </div>
        </div>

        <!-- 13-15 years -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-blue-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-blue-100 dark:bg-blue-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 text-xs font-semibold px-2.5 py-1 rounded">13-15</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['ageGroups']['13_to_15'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">13-15 years</p>
                </div>
            </div>
        </div>

        <!-- 16-18 years -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-yellow-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-yellow-100 dark:bg-yellow-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-yellow-500 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 text-xs font-semibold px-2.5 py-1 rounded">16-18</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['ageGroups']['16_to_18'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">16-18 years</p>
                </div>
            </div>
        </div>

        <!-- Over 18 -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="h-2 bg-red-500"></div>
            <div class="p-6">
                <div class="flex justify-between items-start">
                    <div class="bg-red-100 dark:bg-red-900 p-3 rounded-full">
                        <svg class="w-8 h-8 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300 text-xs font-semibold px-2.5 py-1 rounded">OVER 18</span>
                </div>
                <div class="mt-4">
                    <p class="text-4xl font-bold text-gray-900 dark:text-white">{{ $data['ageGroups']['over_18'] }}</p>
                    <p class="text-sm text-gray-800 dark:text-gray-400 mt-1">Over 18 years</p>
                </div>
            </div>
        </div>
    </div>

    <!-- District Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        District Distribution
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">District</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['studentsPerDistrict'] as $district)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $district->district ?: 'Not Specified' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $district->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStudents > 0 ? round(($district->total / $totalStudents) * 100) : 0;
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

    <!-- Nationality Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Nationality Distribution
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Nationality</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['studentsPerNationality'] as $nationality)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $nationality->nationality ?: 'Not Specified' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $nationality->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStudents > 0 ? round(($nationality->total / $totalStudents) * 100) : 0;
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

    <!-- Tribe Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Tribe Distribution
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Tribe</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['studentsPerTribe'] as $tribe)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $tribe->tribe ?: 'Not Specified' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $tribe->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStudents > 0 ? round(($tribe->total / $totalStudents) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-yellow-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Religion Distribution -->
    <h3 class="my-6 text-xl font-semibold text-gray-700 dark:text-gray-200">
        Religion Distribution
    </h3>
    
    <div class="w-full mb-8 overflow-hidden rounded-lg shadow-xs">
        <div class="w-full overflow-x-auto">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="text-xs font-semibold tracking-wide text-left text-gray-800 uppercase border-b dark:border-gray-700 bg-gray-50 dark:text-gray-400 dark:bg-gray-800">
                        <th class="px-4 py-3">Religion</th>
                        <th class="px-4 py-3">Count</th>
                        <th class="px-4 py-3">Percentage</th>
                        <th class="px-4 py-3">Distribution</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y dark:divide-gray-700 dark:bg-gray-800">
                    @foreach($data['studentsPerReligion'] as $religion)
                    <tr class="text-gray-700 dark:text-gray-400">
                        <td class="px-4 py-3">
                            <div class="flex items-center text-sm">
                                <div>
                                    <p class="font-semibold">{{ $religion->religion ?: 'Not Specified' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm">
                            {{ $religion->total }}
                        </td>
                        <td class="px-4 py-3 text-sm">
                            @php
                                $percentage = $totalStudents > 0 ? round(($religion->total / $totalStudents) * 100) : 0;
                            @endphp
                            {{ $percentage }}%
                        </td>
                        <td class="px-4 py-3 text-sm">
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-purple-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection