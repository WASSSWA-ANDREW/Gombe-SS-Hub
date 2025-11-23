@extends('layouts.admin')

@section('title', 'Alumni List')
@section('header', 'Alumni Management')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
        <div class="px-6 py-4">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-2xl font-semibold text-gray-700 dark:text-gray-200">Alumni List</h2>
                <div class="flex space-x-2">
                    <a href="{{ route('admin.alumni.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        <i class="fas fa-plus mr-2"></i>Add Alumni
                    </a>
                    <a href="{{ route('admin.alumni.export.excel') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Export to Excel
                    </a>
                    <a href="{{ route('admin.alumni.export.pdf') }}" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                        Export to PDF
                    </a>
                </div>
            </div>

            <div id="selection-actions" class="mb-4 hidden">
                <div class="bg-gray-100 dark:bg-gray-700 p-3 rounded-lg flex items-center justify-between">
                    <div>
                        <span id="selected-count" class="font-semibold text-gray-700 dark:text-white">0</span> 
                        <span class="text-gray-800 dark:text-gray-300">alumni selected</span>
                    </div>
                    <div class="flex space-x-2">
                        <button id="select-all-btn" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Select All</button>
                        <button id="deselect-all-btn" class="bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded text-sm">Deselect All</button>
                        <button id="export-selected-btn" class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-sm">Export Selected</button>
                        <button id="delete-selected-btn" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">Delete Selected</button>
                    </div>
                </div>
            </div>

            <div class="mb-4">
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none transition-all duration-300 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">
                        <i class="fas fa-search h-5 w-5 text-gray-800 dark:text-gray-300 group-hover:scale-110 transition-transform"></i>
                    </div>
                    <form action="{{ route('admin.alumni.search') }}" method="GET" class="flex">
                        <input type="text" name="query" id="alumniSearchInput"
                            class="w-full px-4 py-3 pl-10 pr-4 rounded-lg border border-gray-300
                            shadow-sm transition-all duration-300
                            focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500
                            hover:border-indigo-400 hover:shadow-md
                            dark:bg-gray-700 dark:border-gray-600 dark:text-white
                            dark:hover:border-indigo-500 dark:hover:shadow-indigo-700/30
                            bg-white dark:bg-gray-700"
                            placeholder="Search alumni by name, LIN, NIN, graduation class..."
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

            @if (isset($alumni) && $alumni->count() > 0)
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
                                    Graduation Class
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Graduation Year
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-800 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach ($alumni as $alumnus)
                                <tr class="alumni-row hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                                    <td class="px-3 py-4 whitespace-nowrap text-center">
                                        <input type="checkbox" name="selected_alumni[]" value="{{ $alumnus->id }}" class="alumni-checkbox form-checkbox h-5 w-5 text-indigo-600 rounded border-gray-300 focus:ring-indigo-500 dark:border-gray-600 dark:bg-gray-700">
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $alumnus->student_name ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $alumnus->gender ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $alumnus->learners_lin ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        <span class="px-2 py-1 rounded text-sm font-semibold {{ $alumnus->graduation_class === 'S4' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ $alumnus->graduation_class ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-800 dark:text-gray-300">
                                        {{ $alumnus->graduation_year ?? 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('admin.alumni.show', $alumnus->id) }}" class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 mr-2">View</a>
                                        <a href="{{ route('admin.alumni.edit', $alumnus->id) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-200 mr-2">Edit</a>
                                        <form method="POST" action="{{ route('admin.alumni.destroy', $alumnus->id) }}" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this alumni record?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $alumni->links() }}
                </div>
            @else
                <p class="text-gray-700 dark:text-gray-300 mt-4">No alumni records found.</p>
                <p class="text-gray-800 dark:text-gray-400 text-sm">
                    Alumni records are created when students graduate (S4 and S6) through the annual promotion process.
                </p>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllCheckbox = document.getElementById('select-all-checkbox');
        const alumniCheckboxes = document.querySelectorAll('.alumni-checkbox');
        const selectionActions = document.getElementById('selection-actions');
        const selectedCountElement = document.getElementById('selected-count');
        const selectAllBtn = document.getElementById('select-all-btn');
        const deselectAllBtn = document.getElementById('deselect-all-btn');
        const exportSelectedBtn = document.getElementById('export-selected-btn');
        const deleteSelectedBtn = document.getElementById('delete-selected-btn');

        function updateSelectionStatus() {
            const checkedBoxes = document.querySelectorAll('.alumni-checkbox:checked');
            const count = checkedBoxes.length;
            
            selectedCountElement.textContent = count;
            
            if (count > 0) {
                selectionActions.classList.remove('hidden');
            } else {
                selectionActions.classList.add('hidden');
            }
            
            if (count === alumniCheckboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else if (count === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.indeterminate = true;
            }
        }
        
        alumniCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectionStatus);
            
            const row = checkbox.closest('tr');
            row.addEventListener('click', function(e) {
                if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.tagName === 'INPUT') {
                    return;
                }
                
                checkbox.checked = !checkbox.checked;
                updateSelectionStatus();
            });
        });
        
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            document.querySelectorAll('tbody tr.alumni-row:not([style*="display: none"]) .alumni-checkbox').forEach(checkbox => {
                checkbox.checked = isChecked;
            });
            
            updateSelectionStatus();
        });
        
        selectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('tbody tr.alumni-row:not([style*="display: none"]) .alumni-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
            updateSelectionStatus();
        });
        
        deselectAllBtn.addEventListener('click', function() {
            document.querySelectorAll('.alumni-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelectionStatus();
        });
        
        exportSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.alumni-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one alumni to export.');
                return;
            }
            
            const format = confirm('Click OK to export as Excel, or Cancel to export as PDF');
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.alumni.export.selected") }}';
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            const formatInput = document.createElement('input');
            formatInput.type = 'hidden';
            formatInput.name = 'format';
            formatInput.value = format ? 'excel' : 'pdf';
            form.appendChild(formatInput);
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        });
        
        deleteSelectedBtn.addEventListener('click', function() {
            const selectedIds = Array.from(document.querySelectorAll('.alumni-checkbox:checked')).map(checkbox => checkbox.value);
            
            if (selectedIds.length === 0) {
                alert('Please select at least one alumni to delete.');
                return;
            }
            
            if (!confirm(`Are you sure you want to delete ${selectedIds.length} selected alumni record(s)? This action cannot be undone.`)) {
                return;
            }
            
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.alumni.delete.selected") }}';
            form.style.display = 'none';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);
            
            selectedIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'selected_ids[]';
                input.value = id;
                form.appendChild(input);
            });
            
            document.body.appendChild(form);
            form.submit();
        });
        
        updateSelectionStatus();
    });
</script>
@endpush

@endsection
