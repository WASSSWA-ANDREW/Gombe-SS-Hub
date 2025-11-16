@extends('layouts.admin')

@section('title', 'User Details')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                <i class="fas fa-user mr-2"></i>User Details
            </h1>
            <p class="text-gray-800 dark:text-gray-400">Viewing detailed information for {{ $user->name }}</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.users.edit', $user) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-semibold py-2 px-4 rounded-lg flex items-center transition">
                <i class="fas fa-arrow-left mr-2"></i>Back to List
            </a>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden mb-6">
        <div class="md:flex">
            <!-- User Avatar Section -->
            <div class="md:w-1/3 bg-gray-50 dark:bg-gray-700 p-6 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-gray-200 dark:border-gray-600">
                @if($user->avatar)
                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="h-48 w-48 rounded-full object-cover mb-4 border-4 border-white dark:border-gray-800 shadow-lg">
                @else
                    <div class="h-48 w-48 rounded-full bg-indigo-600 flex items-center justify-center text-white text-6xl font-bold mb-4 border-4 border-white dark:border-gray-800 shadow-lg">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">{{ $user->name }}</h2>
                
                <div class="mt-2 mb-4">
                    @if($user->role === 'super_admin')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            <i class="fas fa-crown mr-1"></i>Super Admin
                        </span>
                    @elseif($user->role === 'admin')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">
                            <i class="fas fa-user-tie mr-1"></i>Admin
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                            <i class="fas fa-user mr-1"></i>User
                        </span>
                    @endif
                </div>
                
                <div class="flex items-center mt-2">
                    @if($user->status === 'active')
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                            <i class="fas fa-check-circle mr-1"></i>Active
                        </span>
                    @else
                        <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                            <i class="fas fa-times-circle mr-1"></i>Inactive
                        </span>
                    @endif
                </div>
            </div>
            
            <!-- User Details Section -->
            <div class="md:w-2/3 p-6">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 pb-2 border-b border-gray-200 dark:border-gray-600">
                    <i class="fas fa-info-circle mr-2"></i>User Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Email Address</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-envelope mr-2 text-indigo-500"></i>
                            {{ $user->email }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Phone Number</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-phone mr-2 text-indigo-500"></i>
                            {{ $user->phone ?? 'Not provided' }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Account Created</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-calendar-plus mr-2 text-indigo-500"></i>
                            {{ $user->created_at->format('F d, Y') }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Last Updated</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-calendar-check mr-2 text-indigo-500"></i>
                            {{ $user->updated_at->format('F d, Y') }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Last Login</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-sign-in-alt mr-2 text-indigo-500"></i>
                            {{ $user->last_login_at ? $user->last_login_at->format('F d, Y h:i A') : 'Never' }}
                        </p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Last Login IP</h4>
                        <p class="text-base font-medium text-gray-900 dark:text-white flex items-center mt-1">
                            <i class="fas fa-network-wired mr-2 text-indigo-500"></i>
                            {{ $user->last_login_ip ?? 'Not available' }}
                        </p>
                    </div>
                </div>
                
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Address</h4>
                    <p class="text-base font-medium text-gray-900 dark:text-white flex items-start mt-1">
                        <i class="fas fa-map-marker-alt mr-2 text-indigo-500 mt-1"></i>
                        <span>{{ $user->address ?? 'No address provided' }}</span>
                    </p>
                </div>
                
                @if($user->bio)
                <div class="mt-6">
                    <h4 class="text-sm font-medium text-gray-800 dark:text-gray-400">Bio</h4>
                    <p class="text-base font-medium text-gray-900 dark:text-white flex items-start mt-1">
                        <i class="fas fa-user-circle mr-2 text-indigo-500 mt-1"></i>
                        <span>{{ $user->bio }}</span>
                    </p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4">
            <i class="fas fa-cogs mr-2"></i>User Actions
        </h3>
        
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                <i class="fas fa-edit mr-2"></i>Edit User
            </a>
            
            @if($user->id !== auth()->id())
                <button onclick="toggleStatus({{ $user->id }})" class="inline-flex items-center px-4 py-2 bg-{{ $user->status === 'active' ? 'orange' : 'green' }}-600 hover:bg-{{ $user->status === 'active' ? 'orange' : 'green' }}-700 text-white rounded-lg transition">
                    <i class="fas fa-toggle-{{ $user->status === 'active' ? 'off' : 'on' }} mr-2"></i>{{ $user->status === 'active' ? 'Deactivate' : 'Activate' }} User
                </button>
                
                <button onclick="resetPassword({{ $user->id }})" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                    <i class="fas fa-key mr-2"></i>Reset Password
                </button>
                
                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition">
                        <i class="fas fa-trash mr-2"></i>Delete User
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<script>
// Reset password
function resetPassword(userId) {
    if (confirm('Are you sure you want to reset this user\'s password?')) {
        fetch(`/admin/users/${userId}/reset-password`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(`Password reset successfully! New password: ${data.new_password}\n\nPlease save this password and share it with the user securely.`);
            } else {
                alert('Failed to reset password. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
        });
    }
}

// Toggle status
function toggleStatus(userId) {
    fetch(`/admin/users/${userId}/toggle-status`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            location.reload();
        } else {
            alert(data.error || 'Failed to toggle status. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection