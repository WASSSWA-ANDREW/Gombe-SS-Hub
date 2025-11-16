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
                                <tr>
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
                                        {{ $alumnus->graduation_class ?? 'N/A' }}
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
        // Search functionality
        const searchInput = document.getElementById('alumniSearchInput');

        // Auto-submit search on enter
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                this.closest('form').submit();
            }
        });
    });
</script>
@endpush
@endsection