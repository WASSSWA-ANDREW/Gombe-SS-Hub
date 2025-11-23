@extends('layouts.admin')

@section('title', 'O\'Level Marks Entry')

@section('header', 'O\'Level Marks Entry')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Enter Marks for O'Level Students</h3>
        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">Ensure all general and optional subjects have marks entry for S3 and S4 students</p>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Class</label>
                <select id="classSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Subject</label>
                <select id="subjectSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Subject --</option>
                    <optgroup label="General Subjects">
                        @foreach($subjects->where('category', 'general') as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="Optional Subjects">
                        @foreach($subjects->where('category', 'optional') as $subject)
                            <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                        @endforeach
                    </optgroup>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Entry Type</label>
                <select id="entryTypeSelect" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Entry Type --</option>
                    <option value="beginning_of_term">Beginning of Term</option>
                    <option value="activities_of_integration">Activities of Integration</option>
                    <option value="test">Test Marks</option>
                    <option value="end_of_term">End of Term</option>
                </select>
            </div>
        </div>

        <div class="bg-blue-50 dark:bg-blue-900 border border-blue-200 dark:border-blue-700 rounded-lg p-4 mb-6">
            <h4 class="font-semibold text-blue-900 dark:text-blue-100 mb-3">Required Subjects for S3/S4</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-2">General Subjects (All Students)</h5>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        @foreach($subjects->where('category', 'general') as $subject)
                            <li class="flex items-center">
                                <i class="fas fa-book mr-2"></i>{{ $subject->subject_name }}
                                @if($subject->requires_practical)
                                    <span class="ml-2 text-xs bg-blue-200 dark:bg-blue-800 px-2 py-1 rounded">Practical</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h5 class="font-medium text-blue-800 dark:text-blue-200 mb-2">Optional Subjects (As Selected)</h5>
                    <ul class="text-sm text-blue-700 dark:text-blue-300 space-y-1">
                        @foreach($subjects->where('category', 'optional') as $subject)
                            <li class="flex items-center">
                                <i class="fas fa-checkbox mr-2"></i>{{ $subject->subject_name }}
                                @if($subject->requires_practical)
                                    <span class="ml-2 text-xs bg-blue-200 dark:bg-blue-800 px-2 py-1 rounded">Practical</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Student Name</th>
                        <th class="px-4 py-2 text-left">Admission Number</th>
                        <th class="px-4 py-2 text-center">Class</th>
                        <th class="px-4 py-2 text-center">Theory Marks</th>
                        <th class="px-4 py-2 text-center">Practical Marks</th>
                        <th class="px-4 py-2 text-center">Total</th>
                        <th class="px-4 py-2 text-center">Grade</th>
                        <th class="px-4 py-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($olevelStudents->count() > 0)
                        @foreach($olevelStudents as $student)
                            @if(in_array($student->class, ['S3', 'S4']))
                                <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $student->getDisplayName() }}</td>
                                    <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $student->admission_number ?? '-' }}</td>
                                    <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-400">{{ $student->class }}</td>
                                    <td class="px-4 py-3">
                                        <input type="number" class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white" min="0" max="100" placeholder="0">
                                    </td>
                                    <td class="px-4 py-3">
                                        <input type="number" class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white" min="0" max="100" placeholder="0">
                                    </td>
                                    <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-400">-</td>
                                    <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-400">-</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Pending
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                        @if($olevelStudents->where('class', 'S3')->count() === 0 && $olevelStudents->where('class', 'S4')->count() === 0)
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-center text-gray-800 dark:text-gray-400">
                                    No S3/S4 students found
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td colspan="8" class="px-4 py-8 text-center text-gray-800 dark:text-gray-400">
                                No students found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end gap-4">
            <button class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                Cancel
            </button>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-save"></i> Save Marks
            </button>
        </div>
    </div>
</div>
@endsection
