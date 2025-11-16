@extends('layouts.admin')

@section('title', 'Alumni Details')
@section('header', 'Alumni Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <!-- Header with Actions -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white">ALUMNI DETAILS</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.alumni.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to List
                </a>
            </div>
        </div>

        <!-- Passport Photo Section -->
        @if($alumnus->photo_path)
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-center">
            <div class="text-center">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">PASSPORT PHOTO</label>
                <img src="{{ Storage::url($alumnus->photo_path) }}" alt="Passport Photo" class="h-40 w-32 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600">
            </div>
        </div>
        @endif

        <!-- Graduation Details -->
        <fieldset class="mb-6 border border-green-300 dark:border-green-600 p-4 rounded-md bg-green-50 dark:bg-green-900/20">
            <legend class="text-lg font-semibold text-green-700 dark:text-green-300 px-2">GRADUATION DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-green-700 dark:text-green-300">Graduation Class:</label>
                    <p class="mt-1 text-base text-green-900 dark:text-green-100 font-semibold">{{ $alumnus->graduation_class ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-700 dark:text-green-300">Graduation Year:</label>
                    <p class="mt-1 text-base text-green-900 dark:text-green-100 font-semibold">{{ $alumnus->graduation_year ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-green-700 dark:text-green-300">Original Student ID:</label>
                    <p class="mt-1 text-base text-green-900 dark:text-green-100">{{ $alumnus->student_id ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Student's Details -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">STUDENT'S DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div class="md:col-span-3">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name of Student:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $alumnus->student_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->gender ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's LIN:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->learners_lin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's NIN:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->learners_nin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->date_of_birth ? $alumnus->date_of_birth->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->religion ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->mobile_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">District of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->district_of_birth ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">District:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->district ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->nationality ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tribe:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->tribe ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous School:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->previous_school ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stream:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->stream ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $alumnus->level ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Academic Performance -->
        <fieldset class="mb-6 border border-blue-300 dark:border-blue-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-blue-700 dark:text-blue-300 px-2">ACADEMIC PERFORMANCE</legend>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">PLE Index Number:</label>
                    <p class="mt-1 text-base text-blue-900 dark:text-blue-100">{{ $alumnus->ple_index_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">PLE Aggregates:</label>
                    <p class="mt-1 text-base text-blue-900 dark:text-blue-100">{{ $alumnus->ple_aggregates ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">UCE Index Number:</label>
                    <p class="mt-1 text-base text-blue-900 dark:text-blue-100">{{ $alumnus->uce_index_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-blue-700 dark:text-blue-300">UCE Combination:</label>
                    <p class="mt-1 text-base text-blue-900 dark:text-blue-100">{{ $alumnus->combination ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Parent/Guardian Information -->
        <fieldset class="mb-6 border border-purple-300 dark:border-purple-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-purple-700 dark:text-purple-300 px-2">FATHER'S DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Father's Name:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->father_full_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Father's Mobile:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->father_mobile_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Father's NIN:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->father_nin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Father's Occupation:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->father_occupation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Father's Status:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->father_dead_alive ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-6 border border-purple-300 dark:border-purple-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-purple-700 dark:text-purple-300 px-2">MOTHER'S DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Mother's Name:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->mother_full_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Mother's Mobile:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->mother_mobile_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Mother's NIN:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->mother_nin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Mother's Occupation:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->mother_occupation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Mother's Status:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->mother_dead_alive ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <fieldset class="mb-6 border border-purple-300 dark:border-purple-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-purple-700 dark:text-purple-300 px-2">GUARDIAN'S DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Guardian's Name:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->guardian_full_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Guardian's Mobile:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->guardian_mobile_number ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Guardian's NIN:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->guardian_nin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Guardian's Occupation:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->guardian_occupation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-purple-700 dark:text-purple-300">Relationship:</label>
                    <p class="mt-1 text-base text-purple-900 dark:text-purple-100">{{ $alumnus->guardian_relationship ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Official Comment -->
        @if($alumnus->official_comment)
        <fieldset class="mb-6 border border-yellow-300 dark:border-yellow-600 p-4 rounded-md bg-yellow-50 dark:bg-yellow-900/20">
            <legend class="text-lg font-semibold text-yellow-700 dark:text-yellow-300 px-2">OFFICIAL COMMENT</legend>
            <p class="mt-2 text-base text-yellow-900 dark:text-yellow-100">{{ $alumnus->official_comment }}</p>
        </fieldset>
        @endif
    </div>
</div>
@endsection