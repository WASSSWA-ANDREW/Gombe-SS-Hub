@extends('layouts.admin')

@section('title', 'Edit Alumni')
@section('header', 'Edit Alumni')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-white mb-6 text-center">EDIT ALUMNI RECORD</h2>

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

        <form action="{{ route('admin.alumni.update', $alumnus->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- Alumni Details -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">ALUMNI DETAILS</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div class="md:col-span-2">
                        <label for="student_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name of Alumni:</label>
                        <input type="text" name="student_name" id="student_name" value="{{ old('student_name', $alumnus->student_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" required>
                    </div>
                    <div class="md:col-span-1">
                        <label for="photo" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Passport Photo:</label>
                        <input type="file" name="photo" id="photo" accept="image/*" class="mt-1 block w-full text-sm text-gray-800 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-1 text-sm text-gray-800">Upload a new passport-sized photo (JPG, PNG)</p>
                        @if($alumnus->photo_path)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $alumnus->photo_path) }}" alt="Current Photo" class="h-20 w-20 object-cover rounded-md">
                                <p class="mt-1 text-sm text-gray-800">Current photo - upload new one to replace</p>
                            </div>
                        @endif
                    </div>
                    <div>
                        <label for="gender" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Gender:</label>
                        <select name="gender" id="gender" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" required>
                            <option value="">Select Gender</option>
                            <option value="Male" {{ old('gender', $alumnus->gender) == 'Male' ? 'selected' : '' }}>Male</option>
                            <option value="Female" {{ old('gender', $alumnus->gender) == 'Female' ? 'selected' : '' }}>Female</option>
                        </select>
                    </div>
                    <div>
                        <label for="level" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Education Level:</label>
                        <select name="level" id="level" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" required>
                            <option value="">Select Level</option>
                            <option value="olevel" {{ old('level', $alumnus->level) == 'olevel' ? 'selected' : '' }}>O'Level (S4)</option>
                            <option value="alevel" {{ old('level', $alumnus->level) == 'alevel' ? 'selected' : '' }}>A'Level (S6)</option>
                        </select>
                    </div>
                    <div>
                        <label for="graduation_class" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Graduation Class:</label>
                        <select name="graduation_class" id="graduation_class" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" required>
                            <option value="">Select Class</option>
                            <option value="S4" {{ old('graduation_class', $alumnus->graduation_class) == 'S4' ? 'selected' : '' }}>S4 (O'Level)</option>
                            <option value="S6" {{ old('graduation_class', $alumnus->graduation_class) == 'S6' ? 'selected' : '' }}>S6 (A'Level)</option>
                        </select>
                    </div>
                    <div>
                        <label for="graduation_year" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Graduation Year:</label>
                        <input type="number" name="graduation_year" id="graduation_year" value="{{ old('graduation_year', $alumnus->graduation_year) }}" min="2000" max="{{ date('Y') + 1 }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" required>
                    </div>
                    <div>
                        <label for="stream" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Stream:</label>
                        <select name="stream" id="stream" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Stream</option>
                            <option value="A" {{ old('stream', $alumnus->stream) == 'A' ? 'selected' : '' }}>A</option>
                            <option value="B" {{ old('stream', $alumnus->stream) == 'B' ? 'selected' : '' }}>B</option>
                            <option value="C" {{ old('stream', $alumnus->stream) == 'C' ? 'selected' : '' }}>C</option>
                            <option value="D" {{ old('stream', $alumnus->stream) == 'D' ? 'selected' : '' }}>D</option>
                            <option value="E" {{ old('stream', $alumnus->stream) == 'E' ? 'selected' : '' }}>E</option>
                            <option value="G" {{ old('stream', $alumnus->stream) == 'G' ? 'selected' : '' }}>G</option>
                            <option value="H" {{ old('stream', $alumnus->stream) == 'H' ? 'selected' : '' }}>H</option>
                            <option value="T" {{ old('stream', $alumnus->stream) == 'T' ? 'selected' : '' }}>T</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Personal Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PERSONAL INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="learners_lin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's LIN:</label>
                        <input type="text" name="learners_lin" id="learners_lin" value="{{ old('learners_lin', $alumnus->learners_lin) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="learners_nin" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Learner's NIN:</label>
                        <input type="text" name="learners_nin" id="learners_nin" value="{{ old('learners_nin', $alumnus->learners_nin) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="date_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date of Birth:</label>
                        <input type="date" name="date_of_birth" id="date_of_birth" value="{{ old('date_of_birth', $alumnus->date_of_birth ? $alumnus->date_of_birth->format('Y-m-d') : '') }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="religion" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Religion:</label>
                        <input type="text" name="religion" id="religion" value="{{ old('religion', $alumnus->religion) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mobile_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mobile Number:</label>
                        <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number', $alumnus->mobile_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email:</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $alumnus->email) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="district_of_birth" class="block text-sm font-medium text-gray-700 dark:text-gray-300">District of Birth:</label>
                        <input type="text" name="district_of_birth" id="district_of_birth" value="{{ old('district_of_birth', $alumnus->district_of_birth) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="district" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current District:</label>
                        <input type="text" name="district" id="district" value="{{ old('district', $alumnus->district) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="nationality" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Nationality:</label>
                        <input type="text" name="nationality" id="nationality" value="{{ old('nationality', $alumnus->nationality) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="tribe" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Tribe:</label>
                        <input type="text" name="tribe" id="tribe" value="{{ old('tribe', $alumnus->tribe) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="previous_school" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Previous School:</label>
                        <input type="text" name="previous_school" id="previous_school" value="{{ old('previous_school', $alumnus->previous_school) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="medical_status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Medical Status:</label>
                        <select name="medical_status" id="medical_status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                            <option value="">Select Medical Status</option>
                            <option value="Healthy" {{ old('medical_status', $alumnus->medical_status) == 'Healthy' ? 'selected' : '' }}>Healthy</option>
                            <option value="Medical care" {{ old('medical_status', $alumnus->medical_status) == 'Medical care' ? 'selected' : '' }}>Medical care</option>
                        </select>
                    </div>
                </div>
            </fieldset>

            <!-- Academic Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">ACADEMIC INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="ple_index_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">PLE Index Number:</label>
                        <input type="text" name="ple_index_number" id="ple_index_number" value="{{ old('ple_index_number', $alumnus->ple_index_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_index_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">UCE Index Number:</label>
                        <input type="text" name="uce_index_number" id="uce_index_number" value="{{ old('uce_index_number', $alumnus->uce_index_number) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="combination" class="block text-sm font-medium text-gray-700 dark:text-gray-300">A'Level Combination:</label>
                        <input type="text" name="combination" id="combination" value="{{ old('combination', $alumnus->combination) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>
            </fieldset>

            <!-- PLE Results -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">PLE RESULTS</legend>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="ple_english" class="block text-sm font-medium text-gray-700 dark:text-gray-300">English:</label>
                        <input type="number" name="ple_english" id="ple_english" value="{{ old('ple_english', $alumnus->ple_english) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_mathematics" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mathematics:</label>
                        <input type="number" name="ple_mathematics" id="ple_mathematics" value="{{ old('ple_mathematics', $alumnus->ple_mathematics) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_sst" class="block text-sm font-medium text-gray-700 dark:text-gray-300">SST:</label>
                        <input type="number" name="ple_sst" id="ple_sst" value="{{ old('ple_sst', $alumnus->ple_sst) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_science" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Science:</label>
                        <input type="number" name="ple_science" id="ple_science" value="{{ old('ple_science', $alumnus->ple_science) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Total:</label>
                        <input type="number" name="ple_total" id="ple_total" value="{{ old('ple_total', $alumnus->ple_total) }}" min="0" max="500" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="ple_aggregates" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Aggregates:</label>
                        <input type="text" name="ple_aggregates" id="ple_aggregates" value="{{ old('ple_aggregates', $alumnus->ple_aggregates) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>
            </fieldset>

            <!-- UCE Results -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">UCE RESULTS</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="uce_english" class="block text-sm font-medium text-gray-700 dark:text-gray-300">English:</label>
                        <input type="number" name="uce_english" id="uce_english" value="{{ old('uce_english', $alumnus->uce_english) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_mathematics" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mathematics:</label>
                        <input type="number" name="uce_mathematics" id="uce_mathematics" value="{{ old('uce_mathematics', $alumnus->uce_mathematics) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_physics" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Physics:</label>
                        <input type="number" name="uce_physics" id="uce_physics" value="{{ old('uce_physics', $alumnus->uce_physics) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_chemistry" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Chemistry:</label>
                        <input type="number" name="uce_chemistry" id="uce_chemistry" value="{{ old('uce_chemistry', $alumnus->uce_chemistry) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_biology" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Biology:</label>
                        <input type="number" name="uce_biology" id="uce_biology" value="{{ old('uce_biology', $alumnus->uce_biology) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_history" class="block text-sm font-medium text-gray-700 dark:text-gray-300">History:</label>
                        <input type="number" name="uce_history" id="uce_history" value="{{ old('uce_history', $alumnus->uce_history) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_geography" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Geography:</label>
                        <input type="number" name="uce_geography" id="uce_geography" value="{{ old('uce_geography', $alumnus->uce_geography) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_economics" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Economics:</label>
                        <input type="number" name="uce_economics" id="uce_economics" value="{{ old('uce_economics', $alumnus->uce_economics) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="uce_literature" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Literature:</label>
                        <input type="number" name="uce_literature" id="uce_literature" value="{{ old('uce_literature', $alumnus->uce_literature) }}" min="0" max="100" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div class="md:col-span-3">
                        <label for="uce_other" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Other Subjects:</label>
                        <input type="text" name="uce_other" id="uce_other" value="{{ old('uce_other', $alumnus->uce_other) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900" placeholder="e.g., Art 85, Music 78">
                    </div>
                </div>
            </fieldset>

            <!-- Guardian Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">GUARDIAN INFORMATION</legend>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mt-4">
                    <div>
                        <label for="father_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Father's Full Name:</label>
                        <input type="text" name="father_full_name" id="father_full_name" value="{{ old('father_full_name', $alumnus->father_full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="mother_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Mother's Full Name:</label>
                        <input type="text" name="mother_full_name" id="mother_full_name" value="{{ old('mother_full_name', $alumnus->mother_full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_full_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Guardian's Full Name:</label>
                        <input type="text" name="guardian_full_name" id="guardian_full_name" value="{{ old('guardian_full_name', $alumnus->guardian_full_name) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                    <div>
                        <label for="guardian_relationship" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Relationship to Student:</label>
                        <input type="text" name="guardian_relationship" id="guardian_relationship" value="{{ old('guardian_relationship', $alumnus->guardian_relationship) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">
                    </div>
                </div>
            </fieldset>

            <!-- Additional Information -->
            <fieldset class="mb-6 border border-gray-300 dark:border-gray-600 p-4 rounded-md">
                <legend class="text-lg font-semibold text-gray-700 dark:text-white px-2">ADDITIONAL INFORMATION</legend>
                <div class="mt-4">
                    <label for="special_issue" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Special Issues (if any):</label>
                    <textarea name="special_issue" id="special_issue" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">{{ old('special_issue', $alumnus->special_issue) }}</textarea>
                </div>
                <div class="mt-4">
                    <label for="official_comment" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Official Comments:</label>
                    <textarea name="official_comment" id="official_comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-base py-3 px-4 bg-white text-gray-900">{{ old('official_comment', $alumnus->official_comment) }}</textarea>
                </div>
            </fieldset>

            <!-- Submit Buttons -->
            <div class="flex justify-end space-x-4">
                <a href="{{ route('admin.alumni.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Cancel
                </a>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i>Update Alumni
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-sync level and graduation class
        const levelSelect = document.getElementById('level');
        const graduationClassSelect = document.getElementById('graduation_class');

        levelSelect.addEventListener('change', function() {
            if (this.value === 'olevel') {
                graduationClassSelect.value = 'S4';
            } else if (this.value === 'alevel') {
                graduationClassSelect.value = 'S6';
            }
        });

        graduationClassSelect.addEventListener('change', function() {
            if (this.value === 'S4') {
                levelSelect.value = 'olevel';
            } else if (this.value === 'S6') {
                levelSelect.value = 'alevel';
            }
        });
    });
</script>
@endpush
@endsection