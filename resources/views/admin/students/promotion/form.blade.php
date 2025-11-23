@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-6 border border-blue-200">
            <h3 class="text-lg font-semibold text-blue-900 mb-2">Current Academic Year</h3>
            <p class="text-3xl font-bold text-blue-600">{{ now()->year }}</p>
        </div>
        <div class="bg-green-50 rounded-lg p-6 border border-green-200">
            <h3 class="text-lg font-semibold text-green-900 mb-2">Total Students</h3>
            <p class="text-3xl font-bold text-green-600">{{ $students->total() ?? 0 }}</p>
        </div>
        <div class="bg-orange-50 rounded-lg p-6 border border-orange-200">
            <h3 class="text-lg font-semibold text-orange-900 mb-2">Students Ready</h3>
            <p class="text-3xl font-bold text-orange-600">{{ $students->count() ?? 0 }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8 mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Student Promotion</h2>
        
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <h3 class="text-red-900 font-semibold mb-2">Promotion Failed</h3>
                <ul class="text-red-700">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <p class="text-green-700 font-semibold">{{ session('success') }}</p>
            </div>
        @endif

        <form action="{{ route('admin.students.promotion.store') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label for="academic_year" class="block text-gray-700 font-semibold mb-2">
                    Academic Year <span class="text-red-600">*</span>
                </label>
                <input
                    type="number"
                    id="academic_year"
                    name="academic_year"
                    value="{{ old('academic_year', now()->year) }}"
                    min="2020"
                    max="{{ now()->year + 1 }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                <p class="text-gray-600 text-sm mt-2">
                    Enter the academic year for which you want to promote students.
                </p>
            </div>

            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 my-6">
                <h3 class="text-yellow-900 font-semibold mb-2 flex items-center">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Important Notice
                </h3>
                <ul class="text-yellow-800 text-sm space-y-1">
                    <li>• S.4 and S.6 students will be archived as alumni</li>
                    <li>• All other students will be promoted to the next class</li>
                    <li>• Promotion tracking will be updated automatically</li>
                    <li>• This action cannot be undone</li>
                    <li>• A database transaction will ensure data consistency</li>
                </ul>
            </div>

            <button
                type="submit"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg transition duration-200"
                onclick="return confirm('Are you sure you want to promote all eligible students? This action cannot be undone.')"
            >
                <i class="fas fa-arrow-up mr-2"></i>
                Promote All Students
            </button>
        </form>
    </div>

    <div class="bg-white rounded-lg shadow-md p-8">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Students Ready for Promotion</h3>
        
        @if ($students->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 border-b-2 border-gray-300">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Level</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Current Class</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Stream</th>
                            <th class="px-4 py-2 text-left font-semibold text-gray-700">Promotion Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($students as $student)
                            <tr class="border-b border-gray-200 hover:bg-gray-50">
                                <td class="px-4 py-3 text-gray-900">{{ $student->student_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="px-3 py-1 rounded-full text-sm font-semibold {{ $student->level === 'olevel' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                        {{ strtoupper($student->level) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 font-semibold text-gray-900">{{ $student->class }}</td>
                                <td class="px-4 py-3">{{ $student->stream }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 bg-gray-100 rounded text-gray-900">{{ $student->promotion_count ?? 0 }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $students->links('pagination::tailwind') }}
            </div>
        @else
            <div class="text-center py-12 bg-gray-50 rounded-lg">
                <i class="fas fa-info-circle text-gray-400 text-3xl mb-4"></i>
                <p class="text-gray-600">No students available for promotion</p>
            </div>
        @endif
    </div>
</div>
@endsection
