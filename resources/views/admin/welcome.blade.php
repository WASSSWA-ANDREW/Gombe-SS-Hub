@extends('layouts.admin')

@section('title', 'Welcome')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl w-full space-y-8">
        <!-- Welcome Header -->
        <div class="text-center">
            <div class="mx-auto h-20 w-20 bg-green-100 rounded-full flex items-center justify-center mb-6">
                <i class="fas fa-check-circle text-5xl text-green-600"></i>
            </div>
            <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome to Gombe SS Hub!</h1>
            <p class="text-xl text-gray-800 mb-2">Hello, <span class="font-semibold text-indigo-600">{{ Auth::user()->name }}</span></p>
            <p class="text-lg text-gray-800">You have successfully logged in as {{ ucfirst(str_replace('_', ' ', Auth::user()->role ?? 'user')) }}</p>
        </div>

        <!-- Quick Stats (Quick Overview) -->
        <div class="bg-white rounded-xl shadow-lg p-4">
            <div class="flex flex-wrap justify-center gap-4">
                <div class="bg-blue-50 rounded-lg p-4 text-center" style="flex: 0 1 auto; min-width: 110px;">
                    <i class="fas fa-chalkboard-teacher text-3xl text-blue-600 mb-2"></i>
                    <div class="text-xl font-bold text-blue-600">{{ \App\Models\Staff::count() ?? 0 }}</div>
                    <div class="text-xs text-blue-600">Staff</div>
                </div>
                <div class="bg-green-50 rounded-lg p-4 text-center" style="flex: 0 1 auto; min-width: 110px;">
                    <i class="fas fa-user-graduate text-3xl text-green-600 mb-2"></i>
                    <div class="text-xl font-bold text-green-600">{{ \App\Models\Student::count() ?? 0 }}</div>
                    <div class="text-xs text-green-600">Students</div>
                </div>
                <div class="bg-purple-50 rounded-lg p-4 text-center" style="flex: 0 1 auto; min-width: 110px;">
                    <i class="fas fa-user-shield text-3xl text-purple-600 mb-2"></i>
                    <div class="text-xl font-bold text-purple-600">{{ \App\Models\User::whereIn('role', ['admin', 'super_admin'])->count() ?? 0 }}</div>
                    <div class="text-xs text-purple-600">Admins</div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Card -->
        <div class="bg-white rounded-xl shadow-lg p-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4 text-center">What would you like to do?</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-chart-line text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">Dashboard</h3>
                    <p class="text-xs text-blue-100 text-center">View system overview</p>
                </a>

                <!-- Profile -->
                <a href="{{ route('profile') }}" class="group bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-user-circle text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">My Profile</h3>
                    <p class="text-xs text-green-100 text-center">Update your information</p>
                </a>

                <!-- Staff Management -->
                <a href="{{ route('admin.staff.index') }}" class="group bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-chalkboard-teacher text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">Staff Management</h3>
                    <p class="text-xs text-purple-100 text-center">Manage staff records</p>
                </a>

                <!-- Student Management -->
                <a href="{{ route('admin.students.olevel.index') }}" class="group bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">Student Records</h3>
                    <p class="text-xs text-orange-100 text-center">Manage students</p>
                </a>

                <!-- Reports -->
                <a href="{{ route('admin.reports.index') }}" class="group bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-chart-bar text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">Reports</h3>
                    <p class="text-xs text-red-100 text-center">Generate reports</p>
                </a>

                <!-- Settings -->
                @if(Auth::user()->role === 'super_admin' || Auth::user()->role === 'admin')
                <a href="{{ route('admin.settings.index') }}" class="group bg-gradient-to-r from-gray-500 to-gray-600 hover:from-gray-600 hover:to-gray-700 text-white rounded-lg p-4 transition-all duration-200 transform hover:scale-105 shadow-md hover:shadow-lg">
                    <div class="flex items-center justify-center mb-2">
                        <i class="fas fa-cogs text-3xl"></i>
                    </div>
                    <h3 class="text-sm font-semibold mb-1 text-center">Settings</h3>
                    <p class="text-xs text-gray-100 text-center">System configuration</p>
                </a>
                @endif
            </div>
        </div>

        <!-- Continue Button -->
        <div class="text-center">
            <p class="text-gray-800 mb-4">Ready to get started?</p>
            <div class="space-y-3">
                <div>
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-lg transition-colors duration-200">
                        <span>Go to Dashboard</span>
                        <i class="fas fa-arrow-right ml-2"></i>
                    </a>
                </div>
                <div>
                    <p class="text-sm text-gray-800">
                        <i class="fas fa-lightbulb text-yellow-500 mr-2"></i>
                        Tip: You can bookmark this welcome page for quick access to all sections!
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection