@extends('layouts.admin')

@section('title', 'Teacher Subject Assignments')

@section('header', 'Teacher Subject Assignments')

@section('content')
<div class="space-y-6">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Teacher Subject Assignments</h3>
            <button onclick="openAssignModal()" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-150 ease-in-out">
                <i class="fas fa-plus"></i> Assign Subject
            </button>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-100 dark:bg-gray-700">
                    <tr>
                        <th class="px-4 py-2 text-left">Teacher Name</th>
                        <th class="px-4 py-2 text-left">Level</th>
                        <th class="px-4 py-2 text-left">Subject</th>
                        <th class="px-4 py-2 text-left">Specialty</th>
                        <th class="px-4 py-2 text-left">Classes</th>
                        <th class="px-4 py-2 text-left">Streams</th>
                        <th class="px-4 py-2 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @if($teacherSubjects->count() > 0)
                        @foreach($teacherSubjects as $assignment)
                            <tr class="border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-4 py-3 text-gray-900 dark:text-white">{{ $assignment->staff->getDisplayName() ?? '-' }}</td>
                                <td class="px-4 py-3">
                                    <span class="bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-2 py-1 rounded text-xs uppercase">{{ $assignment->level }}</span>
                                </td>
                                <td class="px-4 py-3 text-gray-900 dark:text-white">
                                    @if($assignment->olevelSubject)
                                        {{ $assignment->olevelSubject->subject_name }}
                                    @elseif($assignment->alevelSubject)
                                        {{ $assignment->alevelSubject->subject_name }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    @if($assignment->specialty)
                                        <span class="bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 px-2 py-1 rounded text-xs capitalize">{{ $assignment->specialty }}</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($assignment->classes)
                                        {{ implode(', ', $assignment->classes) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-gray-800 dark:text-gray-400">
                                    @if($assignment->streams)
                                        {{ implode(', ', $assignment->streams) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button onclick="editAssignment({{ $assignment->id }})" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <form action="{{ route('admin.academics.teachers.destroy', $assignment->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this assignment?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded text-xs transition">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-800 dark:text-gray-400">
                                No teacher subject assignments found
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Assign Subject Modal -->
<div id="assignSubjectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-100 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-md bg-white dark:bg-gray-800">
        <div class="flex justify-between items-center mb-4">
            <h3 id="assignModalTitle" class="text-lg font-semibold text-gray-900 dark:text-white">Assign Subject to Teacher</h3>
            <button onclick="closeAssignModal()" class="text-gray-800 hover:text-gray-800">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        <form id="assignSubjectForm" method="POST" action="{{ route('admin.academics.teachers.assign') }}">
            @csrf
            @method('POST')
            <input type="hidden" id="assignmentId" name="assignment_id">
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="teacherId" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Teacher</label>
                    <select id="teacherId" name="staff_id" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Teacher</option>
                        @foreach($teachers as $teacher)
                            <option value="{{ $teacher->id }}">{{ $teacher->getDisplayName() }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label for="level" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Level</label>
                    <select id="level" name="level" required onchange="updateSubjectDropdown()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Level</option>
                        <option value="olevel">O'Level</option>
                        <option value="alevel">A'Level</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="subjectId" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Subject</label>
                    <select id="subjectId" name="olevel_subject_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Subject</option>
                    </select>
                </div>
                
                <div>
                    <label for="specialty" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Specialty (A'Level)</label>
                    <select id="specialty" name="specialty" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">None</option>
                        <option value="arts">Arts</option>
                        <option value="science">Science</option>
                    </select>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="classes" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Classes (comma-separated)</label>
                    <input type="text" id="classes" name="classes" placeholder="e.g., S1, S2, S3" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                </div>
                <div>
                    <label for="streams" class="block text-gray-700 dark:text-gray-300 font-semibold mb-2">Streams (comma-separated)</label>
                    <input type="text" id="streams" name="streams" placeholder="e.g., Arts, Science" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:border-blue-500">
                </div>
            </div>
            
            <div class="flex justify-end gap-2">
                <button type="button" onclick="closeAssignModal()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Assign Subject</button>
            </div>
        </form>
    </div>
</div>

<script>
const olevelSubjects = {!! json_encode($olevelSubjects->map(fn($s) => ['id' => $s->id, 'name' => $s->subject_name, 'category' => $s->category])->groupBy('category')) !!};
const alevelSubjects = {!! json_encode($alevelSubjects->map(fn($s) => ['id' => $s->id, 'name' => $s->subject_name, 'stream' => $s->stream, 'category' => $s->category])->groupBy('stream')) !!};

function openAssignModal() {
    document.getElementById('assignSubjectModal').classList.remove('hidden');
    document.getElementById('assignModalTitle').textContent = 'Assign Subject to Teacher';
    document.getElementById('assignSubjectForm').action = '{{ route("admin.academics.teachers.assign") }}';
    document.getElementById('assignSubjectForm').method = 'POST';
    document.getElementById('assignmentId').value = '';
    document.getElementById('teacherId').value = '';
    document.getElementById('level').value = '';
    document.getElementById('subjectId').value = '';
    document.getElementById('specialty').value = '';
    document.getElementById('classes').value = '';
    document.getElementById('streams').value = '';
    updateSubjectDropdown();
}

function closeAssignModal() {
    document.getElementById('assignSubjectModal').classList.add('hidden');
}

function updateSubjectDropdown() {
    const level = document.getElementById('level').value;
    const subjectSelect = document.getElementById('subjectId');
    const subjectName = document.querySelector('label[for="subjectId"]');
    
    subjectSelect.innerHTML = '<option value="">Select Subject</option>';
    
    if (level === 'olevel') {
        subjectName.textContent = 'O\'Level Subject';
        const allOlevel = Object.values(olevelSubjects).flat();
        allOlevel.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = `${subject.name} (${subject.category})`;
            subjectSelect.appendChild(option);
        });
        subjectSelect.name = 'olevel_subject_id';
        document.getElementById('alevel_subject_id').remove?.();
    } else if (level === 'alevel') {
        subjectName.textContent = 'A\'Level Subject';
        const allAlevel = Object.values(alevelSubjects).flat();
        allAlevel.forEach(subject => {
            const option = document.createElement('option');
            option.value = subject.id;
            option.textContent = `${subject.name} (${subject.stream})`;
            subjectSelect.appendChild(option);
        });
        subjectSelect.name = 'alevel_subject_id';
    }
}

function editAssignment(assignmentId) {
    fetch(`{{ route('admin.academics.teachers.edit', ':id') }}`.replace(':id', assignmentId))
        .then(response => response.json())
        .then(data => {
            document.getElementById('assignSubjectModal').classList.remove('hidden');
            document.getElementById('assignModalTitle').textContent = 'Edit Subject Assignment';
            document.getElementById('assignSubjectForm').action = `{{ route('admin.academics.teachers.update', ':id') }}`.replace(':id', assignmentId);
            document.getElementById('assignSubjectForm').method = 'POST';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PUT';
            document.getElementById('assignSubjectForm').appendChild(methodInput);
            
            document.getElementById('assignmentId').value = data.id;
            document.getElementById('teacherId').value = data.staff_id;
            document.getElementById('level').value = data.level;
            document.getElementById('specialty').value = data.specialty || '';
            
            const classesArray = Array.isArray(data.classes) ? data.classes : (data.classes ? Object.values(data.classes) : []);
            document.getElementById('classes').value = classesArray.join(', ');
            
            const streamsArray = Array.isArray(data.streams) ? data.streams : (data.streams ? Object.values(data.streams) : []);
            document.getElementById('streams').value = streamsArray.join(', ');
            
            updateSubjectDropdown();
            
            setTimeout(() => {
                if (data.level === 'olevel' && data.olevel_subject_id) {
                    document.getElementById('subjectId').value = data.olevel_subject_id;
                } else if (data.level === 'alevel' && data.alevel_subject_id) {
                    document.getElementById('subjectId').value = data.alevel_subject_id;
                }
            }, 100);
        })
        .catch(error => console.error('Error:', error));
}
</script>
@endsection
