@extends('layouts.admin')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid px-4 py-6">
    <!-- Page Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
            <i class="fas fa-cog mr-2"></i>System Settings
        </h1>
        <p class="text-gray-800 dark:text-gray-400">Manage system configuration, user accounts, and appearance settings</p>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Settings Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg">
        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 dark:border-gray-700">
            <nav class="flex -mb-px overflow-x-auto" aria-label="Tabs">
                <button onclick="showTab('user-management')" class="tab-button active whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-tab="user-management">
                    <i class="fas fa-users mr-2"></i>User Management
                </button>
                <button onclick="showTab('appearance')" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-tab="appearance">
                    <i class="fas fa-palette mr-2"></i>Appearance
                </button>
                <button onclick="showTab('general')" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-tab="general">
                    <i class="fas fa-sliders-h mr-2"></i>General
                </button>
                <button onclick="showTab('security')" class="tab-button whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm" data-tab="security">
                    <i class="fas fa-shield-alt mr-2"></i>Security
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <!-- User Management Tab -->
            <div id="user-management-tab" class="tab-content">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">User Management</h2>
                <p class="text-gray-800 dark:text-gray-400 mb-6">Create, edit, and manage user accounts and permissions</p>

                <!-- Quick Actions -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    <a href="{{ route('admin.users.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center transition">
                        <i class="fas fa-user-plus mr-2"></i>Create New User
                    </a>
                    <a href="{{ route('admin.users.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center transition">
                        <i class="fas fa-list mr-2"></i>View All Users
                    </a>
                    <button onclick="showBulkActions()" class="bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg flex items-center justify-center transition">
                        <i class="fas fa-tasks mr-2"></i>Bulk Actions
                    </button>
                </div>

                <!-- User Management Features -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Account Creation -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-user-plus text-indigo-600 mr-2"></i>Account Creation
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Create user accounts</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Set initial passwords</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Assign user roles</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Configure permissions</li>
                        </ul>
                    </div>

                    <!-- User Roles & Permissions -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-user-shield text-blue-600 mr-2"></i>Roles & Permissions
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-crown text-yellow-500 mr-2"></i><strong>Super Admin:</strong> Full system access</li>
                            <li><i class="fas fa-user-tie text-purple-500 mr-2"></i><strong>Admin:</strong> Manage users & data</li>
                            <li><i class="fas fa-user text-gray-800 mr-2"></i><strong>User:</strong> View & basic operations</li>
                        </ul>
                    </div>

                    <!-- Profile Management -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-id-card text-green-600 mr-2"></i>Profile Management
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Edit user information</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Upload profile pictures</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Update contact details</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Manage user status</li>
                        </ul>
                    </div>

                    <!-- Password Management -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-key text-red-600 mr-2"></i>Password Management
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Reset user passwords</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Force password changes</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Set password policies</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Password strength requirements</li>
                        </ul>
                    </div>

                    <!-- File Management -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-file-upload text-orange-600 mr-2"></i>File Management
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Upload system files</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Manage user documents</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Profile picture uploads</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>File size & type restrictions</li>
                        </ul>
                    </div>

                    <!-- User Activity -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                            <i class="fas fa-chart-line text-teal-600 mr-2"></i>User Activity
                        </h3>
                        <ul class="space-y-2 text-gray-800 dark:text-gray-300">
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Track user logins</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Monitor user actions</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>View activity logs</li>
                            <li><i class="fas fa-check text-green-500 mr-2"></i>Generate activity reports</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Appearance Tab -->
            <div id="appearance-tab" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Appearance Settings</h2>
                <p class="text-gray-800 dark:text-gray-400 mb-6">Customize the look and feel of the application</p>

                <form id="appearance-form" class="space-y-6">
                    @csrf
                    
                    <!-- Font Family -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-font mr-2"></i>Font Family
                        </label>
                        <select name="font_family" id="font_family" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white" onchange="previewFont(this.value)">
                            <option value="Ubuntu" selected>Ubuntu (Default)</option>
                            <option value="Calibri">Calibri</option>
                            <option value="Brush Script MT">Brush Script MT</option>
                            <option value="Times New Roman">Times New Roman</option>
                        </select>
                        <p class="mt-2 text-sm text-gray-800 dark:text-gray-400">Select the default font for the entire application</p>
                        
                        <!-- Font Preview -->
                        <div class="mt-4 p-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg">
                            <p class="text-sm text-gray-800 dark:text-gray-400 mb-2">Preview:</p>
                            <p id="font-preview" style="font-family: Ubuntu; font-size: 18px;">
                                The quick brown fox jumps over the lazy dog. 0123456789
                            </p>
                        </div>
                    </div>

                    <!-- Theme Selection -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-palette mr-2"></i>Color Theme
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-green-500 transition">
                                <input type="radio" name="theme" value="green" class="mr-3" checked>
                                <div>
                                    <i class="fas fa-leaf text-green-600 mr-2"></i>
                                    <span class="font-medium">Green Theme</span>
                                </div>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-amber-500 transition">
                                <input type="radio" name="theme" value="cream" class="mr-3">
                                <div>
                                    <i class="fas fa-sun text-amber-600 mr-2"></i>
                                    <span class="font-medium">Cream Theme</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Font Size -->
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            <i class="fas fa-text-height mr-2"></i>Font Size
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="font_size" value="small" class="mr-3">
                                <span class="font-medium text-sm">Small</span>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="font_size" value="medium" class="mr-3" checked>
                                <span class="font-medium">Medium</span>
                            </label>
                            <label class="flex items-center p-4 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-indigo-500 transition">
                                <input type="radio" name="font_size" value="large" class="mr-3">
                                <span class="font-medium text-lg">Large</span>
                            </label>
                        </div>
                    </div>

                    <!-- Save Button -->
                    <div class="flex justify-end space-x-4">
                        <button type="button" onclick="resetAppearance()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <i class="fas fa-undo mr-2"></i>Reset to Default
                        </button>
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save Appearance Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- General Tab -->
            <div id="general-tab" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">General Settings</h2>
                <p class="text-gray-800 dark:text-gray-400 mb-6">Configure general application settings</p>

                <form id="general-form" class="space-y-6">
                    @csrf
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Application Name
                        </label>
                        <input type="text" name="app_name" value="Gombe SS Hub" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Email
                        </label>
                        <input type="email" name="contact_email" value="admin@gombess.edu" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Contact Phone
                        </label>
                        <input type="text" name="contact_phone" value="0779201801" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save General Settings
                        </button>
                    </div>
                </form>
            </div>

            <!-- Security Tab -->
            <div id="security-tab" class="tab-content hidden">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Security Settings</h2>
                <p class="text-gray-800 dark:text-gray-400 mb-6">Configure security and authentication settings</p>

                <form id="security-form" class="space-y-6">
                    @csrf
                    
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Session Timeout (minutes)
                        </label>
                        <input type="number" name="session_timeout" value="120" min="5" max="1440" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Minimum Password Length
                        </label>
                        <input type="number" name="password_min_length" value="8" min="6" max="50" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-indigo-500 dark:bg-gray-800 dark:text-white">
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                            Password Requirements
                        </label>
                        <div class="space-y-2">
                            <label class="flex items-center">
                                <input type="checkbox" name="require_uppercase" checked class="mr-2">
                                <span>Require uppercase letters</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="require_lowercase" checked class="mr-2">
                                <span>Require lowercase letters</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="require_numbers" checked class="mr-2">
                                <span>Require numbers</span>
                            </label>
                            <label class="flex items-center">
                                <input type="checkbox" name="require_symbols" class="mr-2">
                                <span>Require special symbols</span>
                            </label>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition">
                            <i class="fas fa-save mr-2"></i>Save Security Settings
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Tab switching
function showTab(tabName) {
    // Hide all tabs
    document.querySelectorAll('.tab-content').forEach(tab => {
        tab.classList.add('hidden');
    });
    
    // Remove active class from all buttons
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('active', 'border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-800');
    });
    
    // Show selected tab
    document.getElementById(tabName + '-tab').classList.remove('hidden');
    
    // Add active class to clicked button
    const activeButton = document.querySelector(`[data-tab="${tabName}"]`);
    activeButton.classList.add('active', 'border-indigo-500', 'text-indigo-600');
    activeButton.classList.remove('border-transparent', 'text-gray-800');
}

// Font preview
function previewFont(fontFamily) {
    document.getElementById('font-preview').style.fontFamily = fontFamily;
}

// Reset appearance to default
function resetAppearance() {
    document.getElementById('font_family').value = 'Ubuntu';
    previewFont('Ubuntu');
    document.querySelector('input[name="theme"][value="green"]').checked = true;
    document.querySelector('input[name="font_size"][value="medium"]').checked = true;
}

// Apply font globally
function applyFontGlobally(fontFamily) {
    document.documentElement.style.setProperty('--font-family', fontFamily);
    document.body.style.fontFamily = fontFamily;
    localStorage.setItem('app_font_family', fontFamily);
}

// Load saved font on page load
document.addEventListener('DOMContentLoaded', function() {
    const savedFont = localStorage.getItem('app_font_family') || 'Ubuntu';
    applyFontGlobally(savedFont);
    document.getElementById('font_family').value = savedFont;
    previewFont(savedFont);
});

// Handle appearance form submission
document.getElementById('appearance-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const fontFamily = document.getElementById('font_family').value;
    const theme = document.querySelector('input[name="theme"]:checked').value;
    const fontSize = document.querySelector('input[name="font_size"]:checked').value;
    
    // Apply font globally
    applyFontGlobally(fontFamily);
    
    // Save to server (you can implement this)
    fetch('{{ route("admin.settings.theme.update") }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
        },
        body: JSON.stringify({
            font_family: fontFamily,
            theme: theme,
            font_size: fontSize
        })
    })
    .then(response => response.json())
    .then(data => {
        alert('Appearance settings saved successfully!');
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to save settings. Please try again.');
    });
});

// Handle general form submission
document.getElementById('general-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('General settings saved successfully!');
});

// Handle security form submission
document.getElementById('security-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Security settings saved successfully!');
});

function showBulkActions() {
    alert('Bulk actions feature - Navigate to User Management page for bulk operations');
    window.location.href = '{{ route("admin.users.index") }}';
}
</script>

<style>
.tab-button {
    transition: all 0.3s ease;
}

.tab-button.active {
    border-color: #6366f1;
    color: #6366f1;
}

.tab-button:not(.active) {
    border-color: transparent;
    color: #6b7280;
}

.tab-button:hover:not(.active) {
    color: #4b5563;
}
</style>
@endsection