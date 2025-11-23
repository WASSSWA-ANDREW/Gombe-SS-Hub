@extends('layouts.admin')

@section('title', isset($staff) ? 'Edit Staff on Government Form' : 'Add Staff on Government Form')
@section('header', isset($staff) ? 'Edit Staff on Government Form' : 'Add Staff on Government Form')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h2 class="text-xl font-semibold text-gray-700 dark:text-white mb-2">Staff on Government Form</h2>
        <p class="text-sm text-gray-800 dark:text-gray-400 mb-6">Employee Information Form</p>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <strong class="font-bold">Oops! Something went wrong.</strong>
                <ul class="mt-3 list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ isset($staff) ? route('admin.staff.update_govt', $staff->id) : route('admin.staff.store_govt') }}" method="POST">
            @csrf
            @if(isset($staff))
                @method('PUT')
            @endif
            <input type="hidden" name="staff_type" value="government">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-6">
                {{-- Column 1 --}}
                <div class="space-y-4">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">1. Employee Name: [First Name]</label>
                        <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $staff->first_name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">[Last Name]</label>
                        <input type="text" name="surname" id="surname" value="{{ old('surname', $staff->surname ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300">2. Sex:</label>
                        <select name="sex" id="sex" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Sex</option>
                            <option value="M" {{ old('sex', $staff->sex ?? '') == 'M' ? 'selected' : '' }}>M</option>
                            <option value="F" {{ old('sex', $staff->sex ?? '') == 'F' ? 'selected' : '' }}>F</option>
                        </select>
                    </div>
                    <div>
                        <label for="uts_file_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">3. UTS/File No.</label>
                        <input type="text" name="uts_file_no" id="uts_file_no" value="{{ old('uts_file_no', $staff->uts_file_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">4. Date of Birth [DD/MM/YYYY]</label>
                        <input type="text" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', isset($staff->date_of_birth) ? \Carbon\Carbon::parse($staff->date_of_birth)->format('d/m/Y') : '') }}" placeholder="DD/MM/YYYY" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="registration_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">5. Employee Registration Number(s) for Teachers</label>
                        <input type="text" name="registration_no" id="registration_no" value="{{ old('registration_no', $staff->registration_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ipps_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">6. IPPS Number</label>
                        <input type="text" name="ipps_no" id="ipps_no" value="{{ old('ipps_no', $staff->ipps_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                     <div>
                        <label for="date_of_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">7. Date of 1st / Probationary Appointment [DD/MM/YYYY]</label>
                        <input type="text" name="date_of_1st_appt" id="date_of_1st_appt" value="{{ old('date_of_1st_appt', isset($staff->date_of_1st_appt) ? \Carbon\Carbon::parse($staff->date_of_1st_appt)->format('d/m/Y') : '') }}" placeholder="DD/MM/YYYY" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="space-y-4">
                    <div>
                        <label for="designation_of_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">8. Designation at Probationary Appointment</label>
                        <input type="text" name="designation_of_1st_appt" id="designation_of_1st_appt" value="{{ old('designation_of_1st_appt', $staff->designation_of_1st_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">9. ESC Minute of 1st Appointment</label>
                        <input type="text" name="minute_no_1st_appt" id="minute_no_1st_appt" value="{{ old('minute_no_1st_appt', $staff->minute_no_1st_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">10. ESC Minute of Confirmation</label>
                        <input type="text" name="minute_no_confirmation" id="minute_no_confirmation" value="{{ old('minute_no_confirmation', $staff->minute_no_confirmation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                     <div>
                        <label for="designation_of_current_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">11. Current Position</label>
                        <input type="text" name="designation_of_current_appt" id="designation_of_current_appt" value="{{ old('designation_of_current_appt', $staff->designation_of_current_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_current_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">12. ESC Minute of Appointment to Current Position</label>
                        <input type="text" name="minute_no_current_appt" id="minute_no_current_appt" value="{{ old('minute_no_current_appt', $staff->minute_no_current_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_current_posting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">13. Date of Posting to Current Station [DD/MM/YYYY]</label>
                        <input type="text" name="date_of_current_posting" id="date_of_current_posting" value="{{ old('date_of_current_posting', isset($staff->date_of_current_posting) ? \Carbon\Carbon::parse($staff->date_of_current_posting)->format('d/m/Y') : '') }}" placeholder="DD/MM/YYYY" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="teaching_subjects" class="block text-sm font-medium text-gray-700 dark:text-gray-300">14. Teaching Subjects (for teachers)</label>
                        <input type="text" name="teaching_subjects" id="teaching_subjects" value="{{ old('teaching_subjects', $staff->teaching_subjects ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="medical_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">15. Medical Status</label>
                        <select name="medical_status" id="medical_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Medical Status</option>
                            <option value="Healthy" {{ old('medical_status', $staff->medical_status ?? '') == 'Healthy' ? 'selected' : '' }}>Healthy</option>
                            <option value="Medical care" {{ old('medical_status', $staff->medical_status ?? '') == 'Medical care' ? 'selected' : '' }}>Medical care</option>
                        </select>
                    </div>
                    <div>
                        <label for="physical_health" class="block text-sm font-medium text-gray-700 dark:text-gray-300">16. Physical Health</label>
                        <select name="physical_health" id="physical_health" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Physical Health</option>
                            <option value="Fit" {{ old('physical_health', $staff->physical_health ?? '') == 'Fit' ? 'selected' : '' }}>Fit</option>
                            <option value="Disabled" {{ old('physical_health', $staff->physical_health ?? '') == 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">17. Religion</label>
                        <select name="religion" id="religion" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Religion</option>
                            <option value="Christianity" {{ old('religion', $staff->religion ?? '') == 'Christianity' ? 'selected' : '' }}>Christianity</option>
                            <option value="Islam" {{ old('religion', $staff->religion ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                            <option value="Judaism" {{ old('religion', $staff->religion ?? '') == 'Judaism' ? 'selected' : '' }}>Judaism</option>
                            <option value="Hinduism" {{ old('religion', $staff->religion ?? '') == 'Hinduism' ? 'selected' : '' }}>Hinduism</option>
                            <option value="Buddhism" {{ old('religion', $staff->religion ?? '') == 'Buddhism' ? 'selected' : '' }}>Buddhism</option>
                            <option value="Atheism" {{ old('religion', $staff->religion ?? '') == 'Atheism' ? 'selected' : '' }}>Atheism</option>
                            <option value="Other" {{ old('religion', $staff->religion ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="staff_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">18. Staff Designation/Role</label>
                        <select name="staff_designation" id="staff_designation" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Designation</option>
                            <option value="HEADMASTER" {{ old('staff_designation', $staff->staff_designation ?? '') == 'HEADMASTER' ? 'selected' : '' }}>HEADMASTER</option>
                            <option value="DEPUTY HEADMASTER-ADMINISTRATION" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DEPUTY HEADMASTER-ADMINISTRATION' ? 'selected' : '' }}>DEPUTY HEADMASTER-ADMINISTRATION</option>
                            <option value="DEPUTY HEADMASTER-ACADEMICS" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DEPUTY HEADMASTER-ACADEMICS' ? 'selected' : '' }}>DEPUTY HEADMASTER-ACADEMICS</option>
                            <option value="DEPUTY HEADMASTER-DISCIPLINE" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DEPUTY HEADMASTER-DISCIPLINE' ? 'selected' : '' }}>DEPUTY HEADMASTER-DISCIPLINE</option>
                            <option value="DIRECTOR OF STUDIES" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DIRECTOR OF STUDIES' ? 'selected' : '' }}>DIRECTOR OF STUDIES</option>
                            <option value="DEAN OF STUDENTS" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DEAN OF STUDENTS' ? 'selected' : '' }}>DEAN OF STUDENTS</option>
                            <option value="SCHOOL-COUNSELOR" {{ old('staff_designation', $staff->staff_designation ?? '') == 'SCHOOL-COUNSELOR' ? 'selected' : '' }}>SCHOOL-COUNSELOR</option>
                            <option value="DISCIPLINARY OFFICER" {{ old('staff_designation', $staff->staff_designation ?? '') == 'DISCIPLINARY OFFICER' ? 'selected' : '' }}>DISCIPLINARY OFFICER</option>
                            <option value="CLASS TEACHER" {{ old('staff_designation', $staff->staff_designation ?? '') == 'CLASS TEACHER' ? 'selected' : '' }}>CLASS TEACHER</option>
                            <option value="PREFECT PATRON" {{ old('staff_designation', $staff->staff_designation ?? '') == 'PREFECT PATRON' ? 'selected' : '' }}>PREFECT PATRON</option>
                            <option value="WARDEN" {{ old('staff_designation', $staff->staff_designation ?? '') == 'WARDEN' ? 'selected' : '' }}>WARDEN</option>
                            <option value="HEAD OF DEPARTMENT" {{ old('staff_designation', $staff->staff_designation ?? '') == 'HEAD OF DEPARTMENT' ? 'selected' : '' }}>HEAD OF DEPARTMENT</option>
                            <option value="LAB TECHNICIAN" {{ old('staff_designation', $staff->staff_designation ?? '') == 'LAB TECHNICIAN' ? 'selected' : '' }}>LAB TECHNICIAN</option>
                            <option value="LIBRARY OFFICER" {{ old('staff_designation', $staff->staff_designation ?? '') == 'LIBRARY OFFICER' ? 'selected' : '' }}>LIBRARY OFFICER</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-5">
                <div class="flex justify-end">
                    <a href="{{ route('admin.staff.index_govt') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md shadow-sm mr-3 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ isset($staff) ? 'Update Staff' : 'Save Staff' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection