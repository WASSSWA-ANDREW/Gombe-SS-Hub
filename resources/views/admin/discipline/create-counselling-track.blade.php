@extends('layouts.admin')

@section('title', 'Create Counselling Track Record')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-2">
                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                    <i class="fas fa-heart text-blue-600 dark:text-blue-400"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create Counselling Record</h1>
            </div>
            <p class="text-gray-800 dark:text-gray-400 ml-11">Record a new counselling session for a student</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            <form action="{{ route('admin.counselling.tracks.store') }}" method="POST" class="divide-y divide-gray-200 dark:divide-gray-700">
                @csrf
                
                <!-- Form Content -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                        <!-- Student Selection -->
                        <div class="group">
                            <label for="student_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-user-check text-blue-500 mr-2"></i>Student <span class="text-red-600">*</span>
                            </label>
                            <select name="student_id" id="student_id" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                                <option value="">Select a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_name }} ({{ $student->level ?? 'N/A' }} - {{ $student->class ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Counselling Type -->
                        <div class="group">
                            <label for="counselling_type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-list text-blue-500 mr-2"></i>Counselling Type <span class="text-red-600">*</span>
                            </label>
                            <select name="counselling_type" id="counselling_type" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                                <option value="">Select type...</option>
                                <option value="life" {{ old('counselling_type') === 'life' ? 'selected' : '' }}>Life Counselling</option>
                                <option value="academic" {{ old('counselling_type') === 'academic' ? 'selected' : '' }}>Academic Counselling</option>
                                <option value="behavior" {{ old('counselling_type') === 'behavior' ? 'selected' : '' }}>Behavioral Counselling</option>
                                <option value="gender" {{ old('counselling_type') === 'gender' ? 'selected' : '' }}>Gender Counselling</option>
                                <option value="character" {{ old('counselling_type') === 'character' ? 'selected' : '' }}>Character Development</option>
                                <option value="sex" {{ old('counselling_type') === 'sex' ? 'selected' : '' }}>Sexual Health Education</option>
                            </select>
                            @error('counselling_type')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Counsellor -->
                        <div class="group">
                            <label for="counsellor_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-user-md text-blue-500 mr-2"></i>Counsellor
                            </label>
                            <select name="counsellor_id" id="counsellor_id"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                                <option value="">Select counsellor...</option>
                                @foreach($staff as $member)
                                    <option value="{{ $member->id }}" {{ old('counsellor_id') == $member->id ? 'selected' : '' }}>
                                        {{ $member->staff_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('counsellor_id')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Session -->
                        <div class="group">
                            <label for="date_of_session" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-calendar-alt text-blue-500 mr-2"></i>Date of Session <span class="text-red-600">*</span>
                            </label>
                            <input type="date" name="date_of_session" id="date_of_session" value="{{ old('date_of_session') }}" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                            @error('date_of_session')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div class="group">
                            <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-clock text-blue-500 mr-2"></i>Session Status <span class="text-red-600">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                                <option value="ongoing" {{ old('status', 'ongoing') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                                <option value="completed" {{ old('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            </select>
                            @error('status')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="md:col-span-2 group">
                            <label for="notes" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-sticky-note text-blue-500 mr-2"></i>Session Notes
                            </label>
                            <textarea name="notes" id="notes" rows="3"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 placeholder-gray-700 dark:placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 resize-none"
                                placeholder="Notes from the counselling session...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Outcome -->
                        <div class="md:col-span-2 group">
                            <label for="outcome" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-thumbs-up text-blue-500 mr-2"></i>Session Outcome
                            </label>
                            <textarea name="outcome" id="outcome" rows="3"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 placeholder-gray-700 dark:placeholder-gray-500 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 resize-none"
                                placeholder="Outcome and follow-up recommendations...">{{ old('outcome') }}</textarea>
                            @error('outcome')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-exclamation-circle mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 p-8 bg-gray-50 dark:bg-gray-700/50">
                    <a href="{{ route('admin.counselling.tracks.index') }}" 
                        class="inline-flex items-center px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-8 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <i class="fas fa-save mr-2"></i>Create Counselling Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection