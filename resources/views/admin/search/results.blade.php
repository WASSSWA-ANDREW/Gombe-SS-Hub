@extends('layouts.admin')

@section('title', 'Search Results')
@section('header', 'Search Results')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-8">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-gray-800 dark:text-white">Search Results for "{{ $query }}"</h2>
            <a href="{{ route('search') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2">
                <i class="fas fa-search mr-2"></i> New Search
            </a>
        </div>
        
        <div class="mb-4 text-gray-800 dark:text-gray-400">
            Found {{ $totalResults }} results {{ $type !== 'all' ? 'in ' . ucfirst($type) : 'across all categories' }}
        </div>
        
        <!-- Search Filters Summary -->
        @if(!empty($filters))
            <div class="mb-6 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                <h3 class="text-sm font-medium text-blue-800 dark:text-blue-300 mb-2">Applied Filters:</h3>
                <div class="flex flex-wrap gap-2">
                    @foreach($filters as $key => $value)
                        @if(!empty($value))
                            <div class="px-3 py-1 bg-blue-100 dark:bg-blue-800/30 text-blue-800 dark:text-blue-300 rounded-full text-xs">
                                {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ ucfirst($value) }}
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
        
        <!-- Results Tabs -->
        <div class="mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <button class="tab-button px-4 py-2 font-medium text-sm {{ ($type === 'all' || $type === 'students') && $results['students']->count() > 0 ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500' : 'text-gray-800 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-800' }}" 
                            data-target="students-results" 
                            {{ $results['students']->count() === 0 ? 'disabled' : '' }}>
                        Students ({{ $results['students']->count() }})
                    </button>
                    <button class="tab-button px-4 py-2 font-medium text-sm {{ ($type === 'staff') && $results['staff']->count() > 0 ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500' : 'text-gray-800 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-800' }}" 
                            data-target="staff-results"
                            {{ $results['staff']->count() === 0 ? 'disabled' : '' }}>
                        Staff ({{ $results['staff']->count() }})
                    </button>
                    <button class="tab-button px-4 py-2 font-medium text-sm {{ ($type === 'users') && $results['users']->count() > 0 ? 'text-blue-600 dark:text-blue-400 border-b-2 border-blue-500' : 'text-gray-800 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-800' }}" 
                            data-target="users-results"
                            {{ $results['users']->count() === 0 ? 'disabled' : '' }}>
                        Users ({{ $results['users']->count() }})
                    </button>
                </nav>
            </div>
        </div>
        
        <!-- Students Results -->
        <div id="students-results" class="result-section {{ ($type === 'all' || $type === 'students') && $results['students']->count() > 0 ? 'block' : 'hidden' }}">
            @if($results['students']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Level</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Gender</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($results['students'] as $student)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                                                <span class="text-blue-600 dark:text-blue-400 font-medium">{{ substr($student->student_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $student->student_name }}</div>
                                                <div class="text-sm text-gray-800 dark:text-gray-400">ID: {{ $student->learners_lin ?? $student->learners_nin ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $student->level === 'olevel' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' }}">
                                            {{ $student->level === 'olevel' ? "O'Level" : "A'Level" }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-400">
                                        {{ $student->gender }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-400">
                                        {{ $student->mobile_number ?? $student->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.students.' . $student->level . '.edit', $student->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.students.' . $student->level . '.show', $student->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-800 dark:text-gray-500 mb-2">
                        <i class="fas fa-search fa-3x"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No student results found</h3>
                    <p class="text-gray-800 dark:text-gray-400">Try adjusting your search or filters</p>
                </div>
            @endif
        </div>
        
        <!-- Staff Results -->
        <div id="staff-results" class="result-section {{ ($type === 'staff') && $results['staff']->count() > 0 ? 'block' : 'hidden' }}">
            @if($results['staff']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Designation</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($results['staff'] as $staff)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                                                <span class="text-green-600 dark:text-green-400 font-medium">{{ substr($staff->first_name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $staff->first_name }} {{ $staff->surname }}</div>
                                                <div class="text-sm text-gray-800 dark:text-gray-400">ID: {{ $staff->national_id_no ?? $staff->registration_no ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $staff->staff_type === 'government' ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400' }}">
                                            {{ ucfirst($staff->staff_type ?? 'Private') }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-400">
                                        {{ $staff->designation_of_current_appt ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-400">
                                        {{ $staff->telephone_contacts ?? $staff->email ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.staff.edit', $staff->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.staff.show', $staff->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-800 dark:text-gray-500 mb-2">
                        <i class="fas fa-search fa-3x"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No staff results found</h3>
                    <p class="text-gray-800 dark:text-gray-400">Try adjusting your search or filters</p>
                </div>
            @endif
        </div>
        
        <!-- Users Results -->
        <div id="users-results" class="result-section {{ ($type === 'users') && $results['users']->count() > 0 ? 'block' : 'hidden' }}">
            @if($results['users']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-800">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Role</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($results['users'] as $user)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                                                <span class="text-purple-600 dark:text-purple-400 font-medium">{{ substr($user->name, 0, 1) }}</span>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-800 dark:text-gray-400">ID: {{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-400">
                                        {{ $user->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $user->role === 'super_admin' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' : 
                                               ($user->role === 'admin' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' : 
                                                'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400') }}">
                                            {{ ucfirst(str_replace('_', ' ', $user->role ?? 'user')) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400' }}">
                                            {{ $user->active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                                            <i class="fas fa-eye"></i> View
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-8">
                    <div class="text-gray-800 dark:text-gray-500 mb-2">
                        <i class="fas fa-search fa-3x"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300">No user results found</h3>
                    <p class="text-gray-800 dark:text-gray-400">Try adjusting your search or filters</p>
                </div>
            @endif
        </div>
        
        <!-- No Results Message -->
        @if($totalResults === 0)
            <div class="text-center py-12">
                <div class="text-gray-800 dark:text-gray-500 mb-4">
                    <i class="fas fa-search fa-5x"></i>
                </div>
                <h3 class="text-xl font-medium text-gray-700 dark:text-gray-300 mb-2">No results found</h3>
                <p class="text-gray-800 dark:text-gray-400 mb-6">We couldn't find any matches for "{{ $query }}"</p>
                <div class="flex justify-center">
                    <a href="{{ route('search') }}" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-search mr-2"></i> Try a new search
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabButtons = document.querySelectorAll('.tab-button');
        const resultSections = document.querySelectorAll('.result-section');
        
        tabButtons.forEach(button => {
            if (!button.hasAttribute('disabled')) {
                button.addEventListener('click', function() {
                    const target = this.getAttribute('data-target');
                    
                    // Hide all sections
                    resultSections.forEach(section => {
                        section.classList.add('hidden');
                    });
                    
                    // Show target section
                    document.getElementById(target).classList.remove('hidden');
                    
                    // Update active tab
                    tabButtons.forEach(btn => {
                        btn.classList.remove('text-blue-600', 'dark:text-blue-400', 'border-b-2', 'border-blue-500');
                        btn.classList.add('text-gray-800', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-800');
                    });
                    
                    this.classList.remove('text-gray-800', 'dark:text-gray-400', 'hover:text-gray-700', 'dark:hover:text-gray-800');
                    this.classList.add('text-blue-600', 'dark:text-blue-400', 'border-b-2', 'border-blue-500');
                });
            }
        });
    });
</script>
@endpush