@extends('layouts.admin')

@section('title', 'O\'Level Subjects')

@section('header', 'O\'Level Subjects Management')

@section('content')
<div class="space-y-6">
    <!-- General Subjects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">General Subjects</h3>
            <button onclick="openGeneralModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Add Subject
            </button>
        </div>
        
        @if($generalSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject Name</th>
                            <th class="px-4 py-2 text-left">Subject Code</th>
                            <th class="px-4 py-2 text-left">Classes</th>
                            <th class="px-4 py-2 text-left">Practical</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($generalSubjects as $subject)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $subject->subject_name }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $subject->subject_code ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($subject->classes)
                                        {{ implode(', ', $subject->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($subject->requires_practical)
                                        <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs">Yes</span>
                                    @else
                                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded text-xs">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editGeneralSubject({{ $subject->id }}, '{{ $subject->subject_name }}', '{{ $subject->subject_code }}', {{ $subject->requires_practical ? 'true' : 'false' }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.olevel.subjects.general.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-800 text-4xl mb-4"></i>
                <p class="text-gray-800 dark:text-gray-400">No general subjects found</p>
            </div>
        @endif
    </div>

    <!-- Optional Subjects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Optional Subjects</h3>
            <button onclick="openOptionalModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Add Subject
            </button>
        </div>

        @if($optionalSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject Name</th>
                            <th class="px-4 py-2 text-left">Subject Code</th>
                            <th class="px-4 py-2 text-left">Classes</th>
                            <th class="px-4 py-2 text-left">Practical</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($optionalSubjects as $subject)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $subject->subject_name }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $subject->subject_code ?? '-' }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($subject->classes)
                                        {{ implode(', ', $subject->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($subject->requires_practical)
                                        <span class="bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 px-2 py-1 rounded text-xs">Yes</span>
                                    @else
                                        <span class="bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200 px-2 py-1 rounded text-xs">No</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editOptionalSubject({{ $subject->id }}, '{{ $subject->subject_name }}', '{{ $subject->subject_code }}', {{ $subject->requires_practical ? 'true' : 'false' }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.olevel.subjects.optional.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-8">
                <i class="fas fa-inbox text-gray-800 text-4xl mb-4"></i>
                <p class="text-gray-800 dark:text-gray-400">No optional subjects found</p>
            </div>
        @endif
    </div>
</div>

<!-- General Subject Modal -->
<div id="generalSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="generalModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add General Subject</h3>
            <button onclick="closeGeneralModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="generalSubjectForm" method="POST" action="{{ route('admin.academics.olevel.subjects.general.store') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="generalSubjectId" name="subject_id">
            
            <div class="mb-4">
                <label for="generalSubjectName" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Name</label>
                <input type="text" id="generalSubjectName" name="subject_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="generalSubjectCode" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Code</label>
                <input type="text" id="generalSubjectCode" name="subject_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="generalRequiresPractical" name="requires_practical" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                <label for="generalRequiresPractical" class="ml-2 text-gray-700 dark:text-gray-300">Requires Practical</label>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeGeneralModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Subject</button>
            </div>
        </form>
    </div>
</div>

<!-- Optional Subject Modal -->
<div id="optionalSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="optionalModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Optional Subject</h3>
            <button onclick="closeOptionalModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="optionalSubjectForm" method="POST" action="{{ route('admin.academics.olevel.subjects.optional.store') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="optionalSubjectId" name="subject_id">
            
            <div class="mb-4">
                <label for="optionalSubjectName" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Name</label>
                <input type="text" id="optionalSubjectName" name="subject_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="optionalSubjectCode" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Code</label>
                <input type="text" id="optionalSubjectCode" name="subject_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4 flex items-center">
                <input type="checkbox" id="optionalRequiresPractical" name="requires_practical" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded">
                <label for="optionalRequiresPractical" class="ml-2 text-gray-700 dark:text-gray-300">Requires Practical</label>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeOptionalModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Subject</button>
            </div>
        </form>
    </div>
</div>

<script>
function openGeneralModal() {
    document.getElementById('generalSubjectModal').classList.remove('hidden');
    document.getElementById('generalModalTitle').textContent = 'Add General Subject';
    document.getElementById('generalSubjectForm').action = '{{ route("admin.academics.olevel.subjects.general.store") }}';
    document.getElementById('generalSubjectForm').method = 'POST';
    document.getElementById('generalSubjectForm').querySelector('[name="_method"]').value = 'POST';
    document.getElementById('generalSubjectId').value = '';
    document.getElementById('generalSubjectName').value = '';
    document.getElementById('generalSubjectCode').value = '';
    document.getElementById('generalRequiresPractical').checked = false;
}

function closeGeneralModal() {
    document.getElementById('generalSubjectModal').classList.add('hidden');
}

function editGeneralSubject(id, name, code, requiresPractical) {
    document.getElementById('generalSubjectModal').classList.remove('hidden');
    document.getElementById('generalModalTitle').textContent = 'Edit General Subject';
    document.getElementById('generalSubjectForm').action = '{{ route("admin.academics.olevel.subjects.general.update", ":id") }}'.replace(':id', id);
    document.getElementById('generalSubjectForm').method = 'POST';
    document.getElementById('generalSubjectForm').querySelector('[name="_method"]').value = 'PUT';
    document.getElementById('generalSubjectId').value = id;
    document.getElementById('generalSubjectName').value = name;
    document.getElementById('generalSubjectCode').value = code;
    document.getElementById('generalRequiresPractical').checked = requiresPractical;
}

function openOptionalModal() {
    document.getElementById('optionalSubjectModal').classList.remove('hidden');
    document.getElementById('optionalModalTitle').textContent = 'Add Optional Subject';
    document.getElementById('optionalSubjectForm').action = '{{ route("admin.academics.olevel.subjects.optional.store") }}';
    document.getElementById('optionalSubjectForm').method = 'POST';
    document.getElementById('optionalSubjectForm').querySelector('[name="_method"]').value = 'POST';
    document.getElementById('optionalSubjectId').value = '';
    document.getElementById('optionalSubjectName').value = '';
    document.getElementById('optionalSubjectCode').value = '';
    document.getElementById('optionalRequiresPractical').checked = false;
}

function closeOptionalModal() {
    document.getElementById('optionalSubjectModal').classList.add('hidden');
}

function editOptionalSubject(id, name, code, requiresPractical) {
    document.getElementById('optionalSubjectModal').classList.remove('hidden');
    document.getElementById('optionalModalTitle').textContent = 'Edit Optional Subject';
    document.getElementById('optionalSubjectForm').action = '{{ route("admin.academics.olevel.subjects.optional.update", ":id") }}'.replace(':id', id);
    document.getElementById('optionalSubjectForm').method = 'POST';
    document.getElementById('optionalSubjectForm').querySelector('[name="_method"]').value = 'PUT';
    document.getElementById('optionalSubjectId').value = id;
    document.getElementById('optionalSubjectName').value = name;
    document.getElementById('optionalSubjectCode').value = code;
    document.getElementById('optionalRequiresPractical').checked = requiresPractical;
}
</script>
@endsection
