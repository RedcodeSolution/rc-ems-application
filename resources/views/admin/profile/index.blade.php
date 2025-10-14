@extends('layouts.admin')

<style>
:root {
    --primary: #D32F2F;
    --accent: #212121;
    --primary-light: #F5F5F5;
    --secondary: #3F51B5;
    --success: #43A047;
    --warning: #FFA000;
    --danger: #E64A19;
    --error: #E64A19;
    --info: #0097A7;
    --text-primary: #212121;
    --text-secondary: #757575;
    --text-disabled: #BDBDBD;
    --divider: #E0E0E0;
}

.card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
}

.card-header {
    border-bottom: 1px solid var(--divider);
    background: linear-gradient(90deg, var(--primary-light) 60%, #fff 100%);
    border-radius: 1rem 1rem 0 0;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-body {
    padding: 2rem;
}

.btn {
    border-radius: 0.75rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
}

.btn-primary {
    background: linear-gradient(90deg, var(--primary) 60%, var(--secondary) 100%);
    color: #fff;
    border: none;
}

.btn-primary:hover {
    background: linear-gradient(90deg, var(--secondary) 60%, var(--primary) 100%);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
}

.form-input, .form-select {
    width: 100%;
    border-radius: 0.5rem;
    border: 1px solid var(--divider);
    background: var(--primary-light);
    padding: 0.75rem 1rem;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
}

.form-input:focus, .form-select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px #d32f2f22;
}

.alert {
    padding: 1rem 1.5rem;
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.alert-success {
    background: #e8f5e8;
    color: var(--success);
    border: 1px solid #c8e6c9;
}

.alert-error {
    background: #ffebee;
    color: var(--error);
    border: 1px solid #ffcdd2;
}

.profile-section {
    background: #fff;
    border-radius: 1rem;
    padding: 2rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 8px 0 rgba(0,0,0,0.03);
}

.profile-header {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid var(--divider);
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 40%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 2rem;
    font-weight: 700;
}

.profile-info h3 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
}

.profile-info p {
    margin: 0;
    color: var(--text-secondary);
}

.grid {
    display: grid;
    gap: 1.5rem;
}

.grid-cols-2 {
    grid-template-columns: repeat(2, 1fr);
}

@media (max-width: 768px) {
    .grid-cols-2 {
        grid-template-columns: 1fr;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
    }
}
</style>

@section('title', 'Admin Profile Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-shield"></i> Admin Profile Management</h2>
        <div>
            <button type="button" class="btn btn-primary" onclick="saveProfile()">
                <i class="fas fa-save"></i>
                Save Changes
            </button>
        </div>
    </div>

    <div class="card-body">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <ul style="margin: 0; padding-left: 1rem;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="profileForm" method="POST" action="{{ route('admin.profile.update') }}">
            @csrf

            <!-- Profile Header -->
            <div class="profile-section">
                <div class="profile-header">
                    <div class="profile-avatar">
                        {{ substr(Auth::user()->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="profile-info">
                        <h3>{{ Auth::user()->name ?? 'Admin User' }}</h3>
                        <p>{{ ucfirst(Auth::user()->role ?? 'admin') }}</p>
                        <p>Last updated: {{ Auth::user()->updated_at ? Auth::user()->updated_at->format('M d, Y H:i') : 'Never' }}</p>
                    </div>
                </div>
            </div>

            <!-- Personal Information -->
            <div class="profile-section">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">
                    <i class="fas fa-user"></i> Personal Information
                </h3>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" id="name" name="name" class="form-input"
                               value="{{ Auth::user()->name ?? '' }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-input"
                               value="{{ Auth::user()->email ?? '' }}" required>
                    </div>
                </div>
            </div>

            <!-- Security Settings -->
            <div class="profile-section">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">
                    <i class="fas fa-lock"></i> Security Settings
                </h3>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="form-input" placeholder="Enter current password">
                    </div>

                    <div class="form-group">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" id="new_password" name="new_password"
                               class="form-input" placeholder="Enter new password">
                    </div>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                           class="form-input" placeholder="Confirm new password">
                </div>
            </div>

            <!-- Admin Details -->
            <div class="profile-section">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">
                    <i class="fas fa-id-card"></i> Admin Details
                </h3>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label for="admin_id" class="form-label">Admin ID</label>
                        <input type="text" id="admin_id" name="admin_id" class="form-input"
                               value="{{ Auth::user()->id }}" readonly>
                    </div>

                    <div class="form-group">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" id="role" name="role" class="form-input" value="{{ Auth::user()->role ?? 'N/A' }}" readonly>
                    </div>

                </div>

                <div class="form-group">
                    <label for="contact_no" class="form-label">Contact Number</label>
                    <input type="text" id="contact_no" name="contact_no" class="form-input"
                           value="{{ Auth::user()->contact_no ?? '' }}" placeholder="Enter contact number">
                </div>
            </div>

            <!-- System Information -->
            <div class="profile-section">
                <h3 style="margin-bottom: 1.5rem; color: var(--text-primary);">
                    <i class="fas fa-cog"></i> System Information
                </h3>

                <div class="grid grid-cols-2">
                    <div class="form-group">
                        <label class="form-label">Account Created</label>
                        <input type="text" class="form-input"
                               value="{{ Auth::user()->created_at ? Auth::user()->created_at->format('M d, Y H:i') : 'N/A' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Last Login</label>
                        <input type="text" class="form-input"
                               value="{{ Auth::user()->last_login_at ? Auth::user()->last_login_at->format('M d, Y H:i') : 'N/A' }}" readonly>
                    </div>

                </div>
            </div>
        </form>
    </div>
</div>

<script>

    function saveProfile() {
        const form = document.getElementById('profileForm');
        const name = form.name.value.trim();
        const email = form.email.value.trim();
        const currentPassword = form.current_password.value;
        const newPassword = form.new_password.value;
        const confirmPassword = form.new_password_confirmation.value;

        if (!name) { alert('Please enter your full name.'); return; }
        if (!email) { alert('Please enter your email.'); return; }

        if (newPassword || confirmPassword) {
            if (!currentPassword) { alert('Please enter your current password.'); return; }
            if (newPassword !== confirmPassword) { alert('New password and confirmation do not match.'); return; }
            if (newPassword.length < 8) { alert('New password must be at least 8 characters.'); return; }
        }

        form.submit();
    }


// Auto-save functionality (optional)
let autoSaveTimer;
function setupAutoSave() {
    const inputs = document.querySelectorAll('#profileForm input, #profileForm select');
    inputs.forEach(input => {
        input.addEventListener('change', () => {
            clearTimeout(autoSaveTimer);
            autoSaveTimer = setTimeout(() => {
                // You can implement auto-save here if needed
                console.log('Auto-save triggered');
            }, 2000);
        });
    });
}

// Initialize auto-save
document.addEventListener('DOMContentLoaded', setupAutoSave);
</script>
@endsection
