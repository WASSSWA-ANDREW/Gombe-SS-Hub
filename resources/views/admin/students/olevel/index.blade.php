@extends('layouts.admin')

@section('title', 'O\'Level Students List')
@section('header', 'O\'Level Students')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200"><i class="fas fa-graduation-cap mr-2"></i>O'Level Student List</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.students.olevel.export.excel') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        <i class="fas fa-file-excel mr-2"></i>Export to Excel
                    </a>
                    <a href="{{ route('admin.students.olevel.export.pdf') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        <i class="fas fa-file-pdf mr-2"></i>Export to PDF
                    </a>
                    <a href="{{ route('admin.students.olevel.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        <i class="fas fa-user-plus mr-2"></i>Add New O'Level Student
                    </a>
                </div>
            </div>
            
            <div id="selection-actions" class="mb-4 hidden">
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg flex items-center justify-between">
                    <div>
                        <span id="selected-count" class="font-semibold text-gray-700 dark:text-white">0</span> 
                        <span class="text-gray-800 dark:text-gray-300">students selected</span>
                    </div>
                    <div class="flex space-x-2">
                        <button id="select-all-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-check-square mr-1"></i>Select All</button>
                        <button id="deselect-all-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-times-circle mr-1"></i>Deselect All</button>
                        <button id="export-selected-btn" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-download mr-1"></i>Export Selected</button>
                        <button id="delete-selected-btn" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"><i class="fas fa-trash mr-1"></i>Delete Selected</button>
                    </div>
                </div>
            </div>
            
            <div class="mb-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                        <i class="fas fa-search h-5 w-5 text-gray-800 dark:text-gray-300 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <form action="{{ route('admin.students.olevel.search') }}" method="GET" class="flex">
                        <input type="text" name="query" id="studentSearchInput"
                            class="w-full px-4 py-3 pl-10 pr-4 rounded-lg border border-gray-300
                            shadow-sm transition-all duration-300
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            hover:border-indigo-400 hover:shadow-md
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white
                            dark:hover:border-indigo-500 dark:hover:shadow-indigo-700/30
                            bg-white dark:bg-gray-700"
                            placeholder="Search students by name, LIN, previous school..."
                            value="{{ $query ?? '' }}"
                            autocomplete="off">
                        <button type="submit" class="ml-2 bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                            <i class="fas fa-search mr-2"></i>Search
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

            @if (isset($students) && $students->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th scope="col" class="px-3 py-3 text-center text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    <input type="checkbox" id="select-all-checkbox" class="form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Gender
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    LIN
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Previous School
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($students as $student)
                                <tr class="student-row">
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="selected_students[]" value="{{ $student->id }}" class="student-checkbox form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        <a href="{{ route('admin.students.olevel.view.pdf', $student->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 hover:underline" target="_blank">
                                            {{ $student->student_name ?? 'N/A' }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $student->gender ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $student->learners_lin ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $student->previous_school ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.students.olevel.show', $student->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 mr-3" title="View student details"><i class="fas fa-eye mr-1"></i>View</a>
                                        <a href="{{ route('admin.students.olevel.edit', $student->id) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 mr-3" title="Edit student information"><i class="fas fa-edit mr-1"></i>Edit</a>
                                        <form action="{{ route('admin.students.olevel.destroy', $student->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200" title="Delete student record"><i class="fas fa-trash mr-1"></i>Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{-- $students->links() --}} {{-- Uncomment if using pagination --}}
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-300 mt-4">No O'Level students found.</p>
                <p class="text-gray-800 dark:text-gray-400 text-sm">
                    You can add a new O'Level student by clicking the "Add New O'Level Student" button above.
                    Ensure your Student model and database are set up correctly if you expect to see data.
                </p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Search functionality
        const searchInput = document.getElementById('studentSearchInput');
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
            const tableRows = document.querySelectorAll('tbody tr.student-row');
            
            // Show/hide search info based on input content
            if (searchTerm.length > 0) {
                searchInfo.classList.add('opacity-0');
            } else {
                searchInfo.classList.remove('opacity-0');
            }
            
            tableRows.forEach(row => {
                const name = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                const gender = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
                const lin = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
                const prevSchool = row.querySelector('td:nth-child(5)').textContent.toLowerCase();
                
                if (name.includes(searchTerm) || 
                    gender.includes(searchTerm) || 
                    lin.includes(searchTerm) || 
                    prevSchool.includes(searchTerm)) {
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
                    <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300 text-center">
                        No students found matching "${searchTerm}".
                    </td>
                `;
                tbody.appendChild(newRow);
            } else if (visibleRows.length > 0 && noResultsRow) {
                noResultsRow.remove();
            }
        });
        
        // Selection functionality
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');
        const selectionActions = document.getElementById('selection-actions');
        const selectedCountElement = document.getElementById('selected-count');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const exportSelectedBtn = document.getElementById('export-selected-btn');
        
        // Function to update the selected count and visibility of selection actions
        function updateSelectionStatus() {
            const checkedBoxes = document.querySelectorAll('.student-checkbox:checked');
            const count = checkedBoxes.length;
            
            selectedCountElement.textContent = count;
            
            if (count > 0) {
                selectionActions.classList.remove('hidden');
            } else {
                selectionActions.classList.add('hidden');
            }
            
            // Update the "select all" checkbox state
            if (count === studentCheckboxes.length) {
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
        studentCheckboxes.forEach(checkbox => {
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
            document.querySelectorAll('tbody tr.student-row:not([style*="display: none"]) .student-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateSelectionStatus();
        });
        
        // Select all button
        selectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('tbody tr.student-row:not([style*="display: none"]) .student-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectionStatus();
        });
        
        // Deselect all button
        deselectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectionStatus();
        });
        
        // Export selected button
        exportSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one student to export.');
                return;
            }
            
            // Ask for export format
            const format = confirm('Click OK to export as Excel, or Cancel to export as PDF');
            
            // Create a form to submit the selected IDs
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.students.olevel.export.selected") }}';
            form.style.display = 'none';
            
            // Add CSRF token
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            // Add format
            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format ? 'excel' : 'pdf';
            form.appendChild(formatInput);
            
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
        const deleteSelectedBtn = document.getElementById('delete-selected-btn');
        deleteSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.student-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one student to delete.');
                return;
            }
            
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected student(s)? This action cannot be undone.`)) {
                return;
            }
            
            // Create a form to submit the selected IDs
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.students.olevel.delete.selected") }}';
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