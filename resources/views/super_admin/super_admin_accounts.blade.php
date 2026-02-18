@extends('layouts.super_admin')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/superAdminAccount.css') }}">
@section('title', 'Super Admin Account Management')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-user-cog"></i>
            Super Admin Account Management
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
        <button class="btn btn-secondary" onclick="exportAccounts()">
            <i class="fas fa-download"></i>
            Export Data
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
                <button class="btn btn-sm btn-outline" onclick="bulkAction()">
                    <i class="fas fa-tasks"></i>
                    Bulk Actions
                </button>
            </div>
        </div>

        <div class="table-container">
            <table class="data-table">
                <thead>
                <tr>
                    <th>
                        <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                    </th>
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
                    <td data-label="Select">
                        <input type="checkbox" class="row-select" value="{{ $user->super_admin_id }}">
                    </td>
                    <td data-label="Name">
                        <div class="user-info">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->super_admin_name, 0, 1)) }}
                            </div>
                            <div class="user-details">
                                <span class="user-name">{{ $user->super_admin_name }}</span>
                                <span class="user-id">ID: {{ $user->super_admin_id }}</span>
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
                            {{-- If you track last login, show it here, else show 'Never' --}}
                            {{ $user->last_login ?? 'Never' }}
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

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-history"></i>
                Recent Activities
            </h2>
        </div>

        <div class="activities-list">
            @foreach($recentActivities as $activity)
            <div class="activity-item">
                <div class="activity-icon {{ $activity['type'] }}">
                    <i class="{{ $activity['icon'] }}"></i>
                </div>
                <div class="activity-content">
                    <h4 class="activity-title">{{ $activity['action'] }}</h4>
                    <p class="activity-details">{{ $activity['details'] }}</p>
                    <span class="activity-time">{{ \Carbon\Carbon::parse($activity['timestamp'])->diffForHumans() }}</span>
                </div>
            </div>
            @endforeach
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
                    <input type="password" id="password" name="password" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Confirm Password *</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label for="permissions">Permissions</label>
                <div class="permissions-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="user_management" checked>
                        <span>User Management</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="system_settings" checked>
                        <span>System Settings</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="security" checked>
                        <span>Security Settings</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="reports" checked>
                        <span>Reports & Analytics</span>
                    </label>
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
                <div class="detail-section">
                    <h3>Basic Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Full Name:</label>
                            <span id="view-name">John Smith</span>
                        </div>
                        <div class="detail-item">
                            <label>Email:</label>
                            <span id="view-email">john.smith@company.com</span>
                        </div>
                        <div class="detail-item">
                            <label>Account ID:</label>
                            <span id="view-id">SA001</span>
                        </div>
                        <div class="detail-item">
                            <label>Status:</label>
                            <span class="status-badge active" id="view-status">Active</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Account Information</h3>
                    <div class="detail-grid">
                        <div class="detail-item">
                            <label>Created:</label>
                            <span id="view-created">January 15, 2024</span>
                        </div>
                        <div class="detail-item">
                            <label>Last Login:</label>
                            <span id="view-last-login">2 hours ago</span>
                        </div>
                        <div class="detail-item">
                            <label>Login Count:</label>
                            <span id="view-login-count">47</span>
                        </div>
                        <div class="detail-item">
                            <label>Last IP:</label>
                            <span id="view-last-ip">192.168.1.100</span>
                        </div>
                    </div>
                </div>

                <div class="detail-section">
                    <h3>Permissions</h3>
                    <div class="permissions-list" id="view-permissions">
                        <span class="permission-badge">User Management</span>
                        <span class="permission-badge">System Settings</span>
                        <span class="permission-badge">Security Settings</span>
                        <span class="permission-badge">Reports & Analytics</span>
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

            <div class="form-row">
                <div class="form-group">
                    <label for="edit-status">Account Status</label>
                    <select id="edit-status" name="status" class="form-control">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="suspended">Suspended</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="edit-role">Role</label>
                    <select id="edit-role" name="role" class="form-control">
                        <option value="super_admin">Super Admin</option>
                        <option value="system_admin">System Admin</option>
                        <option value="security_admin">Security Admin</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Permissions</label>
                <div class="permissions-grid">
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="user_management" id="edit-perm-user">
                        <span>User Management</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="system_settings" id="edit-perm-system">
                        <span>System Settings</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="security" id="edit-perm-security">
                        <span>Security Settings</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="reports" id="edit-perm-reports">
                        <span>Reports & Analytics</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="backup" id="edit-perm-backup">
                        <span>Backup & Restore</span>
                    </label>
                    <label class="checkbox-item">
                        <input type="checkbox" name="permissions[]" value="logs" id="edit-perm-logs">
                        <span>System Logs</span>
                    </label>
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
                <input type="password" id="current-password" name="current_password" required class="form-control">
                <small class="form-help">Enter the current password to verify your identity</small>
            </div>


            <div class="form-row">
                <div class="form-group">
                    <label for="new-password">New Password *</label>
                    <input type="password" id="new-password" name="new_password" required class="form-control">
                    <div class="password-strength" id="password-strength">
                        <div class="strength-bar">
                            <div class="strength-fill" id="strength-fill"></div>
                        </div>
                        <span class="strength-text" id="strength-text">Weak</span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="confirm-new-password">Confirm New Password *</label>
                    <input type="password" id="confirm-new-password" name="confirm_new_password" required class="form-control">
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
    // Modal functions
    function createAccount() {
        document.getElementById('createAccountModal').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Table functions
    function toggleSelectAll() {
        const selectAll = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.row-select');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    function refreshTable() {
        location.reload();
    }

    function bulkAction() {
        const selectedRows = document.querySelectorAll('.row-select:checked');
        if (selectedRows.length === 0) {
            alert('Please select at least one account.');
            return;
        }

        const action = prompt('Enter action (activate/deactivate/delete):');
        if (action) {
            // Implement bulk action logic here
            console.log('Bulk action:', action, 'on', selectedRows.length, 'accounts');
        }
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
                document.getElementById('view-id').textContent = accountData.id;
                document.getElementById('view-status').textContent = accountData.status.charAt(0).toUpperCase() + accountData.status.slice(1);
                document.getElementById('view-created').textContent = accountData.created;
                document.getElementById('view-last-login').textContent = accountData.lastLogin;
                document.getElementById('view-login-count').textContent = accountData.loginCount;
                document.getElementById('view-last-ip').textContent = accountData.lastIp;

                const statusBadge = document.getElementById('view-status');
                statusBadge.className = `status-badge ${accountData.status}`;

                // Update permissions
                const permissionsContainer = document.getElementById('view-permissions');
                permissionsContainer.innerHTML = accountData.permissions.map(perm =>
                    `<span class="permission-badge">${perm}</span>`
                ).join('');

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
                document.getElementById('edit-status').value = accountData.status;
                document.getElementById('edit-role').value = accountData.role;
                document.getElementById('edit-perm-user').checked = accountData.permissions.includes('User Management');
                document.getElementById('edit-perm-system').checked = accountData.permissions.includes('System Settings');
                document.getElementById('edit-perm-security').checked = accountData.permissions.includes('Security Settings');
                document.getElementById('edit-perm-reports').checked = accountData.permissions.includes('Reports & Analytics');
                document.getElementById('edit-perm-backup').checked = accountData.permissions.includes('Backup & Restore');
                document.getElementById('edit-perm-logs').checked = accountData.permissions.includes('System Logs');

                window.currentAccountId = id;
                document.getElementById('editAccountModal').style.display = 'block';
            })
            .catch(() => {
                alert('Failed to load account details.');
            });
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
        if (confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
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
                    alert('Account deleted successfully!');
                    location.reload();
                } else {
                    alert(data.message || 'Failed to delete account.');
                }
            })
            .catch(() => {
                alert('Error deleting account.');
        const strengthFill = document.getElementById('strength-fill');
        const strengthText = document.getElementById('strength-text');
            });
        }
    }

    function exportAccounts() {
        console.log('Exporting accounts data...');

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

        formData.set('status', document.getElementById('edit-status').value);
        formData.set('role', document.getElementById('edit-role').value);

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
                    alert('Account updated successfully!');
                    closeModal('editAccountModal');
                    location.reload();
                } else if (data.errors) {
                    // Show validation errors
                    let msg = 'Please fix the following errors:\n';
                    Object.values(data.errors).forEach(errArr => {
                        msg += '- ' + errArr.join(', ') + '\n';
                    });
                    alert(msg);
                } else if (data.message) {
                    alert(data.message);
                }
                // No generic fail alert
            })
            .catch(error => {
                alert('Error updating account.');
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
                alert('Password changed successfully!');
                closeModal('changePasswordModal');
                location.reload();
            } else {
                alert(data.message || 'Failed to change password.');
            }
        })
        .catch(() => {
            alert('Error changing password.');
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
                    alert('Super Admin account created successfully!');
                    closeModal('createAccountModal');
                    location.reload();
                } else if (data.errors) {
                    // Show validation errors
                    let msg = 'Please fix the following errors:\n';
                    Object.values(data.errors).forEach(errArr => {
                        msg += '- ' + errArr.join(', ') + '\n';
                    });
                    alert(msg);
                } else if (data.message) {
                    alert(data.message);
                }
                // No generic fail alert
            })
            .catch(error => {
                alert('Error creating account.');
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
