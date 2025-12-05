<x-admin-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __("Import O'Level Students Data") }}
            </h2>
            <a href="{{ route('admin.students.olevel.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> {{ __("Back to O'Level Students List") }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <strong class="font-bold">Error!</strong>
                            <span class="block sm:inline">{{ session('error') }}</span>
                        </div>
                    @endif

                    <div class="mb-6 p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-700">
                        <h3 class="font-bold text-lg mb-2">Instructions for Importing O'Level Students Data</h3>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>Prepare your Excel file with the following columns: student_name, gender, date_of_birth, etc.</li>
                            <li>Make sure the column headers match the field names in the system.</li>
                            <li>The first row should contain the column headers.</li>
                            <li>Required fields: student_name, gender, date_of_birth.</li>
                            <li>Health fields: medical_status (values: "Healthy" or "Medical care"), physical_health (values: "Fit" or "Disabled").</li>
                            <li>Date format should be YYYY-MM-DD (e.g., 2005-01-15).</li>
                            <li>Gender should be either "Male" or "Female".</li>
                            <li>Maximum file size: 10MB.</li>
                            <li>Supported formats: .xlsx, .xls, .csv</li>
                        </ul>
                        <div class="mt-3">
                            <a href="{{ route('admin.students.olevel.export.excel') }}" class="text-blue-600 hover:text-blue-800 underline">
                                <i class="fas fa-download mr-1"></i> Download sample template
                            </a>
                        </div>
                    </div>

                    <form action="{{ route('admin.students.olevel.import.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">Excel File</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <i class="fas fa-file-excel text-gray-800 text-3xl mb-2"></i>
                                    <div class="flex text-sm text-gray-800">
                                        <label for="file" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Upload a file</span>
                                            <input id="file" name="file" type="file" class="sr-only" accept=".xlsx,.xls,.csv">
                                        </label>
                                        <p class="pl-1">or drag and drop</p>
                                    </div>
                                    <p class="text-xs text-gray-800">
                                        XLSX, XLS, CSV up to 10MB
                                    </p>
                                </div>
                            </div>
                            <p id="selected-file" class="mt-2 text-sm text-gray-800"></p>
                            @error('file')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                <i class="fas fa-upload mr-2"></i> Import O'Level Students Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('file').addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name || 'No file selected';
            document.getElementById('selected-file').textContent = 'Selected file: ' + fileName;
        });
    </script>
</x-admin-layout>