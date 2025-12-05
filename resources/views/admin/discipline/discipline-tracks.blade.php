@extends('layouts.admin')

@section('title', 'Discipline Track Records')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-50 to-gray-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-3 bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 rounded-xl">
                        <i class="fas fa-list-check text-red-600 dark:text-red-400 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-red-600 to-orange-600 dark:from-red-400 dark:to-orange-400 bg-clip-text text-transparent">Discipline Records</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Manage and track student disciplinary actions</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.discipline.create-discipline-track') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i>Add Record
            </a>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl mb-6 border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.discipline.discipline-tracks') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div class="group">
                        <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-spinner text-red-500 mr-2"></i>Case Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2.5 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sorted" {{ request('status') === 'sorted' ? 'selected' : '' }}>Sorted</option>
                        </select>
                    </div>

                    <!-- Disciplinary Action Filter -->
                    <div class="group">
                        <label for="action" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-gavel text-red-500 mr-2"></i>Disciplinary Action</label>
                        <select name="action" id="action" class="w-full px-4 py-2.5 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-red-500 focus:ring-2 focus:ring-red-200 dark:focus:ring-red-200 transition-all hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                            <option value="">All Actions</option>
                            <option value="statement_letter" {{ request('action') === 'statement_letter' ? 'selected' : '' }}>Statement Letter</option>
                            <option value="cautions" {{ request('action') === 'cautions' ? 'selected' : '' }}>Cautions</option>
                            <option value="active_punishment" {{ request('action') === 'active_punishment' ? 'selected' : '' }}>Active Punishment</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="sm:col-span-2 group">
                        <label for="search" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-magnifying-glass text-red-500 mr-2"></i>Search</label>
                        <div class="relative flex rounded-lg overflow-hidden shadow-sm border-2 border-gray-200 hover:border-red-300 dark:border-gray-600 dark:hover:border-red-700 transition-colors focus-within:border-red-500">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search student or case name..." class="flex-1 px-4 py-2.5 bg-white dark:bg-white text-gray-900 dark:text-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none">
                            <button type="submit" class="px-4 py-2 bg-red-50 dark:bg-red-900/20 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 transition-all font-medium">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Discipline Records Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            @if($disciplineTracks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>Student
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-heading mr-2"></i>Case Name
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Action
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-check-circle mr-2"></i>Resolution
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-spinner mr-2"></i>Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-red-900 dark:text-red-300 uppercase tracking-wider">
                                    <i class="fas fa-calendar mr-2"></i>Date
                                </th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($disciplineTracks as $record)
                                <tr class="hover:bg-red-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-circle text-red-600 dark:text-red-400"></i>
                                            </div>
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    {{ $record->student->student_name }}
                                                </div>
                                                <div class="text-xs text-gray-800 dark:text-gray-400">
                                                    {{ $record->student->level ?? 'N/A' }} • {{ $record->student->class ?? 'N/A' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $record->case_name }}</div>
                                            @if($record->description)
                                                <div class="text-sm text-gray-800 dark:text-gray-400">
                                                    {{ $record->description }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($record->disciplinary_action === 'statement_letter') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif($record->disciplinary_action === 'cautions') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                            <i class="fas fa-circle-notch mr-1"></i>{{ $record->disciplinary_action_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($record->resolution)
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                @if($record->resolution === 'suspension') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                                @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                                <i class="fas fa-ban mr-1"></i>{{ $record->resolution_display }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-400">
                                                <i class="fas fa-minus-circle mr-1"></i>Not Set
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($record->case_status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300
                                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                            <i class="fas fa-dot-circle mr-1"></i>{{ $record->case_status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt mr-2 text-red-500"></i>{{ $record->date_of_incident ? $record->date_of_incident->format('M d, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.discipline.student-records', $record->student_id) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-20">
                    <div class="mb-4 flex justify-center">
                        <div class="p-5 bg-gradient-to-br from-red-100 to-orange-100 dark:from-red-900/20 dark:to-orange-900/20 rounded-full">
                            <i class="fas fa-inbox text-red-600 dark:text-red-400 text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Records Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">Start creating discipline records to manage student conduct effectively.</p>
                    <div>
                        <a href="{{ route('admin.discipline.create-discipline-track') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white font-semibold rounded-xl hover:from-red-700 hover:to-red-800 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>Create First Record
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection