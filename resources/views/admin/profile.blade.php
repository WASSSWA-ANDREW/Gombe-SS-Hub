@extends('layouts.admin')

@section('title', 'Profile')

@section('content')
<style>
    .profile-page-wrapper {
        max-width: 1400px;
        margin: 0 auto;
    }

    .profile-header {
        background: linear-gradient(135deg, #10b981 0%, #964B00 100%);
        color: white;
        padding: 2rem;
        border-radius: 1rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(16, 185, 129, 0.2);
    }

    .profile-header h2 {
        font-size: 2rem;
        font-weight: 700;
        margin: 0;
    }

    .breadcrumb-custom {
        margin-top: 1rem;
        opacity: 0.9;
    }

    .breadcrumb-custom a {
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        transition: color 0.3s;
    }

    .breadcrumb-custom a:hover {
        color: white;
    }

    .profile-container {
        display: grid;
        grid-template-columns: 350px 1fr;
        gap: 2rem;
    }

    @media (max-width: 1024px) {
        .profile-container {
            grid-template-columns: 1fr;
        }
    }

    .profile-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }

    .profile-card-body {
        padding: 2.5rem 2rem;
        text-align: center;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1.5rem;
        border: 5px solid #10b981;
        object-fit: cover;
        box-shadow: 0 10px 25px rgba(16, 185, 129, 0.2);
    }

    .profile-name {
        font-size: 1.75rem;
        font-weight: 700;
        color: #1a202c;
        margin: 1rem 0 0.5rem;
    }

    .profile-role {
        font-size: 1rem;
        color: #10b981;
        font-weight: 600;
        text-transform: capitalize;
        margin-bottom: 1.5rem;
    }

    .role-badge {
        display: inline-block;
        background: linear-gradient(135deg, #10b981 0%, #964B00 100%);
        color: white;
        padding: 0.5rem 1rem;
        border-radius: 2rem;
        font-size: 0.85rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .profile-actions {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 2rem;
        justify-content: center;
    }

    .profile-actions button {
        flex: 1;
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 0.9rem;
    }

    .btn-follow {
        background: #667eea;
        color: white;
    }

    .btn-follow:hover {
        background: #5568d3;
        transform: translateY(-2px);
    }

    .btn-message {
        background: #f56565;
        color: white;
    }

    .btn-message:hover {
        background: #e53e3e;
        transform: translateY(-2px);
    }

    .profile-info-section {
        border-top: 1px solid #e2e8f0;
        padding-top: 1.5rem;
        text-align: left;
    }

    .info-label {
        font-size: 0.85rem;
        text-transform: uppercase;
        color: #718096;
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .info-item {
        margin-bottom: 1.25rem;
    }

    .info-value {
        color: #2d3748;
        font-weight: 500;
        word-break: break-all;
    }

    .about-section {
        background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(150, 75, 0, 0.05) 100%);
        padding: 1.5rem;
        border-radius: 0.75rem;
        border-left: 4px solid #10b981;
    }

    .about-section .info-label {
        margin-bottom: 0.75rem;
    }

    .about-section .info-value {
        font-style: italic;
        color: #4a5568;
    }

    .settings-card {
        background: white;
        border-radius: 1.5rem;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .settings-tabs {
        display: flex;
        border-bottom: 2px solid #e2e8f0;
        background: #f7fafc;
    }

    .settings-tabs button {
        flex: 1;
        padding: 1.25rem;
        border: none;
        background: none;
        cursor: pointer;
        font-weight: 600;
        color: #718096;
        transition: all 0.3s;
        position: relative;
        font-size: 1rem;
    }

    .settings-tabs button:hover {
        color: #10b981;
    }

    .settings-tabs button.active {
        color: #10b981;
    }

    .settings-tabs button.active::after {
        content: '';
        position: absolute;
        bottom: -2px;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #10b981 0%, #964B00 100%);
    }

    .settings-body {
        padding: 2.5rem;
    }

    .form-group-custom {
        margin-bottom: 1.5rem;
    }

    .form-group-custom label {
        display: block;
        font-weight: 600;
        color: #2d3748;
        margin-bottom: 0.5rem;
        font-size: 0.95rem;
    }

    .form-group-custom input,
    .form-group-custom select,
    .form-group-custom textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid #e2e8f0;
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.3s;
        font-family: inherit;
    }

    .form-group-custom input:focus,
    .form-group-custom select:focus,
    .form-group-custom textarea:focus {
        outline: none;
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    .form-row-custom {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 768px) {
        .form-row-custom {
            grid-template-columns: 1fr;
        }
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 1.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .form-section-title i {
        color: #10b981;
    }

    .btn-submit {
        background: linear-gradient(135deg, #10b981 0%, #964B00 100%);
        color: white;
        padding: 0.875rem 2rem;
        border: none;
        border-radius: 0.5rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        font-size: 1rem;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-submit:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
    }

    .btn-submit:active {
        transform: translateY(0);
    }

    .submit-container {
        text-align: right;
        margin-top: 2rem;
        padding-top: 2rem;
        border-top: 1px solid #e2e8f0;
    }

    .error-message {
        color: #e53e3e;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .tab-content-custom {
        display: none;
    }

    .tab-content-custom.active {
        display: block;
        animation: fadeIn 0.3s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    .avatar-upload-section {
        position: relative;
        display: inline-block;
        margin-bottom: 1.5rem;
    }

    .avatar-upload-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        border-radius: 50%;
        opacity: 0;
        transition: opacity 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
    }

    .avatar-upload-section:hover .avatar-upload-overlay {
        opacity: 1;
    }

    .avatar-upload-overlay i {
        color: white;
        font-size: 1.5rem;
    }

    #avatar-input {
        display: none;
    }

    .upload-progress {
        margin-top: 1rem;
        text-align: center;
    }

    .progress-bar-container {
        width: 100%;
        height: 4px;
        background: #e2e8f0;
        border-radius: 2px;
        overflow: hidden;
        margin-bottom: 0.5rem;
    }

    .progress-bar-fill {
        height: 100%;
        background: linear-gradient(90deg, #10b981 0%, #964B00 100%);
        width: 0%;
        transition: width 0.3s;
    }

    .upload-status {
        font-size: 0.85rem;
        color: #718096;
    }
</style>

<div class="profile-page-wrapper">
    <div class="profile-header">
        <h2>{{ auth()->user()->role === 'super_admin' ? 'Super Administrator' : 'Administrator' }} Profile</h2>
        <div class="breadcrumb-custom">
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <span> / </span>
            <span>Profile</span>
        </div>
    </div>

    <div class="profile-container">
        <div class="profile-card">
            <div class="profile-card-body">
                <div class="avatar-upload-section">
                    <img id="avatar-preview" src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('assets/gombe_logo.png') }}" alt="{{ $user->name }}" class="profile-avatar">
                    <div class="avatar-upload-overlay" onclick="document.getElementById('avatar-input').click()">
                        <i class="fas fa-camera"></i>
                    </div>
                    <input type="file" id="avatar-input" accept="image/*">
                </div>
                <div id="upload-progress" class="upload-progress" style="display: none;">
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill"></div>
                    </div>
                    <div class="upload-status">Uploading...</div>
                </div>
                <h3 class="profile-name">{{ $user->name }}</h3>
                <div class="role-badge">
                    <i class="fas fa-shield-alt"></i> {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </div>

                <div class="profile-actions">
                    <button type="button" class="btn-follow">
                        <i class="fas fa-star"></i> Follow
                    </button>
                    <button type="button" class="btn-message">
                        <i class="fas fa-envelope"></i> Message
                    </button>
                </div>

                <div class="profile-info-section">
                    <div class="about-section">
                        <div class="info-label">About Me</div>
                        <div class="info-value">{{ $user->bio ?? 'No bio available.' }}</div>
                    </div>

                    <div style="margin-top: 1.5rem;">
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-user"></i> Full Name</div>
                            <div class="info-value">{{ $user->name }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-phone"></i> Mobile</div>
                            <div class="info-value">{{ $user->phone ?? 'Not provided' }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-envelope"></i> Email</div>
                            <div class="info-value">{{ $user->email }}</div>
                        </div>
                        <div class="info-item">
                            <div class="info-label"><i class="fas fa-map-marker-alt"></i> Location</div>
                            <div class="info-value">{{ $user->address ?? 'Not provided' }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="settings-card">
            <div class="settings-tabs">
                <button type="button" class="settings-tab-btn active" onclick="switchTab('profile-settings')">
                    <i class="fas fa-user-cog"></i> Profile Settings
                </button>
                <button type="button" class="settings-tab-btn" onclick="switchTab('password-settings')">
                    <i class="fas fa-lock"></i> Change Password
                </button>
            </div>

            <div class="settings-body">
                <div id="profile-settings" class="tab-content-custom active">
                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('PUT')
                        <h4 class="form-section-title"><i class="fas fa-user-circle"></i> Personal Information</h4>

                        <div class="form-row-custom">
                            <div class="form-group-custom">
                                <label for="name">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                @error('name')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group-custom">
                                <label for="email">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                @error('email')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group-custom">
                                <label for="phone">Phone Number</label>
                                <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                @error('phone')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group-custom">
                                <label for="address">Address</label>
                                <input type="text" id="address" name="address" value="{{ old('address', $user->address) }}">
                                @error('address')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="submit-container">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-check-circle"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <div id="password-settings" class="tab-content-custom">
                    <form method="POST" action="{{ route('profile.password') }}">
                        @csrf
                        @method('PUT')
                        <h4 class="form-section-title"><i class="fas fa-lock"></i> Update Password</h4>

                        <div class="form-group-custom">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" required>
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-row-custom">
                            <div class="form-group-custom">
                                <label for="new_password">New Password</label>
                                <input type="password" id="new_password" name="new_password" required>
                                @error('new_password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group-custom">
                                <label for="new_password_confirmation">Confirm New Password</label>
                                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required>
                            </div>
                        </div>

                        <div class="submit-container">
                            <button type="submit" class="btn-submit">
                                <i class="fas fa-check-circle"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function switchTab(tabName) {
        const tabs = document.querySelectorAll('.tab-content-custom');
        const buttons = document.querySelectorAll('.settings-tab-btn');

        tabs.forEach(tab => tab.classList.remove('active'));
        buttons.forEach(btn => btn.classList.remove('active'));

        document.getElementById(tabName).classList.add('active');
        event.target.closest('.settings-tab-btn').classList.add('active');
    }

    document.getElementById('avatar-input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
        if (!allowedTypes.includes(file.type)) {
            alert('Please select a valid image file (jpeg, png, jpg, or gif)');
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('File size must not exceed 2MB');
            return;
        }

        const formData = new FormData();
        formData.append('avatar', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        formData.append('_method', 'PUT');

        const progressDiv = document.getElementById('upload-progress');
        const progressBar = document.querySelector('.progress-bar-fill');
        progressDiv.style.display = 'block';

        const xhr = new XMLHttpRequest();
        
        xhr.upload.addEventListener('progress', function(e) {
            if (e.lengthComputable) {
                const percentComplete = (e.loaded / e.total) * 100;
                progressBar.style.width = percentComplete + '%';
            }
        });

        xhr.addEventListener('load', function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    const reader = new FileReader();
                    reader.onload = function(event) {
                        document.getElementById('avatar-preview').src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                    progressDiv.style.display = 'none';
                    progressBar.style.width = '0%';
                    toastr.success('Avatar uploaded successfully!');
                } else {
                    progressDiv.style.display = 'none';
                    progressBar.style.width = '0%';
                    toastr.error(response.message || 'Failed to upload avatar');
                }
            } else {
                progressDiv.style.display = 'none';
                progressBar.style.width = '0%';
                toastr.error('Failed to upload avatar');
            }
        });

        xhr.addEventListener('error', function() {
            progressDiv.style.display = 'none';
            progressBar.style.width = '0%';
            toastr.error('Error uploading avatar');
        });

        xhr.open('POST', '{{ route("profile.update") }}', true);
        xhr.send(formData);
    });
</script>
@endsection

@push('scripts')
<script>
    // Show success/error messages
    @if(session('success'))
        toastr.success('{{ session('success') }}');
    @endif
    
    @if(session('error'))
        toastr.error('{{ session('error') }}');
    @endif
</script>
@endpush