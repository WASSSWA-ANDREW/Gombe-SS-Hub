@extends('layouts.admin')

@section('title', 'Government Staff Details')
@section('header', 'Government Staff Member Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <!-- Header with Actions -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white">GOVERNMENT STAFF DETAILS</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.staff.index_govt') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to List
                </a>
                <a href="{{ route('admin.staff.edit_govt', $staff->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Edit
                </a>
                <a href="{{ route('admin.staff.view.pdf_govt', $staff->id) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out" target="_blank">
                    Download PDF
                </a>
            </div>
        </div>

        <!-- Passport Photo Section -->
        @if($staff->photo_path)
        <div class="mb-6 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg flex justify-center">
            <div class="text-center">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">PASSPORT PHOTO</label>
                <img src="{{ Storage::url($staff->photo_path) }}" alt="Passport Photo" class="h-40 w-32 object-cover rounded-lg shadow-md border-2 border-gray-300 dark:border-gray-600">
            </div>
        </div>
        @endif

        <!-- Employee Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">EMPLOYEE INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">1. Employee Name (First Name):</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->first_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Last Name (Surname):</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->surname ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">2. Sex:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->sex ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">3. UTS/File No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->uts_file_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">4. Date of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_birth ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">5. Employee Registration Number(s):</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->registration_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">6. IPPS Number:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->ipps_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">14. Teaching Subjects:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->teaching_subjects ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">17. Religion:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->religion ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Appointment Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">APPOINTMENT INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">7. Date of 1st/Probationary Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_1st_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">8. Designation at Probationary Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->designation_of_1st_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">9. ESC Minute of 1st Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_1st_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">10. ESC Minute of Confirmation:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_confirmation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">11. Current Position:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->designation_of_current_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">12. ESC Minute of Appointment to Current Position:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_current_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">13. Date of Posting to Current Station:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_current_posting ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Record Information -->
        <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-800 dark:text-gray-400">
                <div>
                    <span class="font-medium">Created:</span> {{ $staff->created_at->format('d M, Y h:i A') }}
                </div>
                <div>
                    <span class="font-medium">Last Updated:</span> {{ $staff->updated_at->format('d M, Y h:i A') }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection