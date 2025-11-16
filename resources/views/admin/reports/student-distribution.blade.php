@extends('layouts.admin')

@section('title', 'Student Distribution Reports')

@section('content')
<div class="container px-6 mx-auto grid">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Student Distribution Reports
    </h2>
    
    <!-- Description -->
    <div class="px-4 py-3 mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            View detailed reports about student distribution by class, stream, and level.
        </p>
    </div>

    <!-- Back to Reports Dashboard -->
    <div class="mb-6">
        <a href="{{ route('admin.reports.index') }}" class="flex items-center text-sm font-medium text-blue-600 dark:text-blue-500 hover:underline">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Back to Reports Dashboard
        </a>
    </div>

    <!-- Students per Class -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Students per Class</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Class</th>
                        <th scope="col" class="px-6 py-3">Number of Students</th>
                        <th scope="col" class="px-6 py-3">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalStudents = $data['studentsPerClass']->sum('total');
                    @endphp
                    
                    @foreach($data['studentsPerClass'] as $classData)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $classData->class }}</td>
                        <td class="px-6 py-4">{{ $classData->total }}</td>
                        <td class="px-6 py-4">
                            {{ number_format(($classData->total / $totalStudents) * 100, 1) }}%
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ ($classData->total / $totalStudents) * 100 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold text-gray-900 dark:text-white">
                        <th scope="row" class="px-6 py-3 text-base">Total</th>
                        <td class="px-6 py-3">{{ $totalStudents }}</td>
                        <td class="px-6 py-3">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Students per Stream -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Students per Stream</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Stream</th>
                        <th scope="col" class="px-6 py-3">Number of Students</th>
                        <th scope="col" class="px-6 py-3">Percentage</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalStudents = $data['studentsPerStream']->sum('total');
                    @endphp
                    
                    @foreach($data['studentsPerStream'] as $streamData)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $streamData->stream }}</td>
                        <td class="px-6 py-4">{{ $streamData->total }}</td>
                        <td class="px-6 py-4">
                            {{ number_format(($streamData->total / $totalStudents) * 100, 1) }}%
                            <div class="w-full bg-gray-200 rounded-full h-2.5 dark:bg-gray-700">
                                <div class="bg-green-600 h-2.5 rounded-full" style="width: {{ ($streamData->total / $totalStudents) * 100 }}%"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="font-semibold text-gray-900 dark:text-white">
                        <th scope="row" class="px-6 py-3 text-base">Total</th>
                        <td class="px-6 py-3">{{ $totalStudents }}</td>
                        <td class="px-6 py-3">100%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Students per Class and Stream -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Students per Class and Stream</h3>
        
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-800 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" class="px-6 py-3">Class</th>
                        <th scope="col" class="px-6 py-3">Stream</th>
                        <th scope="col" class="px-6 py-3">Number of Students</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data['studentsPerClassAndStream'] as $classStreamData)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white">{{ $classStreamData->class }}</td>
                        <td class="px-6 py-4">{{ $classStreamData->stream }}</td>
                        <td class="px-6 py-4">{{ $classStreamData->total }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-4">Export Options</h3>
        
        <div class="flex flex-col md:flex-row gap-4">
            <a href="{{ route('admin.reports.generate-pdf', ['report_type' => 'student_distribution']) }}" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                Export to PDF
            </a>
            <a href="{{ route('admin.reports.export-excel', ['report_type' => 'student_distribution']) }}" class="text-white bg-green-700 hover:bg-green-800 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 dark:bg-green-600 dark:hover:bg-green-700 focus:outline-none dark:focus:ring-green-800">
                Export to Excel
            </a>
        </div>
    </div>
</div>
@endsection