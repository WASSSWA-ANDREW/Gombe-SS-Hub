@extends('layouts.admin')

@section('title', isset($student) ? "Edit O'Level Student" : "Add O'Level Student")
@section('header', isset($student) ? "Edit O'Level Student" : "Add O'Level Student")

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6 text-center">O'LEVEL FORM</h2>

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

        <form action="{{ isset($student) ? route('admin.students.olevel.update', $student->id) : route('admin.students.olevel.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @if(isset($student))
                @method('PUT')
            @endif

            <!-- Student's Details -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">STUDENT'S DETAILS</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div class="md:col-span-2">
                        <label for="student_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name of Student:</label>
                        <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $student->student_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div class="md:col-span-1">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a passport-sized photo (JPG, PNG)</p>
                        @if(isset($student) && $student->photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $student->photo_path) }}" alt="Student Photo" class="h-20 w-20 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender:</label>
                        <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $student->gender ?? '') == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $student->gender ?? '') == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class:</label>
                        <select name="class" id="class" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Class</option>
                            <option value="S.1" {{ old('class', $student->class ?? '') == 'S.1' ? 'selected' : '' }}>S.1</option>
                            <option value="S.2" {{ old('class', $student->class ?? '') == 'S.2' ? 'selected' : '' }}>S.2</option>
                            <option value="S.3" {{ old('class', $student->class ?? '') == 'S.3' ? 'selected' : '' }}>S.3</option>
                            <option value="S.4" {{ old('class', $student->class ?? '') == 'S.4' ? 'selected' : '' }}>S.4</option>
                        </select>
                    </div>
                    <div>
                        <label for="stream" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stream:</label>
                        <select name="stream" id="stream" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Stream</option>
                            <option value="A" {{ old('stream', $student->stream ?? '') == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('stream', $student->stream ?? '') == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('stream', $student->stream ?? '') == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('stream', $student->stream ?? '') == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E" {{ old('stream', $student->stream ?? '') == 'E' ? 'selected' : '' }}>E</option>
                            <option value="G" {{ old('stream', $student->stream ?? '') == 'G' ? 'selected' : '' }}>G</option>
                            <option value="H" {{ old('stream', $student->stream ?? '') == 'H' ? 'selected' : '' }}>H</option>
                            <option value="T" {{ old('stream', $student->stream ?? '') == 'T' ? 'selected' : '' }}>T</option>
                        </select>
                    </div>
                    <div>
                        <label for="admission_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Admission Number:</label>
                        <input type="text" name="admission_number" id="admission_number" value="{{ old('admission_number', $student->admission_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="learners_lin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's LIN:</label>
                        <input type="text" name="learners_lin" id="learners_lin" value="{{ old('learners_lin', $student->learners_lin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="learners_nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's NIN:</label>
                        <input type="text" name="learners_nin" id="learners_nin" value="{{ old('learners_nin', $student->learners_nin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $student->date_of_birth ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion', $student->religion ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                        <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $student->mobile_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $student->email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="district_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">District of Birth:</label>
                        <input type="text" name="district_of_birth" id="district_of_birth" value="{{ old('district_of_birth', $student->district_of_birth ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="previous_school" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous School:</label>
                        <input type="text" name="previous_school" id="previous_school" value="{{ old('previous_school', $student->previous_school ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_index_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PLE Index Number:</label>
                        <input type="text" name="ple_index_number" id="ple_index_number" value="{{ old('ple_index_number', $student->ple_index_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="medical_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical Status:</label>
                        <select name="medical_status" id="medical_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Medical Status</option>
                            <option value="Healthy" {{ old('medical_status', $student->medical_status ?? '') == 'Healthy' ? 'selected' : '' }}>Healthy</option>
                            <option value="Medical care" {{ old('medical_status', $student->medical_status ?? '') == 'Medical care' ? 'selected' : '' }}>Medical care</option>
                        </select>
                    </div>
                    <div>
                        <label for="physical_health" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Health:</label>
                        <select name="physical_health" id="physical_health" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Physical Health</option>
                            <option value="Fit" {{ old('physical_health', $student->physical_health ?? '') == 'Fit' ? 'selected' : '' }}>Fit</option>
                            <option value="Disabled" {{ old('physical_health', $student->physical_health ?? '') == 'Disabled' ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                    <div class="md:col-span-3">
                        <label for="special_issue" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Does the student have any special issue? (Mention if any):</label>
                        <textarea name="special_issue" id="special_issue" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">{{ old('special_issue', $student->special_issue ?? '') }}</textarea>
                    </div>
                </div>
            </fieldset>

            <!-- PLE Results -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PLE RESULTS</legend>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4 mt-4">
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label></div>
                    <div class="md:col-span-2"><label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Result</label></div>

                    <div class="md:col-span-2"><input type="text" value="ENGLISH" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-base py-3 px-4 bg-gray-100 text-gray-700" readonly></div>
                    <div class="md:col-span-2"><input type="text" name="ple_english" value="{{ old('ple_english', $student->ple_results['english'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-2"><input type="text" value="MATHEMATICS" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-base py-3 px-4 bg-gray-100 text-gray-700" readonly></div>
                    <div class="md:col-span-2"><input type="text" name="ple_mathematics" value="{{ old('ple_mathematics', $student->ple_results['mathematics'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-2"><input type="text" value="S.S.T" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-base py-3 px-4 bg-gray-100 text-gray-700" readonly></div>
                    <div class="md:col-span-2"><input type="text" name="ple_sst" value="{{ old('ple_sst', $student->ple_results['sst'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-2"><input type="text" value="SCIENCE" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm text-base py-3 px-4 bg-gray-100 text-gray-700" readonly></div>
                    <div class="md:col-span-2"><input type="text" name="ple_science" value="{{ old('ple_science', $student->ple_results['science'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-2"><label for="ple_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">TOTAL:</label></div>
                    <div class="md:col-span-2"><input type="number" name="ple_total" id="ple_total" value="{{ old('ple_total', $student->ple_results['total'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-2"><label for="ple_aggregates" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-2">AGGREGATES:</label></div>
                    <div class="md:col-span-2"><input type="number" name="ple_aggregates" id="ple_aggregates" value="{{ old('ple_aggregates', $student->ple_results['aggregates'] ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900"></div>

                    <div class="md:col-span-4 mt-2">
                        <label for="pass_slip" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Attach Photocopy of Pass Slip/Testimonial:</label>
                        <input type="file" name="pass_slip" id="pass_slip" class="mt-1 block w-full text-sm text-gray-800 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 dark:file:bg-gray-700 file:text-indigo-700 dark:file:text-indigo-300 hover:file:bg-indigo-100 dark:hover:file:bg-gray-600">
                        @if(isset($student) && $student->pass_slip_path)
                        <p class="text-xs text-gray-800 mt-1">Current file: <a href="{{ asset('storage/' . $student->pass_slip_path) }}" target="_blank" class="text-indigo-600 hover:underline">View Pass Slip</a></p>
                        @endif
                    </div>
                </div>
            </fieldset>

            <!-- Father's Details -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">FATHER'S DETAILS</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="father_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name:</label>
                        <input type="text" name="father_full_name" id="father_full_name" value="{{ old('father_full_name', $student->father_full_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                        <input type="tel" name="father_mobile_number" id="father_mobile_number" value="{{ old('father_mobile_number', $student->father_mobile_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="father_email" id="father_email" value="{{ old('father_email', $student->father_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIN:</label>
                        <input type="text" name="father_nin" id="father_nin" value="{{ old('father_nin', $student->father_nin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_physical_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Address:</label>
                        <input type="text" name="father_physical_address" id="father_physical_address" value="{{ old('father_physical_address', $student->father_physical_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Occupation:</label>
                        <input type="text" name="father_occupation" id="father_occupation" value="{{ old('father_occupation', $student->father_occupation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="father_passport_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                        <input type="file" name="father_passport_photo" id="father_passport_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a passport-sized photo (JPG, PNG)</p>
                        @if(isset($student) && $student->father_passport_photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $student->father_passport_photo_path) }}" alt="Father Photo" class="h-20 w-20 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label for="father_dead_alive" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dead/Alive:</label>
                        <select name="father_dead_alive" id="father_dead_alive" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Status</option>
                            <option value="Alive" {{ old('father_dead_alive', $student->father_dead_alive ?? '') == 'Alive' ? 'selected' : '' }}>Alive</option>
                            <option value="Dead" {{ old('father_dead_alive', $student->father_dead_alive ?? '') == 'Dead' ? 'selected' : '' }}>Dead</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Mother's Details -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">MOTHER'S DETAILS</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="mother_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name:</label>
                        <input type="text" name="mother_full_name" id="mother_full_name" value="{{ old('mother_full_name', $student->mother_full_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                        <input type="tel" name="mother_mobile_number" id="mother_mobile_number" value="{{ old('mother_mobile_number', $student->mother_mobile_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="mother_email" id="mother_email" value="{{ old('mother_email', $student->mother_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIN:</label>
                        <input type="text" name="mother_nin" id="mother_nin" value="{{ old('mother_nin', $student->mother_nin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_physical_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Address:</label>
                        <input type="text" name="mother_physical_address" id="mother_physical_address" value="{{ old('mother_physical_address', $student->mother_physical_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Occupation:</label>
                        <input type="text" name="mother_occupation" id="mother_occupation" value="{{ old('mother_occupation', $student->mother_occupation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_passport_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                        <input type="file" name="mother_passport_photo" id="mother_passport_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a passport-sized photo (JPG, PNG)</p>
                        @if(isset($student) && $student->mother_passport_photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $student->mother_passport_photo_path) }}" alt="Mother Photo" class="h-20 w-20 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label for="mother_dead_alive" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Dead/Alive:</label>
                        <select name="mother_dead_alive" id="mother_dead_alive" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Status</option>
                            <option value="Alive" {{ old('mother_dead_alive', $student->mother_dead_alive ?? '') == 'Alive' ? 'selected' : '' }}>Alive</option>
                            <option value="Dead" {{ old('mother_dead_alive', $student->mother_dead_alive ?? '') == 'Dead' ? 'selected' : '' }}>Dead</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Guardian's Details -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">GUARDIAN'S DETAILS</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="guardian_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name:</label>
                        <input type="text" name="guardian_full_name" id="guardian_full_name" value="{{ old('guardian_full_name', $student->guardian_full_name ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                        <input type="tel" name="guardian_mobile_number" id="guardian_mobile_number" value="{{ old('guardian_mobile_number', $student->guardian_mobile_number ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="guardian_email" id="guardian_email" value="{{ old('guardian_email', $student->guardian_email ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">NIN:</label>
                        <input type="text" name="guardian_nin" id="guardian_nin" value="{{ old('guardian_nin', $student->guardian_nin ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_physical_address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physical Address:</label>
                        <input type="text" name="guardian_physical_address" id="guardian_physical_address" value="{{ old('guardian_physical_address', $student->guardian_physical_address ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_occupation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Occupation:</label>
                        <input type="text" name="guardian_occupation" id="guardian_occupation" value="{{ old('guardian_occupation', $student->guardian_occupation ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_passport_photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                        <input type="file" name="guardian_passport_photo" id="guardian_passport_photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a passport-sized photo (JPG, PNG)</p>
                        @if(isset($student) && $student->guardian_passport_photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $student->guardian_passport_photo_path) }}" alt="Guardian Photo" class="h-20 w-20 object-cover rounded-md">
                            </div>
                        @endif
                    </div>
                    <div class="md:col-span-2">
                        <label for="guardian_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guardian's Relationship:</label>
                        <input type="text" name="guardian_relationship" id="guardian_relationship" value="{{ old('guardian_relationship', $student->guardian_relationship ?? '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>
            </fieldset>

            <!-- Official Comment -->
            <div class="mb-6">
                <label for="official_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Official Comment:</label>
                <textarea name="official_comment" id="official_comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">{{ old('official_comment', $student->official_comment ?? '') }}</textarea>
            </div>

            <div class="mt-8 pt-5 border-t border-gray-200 dark:border-gray-700">
                <div class="flex justify-end">
                    <a href="{{ route('admin.dashboard') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold py-2 px-4 rounded-md shadow-sm mr-3 dark:bg-gray-600 dark:text-gray-200 dark:hover:bg-gray-500">
                        Cancel
                    </a>
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                        {{ isset($student) ? 'Update Student' : 'Add New Student' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection