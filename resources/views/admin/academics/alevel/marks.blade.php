@extends('layouts.admin')

@section('title', 'A\'Level Marks Entry')

@section('header', 'A\'Level Marks Entry')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Enter Marks for A'Level Students</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Class</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Class --</option>
                    @foreach($classes as $class)
                        <option value="{{ $class }}">{{ $class }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Select Subject</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Subject --</option>
                    @foreach($subjects as $subject)
                        <option value="{{ $subject->id }}">{{ $subject->subject_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Entry Type</label>
                <select class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-700 dark:text-white">
                    <option value="">-- Select Entry Type --</option>
                    <option value="beginning_of_term">Beginning of Term</option>
                    <option value="activities_of_integration">Activities of Integration</option>
                    <option value="test">Test Marks</option>
                    <option value="end_of_term">End of Term</option>
                </select>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Student Name</th>
                        <th class="px-4 py-2 text-left">Combination</th>
                        <th class="px-4 py-2 text-center">Theory Marks</th>
                        <th class="px-4 py-2 text-center">Practical Marks</th>
                        <th class="px-4 py-2 text-center">Total</th>
                        <th class="px-4 py-2 text-center">Grade</th>
                    </tr>
                </thead>
                <tbody>
                    @if($alevelStudents->count() > 0)
                        @foreach($alevelStudents->take(10) as $student)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $student->getDisplayName() }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $student->combination ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <input type="number" class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white" min="0" max="100" placeholder="0">
                                </td>
                                <td class="px-4 py-3">
                                    <input type="number" class="w-full px-2 py-1 border border-gray-300 dark:border-gray-600 rounded dark:bg-gray-700 dark:text-white" min="0" max="100" placeholder="0">
                                </td>
                                <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-400">-</td>
                                <td class="px-4 py-3 text-center text-gray-800 dark:text-gray-400">-</td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-800 dark:text-gray-400">
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
