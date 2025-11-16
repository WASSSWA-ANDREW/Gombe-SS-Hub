@extends('layouts.admin')

@section('title', 'Staff List')
@section('header', 'Staff Members')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700 dark:text-white">Staff Directory</h2>
        <div>
            <a href="{{ route('admin.staff.export.excel') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm mr-2">
                Export to Excel
            </a>
            <a href="{{ route('admin.staff.export.pdf') }}" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm mr-2">
                Export to PDF
            </a>
            <a href="{{ route('admin.staff.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm">
                Add New Staff
            </a>
        </div>
    </div>
    
    <div id="selection-actions" class="mb-4 hidden">
        <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg flex items-center justify-between">
            <div>
                <span id="selected-count" class="font-semibold text-gray-700 dark:text-white">0</span> 
                <span class="text-gray-800 dark:text-gray-300">staff members selected</span>
            </div>
            <div class="flex space-x-2">
                <button id="select-all-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Select All</button>
                <button id="deselect-all-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">Deselect All</button>
                <button id="export-selected-btn" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Export Selected</button>
                <button id="delete-selected-btn" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete Selected</button>
            </div>
        </div>
    </div>
    
    <div class="mb-6">
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                <i class="fas fa-search h-5 w-5 text-gray-800 dark:text-gray-300 group-hover:scale-110 transition-transform"></i>
            </div>
            <form action="{{ route('admin.staff.search') }}" method="GET" class="flex">
                <input type="text" name="query" id="staffSearchInput"
                    class="w-full px-4 py-3 pl-10 pr-4 rounded-lg border border-gray-300
                    shadow-sm transition-all duration-300
                    focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                    hover:border-indigo-400 hover:shadow-md
                    dark:bg-gray-700 dark:border-gray-600 dark:text-white
                    dark:hover:border-indigo-500 dark:hover:shadow-indigo-700/30
                    bg-white dark:bg-gray-700"
                    placeholder="Search staff by name, email..."
                    value="{{ $query ?? '' }}"
                    autocomplete="off">
                <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                    Search
                </button>
            </form>
        </div>
    </div>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <strong class="font-bold">Success!</strong>
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                        <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                        Full Name
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                        Email
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                        Date of Issue (Joined)
                    </th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                        Actions
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($staffMembers as $staff)
                    <tr class="staff-row">
                        <td class="px-3 py-4 whitespace-nowrap text-center">
                            <input type="checkbox" name="selected_staff[]" value="{{ $staff->id }}" class="staff-checkbox form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('admin.staff.view.pdf', $staff) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 hover:underline" target="_blank">
                                {{ $staff->surname }}, {{ $staff->first_name }} {{ $staff->other_name }}
                            </a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                            {{ $staff->email ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                            {{ $staff->created_at->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('admin.staff.show', $staff) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 mr-3">View</a>
                            <a href="{{ route('admin.staff.edit', $staff) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3">Edit</a>
                            <a href="{{ route('admin.staff.export.form_pdf', $staff) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-3">Export Form PDF</a>
                            <form action="{{ route('admin.staff.destroy', $staff) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this staff member?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300 text-center">
                            No staff members found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-4">
        {{-- $staffMembers->links() --}} {{-- Add pagination if many staff members --}}
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('staffSearchInput');
        const searchInfo = searchInput.parentElement.querySelector('.search-info');
        
        // Show search hint when input is focused
        searchInput.addEventListener('focus', function() {
            searchInfo.classList.remove('hidden');
            searchInfo.classList.add('opacity-100');
        });
        
        // Hide search hint when input is blurred and empty
        searchInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                searchInfo.classList.add('hidden');
            }
        });
        
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const tableRows = document.querySelectorAll('tbody tr.staff-row');
            
            // Show/hide search info based on input content
            if (searchTerm.length > 0) {
                searchInfo.classList.add('opacity-0');
            } else {
                searchInfo.classList.remove('opacity-0');
            }
            
            tableRows.forEach(row => {
                const fullName = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const email = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                
                if (fullName.includes(searchTerm) || email.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
            
            // Show "no results" message if all rows are hidden
            const visibleRows = Array.from(tableRows).filter(row => row.style.display !== 'none');
            const noResultsRow = document.getElementById('no-search-results');
            
            if (visibleRows.length === 0 && !noResultsRow) {
                const tbody = document.querySelector('tbody');
                const newRow = document.createElement('tr');
                newRow.id = 'no-search-results';
                newRow.innerHTML = `
                    <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300 text-center">
                        No staff members found matching "${searchTerm}".
                    </td>
                `;
                tbody.appendChild(newRow);
            } else if (visibleRows.length > 0 && noResultsRow) {
                noResultsRow.remove();
            }
        });
        
        // Selection functionality
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const staffCheckboxes = document.querySelectorAll('.staff-checkbox');
        const selectionActions = document.getElementById('selection-actions');
        const selectedCountElement = document.getElementById('selected-count');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const exportSelectedBtn = document.getElementById('export-selected-btn');
        const deleteSelectedBtn = document.getElementById('delete-selected-btn');
        
        // Function to update the selected count and visibility of selection actions
        function updateSelectionStatus() {
            const checkedBoxes = document.querySelectorAll('.staff-checkbox:checked');
            const count = checkedBoxes.length;
            
            selectedCountElement.textContent = count;
            
            if (count > 0) {
                selectionActions.classList.remove('hidden');
            } else {
                selectionActions.classList.add('hidden');
            }
            
            // Update the "select all" checkbox state
            if (count === staffCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
        
        // Add event listeners to individual checkboxes
        staffCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionStatus);
            
            // Make the entire row clickable to select the checkbox
            const row = checkbox.closest('tr');
            row.addEventListener('click', function(e) {
                // Don't toggle if clicking on a link or button or the checkbox itself
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') {
                    return;
                }
                
                checkbox.checked = !checkbox.checked;
                updateSelectionStatus();
            });
        });
        
        // Select all checkbox functionality
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            // Only select visible rows
            document.querySelectorAll('tbody tr.staff-row:not([style*="display: none"]) .staff-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateSelectionStatus();
        });
        
        // Select all button
        selectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('tbody tr.staff-row:not([style*="display: none"]) .staff-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectionStatus();
        });
        
        // Deselect all button
        deselectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.staff-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectionStatus();
        });
        
        // Export selected button
        exportSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.staff-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one staff member to export.');
                return;
            }
            
            // Create a form to submit the selected IDs
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.staff.export.selected") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add selected IDs
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        });
        
        // Delete selected button
        deleteSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.staff-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one staff member to delete.');
                return;
            }
            
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} staff member(s)? This action cannot be undone.`)) {
                return;
            }
            
            // Create a form to submit the selected IDs
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.staff.delete.selected") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add selected IDs
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            // Add to document and submit
            document.body.appendChild(form);
            form.submit();
        });
        
        // Initialize selection status
        updateSelectionStatus();
    });
</script>
@endpush

@endsection