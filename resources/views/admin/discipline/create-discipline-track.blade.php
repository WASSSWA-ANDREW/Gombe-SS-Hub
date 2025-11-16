@extends('layouts.admin')

@section('title', 'Create Discipline Track Record')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-50 to-gray-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-3">
                <div class="p-3 bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 rounded-xl">
                    <i class="fas fa-gavel text-red-600 dark:text-red-400 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 dark:from-red-400 dark:to-orange-400 bg-clip-text text-transparent">Create Discipline Record</h1>
                    <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Record a new disciplinary incident for a student</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
            <form action="{{ route('admin.discipline.store-discipline-track') }}" method="POST" class="divide-y divide-gray-200 dark:divide-gray-700">
                @csrf
                
                <!-- Form Content -->
                <div class="p-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Student Selection -->
                        <div class="group">
                            <label for="student_id" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-user-graduate text-red-500 mr-2"></i>Select Student <span class="text-red-600">*</span>
                            </label>
                            <select name="student_id" id="student_id" required 
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                                <option value="">Select a student...</option>
                                @foreach($students as $student)
                                    <option value="{{ $student->id }}" {{ old('student_id') == $student->id ? 'selected' : '' }}>
                                        {{ $student->student_name }} ({{ $student->level ?? 'N/A' }} - {{ $student->class ?? 'N/A' }})
                                    </option>
                                @endforeach
                            </select>
                            @error('student_id')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Case Name -->
                        <div class="group">
                            <label for="case_name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-tag text-red-500 mr-2"></i>Case Name <span class="text-red-600">*</span>
                            </label>
                            <input type="text" name="case_name" id="case_name" value="{{ old('case_name') }}" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400"
                                placeholder="e.g., Classroom Disruption">
                            @error('case_name')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Disciplinary Action -->
                        <div class="group">
                            <label for="disciplinary_action" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-gavel text-red-500 mr-2"></i>Disciplinary Action <span class="text-red-600">*</span>
                            </label>
                            <select name="disciplinary_action" id="disciplinary_action" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                                <option value="">Select an action...</option>
                                <option value="statement_letter" {{ old('disciplinary_action') === 'statement_letter' ? 'selected' : '' }}>Statement Letter</option>
                                <option value="cautions" {{ old('disciplinary_action') === 'cautions' ? 'selected' : '' }}>Cautions</option>
                                <option value="active_punishment" {{ old('disciplinary_action') === 'active_punishment' ? 'selected' : '' }}>Active Punishment</option>
                            </select>
                            @error('disciplinary_action')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Resolution -->
                        <div class="group">
                            <label for="resolution" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-scale-balanced text-red-500 mr-2"></i>Resolution
                            </label>
                            <select name="resolution" id="resolution"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                                <option value="">No resolution yet...</option>
                                <option value="suspension" {{ old('resolution') === 'suspension' ? 'selected' : '' }}>Suspension</option>
                                <option value="expulsion" {{ old('resolution') === 'expulsion' ? 'selected' : '' }}>Expulsion</option>
                            </select>
                            @error('resolution')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Case Status -->
                        <div class="group">
                            <label for="case_status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-spinner text-red-500 mr-2"></i>Case Status <span class="text-red-600">*</span>
                            </label>
                            <select name="case_status" id="case_status" required
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                                <option value="pending" {{ old('case_status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="sorted" {{ old('case_status') === 'sorted' ? 'selected' : '' }}>Sorted</option>
                            </select>
                            @error('case_status')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Date of Incident -->
                        <div class="group">
                            <label for="date_of_incident" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-calendar-days text-red-500 mr-2"></i>Date of Incident
                            </label>
                            <input type="date" name="date_of_incident" id="date_of_incident" value="{{ old('date_of_incident') }}"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400">
                            @error('date_of_incident')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2 group">
                            <label for="description" class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                <i class="fas fa-align-left text-red-500 mr-2"></i>Detailed Description
                            </label>
                            <textarea name="description" id="description" rows="5"
                                class="w-full px-4 py-3 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-xl text-gray-900 dark:text-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all duration-200 hover:border-gray-300 dark:hover:border-gray-400 resize-none"
                                placeholder="Describe the incident and any relevant details...">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-2 flex items-center text-sm text-red-600 dark:text-red-400"><i class="fas fa-circle-exclamation mr-1"></i>{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-4 p-8 bg-gray-50 dark:bg-gray-700/50 border-t border-gray-200 dark:border-gray-600">
                    <a href="{{ route('admin.discipline.discipline-tracks') }}" 
                        class="inline-flex items-center px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 font-semibold rounded-lg hover:bg-gray-100 dark:hover:bg-gray-600 transition-all duration-200">
                        <i class="fas fa-xmark mr-2"></i>Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-8 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-lg hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-plus mr-2"></i>Create Record
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection