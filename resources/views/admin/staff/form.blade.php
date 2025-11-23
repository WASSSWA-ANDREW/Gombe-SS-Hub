@extends('layouts.admin')

@section('title', isset($staff) ? 'Edit Staff' : 'Add New Staff')
@section('header', isset($staff) ? 'Edit Staff' : 'Add New Staff')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6 text-center">GOMBE SECONDARY SCHOOL - STAFF DATA FORM</h2>

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

        <form action="{{ isset($staff) ? route('admin.staff.update', $staff) : route('admin.staff.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($staff))
                @method('PUT')
            @endif

            <!-- Staff Type Selection -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">STAFF TYPE</legend>
                <div class="mt-4">
                    <label for="staff_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff Type:</label>
                    <select name="staff_type" id="staff_type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                        <option value="">Select Staff Type</option>
                        <option value="private" {{ old('staff_type', $staff->staff_type ?? '') == 'private' ? 'selected' : '' }}>Private Staff</option>
                        <option value="government" {{ old('staff_type', $staff->staff_type ?? '') == 'government' ? 'selected' : '' }}>Government Staff</option>
                    </select>
                </div>
            </fieldset>

            <!-- Personal Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PERSONAL INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4">
                            <div>
                                <label for="surname" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Surname:</label>
                                <input type="text" name="surname" id="surname" value="{{ old('surname', $staff->surname ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            </div>
                            <div>
                                <label for="first_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">First Name:</label>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name', $staff->first_name ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            </div>
                            <div>
                                <label for="other_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Name:</label>
                                <input type="text" name="other_name" id="other_name" value="{{ old('other_name', $staff->other_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            </div>
                            <div>
                                <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                                <input type="file" name="photo" id="photo" accept="image/jpeg,image/png" class="mt-1 block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-white dark:text-gray-300 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <p class="mt-1 text-sm text-gray-800 dark:text-gray-400">Upload a passport photo (JPG, PNG)</p>
                                @if(isset($staff) && $staff->photo_path)
                                    <div class="mt-2">
                                        <img src="{{ asset($staff->photo_path) }}" alt="Staff Photo" class="h-20 w-20 object-cover rounded-md">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div>
                        <label for="sex" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Sex:</label>
                        <select name="sex" id="sex" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Sex</option>
                            <option value="Male" {{ old('sex', $staff->sex ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('sex', $staff->sex ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $staff->date_of_birth ?? '') }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="national_id_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">National ID No:</label>
                        <input type="text" name="national_id_no" id="national_id_no" value="{{ old('national_id_no', $staff->national_id_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="telephone_contacts" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Telephone Contacts:</label>
                        <input type="tel" name="telephone_contacts" id="telephone_contacts" value="{{ old('telephone_contacts', $staff->telephone_contacts ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $staff->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="marital_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Marital Status:</label>
                        <select name="marital_status" id="marital_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Marital Status</option>
                            <option value="Single" {{ old('marital_status', $staff->marital_status ?? '') == 'Single' ? 'selected' : '' }}>Single</option>
                            <option value="Married" {{ old('marital_status', $staff->marital_status ?? '') == 'Married' ? 'selected' : '' }}>Married</option>
                            <option value="Divorced" {{ old('marital_status', $staff->marital_status ?? '') == 'Divorced' ? 'selected' : '' }}>Divorced</option>
                            <option value="Widowed" {{ old('marital_status', $staff->marital_status ?? '') == 'Widowed' ? 'selected' : '' }}>Widowed</option>
                        </select>
                    </div>
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
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
                        <label for="medical_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical Status:</label>
                        <select name="medical_status" id="medical_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Medical Status</option>
                            <option value="Healthy" {{ old('medical_status', $staff->medical_status ?? '') == 'Healthy' ? 'selected' : '' }}>Healthy</option>
                            <option value="Medical care" {{ old('medical_status', $staff->medical_status ?? '') == 'Medical care' ? 'selected' : '' }}>Medical care</option>
                        </select>
                    </div>
                    <div>
                        <label for="physical_health" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Health:</label>
                        <select name="physical_health" id="physical_health" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Physical Health</option>
                            <option value="Fit" {{ old('physical_health', $staff->physical_health ?? '') == 'Fit' ? 'selected' : '' }}>Fit</option>
                            <option value="Disabled" {{ old('physical_health', $staff->physical_health ?? '') == 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div>
                        <label for="staff_designation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff Designation/Role:</label>
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
                    <div class="md:col-span-2">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                            <div>
                                <label for="next_of_kin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin:</label>
                                <input type="text" name="next_of_kin" id="next_of_kin" value="{{ old('next_of_kin', $staff->next_of_kin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            </div>
                            <div>
                                <label for="next_of_kin_telephone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Next of Kin Telephone:</label>
                                <input type="tel" name="next_of_kin_telephone" id="next_of_kin_telephone" value="{{ old('next_of_kin_telephone', $staff->next_of_kin_telephone ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            </div>
                        </div>
                    </div>
                    <div class="md:col-span-2">
                        <label for="national_id_document" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload National ID Document:</label>
                        <input type="file" name="national_id_document" id="national_id_document" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a scanned copy of your National ID (PDF, JPG, PNG)</p>
                    </div>
                </div>
            </fieldset>

            <!-- Employment Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">EMPLOYMENT INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="staff_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Staff Status:</label>
                        <select name="staff_status" id="staff_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Staff Status</option>
                            <option value="Teaching Staff" {{ old('staff_status', $staff->staff_status ?? '') == 'Teaching Staff' ? 'selected' : '' }}>Teaching Staff</option>
                            <option value="Non Teaching Staff" {{ old('staff_status', $staff->staff_status ?? '') == 'Non Teaching Staff' ? 'selected' : '' }}>Non Teaching Staff</option>
                            <option value="Support Staff" {{ old('staff_status', $staff->staff_status ?? '') == 'Support Staff' ? 'selected' : '' }}>Support Staff</option>
                        </select>
                    </div>
                    <div>
                        <label for="uts_file_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UTS File No:</label>
                        <input type="text" name="uts_file_no" id="uts_file_no" value="{{ old('uts_file_no', $staff->uts_file_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="district_file_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">District File No:</label>
                        <input type="text" name="district_file_no" id="district_file_no" value="{{ old('district_file_no', $staff->district_file_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="computer_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Computer No:</label>
                        <input type="text" name="computer_no" id="computer_no" value="{{ old('computer_no', $staff->computer_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ipps_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">IPPS Number:</label>
                        <input type="text" name="ipps_no" id="ipps_no" value="{{ old('ipps_no', $staff->ipps_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="registration_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Registration No/Nos (for Teachers):</label>
                        <input type="text" name="registration_no" id="registration_no" value="{{ old('registration_no', $staff->registration_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="teaching_subjects" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teaching Subjects:</label>
                        <input type="text" name="teaching_subjects" id="teaching_subjects" value="{{ old('teaching_subjects', $staff->teaching_subjects ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div class="md:col-span-3">
                        <label for="employment_documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Employment Documents:</label>
                        <input type="file" name="employment_documents[]" id="employment_documents" multiple class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload employment verification documents (appointment letters, contracts, etc.)</p>
                    </div>
                    <div>
                        <label for="teacher_category" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Category of Teacher:</label>
                        <select name="teacher_category" id="teacher_category" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Category</option>
                            <option value="Science" {{ old('teacher_category', $staff->teacher_category ?? '') == 'Science' ? 'selected' : '' }}>Science</option>
                            <option value="Arts" {{ old('teacher_category', $staff->teacher_category ?? '') == 'Arts' ? 'selected' : '' }}>Arts</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Education Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">EDUCATION INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="level_of_education" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Level of Education:</label>
                        <select name="level_of_education" id="level_of_education" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Level of Education</option>
                            <option value="Primary" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Primary' ? 'selected' : '' }}>Primary</option>
                            <option value="Secondary" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                            <option value="Certificate" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="Diploma" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                            <option value="Bachelor's Degree" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Bachelor\'s Degree' ? 'selected' : '' }}>Bachelor's Degree</option>
                            <option value="Master's Degree" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Master\'s Degree' ? 'selected' : '' }}>Master's Degree</option>
                            <option value="Doctorate" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Doctorate' ? 'selected' : '' }}>Doctorate</option>
                            <option value="Other" {{ old('level_of_education', $staff->level_of_education ?? '') == 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div>
                        <label for="highest_level_of_education" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Highest Level of Education:</label>
                        <input type="text" name="highest_level_of_education" id="highest_level_of_education" value="{{ old('highest_level_of_education', $staff->highest_level_of_education ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div class="md:col-span-2">
                        <label for="other_academic_qualifications" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Academic Qualifications:</label>
                        <textarea name="other_academic_qualifications" id="other_academic_qualifications" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">{{ old('other_academic_qualifications', $staff->other_academic_qualifications ?? '') }}</textarea>
                    </div>
                    <div>
                        <label for="education_certificate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Education Certificate:</label>
                        <input type="file" name="education_certificate" id="education_certificate" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a scanned copy of your highest education certificate (PDF, JPG, PNG)</p>
                    </div>
                    <div>
                        <label for="academic_documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Academic Documents:</label>
                        <input type="file" name="academic_documents[]" id="academic_documents" multiple class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload scanned copies of your academic qualifications (multiple files allowed)</p>
                    </div>
                </div>
            </fieldset>

            <!-- Salary Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">SALARY INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="salary_scale" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Salary Scale:</label>
                        <input type="text" name="salary_scale" id="salary_scale" value="{{ old('salary_scale', $staff->salary_scale ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="tin_no" class="block text-sm font-medium text-gray-700 dark:text-gray-300">TIN No:</label>
                        <input type="text" name="tin_no" id="tin_no" value="{{ old('tin_no', $staff->tin_no ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="gross_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gross Salary:</label>
                        <input type="number" step="0.01" name="gross_salary" id="gross_salary" value="{{ old('gross_salary', $staff->gross_salary ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="net_salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Net Salary:</label>
                        <input type="number" step="0.01" name="net_salary" id="net_salary" value="{{ old('net_salary', $staff->net_salary ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>
            </fieldset>

            <!-- Appointment Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">APPOINTMENT INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="date_of_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of 1st/Probationary Appointment:</label>
                        <input type="date" name="date_of_1st_appt" id="date_of_1st_appt" value="{{ old('date_of_1st_appt', $staff->date_of_1st_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="designation_of_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Designation at 1st/Probationary Appointment:</label>
                        <input type="text" name="designation_of_1st_appt" id="designation_of_1st_appt" value="{{ old('designation_of_1st_appt', $staff->designation_of_1st_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_1st_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ESC Minute of 1st Appointment:</label>
                        <input type="text" name="minute_no_1st_appt" id="minute_no_1st_appt" value="{{ old('minute_no_1st_appt', $staff->minute_no_1st_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Confirmation:</label>
                        <input type="date" name="date_of_confirmation" id="date_of_confirmation" value="{{ old('date_of_confirmation', $staff->date_of_confirmation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ESC Minute of Confirmation:</label>
                        <input type="text" name="minute_no_confirmation" id="minute_no_confirmation" value="{{ old('minute_no_confirmation', $staff->minute_no_confirmation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_current_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Current Appointment:</label>
                        <input type="date" name="date_of_current_appt" id="date_of_current_appt" value="{{ old('date_of_current_appt', $staff->date_of_current_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="designation_of_current_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Position/Designation:</label>
                        <input type="text" name="designation_of_current_appt" id="designation_of_current_appt" value="{{ old('designation_of_current_appt', $staff->designation_of_current_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="minute_no_current_appt" class="block text-sm font-medium text-gray-700 dark:text-gray-300">ESC Minute of Appointment to Current Position:</label>
                        <input type="text" name="minute_no_current_appt" id="minute_no_current_appt" value="{{ old('minute_no_current_appt', $staff->minute_no_current_appt ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_current_posting" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Posting to Current Station:</label>
                        <input type="date" name="date_of_current_posting" id="date_of_current_posting" value="{{ old('date_of_current_posting', $staff->date_of_current_posting ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div class="md:col-span-2">
                        <label for="appointment_documents" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Upload Appointment Documents:</label>
                        <input type="file" name="appointment_documents[]" id="appointment_documents" multiple class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload appointment letters, confirmation letters, or other relevant documents</p>
                    </div>
                </div>
            </fieldset>

            <div class="mt-8 pt-5 border-t border-gray-200">
                <div class="flex justify-end">
                    <a href="{{ route('admin.staff.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md shadow-sm mr-3 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
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