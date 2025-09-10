@extends('layouts.super_admin')

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
                    <tr>
                        <td>
                            <input type="checkbox" class="row-select" value="{{ $user->super_admin_id }}">
                        </td>
                        <td>
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
                        <td>{{ $user->super_admin_email }}</td>
                        <td>
                            <span class="status-badge active">
                                Active
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">Never</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($user->created_at)->format('M d, Y') }}</td>
                        <td>
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

<style>

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content p {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
}

.header-actions {
    display: flex;
    gap: 1rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
    background: var(--primary);
}

.stat-icon.active {
    background: var(--success);
}

.stat-icon.inactive {
    background: var(--warning);
}

.stat-icon.recent {
    background: var(--info);
}

.stat-number {
    font-size: 2rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.stat-label {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.875rem;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 2rem;
}

.content-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.card-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-title {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-actions {
    display: flex;
    gap: 0.5rem;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 1rem;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}

.data-table th {
    background: var(--bg-secondary);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.data-table tbody tr:hover {
    background: var(--bg-secondary);
}

/* User Info */
.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--gradient-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary);
}

.user-id {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Status Badge */
.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-badge.active {
    background: var(--success);
    color: #fff;
}

.status-badge.inactive {
    background: var(--warning);
    color: #fff;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
}

.btn-icon {
    width: 2rem;
    height: 2rem;
    border-radius: 0.375rem;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: var(--bg-secondary);
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-icon:hover {
    background: var(--primary);
    color: #fff;
}

.btn-icon.danger:hover {
    background: var(--danger);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.empty-content i {
    font-size: 3rem;
    color: var(--text-light);
}

.empty-content h3 {
    color: var(--text-primary);
    margin: 0;
}

.empty-content p {
    color: var(--text-secondary);
    margin: 0;
}

/* Activities List */
.activities-list {
    padding: 1.5rem;
}

.activity-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem 0;
    border-bottom: 1px solid var(--border-light);
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
    color: #fff;
}

.activity-icon.login {
    background: var(--success);
}

.activity-icon.create {
    background: var(--info);
}

.activity-icon.security {
    background: var(--warning);
}

.activity-icon.update {
    background: var(--primary);
}

.activity-content {
    flex: 1;
}

.activity-title {
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.25rem 0;
    font-size: 0.875rem;
}

.activity-details {
    color: var(--text-secondary);
    margin: 0 0 0.5rem 0;
    font-size: 0.75rem;
}

.activity-time {
    color: var(--text-light);
    font-size: 0.75rem;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
}

.modal-header {
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.modal-header h2 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: var(--text-secondary);
    cursor: pointer;
}

.modal-form {
    padding: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
    margin-bottom: 1rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-control {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

.permissions-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5rem;
}

.checkbox-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    cursor: pointer;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    margin-top: 1.5rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--border-light);
}

.btn-outline {
    background: transparent;
    color: var(--text-primary);
    border: 1px solid var(--border-light);
}

.btn-outline:hover {
    background: var(--bg-secondary);
}

.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.75rem;
}

/* Modal Body and Footer */
.modal-body {
    padding: 1.5rem;
    max-height: 60vh;
    overflow-y: auto;
}

.modal-footer {
    padding: 1.5rem;
    border-top: 1px solid var(--border-light);
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
}

/* Account Details */
.account-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.detail-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.detail-section h3::before {
    content: '';
    width: 4px;
    height: 1.125rem;
    background: var(--primary);
    border-radius: 2px;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.detail-item label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.detail-item span {
    color: var(--text-primary);
    font-size: 0.875rem;
}

/* Permissions List */
.permissions-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.permission-badge {
    background: var(--primary-light);
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid var(--primary);
}

/* Password Strength */
.password-strength {
    margin-top: 0.5rem;
}

.strength-bar {
    width: 100%;
    height: 4px;
    background: var(--border-light);
    border-radius: 2px;
    overflow: hidden;
    margin-bottom: 0.25rem;
}

.strength-fill {
    height: 100%;
    width: 0%;
    transition: all 0.3s ease;
    border-radius: 2px;
}

.strength-fill.weak {
    background: var(--danger);
    width: 25%;
}

.strength-fill.fair {
    background: var(--warning);
    width: 50%;
}

.strength-fill.good {
    background: var(--info);
    width: 75%;
}

.strength-fill.strong {
    background: var(--success);
    width: 100%;
}

.strength-text {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Password Requirements */
.password-requirements {
    background: var(--bg-secondary);
    padding: 1rem;
    border-radius: 0.5rem;
    margin-top: 1rem;
}

.password-requirements h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.75rem 0;
}

.password-requirements ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.5rem;
}

.password-requirements li {
    font-size: 0.75rem;
    color: var(--text-secondary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.password-requirements li::before {
    content: '○';
    color: var(--text-light);
    font-size: 0.875rem;
}

.password-requirements li.valid::before {
    content: '●';
    color: var(--success);
}

.password-requirements li.valid {
    color: var(--success);
}

/* Responsive */
@media (max-width: 1024px) {
    .content-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .stats-grid {
        grid-template-columns: 1fr;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .permissions-grid {
        grid-template-columns: 1fr;
    }

    .detail-grid {
        grid-template-columns: 1fr;
    }

    .password-requirements ul {
        grid-template-columns: 1fr;
    }
}
</style>

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
    // Store current account ID
    window.currentAccountId = id;

    // Clear form
    document.getElementById('current-password').value = '';
    document.getElementById('new-password').value = '';
    document.getElementById('confirm-new-password').value = '';

    // Reset password strength
    document.getElementById('strength-fill').className = 'strength-fill';
    document.getElementById('strength-text').textContent = 'Weak';

    // Reset requirements
    document.querySelectorAll('.password-requirements li').forEach(li => {
        li.classList.remove('valid');
    });

    // Show modal
    document.getElementById('changePasswordModal').style.display = 'block';
}

function deleteAccount(id) {
    if (confirm('Are you sure you want to delete this account? This action cannot be undone.')) {
        console.log('Delete account:', id);
        // Implement delete account logic
    }
}

function exportAccounts() {
    console.log('Exporting accounts data...');
    // Implement export functionality
}

// Password strength validation
document.getElementById('new-password').addEventListener('input', function() {
    const password = this.value;
    const strengthFill = document.getElementById('strength-fill');
    const strengthText = document.getElementById('strength-text');

    // Check requirements
    const requirements = {
        length: password.length >= 8,
        uppercase: /[A-Z]/.test(password),
        lowercase: /[a-z]/.test(password),
        number: /[0-9]/.test(password),
        special: /[!@#$%^&*(),.?":{}|<>]/.test(password)
    };

    // Update requirement indicators
    document.getElementById('req-length').classList.toggle('valid', requirements.length);
    document.getElementById('req-uppercase').classList.toggle('valid', requirements.uppercase);
    document.getElementById('req-lowercase').classList.toggle('valid', requirements.lowercase);
    document.getElementById('req-number').classList.toggle('valid', requirements.number);
    document.getElementById('req-special').classList.toggle('valid', requirements.special);

    // Calculate strength
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

    fetch(`{{ url('/super_admin/super_admin_accounts') }}/${id}`, {
        method: 'PUT',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Account updated successfully!');
            closeModal('editAccountModal');
            location.reload();
        } else {
            alert('Failed to update account.');
        }
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

    // Validation
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

    console.log('Changing password for account:', window.currentAccountId);


    setTimeout(() => {
        alert('Password changed successfully!');
        closeModal('changePasswordModal');
    }, 1000);
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
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Super Admin account created successfully!');
            closeModal('createAccountModal');
            location.reload();
        } else {
            alert('Failed to create account.');
        }
    })
    .catch(error => {
        alert('Error creating account.');
        console.error(error);
    });
});

// Close modal when clicking outside
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
