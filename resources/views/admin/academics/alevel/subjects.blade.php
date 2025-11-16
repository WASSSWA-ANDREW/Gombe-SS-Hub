@extends('layouts.admin')

@section('title', 'A\'Level Subjects')

@section('header', 'A\'Level Subjects Management')

@section('content')
<div class="space-y-6">
    <!-- Arts Subjects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Arts Subjects</h3>
            <button onclick="openArtsModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Add Subject
            </button>
        </div>
        
        @if($artsSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject Name</th>
                            <th class="px-4 py-2 text-left">Subject Code</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Classes</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($artsSubjects as $subject)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $subject->subject_name }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $subject->subject_code ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-2 py-1 rounded text-xs capitalize">{{ $subject->category }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($subject->classes)
                                        {{ implode(', ', $subject->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editArtsSubject({{ $subject->id }}, '{{ $subject->subject_name }}', '{{ $subject->subject_code }}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.alevel.subjects.arts.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                <p class="text-gray-800 dark:text-gray-400">No arts subjects found</p>
            </div>
        @endif
    </div>

    <!-- Science Subjects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Science Subjects</h3>
            <button onclick="openScienceModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Add Subject
            </button>
        </div>

        @if($scienceSubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject Name</th>
                            <th class="px-4 py-2 text-left">Subject Code</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Classes</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($scienceSubjects as $subject)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $subject->subject_name }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $subject->subject_code ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200 px-2 py-1 rounded text-xs capitalize">{{ $subject->category }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($subject->classes)
                                        {{ implode(', ', $subject->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editScienceSubject({{ $subject->id }}, '{{ $subject->subject_name }}', '{{ $subject->subject_code }}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.alevel.subjects.science.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                <p class="text-gray-800 dark:text-gray-400">No science subjects found</p>
            </div>
        @endif
    </div>

    <!-- Subsidiary Subjects -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Subsidiary Subjects</h3>
            <button onclick="openSubsidiaryModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Add Subject
            </button>
        </div>

        @if($subsidiarySubjects->count() > 0)
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100 dark:bg-gray-700">
                        <tr>
                            <th class="px-4 py-2 text-left">Subject Name</th>
                            <th class="px-4 py-2 text-left">Subject Code</th>
                            <th class="px-4 py-2 text-left">Stream</th>
                            <th class="px-4 py-2 text-left">Classes</th>
                            <th class="px-4 py-2 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subsidiarySubjects as $subject)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $subject->subject_name }}</td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">{{ $subject->subject_code ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded text-xs capitalize">{{ $subject->stream }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($subject->classes)
                                        {{ implode(', ', $subject->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editSubsidiarySubject({{ $subject->id }}, '{{ $subject->subject_name }}', '{{ $subject->subject_code }}', '{{ $subject->stream }}')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.alevel.subjects.subsidiary.destroy', $subject->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this subject?');">
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
                <p class="text-gray-800 dark:text-gray-400">No subsidiary subjects found</p>
            </div>
        @endif
    </div>
</div>

<!-- Arts Subject Modal -->
<div id="artsSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="artsModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Arts Subject</h3>
            <button onclick="closeArtsModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="artsSubjectForm" method="POST" action="{{ route('admin.academics.alevel.subjects.arts.store') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="artsSubjectId" name="subject_id">
            
            <div class="mb-4">
                <label for="artsSubjectName" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Name</label>
                <input type="text" id="artsSubjectName" name="subject_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="artsSubjectCode" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Code</label>
                <input type="text" id="artsSubjectCode" name="subject_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeArtsModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Subject</button>
            </div>
        </form>
    </div>
</div>

<!-- Science Subject Modal -->
<div id="scienceSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="scienceModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Science Subject</h3>
            <button onclick="closeScienceModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="scienceSubjectForm" method="POST" action="{{ route('admin.academics.alevel.subjects.science.store') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="scienceSubjectId" name="subject_id">
            
            <div class="mb-4">
                <label for="scienceSubjectName" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Name</label>
                <input type="text" id="scienceSubjectName" name="subject_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="scienceSubjectCode" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Code</label>
                <input type="text" id="scienceSubjectCode" name="subject_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeScienceModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Subject</button>
            </div>
        </form>
    </div>
</div>

<!-- Subsidiary Subject Modal -->
<div id="subsidiarySubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="subsidiaryModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Add Subsidiary Subject</h3>
            <button onclick="closeSubsidiaryModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="subsidiarySubjectForm" method="POST" action="{{ route('admin.academics.alevel.subjects.subsidiary.store') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="subsidiarySubjectId" name="subject_id">
            
            <div class="mb-4">
                <label for="subsidiarySubjectName" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Name</label>
                <input type="text" id="subsidiarySubjectName" name="subject_name" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="subsidiarySubjectCode" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject Code</label>
                <input type="text" id="subsidiarySubjectCode" name="subject_code" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
            </div>
            
            <div class="mb-4">
                <label for="subsidiaryStream" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Stream</label>
                <select id="subsidiaryStream" name="stream" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                    <option value="">Select Stream</option>
                    <option value="arts">Arts</option>
                    <option value="science">Science</option>
                </select>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeSubsidiaryModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Subject</button>
            </div>
        </form>
    </div>
</div>

<script>
function openArtsModal() {
    document.getElementById('artsSubjectModal').classList.remove('hidden');
    document.getElementById('artsModalTitle').textContent = 'Add Arts Subject';
    document.getElementById('artsSubjectForm').action = '{{ route("admin.academics.alevel.subjects.arts.store") }}';
    document.getElementById('artsSubjectForm').method = 'POST';
    document.getElementById('artsSubjectForm').querySelector('[name="_method"]').value = 'POST';
    document.getElementById('artsSubjectId').value = '';
    document.getElementById('artsSubjectName').value = '';
    document.getElementById('artsSubjectCode').value = '';
}

function closeArtsModal() {
    document.getElementById('artsSubjectModal').classList.add('hidden');
}

function editArtsSubject(id, name, code) {
    document.getElementById('artsSubjectModal').classList.remove('hidden');
    document.getElementById('artsModalTitle').textContent = 'Edit Arts Subject';
    document.getElementById('artsSubjectForm').action = '{{ route("admin.academics.alevel.subjects.arts.update", ":id") }}'.replace(':id', id);
    document.getElementById('artsSubjectForm').method = 'POST';
    document.getElementById('artsSubjectForm').querySelector('[name="_method"]').value = 'PUT';
    document.getElementById('artsSubjectId').value = id;
    document.getElementById('artsSubjectName').value = name;
    document.getElementById('artsSubjectCode').value = code;
}

function openScienceModal() {
    document.getElementById('scienceSubjectModal').classList.remove('hidden');
    document.getElementById('scienceModalTitle').textContent = 'Add Science Subject';
    document.getElementById('scienceSubjectForm').action = '{{ route("admin.academics.alevel.subjects.science.store") }}';
    document.getElementById('scienceSubjectForm').method = 'POST';
    document.getElementById('scienceSubjectForm').querySelector('[name="_method"]').value = 'POST';
    document.getElementById('scienceSubjectId').value = '';
    document.getElementById('scienceSubjectName').value = '';
    document.getElementById('scienceSubjectCode').value = '';
}

function closeScienceModal() {
    document.getElementById('scienceSubjectModal').classList.add('hidden');
}

function editScienceSubject(id, name, code) {
    document.getElementById('scienceSubjectModal').classList.remove('hidden');
    document.getElementById('scienceModalTitle').textContent = 'Edit Science Subject';
    document.getElementById('scienceSubjectForm').action = '{{ route("admin.academics.alevel.subjects.science.update", ":id") }}'.replace(':id', id);
    document.getElementById('scienceSubjectForm').method = 'POST';
    document.getElementById('scienceSubjectForm').querySelector('[name="_method"]').value = 'PUT';
    document.getElementById('scienceSubjectId').value = id;
    document.getElementById('scienceSubjectName').value = name;
    document.getElementById('scienceSubjectCode').value = code;
}

function openSubsidiaryModal() {
    document.getElementById('subsidiarySubjectModal').classList.remove('hidden');
    document.getElementById('subsidiaryModalTitle').textContent = 'Add Subsidiary Subject';
    document.getElementById('subsidiarySubjectForm').action = '{{ route("admin.academics.alevel.subjects.subsidiary.store") }}';
    document.getElementById('subsidiarySubjectForm').method = 'POST';
    document.getElementById('subsidiarySubjectForm').querySelector('[name="_method"]').value = 'POST';
    document.getElementById('subsidiarySubjectId').value = '';
    document.getElementById('subsidiarySubjectName').value = '';
    document.getElementById('subsidiarySubjectCode').value = '';
    document.getElementById('subsidiaryStream').value = '';
}

function closeSubsidiaryModal() {
    document.getElementById('subsidiarySubjectModal').classList.add('hidden');
}

function editSubsidiarySubject(id, name, code, stream) {
    document.getElementById('subsidiarySubjectModal').classList.remove('hidden');
    document.getElementById('subsidiaryModalTitle').textContent = 'Edit Subsidiary Subject';
    document.getElementById('subsidiarySubjectForm').action = '{{ route("admin.academics.alevel.subjects.subsidiary.update", ":id") }}'.replace(':id', id);
    document.getElementById('subsidiarySubjectForm').method = 'POST';
    document.getElementById('subsidiarySubjectForm').querySelector('[name="_method"]').value = 'PUT';
    document.getElementById('subsidiarySubjectId').value = id;
    document.getElementById('subsidiarySubjectName').value = name;
    document.getElementById('subsidiarySubjectCode').value = code;
    document.getElementById('subsidiaryStream').value = stream;
}
</script>
@endsection
