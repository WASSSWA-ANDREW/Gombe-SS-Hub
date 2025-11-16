@extends('layouts.admin')

@section('title', 'Icon Test')

@section('content')
<div class="p-8">
    <h1 class="text-3xl font-bold mb-8">Font Awesome Icon Test</h1>
    
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Navigation Icons</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 border rounded">
                <i class="fas fa-chart-line text-4xl text-blue-600 mb-2"></i>
                <p class="text-sm">Dashboard</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-hand-sparkles text-4xl text-green-600 mb-2"></i>
                <p class="text-sm">Welcome</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-user-graduate text-4xl text-purple-600 mb-2"></i>
                <p class="text-sm">Students</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-chalkboard-teacher text-4xl text-orange-600 mb-2"></i>
                <p class="text-sm">Staff</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-toolbox text-4xl text-red-600 mb-2"></i>
                <p class="text-sm">Tools</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-chart-bar text-4xl text-indigo-600 mb-2"></i>
                <p class="text-sm">Reports</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-cogs text-4xl text-gray-800 mb-2"></i>
                <p class="text-sm">Settings</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-sign-out-alt text-4xl text-red-600 mb-2"></i>
                <p class="text-sm">Logout</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Student Icons</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 border rounded">
                <i class="fas fa-book-reader text-4xl text-blue-600 mb-2"></i>
                <p class="text-sm">O'Level</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-graduation-cap text-4xl text-purple-600 mb-2"></i>
                <p class="text-sm">A'Level</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-plus-circle text-4xl text-green-600 mb-2"></i>
                <p class="text-sm">Add Student</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-list-ul text-4xl text-gray-800 mb-2"></i>
                <p class="text-sm">View List</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">Staff Icons</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="text-center p-4 border rounded">
                <i class="fas fa-user-tie text-4xl text-blue-600 mb-2"></i>
                <p class="text-sm">Private Staff</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-landmark text-4xl text-red-600 mb-2"></i>
                <p class="text-sm">Government</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-user-plus text-4xl text-green-600 mb-2"></i>
                <p class="text-sm">Add Staff</p>
            </div>
            <div class="text-center p-4 border rounded">
                <i class="fas fa-chevron-right text-4xl text-gray-800 mb-2"></i>
                <p class="text-sm">Chevron</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Font Awesome Status</h2>
        <div class="space-y-2">
            <p><strong>CDN Link:</strong> https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css</p>
            <p><strong>Test Icon:</strong> <i class="fas fa-check-circle text-green-600"></i> If you see a green checkmark, Font Awesome is working!</p>
            <p><strong>Browser:</strong> Try hard refresh (Ctrl+Shift+R or Ctrl+F5) to clear browser cache</p>
        </div>
    </div>
</div>
@endsection