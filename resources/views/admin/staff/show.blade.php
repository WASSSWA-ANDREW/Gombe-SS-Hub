@extends('layouts.admin')

@section('title', 'Staff Details')
@section('header', 'Staff Member Details')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <!-- Header with Actions -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-700 dark:text-white">STAFF DATA DETAILS</h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.staff.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Back to List
                </a>
                <a href="{{ route('admin.staff.edit', $staff) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Edit
                </a>
                <a href="{{ route('admin.staff.view.pdf', $staff) }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out" target="_blank">
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

        <!-- Personal Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PERSONAL INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surname:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->surname ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->first_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->other_name ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sex:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->sex ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_birth ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">National ID No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->national_id_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marital Status:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->marital_status ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->religion ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->email ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telephone:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->telephone_contacts ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Employment Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">EMPLOYMENT INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">UTS File No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->uts_file_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">District File No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->district_file_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Computer No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->computer_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->registration_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">TIN No:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->tin_no ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teaching Subjects:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->teaching_subjects ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Appointment Details -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">APPOINTMENT DETAILS</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of 1st Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_1st_appt ? \Carbon\Carbon::parse($staff->date_of_1st_appt)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation of 1st Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->designation_of_1st_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minute No of 1st Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_1st_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Confirmation:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_confirmation ? \Carbon\Carbon::parse($staff->date_of_confirmation)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minute No of Confirmation:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_confirmation ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Current Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_current_appt ? \Carbon\Carbon::parse($staff->date_of_current_appt)->format('d/m/Y') : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation of Current Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->designation_of_current_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Minute No of Current Appointment:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->minute_no_current_appt ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Current Posting:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->date_of_current_posting ? \Carbon\Carbon::parse($staff->date_of_current_posting)->format('d/m/Y') : 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Salary Information -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">SALARY INFORMATION</legend>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary Scale:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->salary_scale ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gross Salary:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->gross_salary ? 'UGX ' . number_format($staff->gross_salary, 2) : 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Net Salary:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white font-semibold">{{ $staff->net_salary ? 'UGX ' . number_format($staff->net_salary, 2) : 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Next of Kin -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">NEXT OF KIN</legend>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin Name:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->next_of_kin ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin Telephone:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->next_of_kin_telephone ?? 'N/A' }}</p>
                </div>
            </div>
        </fieldset>

        <!-- Academic Qualifications -->
        <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
            <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">ACADEMIC QUALIFICATIONS</legend>
            <div class="grid grid-cols-1 gap-y-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Highest Level of Education:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->highest_level_of_education ?? 'N/A' }}</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Academic Qualifications:</label>
                    <p class="mt-1 text-base text-gray-900 dark:text-white">{{ $staff->other_academic_qualifications ?? 'None' }}</p>
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