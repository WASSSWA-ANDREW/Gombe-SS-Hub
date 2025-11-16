@extends('layouts.admin')

@section('title', 'Search')
@section('header', 'Search Results')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 mb-8">
        <h2 class="text-2xl font-semibold text-gray-800 dark:text-white mb-6">Search</h2>
        
        <form action="{{ route('search.submit') }}" method="POST" class="mb-8">
            @csrf
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" name="query" value="{{ old('query', request('query')) }}" 
                           class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white" 
                           placeholder="Search for students, staff, or users..." required>
                </div>
                <div>
                    <select name="type" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:text-white">
                        <option value="all" {{ old('type', request('type')) == 'all' ? 'selected' : '' }}>All</option>
                        <option value="students" {{ old('type', request('type')) == 'students' ? 'selected' : '' }}>Students</option>
                        <option value="staff" {{ old('type', request('type')) == 'staff' ? 'selected' : '' }}>Staff</option>
                        <option value="users" {{ old('type', request('type')) == 'users' ? 'selected' : '' }}>Users</option>
                    </select>
                </div>
                <div>
                    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        <i class="fas fa-search mr-2"></i> Search
                    </button>
                </div>
            </div>
            
            <div class="mt-4">
                <button type="button" id="toggle-filters" class="text-blue-600 dark:text-blue-400 hover:underline flex items-center">
                    <i class="fas fa-filter mr-2"></i> Advanced Filters
                    <i class="fas fa-chevron-down ml-1 transform transition-transform duration-200" id="filter-icon"></i>
                </button>
                
                <div id="advanced-filters" class="hidden mt-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Student Filters -->
                        <div>
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Student Filters</h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm text-gray-800 dark:text-gray-400">Level</label>
                                    <select name="filters[level]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
                                        <option value="">Any Level</option>
                                        <option value="olevel">O'Level</option>
                                        <option value="alevel">A'Level</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-800 dark:text-gray-400">Gender</label>
                                    <select name="filters[gender]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
                                        <option value="">Any Gender</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Staff Filters -->
                        <div>
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">Staff Filters</h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm text-gray-800 dark:text-gray-400">Staff Type</label>
                                    <select name="filters[staff_type]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
                                        <option value="">Any Type</option>
                                        <option value="government">Government</option>
                                        <option value="private">Private</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm text-gray-800 dark:text-gray-400">Designation</label>
                                    <input type="text" name="filters[designation]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white" placeholder="Any designation">
                                </div>
                            </div>
                        </div>
                        
                        <!-- User Filters -->
                        <div>
                            <h3 class="font-medium text-gray-700 dark:text-gray-300 mb-2">User Filters</h3>
                            <div class="space-y-2">
                                <div>
                                    <label class="block text-sm text-gray-800 dark:text-gray-400">Role</label>
                                    <select name="filters[role]" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md dark:bg-gray-800 dark:text-white">
                                        <option value="">Any Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="super_admin">Super Admin</option>
                                        <option value="user">Regular User</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        
        @if(session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif
        
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-700 dark:text-gray-300 mb-4">Recent Searches</h3>
            <div id="search-history" class="flex flex-wrap gap-2">
                <div class="text-gray-800 dark:text-gray-400 text-sm italic">Loading recent searches...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle advanced filters
        const toggleFilters = document.getElementById('toggle-filters');
        const advancedFilters = document.getElementById('advanced-filters');
        const filterIcon = document.getElementById('filter-icon');
        
        toggleFilters.addEventListener('click', function() {
            advancedFilters.classList.toggle('hidden');
            filterIcon.classList.toggle('rotate-180');
        });
        
        // Load search history
        loadSearchHistory();
        
        function loadSearchHistory() {
            fetch('{{ route("search.history") }}')
                .then(response => response.json())
                .then(data => {
                    const historyContainer = document.getElementById('search-history');
                    
                    if (data.length === 0) {
                        historyContainer.innerHTML = '<div class="text-gray-800 dark:text-gray-400 text-sm italic">No recent searches</div>';
                        return;
                    }
                    
                    historyContainer.innerHTML = '';
                    
                    data.forEach(item => {
                        const chip = document.createElement('div');
                        chip.className = 'px-3 py-1 bg-gray-200 dark:bg-gray-700 rounded-full text-sm flex items-center';
                        
                        let typeLabel = '';
                        switch(item.type) {
                            case 'students': typeLabel = '👨‍🎓'; break;
                            case 'staff': typeLabel = '👨‍🏫'; break;
                            case 'users': typeLabel = '👤'; break;
                            default: typeLabel = '🔍';
                        }
                        
                        chip.innerHTML = `
                            <a href="{{ route('search.submit') }}?query=${encodeURIComponent(item.query)}&type=${item.type}" class="flex items-center">
                                <span class="mr-1">${typeLabel}</span>
                                <span>${item.query}</span>
                            </a>
                            <button class="ml-2 text-gray-800 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200" onclick="removeSearchItem('${item.query}', '${item.type}')">
                                <i class="fas fa-times"></i>
                            </button>
                        `;
                        
                        historyContainer.appendChild(chip);
                    });
                    
                    // Add clear all button
                    const clearAllBtn = document.createElement('button');
                    clearAllBtn.className = 'px-3 py-1 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-full text-sm flex items-center hover:bg-red-200 dark:hover:bg-red-800/30';
                    clearAllBtn.innerHTML = '<i class="fas fa-trash-alt mr-1"></i> Clear All';
                    clearAllBtn.addEventListener('click', clearAllSearchHistory);
                    historyContainer.appendChild(clearAllBtn);
                })
                .catch(error => {
                    console.error('Error loading search history:', error);
                });
        }
        
        window.removeSearchItem = function(query, type) {
            fetch('{{ route("search.history.delete", ["id" => "temp"]) }}'.replace('temp', encodeURIComponent(query)), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ type: type })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadSearchHistory();
                }
            })
            .catch(error => {
                console.error('Error removing search item:', error);
            });
        };
        
        window.clearAllSearchHistory = function() {
            fetch('{{ route("search.history.clear") }}', {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    loadSearchHistory();
                }
            })
            .catch(error => {
                console.error('Error clearing search history:', error);
            });
        };
    });
</script>
@endpush