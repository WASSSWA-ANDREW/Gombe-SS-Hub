@extends('layouts.admin')

@section('title', 'Counselling Track Records')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 via-gray-50 to-gray-100 dark:from-gray-900 dark:via-gray-900 dark:to-gray-800 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
            <div>
                <div class="flex items-center space-x-3 mb-2">
                    <div class="p-3 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 rounded-xl">
                        <i class="fas fa-heart-pulse text-blue-600 dark:text-blue-400 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-4xl font-bold bg-gradient-to-r from-blue-600 to-cyan-600 dark:from-blue-400 dark:to-cyan-400 bg-clip-text text-transparent">Counselling Records</h1>
                        <p class="text-gray-600 dark:text-gray-400 text-sm mt-1">Track and manage student counselling sessions</p>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.counselling.tracks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all duration-200 hover:-translate-y-1">
                <i class="fas fa-plus mr-2"></i>Add Record
            </a>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-2xl mb-6 border border-gray-100 dark:border-gray-700">
            <div class="p-6">
                <form method="GET" action="{{ route('admin.counselling.tracks.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Status Filter -->
                    <div class="group">
                        <label for="status" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-spinner text-blue-500 mr-2"></i>Session Status</label>
                        <select name="status" id="status" class="w-full px-4 py-2.5 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                            <option value="">All Status</option>
                            <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                        </select>
                    </div>

                    <!-- Counselling Type Filter -->
                    <div class="group">
                        <label for="type" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-list text-blue-500 mr-2"></i>Counselling Type</label>
                        <select name="type" id="type" class="w-full px-4 py-2.5 bg-white dark:bg-white border-2 border-gray-200 dark:border-gray-300 rounded-lg text-gray-900 dark:text-gray-900 focus:border-blue-500 focus:ring-2 focus:ring-blue-200 dark:focus:ring-blue-200 transition-all hover:border-gray-300 dark:hover:border-gray-400 appearance-none cursor-pointer">
                            <option value="">All Types</option>
                            <option value="life" {{ request('type') === 'life' ? 'selected' : '' }}>Life Counselling</option>
                            <option value="academic" {{ request('type') === 'academic' ? 'selected' : '' }}>Academic Counselling</option>
                            <option value="behavior" {{ request('type') === 'behavior' ? 'selected' : '' }}>Behavioral Counselling</option>
                            <option value="gender" {{ request('type') === 'gender' ? 'selected' : '' }}>Gender Counselling</option>
                            <option value="character" {{ request('type') === 'character' ? 'selected' : '' }}>Character Development</option>
                            <option value="sex" {{ request('type') === 'sex' ? 'selected' : '' }}>Sexual Health Education</option>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="sm:col-span-2 group">
                        <label for="search" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2"><i class="fas fa-magnifying-glass text-blue-500 mr-2"></i>Search</label>
                        <div class="relative flex rounded-lg overflow-hidden shadow-sm border-2 border-gray-200 hover:border-blue-300 dark:border-gray-600 dark:hover:border-blue-700 transition-colors focus-within:border-blue-500">
                            <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search student name..." class="flex-1 px-4 py-2.5 bg-white dark:bg-white text-gray-900 dark:text-gray-900 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none">
                            <button type="submit" class="px-4 py-2 bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 transition-all font-medium">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Counselling Records Table -->
        <div class="bg-white dark:bg-gray-800 shadow-lg rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
            @if($counsellingTracks->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gradient-to-r from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-user mr-2"></i>Student
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-list mr-2"></i>Type
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-user-md mr-2"></i>Counsellor
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-calendar-alt mr-2"></i>Date
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-clock mr-2"></i>Status
                                </th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-blue-900 dark:text-blue-300 uppercase tracking-wider">
                                    <i class="fas fa-sticky-note mr-2"></i>Notes
                                </th>
                                <th scope="col" class="relative px-6 py-4">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($counsellingTracks as $record)
                                <tr class="hover:bg-blue-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0 w-10 h-10 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                                                <i class="fas fa-user-circle text-blue-600 dark:text-blue-400"></i>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($record->counselling_type === 'life') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300
                                            @elseif($record->counselling_type === 'academic') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @elseif($record->counselling_type === 'behavior') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-300
                                            @elseif($record->counselling_type === 'gender') bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-300
                                            @elseif($record->counselling_type === 'character') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300
                                            @else bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 @endif">
                                            <i class="fas fa-circle-notch mr-1"></i>{{ $record->counselling_type_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            @if($record->counsellor)
                                                <div class="flex items-center space-x-2">
                                                    <i class="fas fa-stethoscope text-blue-500"></i>
                                                    <span>{{ $record->counsellor->staff_name }}</span>
                                                </div>
                                            @else
                                                <span class="text-gray-800 dark:text-gray-500"><i class="fas fa-minus-circle mr-1"></i>Not Assigned</span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-400">
                                        <i class="fas fa-calendar-alt mr-2 text-blue-500"></i>{{ $record->date_of_session->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($record->status === 'ongoing') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300
                                            @else bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 @endif">
                                            <i class="fas fa-dot-circle mr-1"></i>{{ $record->status_display }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-700 dark:text-gray-300">
                                            @if($record->notes)
                                                <div class="flex items-start space-x-2 max-w-xs">
                                                    <i class="fas fa-comment-dots text-blue-500 mt-0.5"></i>
                                                    <span>{{ Str::limit($record->notes, 40) }}</span>
                                                </div>
                                            @else
                                                <span class="inline-flex items-center text-gray-800 dark:text-gray-500">
                                                    <i class="fas fa-minus-circle mr-1"></i>No notes
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('admin.discipline.student-records', $record->student_id) }}" class="inline-flex items-center px-3 py-1.5 bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900/50 transition-all">
                                            <i class="fas fa-eye mr-1"></i>View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6 flex justify-center">
                    {{ $counsellingTracks->appends(request()->query())->links() }}
                </div>
            @else
                <div class="text-center py-20">
                    <div class="mb-4 flex justify-center">
                        <div class="p-5 bg-gradient-to-br from-blue-100 to-cyan-100 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-full">
                            <i class="fas fa-inbox text-blue-600 dark:text-blue-400 text-4xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Records Found</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-8">Create counselling records to track student sessions and progress.</p>
                    <div>
                        <a href="{{ route('admin.counselling.tracks.create') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white font-semibold rounded-xl hover:from-blue-700 hover:to-blue-800 shadow-lg hover:shadow-xl transition-all hover:-translate-y-1">
                            <i class="fas fa-plus mr-2"></i>Create First Record
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection