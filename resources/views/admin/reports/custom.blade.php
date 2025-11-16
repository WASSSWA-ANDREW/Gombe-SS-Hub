@extends('layouts.admin')

@section('title', 'Custom Reports')

@section('content')
<div class="container px-4 sm:px-6 mx-auto grid">
    <h2 class="my-4 sm:my-6 text-xl sm:text-2xl font-semibold text-gray-700 dark:text-gray-200">
        Custom Reports
    </h2>
    
    <!-- Description -->
    <div class="px-3 sm:px-4 py-3 mb-6 sm:mb-8 bg-white rounded-lg shadow-md dark:bg-gray-800">
        <p class="text-gray-800 dark:text-gray-400">
            Create custom reports by selecting specific data fields, filters, and display options. Export your custom reports in various formats.
        </p>
    </div>

    <!-- Coming Soon Message -->
    <div class="flex p-4 mb-6 text-sm text-blue-800 border border-blue-300 rounded-lg bg-blue-50 dark:bg-gray-800 dark:text-blue-400 dark:border-blue-800" role="alert">
        <svg class="flex-shrink-0 inline w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
        </svg>
        <div>
            <span class="font-medium">Coming Soon!</span> Custom report builder is currently under development. Check back soon for the ability to create fully customized reports.
        </div>
    </div>

    <!-- Report Builder Form -->
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">Report Builder</h3>
        </div>
        <div class="p-4">
            <form>
                <!-- Data Source Selection -->
                <div class="mb-6">
                    <h4 class="mb-3 text-base font-medium text-gray-900 dark:text-white">Select Data Source</h4>
                    <div class="flex flex-wrap gap-4">
                        <div class="flex items-center">
                            <input id="data-students" type="radio" name="data-source" value="students" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="data-students" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Students</label>
                        </div>
                        <div class="flex items-center">
                            <input id="data-staff" type="radio" name="data-source" value="staff" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="data-staff" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Staff</label>
                        </div>
                        <div class="flex items-center">
                            <input id="data-classes" type="radio" name="data-source" value="classes" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="data-classes" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Classes</label>
                        </div>
                        <div class="flex items-center">
                            <input id="data-attendance" type="radio" name="data-source" value="attendance" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="data-attendance" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Attendance</label>
                        </div>
                    </div>
                </div>

                <!-- Field Selection -->
                <div class="mb-6">
                    <h4 class="mb-3 text-base font-medium text-gray-900 dark:text-white">Select Fields</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="flex items-center">
                            <input id="field-name" type="checkbox" value="name" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-name" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Name</label>
                        </div>
                        <div class="flex items-center">
                            <input id="field-gender" type="checkbox" value="gender" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-gender" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Gender</label>
                        </div>
                        <div class="flex items-center">
                            <input id="field-class" type="checkbox" value="class" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-class" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Class</label>
                        </div>
                        <div class="flex items-center">
                            <input id="field-age" type="checkbox" value="age" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-age" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Age</label>
                        </div>
                        <div class="flex items-center">
                            <input id="field-district" type="checkbox" value="district" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-district" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">District</label>
                        </div>
                        <div class="flex items-center">
                            <input id="field-religion" type="checkbox" value="religion" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                            <label for="field-religion" class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-300">Religion</label>
                        </div>
                    </div>
                </div>

                <!-- Filter Options -->
                <div class="mb-6">
                    <h4 class="mb-3 text-base font-medium text-gray-900 dark:text-white">Filter Options</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="filter-gender" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Gender</label>
                            <select id="filter-gender" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="">All</option>
                                <option value="male">Male</option>
                                <option value="female">Female</option>
                            </select>
                        </div>
                        <div>
                            <label for="filter-class" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Class</label>
                            <select id="filter-class" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="">All Classes</option>
                                <option value="s1">S1</option>
                                <option value="s2">S2</option>
                                <option value="s3">S3</option>
                                <option value="s4">S4</option>
                                <option value="s5">S5</option>
                                <option value="s6">S6</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Display Options -->
                <div class="mb-6">
                    <h4 class="mb-3 text-base font-medium text-gray-900 dark:text-white">Display Options</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="display-type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Display Type</label>
                            <select id="display-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="table">Table</option>
                                <option value="chart">Chart</option>
                                <option value="both">Both Table and Chart</option>
                            </select>
                        </div>
                        <div>
                            <label for="chart-type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Chart Type</label>
                            <select id="chart-type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="bar">Bar Chart</option>
                                <option value="pie">Pie Chart</option>
                                <option value="line">Line Chart</option>
                                <option value="doughnut">Doughnut Chart</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end">
                    <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
                        Generate Report
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Report Preview -->
    <h3 class="my-4 text-lg font-semibold text-gray-700 dark:text-gray-200">
        Report Preview
    </h3>
    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden mb-8">
        <div class="p-4">
            <div class="flex items-center justify-center h-64 bg-gray-100 dark:bg-gray-700 rounded-lg">
                <p class="text-gray-800 dark:text-gray-400">Your custom report will be displayed here</p>
            </div>
        </div>
    </div>

    <!-- Export Options -->
    <div class="flex flex-col sm:flex-row gap-4 mb-8">
        <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800">
            Export to PDF
        </button>
        <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700 focus:ring-4 focus:ring-green-300 dark:focus:ring-green-800">
            Export to Excel
        </button>
        <button type="button" class="px-4 py-2 text-sm font-medium text-white bg-purple-600 rounded-lg hover:bg-purple-700 focus:ring-4 focus:ring-purple-300 dark:focus:ring-purple-800">
            Save Report Template
        </button>
    </div>
</div>
@endsection