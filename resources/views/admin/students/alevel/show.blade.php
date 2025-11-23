@extends('layouts.admin')

@section('title', 'A\'Level Student Details')
@section('header', 'A\'Level Student Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <!-- Header with Actions -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white">A'LEVEL STUDENT DETAILS</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.students.alevel.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to List
                </a>
                <a href="{{ route('admin.students.alevel.edit', $student->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Edit
                </a>
                <a href="{{ route('admin.students.alevel.view.pdf', $student->id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out" target="_blank">
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Passport Photo Section -->
        @if($student->photo_path)
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-center">
            <div class="text-center">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">PASSPORT PHOTO</label>
                <img src="{{ Storage::url($student->photo_path) }}" alt="Passport Photo" class="h-40 w-32 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600">
            </div>
        </div>
        @endif

        <!-- Student's Details -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">STUDENT'S DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name of Student:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $student->student_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->gender ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's LIN:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->learners_lin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->date_of_birth ? \Carbon\Carbon::parse($student->date_of_birth)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Place of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->place_of_birth ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Home District:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->home_district ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->religion ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous School:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->previous_school ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Year of Admission:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->year_of_admission ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject Combination:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->subject_combination ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- UCE Results -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">UCE RESULTS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                @php
                    $uceResults = is_string($student->uce_results) ? json_decode($student->uce_results, true) : $student->uce_results;
                @endphp
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">English:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['english'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Math:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['math'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physics:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['physics'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chemistry:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['chemistry'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biology:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['biology'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">History:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['history'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Geography:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['geography'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">CRE/IRE:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['cre_ire'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Luganda:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $uceResults['luganda'] ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aggregate:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $student->uce_aggregate ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Parent/Guardian Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PARENT/GUARDIAN INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->father_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Contact:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->father_contact ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->mother_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Contact:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->mother_contact ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guardian's Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->guardian_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guardian's Contact:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->guardian_contact ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Parent Passport Photos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6 mt-6 pt-6 border-t border-gray-300 dark:border-gray-500">
                @if($student->father_passport_photo_path)
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">FATHER'S PASSPORT PHOTO</label>
                    <img src="{{ Storage::url($student->father_passport_photo_path) }}" alt="Father's Passport Photo" class="h-32 w-24 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600 mx-auto">
                </div>
                @endif
                @if($student->mother_passport_photo_path)
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">MOTHER'S PASSPORT PHOTO</label>
                    <img src="{{ Storage::url($student->mother_passport_photo_path) }}" alt="Mother's Passport Photo" class="h-32 w-24 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600 mx-auto">
                </div>
                @endif
                @if($student->guardian_passport_photo_path)
                <div class="text-center">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">GUARDIAN'S PASSPORT PHOTO</label>
                    <img src="{{ Storage::url($student->guardian_passport_photo_path) }}" alt="Guardian's Passport Photo" class="h-32 w-24 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600 mx-auto">
                </div>
                @endif
            </div>
        </fieldset>

        <!-- Special Issues & Official Comment -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">ADDITIONAL INFORMATION</legend>
            <div class="grid grid-cols-1 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Special Issue:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->special_issue ?? 'None' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Official Comment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $student->official_comment ?? 'None' }}</p>
                </div>
                @if($student->pass_slip_path)
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Result Slip/Testimonial:</label>
                    <a href="{{ Storage::url($student->pass_slip_path) }}" target="_blank" class="mt-1 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-200">
                        View Document
                    </a>
                </div>
                @endif
            </div>
        </fieldset>

        <!-- Record Information -->
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-400">
                <div>
                    <span class="font-medium">Created:</span> {{ $student->created_at->format('d M, Y h:i A') }}
                </div>
                <div>
                    <span class="font-medium">Last Updated:</span> {{ $student->updated_at->format('d M, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection