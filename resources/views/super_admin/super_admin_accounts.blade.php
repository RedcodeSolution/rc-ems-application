@extends('layouts.super_admin')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/superAdminAccount.css') }}">
@section('title')
    <span class="desktop-title">Super Admin Management</span>
    <span class="mobile-title">Account Management</span>
@endsection

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-user-cog"></i>
            Super Admin Management
        </h1>
        <p class="page-description">
            Manage super admin accounts, permissions, and access controls
        </p>
    </div>
    <div class="header-actions">
        <button class="btn btn-primary" onclick="createAccount()">
            <i class="fas fa-plus"></i>
            Create New Account
        </button>

    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $totalSuperAdmins }}</h3>
            <p class="stat-label">Total Super Admins</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon active">
            <i class="fas fa-user-check"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $activeSuperAdmins }}</h3>
            <p class="stat-label">Active Accounts</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon inactive">
            <i class="fas fa-user-times"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $inactiveSuperAdmins }}</h3>
            <p class="stat-label">Inactive Accounts</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon recent">
            <i class="fas fa-sign-in-alt"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $recentLogins }}</h3>
            <p class="stat-label">Recent Logins (7 days)</p>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="content-grid">
    <!-- Super Admin Accounts Table -->
    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-user-shield"></i>
                Super Admin Accounts
            </h2>
            <div class="card-actions">
                <button class="btn btn-sm btn-outline" onclick="refreshTable()">
                    <i class="fas fa-sync-alt"></i>
                    Refresh
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($superAdminUsers as $user)
                <tr class="responsive-row">
                <tr class="responsive-row">
                    <td data-label="Name">
                        <div class="user-info" style="align-items: center;">
                            <div class="user-avatar">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/' . $user->profile_image) }}" alt="{{ $user->super_admin_name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                                @else
                                    {{ strtoupper(substr($user->super_admin_name, 0, 1)) }}
                                @endif
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ $user->super_admin_name }}</span>
                            </div>
                        </div>
                    </td>
                    <td data-label="Email">{{ $user->super_admin_email }}</td>
                    <td data-label="Status">
                        <span class="status-badge {{ $user->status ?? 'active' }}">
                            {{ ucfirst($user->status ?? 'active') }}
                        </span>
                    </td>
                    <td data-label="Last Login">
                        <span class="text-muted">
                            {{ $user->last_login_at ? \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() : 'Never' }}
                        </span>
                    </td>
                    <td data-label="Created">{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                    <td data-label="Actions">
                        <div class="action-buttons">
                            <button class="btn btn-sm btn-icon" onclick="viewAccount('{{ $user->super_admin_id }}')" title="View">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-sm btn-icon" onclick="editAccount('{{ $user->super_admin_id }}')" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-icon" onclick="resetPassword('{{ $user->super_admin_id }}')" title="Reset Password">
                                <i class="fas fa-key"></i>
                            </button>
                            <button class="btn btn-sm btn-icon danger" onclick="deleteAccount('{{ $user->super_admin_id }}')" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty-state">
                        <div class="empty-content">
                            <i class="fas fa-user-slash"></i>
                            <h3>No Super Admin Accounts</h3>
                            <p>No super admin accounts have been created yet.</p>
                            <button class="btn btn-primary" onclick="createAccount()">
                                <i class="fas fa-plus"></i>
                                Create First Account
                            </button>
                        </div>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>


</div>

<!-- Create Account Modal -->
<div id="createAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create New Super Admin Account</h2>
            <button class="modal-close" onclick="closeModal('createAccountModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form class="modal-form" id="createAccountForm">
            <div class="form-row">
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password *</label>
                    <div class="input-wrapper" style="position: relative;">
                        <input type="password" id="password" name="password" required class="form-control" style="padding-right: 40px;">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280;"></i>
                    </div>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <div class="input-wrapper" style="position: relative;">
                        <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control" style="padding-right: 40px;">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('password_confirmation', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280;"></i>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('createAccountModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Create Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- View Account Modal -->
<div id="viewAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>View Super Admin Account</h2>
            <button class="modal-close" onclick="closeModal('viewAccountModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
                <div class="account-details">
                    <!-- Profile Header Card -->
                    <div class="profile-header-card">
                        <div class="profile-header-avatar">
                            <span id="view-initials" class="profile-header-initials">--</span>
                            <img id="view-avatar-img" src="" alt="Profile" style="display: none;">
                        </div>
                        <div class="profile-header-name" id="view-name">Loading...</div>
                        <div class="profile-header-role">Super Admin</div>
                    </div>

                    <div class="detail-section">
                        <h3>Account Information</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>Email Address</label>
                                <span id="view-email">--</span>
                            </div>
                            
                            <div class="detail-item">
                                <label>Account Status</label>
                                <span><span class="status-badge" id="view-status">--</span></span>
                            </div>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h3>System Activity</h3>
                        <div class="detail-grid">
                            <div class="detail-item">
                                <label>Created</label>
                                <span id="view-created">--</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal('viewAccountModal')">
                Close
            </button>
            <button class="btn btn-primary" onclick="editAccountFromView()">
                <i class="fas fa-edit"></i>
                Edit Account
            </button>
        </div>
    </div>
</div>

<!-- Edit Account Modal -->
<div id="editAccountModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Edit Super Admin Account</h2>
            <button class="modal-close" onclick="closeModal('editAccountModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form class="modal-form" id="editAccountForm">
            <div class="form-row" style="display: flex; justify-content: center; width: 100%; margin-bottom: 20px;">
                <div class="profile-upload" style="display: flex; flex-direction: column; align-items: center; gap: 10px;">
                    <div class="avatar-preview" style="width: 100px; height: 100px; border-radius: 50%; overflow: hidden; background: #f3f4f6; display: flex; align-items: center; justify-content: center; border: 2px solid #e5e7eb;">
                        <img id="edit-avatar-preview" src="" alt="Profile Preview" style="width: 100%; height: 100%; object-fit: cover; display: none;">
                        <span id="edit-avatar-initials" style="font-size: 2.5rem; color: #6b7280; font-weight: 600;"></span>
                    </div>
                    <div class="file-input-wrapper">
                        <label for="edit-profile-image" class="btn btn-outline btn-sm" style="cursor: pointer;">
                            <i class="fas fa-camera"></i> Change Photo
                        </label>
                        <input type="file" id="edit-profile-image" name="profile_image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                    </div>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-name">Full Name *</label>
                    <input type="text" id="edit-name" name="name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="edit-email">Email Address *</label>
                    <input type="email" id="edit-email" name="email" required class="form-control">
                </div>
            </div>



            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editAccountModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    Update Account
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2>Change Account Password</h2>
            <button class="modal-close" onclick="closeModal('changePasswordModal')">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form class="modal-form" id="changePasswordForm">
            <div class="form-group">
                <label for="current-password">Current Password *</label>
                <div class="input-wrapper" style="position: relative;">
                    <input type="password" id="current-password" name="current_password" required class="form-control" style="padding-right: 40px;">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('current-password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280;"></i>
                </div>
                <small class="form-help">Enter the current password to verify your identity</small>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label for="new-password">New Password *</label>
                    <div class="input-wrapper" style="position: relative;">
                        <input type="password" id="new-password" name="new_password" required class="form-control" style="padding-right: 40px;">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('new-password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280;"></i>
                    </div>
                    <div class="password-strength" id="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                        <span class="strength-text" id="strength-text">Weak</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-new-password">Confirm New Password *</label>
                    <div class="input-wrapper" style="position: relative;">
                        <input type="password" id="confirm-new-password" name="confirm_new_password" required class="form-control" style="padding-right: 40px;">
                        <i class="fas fa-eye toggle-password" onclick="togglePassword('confirm-new-password', this)" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #6b7280;"></i>
                    </div>
                </div>

            </div>

            <div class="password-requirements">
                <h4>Password Requirements:</h4>
                <ul>
                    <li id="req-length">At least 8 characters</li>
                    <li id="req-uppercase">One uppercase letter</li>
                    <li id="req-lowercase">One lowercase letter</li>
                    <li id="req-number">One number</li>
                    <li id="req-special">One special character</li>

                </ul>
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal('changePasswordModal')">
                    Cancel
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-key"></i>
                    Change Password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Toggle Password Visibility
    function togglePassword(inputId, icon) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }

    // Modal functions
    function createAccount() {
        document.getElementById('createAccountModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Table functions
    // Table functions


    function refreshTable() {
        location.reload();
    }

    function viewAccount(id) {
        fetch(`{{ url('/super_admin/super_admin_accounts') }}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(accountData => {
                document.getElementById('view-name').textContent = accountData.name;
                document.getElementById('view-email').textContent = accountData.email;
                document.getElementById('view-status').textContent = accountData.status.charAt(0).toUpperCase() + accountData.status.slice(1);
                document.getElementById('view-created').textContent = accountData.created;

                const statusBadge = document.getElementById('view-status');
                statusBadge.className = `status-badge ${accountData.status}`;

                // Handle Profile Image Logic
                const viewAvatarImg = document.getElementById('view-avatar-img');
                const viewInitials = document.getElementById('view-initials');

                if (accountData.profile_image) {
                    viewAvatarImg.src = `{{ asset('storage') }}/${accountData.profile_image}`;
                    viewAvatarImg.style.display = 'block';
                    viewInitials.style.display = 'none';
                    viewAvatarImg.style.width = '100%'; 
                    viewAvatarImg.style.height = '100%';
                    viewAvatarImg.style.objectFit = 'cover';
                    viewAvatarImg.style.borderRadius = '50%';
                } else {
                    viewAvatarImg.style.display = 'none';
                    viewInitials.style.display = 'flex'; // Ensure flex to center text
                    
                    // Generate Initials
                    const nameParts = accountData.name.split(' ');
                    const initials = nameParts.length > 1 
                        ? (nameParts[0][0] + nameParts[1][0]).toUpperCase() 
                        : accountData.name.substring(0, 2).toUpperCase();
                    viewInitials.textContent = initials;
                }

                // Update permissions

                window.currentAccountId = id;
                document.getElementById('viewAccountModal').style.display = 'block';
            })
            .catch(() => {
                alert('Failed to load account details.');
            });
    }

    function editAccount(id) {
        fetch(`{{ url('/super_admin/super_admin_accounts') }}/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
            .then(response => response.json())
            .then(accountData => {
                document.getElementById('edit-name').value = accountData.name;
                document.getElementById('edit-email').value = accountData.email;

                // Handle Profile Image Preview
                const previewImg = document.getElementById('edit-avatar-preview');
                const previewInitials = document.getElementById('edit-avatar-initials');
                
                if (accountData.profile_image) {
                    previewImg.src = `{{ asset('storage') }}/${accountData.profile_image}`;
                    previewImg.style.display = 'block';
                    previewInitials.style.display = 'none';
                } else {
                    previewImg.src = '';
                    previewImg.style.display = 'none';
                    previewInitials.style.display = 'block';
                    const nameParts = accountData.name.split(' ');
                    const initials = nameParts.length > 1 
                        ? (nameParts[0][0] + nameParts[1][0]).toUpperCase() 
                        : accountData.name.substring(0, 2).toUpperCase();
                    previewInitials.textContent = initials;
                }

                window.currentAccountId = id;
                document.getElementById('editAccountModal').style.display = 'block';
            })
            .catch(() => {
                alert('Failed to load account details.');
            });
    }

    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('edit-avatar-preview');
                const previewInitials = document.getElementById('edit-avatar-initials');
                
                previewImg.src = e.target.result;
                previewImg.style.display = 'block';
                previewInitials.style.display = 'none';
            }
            
            reader.readAsDataURL(input.files[0]);
        }
    }

    function editAccountFromView() {
        closeModal('viewAccountModal');
        editAccount(window.currentAccountId);

    }



    function resetPassword(id) {
        window.currentAccountId = id;
        document.getElementById('current-password').value = '';
        document.getElementById('new-password').value = '';
        document.getElementById('confirm-new-password').value = '';
        document.getElementById('strength-fill').className = 'strength-fill';
        document.getElementById('strength-text').textContent = 'Weak';
        document.querySelectorAll('.password-requirements li').forEach(li => {
            li.classList.remove('valid');
        });
        document.getElementById('changePasswordModal').style.display = 'block';
    }

    function deleteAccount(id) {
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`{{ url('/super_admin/super_admin_accounts') }}/${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire('Deleted!', 'Account deleted successfully.', 'success')
                        .then(() => location.reload());
                    } else {
                        Swal.fire('Error', data.message || 'Failed to delete account.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Error deleting account.', 'error');
                });
            }
        });
    }

    document.getElementById('new-password').addEventListener('input', function() {
        const password = this.value;
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');

        const requirements = {
            length: password.length >= 8,
            uppercase: /[A-Z]/.test(password),
            lowercase: /[a-z]/.test(password),
            number: /[0-9]/.test(password),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
        };


        document.getElementById('req-length').classList.toggle('valid', requirements.length);
        document.getElementById('req-uppercase').classList.toggle('valid', requirements.uppercase);
        document.getElementById('req-lowercase').classList.toggle('valid', requirements.lowercase);
        document.getElementById('req-number').classList.toggle('valid', requirements.number);
        document.getElementById('req-special').classList.toggle('valid', requirements.special);


        const validRequirements = Object.values(requirements).filter(Boolean).length;
        let strength = 'weak';
        let strengthClass = 'weak';

        if (validRequirements >= 4 && password.length >= 8) {
            strength = 'strong';
            strengthClass = 'strong';
        } else if (validRequirements >= 3 && password.length >= 6) {
            strength = 'good';
            strengthClass = 'good';
        } else if (validRequirements >= 2 && password.length >= 6) {
            strength = 'fair';
            strengthClass = 'fair';
        }

        strengthFill.className = `strength-fill ${strengthClass}`;
        strengthText.textContent = strength.charAt(0).toUpperCase() + strength.slice(1);
    });

    document.getElementById('editAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = window.currentAccountId;
        const form = e.target;
        const formData = new FormData(form);

        formData.delete('permissions[]');
        // Removed permissions, status, and role appending



        fetch(`{{ url('/super_admin/super_admin_accounts') }}/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: formData
        })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch {
                    data = {};
                }
                if (response.ok && data.success) {
                    Swal.fire('Success', 'Account updated successfully!', 'success')
                    .then(() => {
                        closeModal('editAccountModal');
                        location.reload();
                    });
                } else if (data.errors) {
                    let msg = '';
                    Object.values(data.errors).forEach(errArr => {
                        msg += errArr.join(', ') + '\n';
                    });
                    Swal.fire('Validation Error', msg, 'error');
                } else if (data.message) {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error updating account.', 'error');
                console.error(error);
            });
    });

    document.getElementById('changePasswordForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const currentPassword = document.getElementById('current-password').value;
        const newPassword = document.getElementById('new-password').value;
        const confirmPassword = document.getElementById('confirm-new-password').value;

        if (newPassword !== confirmPassword) {
            alert('New passwords do not match!');
            return;
        }

        if (newPassword.length < 8) {
            alert('Password must be at least 8 characters long!');
            return;
        }

        const requirements = {
            length: newPassword.length >= 8,
            uppercase: /[A-Z]/.test(newPassword),
            lowercase: /[a-z]/.test(newPassword),
            number: /[0-9]/.test(newPassword),
            special: /[!@#$%^&*(),.?":{}|<>]/.test(newPassword)
        };

        const validRequirements = Object.values(requirements).filter(Boolean).length;
        if (validRequirements < 3) {
            alert('Password does not meet security requirements!');
            return;
        }

        fetch(`{{ url('/super_admin/super_admin_accounts') }}/${window.currentAccountId}/change-password`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-HTTP-Method-Override': 'PUT'
            },
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                new_password_confirmation: confirmPassword
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                Swal.fire('Success', 'Password changed successfully!', 'success')
                .then(() => {
                    closeModal('changePasswordModal');
                    location.reload();
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to change password.', 'error');
            }
        })
        .catch(() => {
            Swal.fire('Error', 'Error changing password.', 'error');
        });
    });

    document.getElementById('createAccountForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        if (formData.get('password') !== formData.get('password_confirmation')) {
            alert('Passwords do not match!');
            return;
        }

        const permissions = [];
        form.querySelectorAll('input[name="permissions[]"]:checked').forEach(cb => {
            permissions.push(cb.value);
        });
        formData.delete('permissions[]');
        if (permissions.length === 0) {
            formData.append('permissions', []);
        } else {
            permissions.forEach(p => formData.append('permissions[]', p));
        }

        fetch('{{ url("/super_admin/super_admin_accounts") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: formData
        })
            .then(async response => {
                let data;
                try {
                    data = await response.json();
                } catch {
                    data = {};
                }
                if (response.ok && data.success) {
                    Swal.fire('Success', 'Super Admin account created successfully!', 'success')
                    .then(() => {
                        closeModal('createAccountModal');
                        location.reload();
                    });
                } else if (data.errors) {
                    let msg = '';
                    Object.values(data.errors).forEach(errArr => {
                        msg += errArr.join(', ') + '\n';
                    });
                    Swal.fire('Validation Error', msg, 'error');
                } else if (data.message) {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                Swal.fire('Error', 'Error creating account.', 'error');
                console.error(error);
            });
    });

    window.onclick = function(event) {
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            if (event.target === modal) {
                modal.style.display = 'none';
            }
        });
    }
</script>
@endsection
