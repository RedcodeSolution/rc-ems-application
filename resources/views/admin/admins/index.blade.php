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
/* Modern Admin Management Styles */
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
.btn-secondary {
    background: var(--primary-light);
    color: var(--text-secondary);
    border: none;
}
.btn-secondary:hover {
    background: var(--divider);
}
.btn-warning {
    background: var(--warning);
    color: #fff;
    border: none;
}
.btn-warning:hover {
    background: #ffb300;
}
.btn-danger {
    background: var(--danger);
    color: #fff;
    border: none;
}
.btn-danger:hover {
    background: #d84315;
}
.btn-success {
    background: var(--success);
    color: #fff;
    border: none;
}
.btn-success:hover {
    background: #388e3c;
}
.btn-info {
    background: var(--info);
    color: #fff;
    border: none;
}
.btn-info:hover {
    background: #007c91;
}
.table-container {
    overflow-x: auto;
    border-radius: 0.75rem;
    box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
    background: #fff;
}
.table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    font-size: 0.97rem;
}
.table th, .table td {
    padding: 1rem 1.25rem;
    text-align: left;
    vertical-align: middle;
}
.table th {
    background: var(--primary-light);
    color: var(--text-primary);
    border-bottom: 2px solid var(--divider);
}
.table tr {
    transition: background 0.15s;
}
.table tbody tr:hover {
    background: var(--divider);
}
.user-avatar {
    background: linear-gradient(135deg, var(--primary) 40%, var(--secondary) 100%);
    color: #fff;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
}
.badge {
    font-weight: 600;
    letter-spacing: 0.02em;
    display: inline-block;
}
.form-input, .form-select {
    border-radius: 0.5rem;
    border: 1px solid var(--divider);
    background: var(--primary-light);
    padding: 0.5rem 1rem;
    font-size: 1rem;
    transition: border 0.2s, box-shadow 0.2s;
}
.form-input:focus, .form-select:focus {
    border-color: var(--primary);
    outline: none;
    box-shadow: 0 0 0 2px #d32f2f22;
}
.flex {
    display: flex;
}
.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.75rem; }
.gap-3 { gap: 1.25rem; }
.justify-between { justify-content: space-between; }
.items-center { align-items: center; }
.text-center { text-align: center; }
.mt-4 { margin-top: 1.5rem; }
.mb-4 { margin-bottom: 1.5rem; }
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .table th, .table td { padding: 0.75rem 0.5rem; }
}
::-webkit-scrollbar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}
</style>

@section('title', 'Admin Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-user-shield"></i> Admin Management</h2>
        <div class="flex gap-2">
            <button onclick="openAddAdminModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Admin
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <div class="search-container">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" id="searchInput" placeholder="Search admins..." class="form-input search-input" style="width: 300px; padding-left: 40px;" onkeyup="searchAdmins()">
                </div>
                <select id="roleFilter" class="form-select" style="width: 200px;" onchange="filterAdmins()">
                    <option value="">All Roles</option>
                    <option value="Super Admin">Super Admin</option>
                    <option value="Admin">Admin</option>
                    <option value="HR Admin">HR Admin</option>
                    <option value="Department Admin">Department Admin</option>
                </select>
                <select id="statusFilter" class="form-select" style="width: 150px;" onchange="filterAdmins()">
                    <option value="">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-refresh"></i>
                    Reset
                </button>
            </div>
        </div>

        <!-- Admins Table -->
        <div class="table-container">
            <table class="table" id="adminsTable">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Role</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Last Login</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr data-admin-id="ADM001" data-role="Super Admin" data-status="Active">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">SA</div>
                                <div>
                                    <div style="font-weight: 600;">Super Admin</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">ADM001</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Super Admin</span>
                        </td>
                        <td>All Departments</td>
                        <td>superadmin@company.com</td>
                        <td>2 hours ago</td>
                        <td>
                            <span class="badge status-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewAdmin('ADM001')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editAdmin('ADM001')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="changePassword('ADM001')" title="Change Password">
                                    <i class="fas fa-key"></i>
                                </button>
                                <button class="btn btn-success" style="padding: 0.5rem;" onclick="sendNotification('ADM001')" title="Send Notification">
                                    <i class="fas fa-bell"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-admin-id="ADM002" data-role="Admin" data-status="Active">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">JS</div>
                                <div>
                                    <div style="font-weight: 600;">John Smith</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">ADM002</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Admin</span>
                        </td>
                        <td>Development</td>
                        <td>john.smith@company.com</td>
                        <td>1 day ago</td>
                        <td>
                            <span class="badge status-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewAdmin('ADM002')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editAdmin('ADM002')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteAdmin('ADM002')" title="Delete Admin">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="changePassword('ADM002')" title="Change Password">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-admin-id="ADM003" data-role="HR Admin" data-status="Active">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">LA</div>
                                <div>
                                    <div style="font-weight: 600;">Lisa Anderson</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">ADM003</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">HR Admin</span>
                        </td>
                        <td>Human Resources</td>
                        <td>lisa.anderson@company.com</td>
                        <td>3 hours ago</td>
                        <td>
                            <span class="badge status-badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewAdmin('ADM003')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editAdmin('ADM003')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteAdmin('ADM003')" title="Delete Admin">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="changePassword('ADM003')" title="Change Password">
                                    <i class="fas fa-key"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr data-admin-id="ADM004" data-role="Department Admin" data-status="Inactive">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">MJ</div>
                                <div>
                                    <div style="font-weight: 600;">Mike Johnson</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">ADM004</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Department Admin</span>
                        </td>
                        <td>Marketing</td>
                        <td>mike.johnson@company.com</td>
                        <td>5 days ago</td>
                        <td>
                            <span class="badge status-badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Inactive</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewAdmin('ADM004')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="editAdmin('ADM004')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-success" style="padding: 0.5rem;" onclick="activateAdmin('ADM004')" title="Activate Admin">
                                    <i class="fas fa-power-off"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;" onclick="deleteAdmin('ADM004')" title="Delete Admin">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing 1 to 4 of 8 admins
            </div>
            <div class="flex gap-1">
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;">1</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">2</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Admin Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">8</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Admins</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">6</div>
            <div style="color: var(--gray-600); font-weight: 500;">Active</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">2</div>
            <div style="color: var(--gray-600); font-weight: 500;">Inactive</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">1</div>
            <div style="color: var(--gray-600); font-weight: 500;">Super Admin</div>
        </div>
    </div>
</div>

<!-- View Admin Modal -->
<div id="viewAdminModal" class="admin-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeViewAdminModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> View Admin Details</h3>
            <button type="button" class="modal-close" onclick="closeViewAdminModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="admin-details">
                <div class="admin-avatar-section">
                    <div class="admin-avatar-large">
                        <span id="viewAdminInitials">JS</span>
                    </div>
                    <div class="admin-basic-info">
                        <h4 id="viewAdminName">John Smith</h4>
                        <p id="viewAdminId">ADM002</p>
                        <span id="viewAdminRole" class="badge">Admin</span>
                    </div>
                </div>
                
                <div class="details-grid">
                    <div class="detail-item">
                        <i class="fas fa-envelope detail-icon"></i>
                        <div>
                            <label>Email</label>
                            <span id="viewAdminEmail">john.smith@company.com</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-phone detail-icon"></i>
                        <div>
                            <label>Contact</label>
                            <span id="viewAdminContact">+1 234 567 8900</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-building detail-icon"></i>
                        <div>
                            <label>Department</label>
                            <span id="viewAdminDepartment">Development</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-calendar detail-icon"></i>
                        <div>
                            <label>Last Login</label>
                            <span id="viewAdminLastLogin">1 day ago</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-toggle-on detail-icon"></i>
                        <div>
                            <label>Status</label>
                            <span id="viewAdminStatus" class="badge">Active</span>
                        </div>
                    </div>
                    <div class="detail-item">
                        <i class="fas fa-user-plus detail-icon"></i>
                        <div>
                            <label>Created</label>
                            <span id="viewAdminCreated">2 months ago</span>
                        </div>
                    </div>
                </div>
                
                <div class="permissions-section">
                    <h5><i class="fas fa-key"></i> Permissions</h5>
                    <div class="permissions-list" id="viewAdminPermissions">
                        <span class="permission-badge">Manage Users</span>
                        <span class="permission-badge">Manage Reports</span>
                        <span class="permission-badge">Manage Projects</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Admin Modal -->
<div id="editAdminModal" class="admin-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeEditAdminModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Admin</h3>
            <button type="button" class="modal-close" onclick="closeEditAdminModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="editAdminForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_admin_name" class="form-label">
                                <i class="fas fa-user"></i> Admin Name
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="edit_admin_name" name="admin_name" class="form-field" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_role" class="form-label">
                                <i class="fas fa-shield-alt"></i> Role
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-shield-alt"></i>
                                <select id="edit_role" name="role" class="form-field" required>
                                    <option value="Admin">Admin</option>
                                    <option value="HR Admin">HR Admin</option>
                                    <option value="Department Admin">Department Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" id="edit_email" name="email" class="form-field" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_contact" class="form-label">
                                <i class="fas fa-phone"></i> Contact
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" id="edit_contact" name="contact_no" class="form-field" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department" class="form-label">
                                <i class="fas fa-building"></i> Department
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-building"></i>
                                <select id="edit_department" name="department_id" class="form-field" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments ?? [] as $department)
                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_status" class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-toggle-on"></i>
                                <select id="edit_status" name="status" class="form-field" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditAdminModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteAdminModal" class="admin-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeDeleteAdminModal()"></div>
    <div class="modal-container delete-modal">
        <div class="modal-header">
            <h3><i class="fas fa-trash"></i> Delete Admin</h3>
            <button type="button" class="modal-close" onclick="closeDeleteAdminModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="delete-warning">
                <div class="warning-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="warning-content">
                    <h4>Are you sure?</h4>
                    <p>This action cannot be undone. The admin account will be permanently deleted.</p>
                    <div class="admin-to-delete">
                        <strong>Admin:</strong> <span id="deleteAdminName">John Smith</span><br>
                        <strong>ID:</strong> <span id="deleteAdminId">ADM002</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-actions">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteAdminModal()">
                <i class="fas fa-times"></i> Cancel
            </button>
            <button type="button" class="btn btn-danger" onclick="confirmDeleteAdmin()">
                <i class="fas fa-trash"></i> Delete Admin
            </button>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div id="changePasswordModal" class="admin-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeChangePasswordModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-key"></i> Change Password</h3>
            <button type="button" class="modal-close" onclick="closeChangePasswordModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="changePasswordForm" action="" method="POST">
                @csrf
                <div class="form-container">
                    <div class="admin-info-header">
                        <h5 id="passwordAdminName">John Smith</h5>
                        <p id="passwordAdminId">ADM002</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="new_password" class="form-label">
                            <i class="fas fa-lock"></i> New Password
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" id="new_password" name="new_password" class="form-field" placeholder="Enter new password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">
                            <i class="fas fa-lock"></i> Confirm Password
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-lock"></i>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-field" placeholder="Confirm new password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="send_email" name="send_email" checked>
                            <label for="send_email">Send new password via email</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeChangePasswordModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Change Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Send Notification Modal -->
<div id="sendNotificationModal" class="admin-modal" style="display: none;">
    <div class="modal-backdrop" onclick="closeSendNotificationModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-bell"></i> Send Notification</h3>
            <button type="button" class="modal-close" onclick="closeSendNotificationModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="sendNotificationForm" action="" method="POST">
                @csrf
                <div class="form-container">
                    <div class="admin-info-header">
                        <h5 id="notificationAdminName">John Smith</h5>
                        <p id="notificationAdminId">ADM002</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="notification_type" class="form-label">
                            <i class="fas fa-tag"></i> Notification Type
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-tag"></i>
                            <select id="notification_type" name="notification_type" class="form-field" required>
                                <option value="">Select Type</option>
                                <option value="info">Information</option>
                                <option value="warning">Warning</option>
                                <option value="urgent">Urgent</option>
                                <option value="reminder">Reminder</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notification_title" class="form-label">
                            <i class="fas fa-heading"></i> Title
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-heading"></i>
                            <input type="text" id="notification_title" name="title" class="form-field" placeholder="Enter notification title" required>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="notification_message" class="form-label">
                            <i class="fas fa-comment"></i> Message
                        </label>
                        <div class="input-group">
                            <i class="input-icon fas fa-comment"></i>
                            <textarea id="notification_message" name="message" class="form-field" rows="4" placeholder="Enter notification message" required></textarea>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <div class="checkbox-group">
                            <input type="checkbox" id="send_email_notification" name="send_email" checked>
                            <label for="send_email_notification">Send via email</label>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeSendNotificationModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-paper-plane"></i> Send Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="addAdminModal" class="admin-modal" style="display: none;">>
    <div class="modal-backdrop" onclick="closeAddAdminModal()"></div>
    <div class="modal-container">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Admin</h3>
            <button type="button" class="modal-close" onclick="closeAddAdminModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="addAdminForm" action="{{ route('admins.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="admin_id" class="form-label">
                                <i class="fas fa-id-badge"></i> Admin ID
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-id-badge"></i>
                                <input type="text" id="admin_id" name="admin_id" class="form-field" placeholder="Enter admin ID" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="admin_name" class="form-label">
                                <i class="fas fa-user"></i> Admin Name
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="admin_name" name="admin_name" class="form-field" placeholder="Enter admin name" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">
                                <i class="fas fa-id-card"></i> Employee ID
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-id-card"></i>
                                <select id="employee_id" name="employee_id" class="form-field" required>
                                    <option value="">Select Employee</option>
                                    @foreach($employees ?? [] as $employee)
                                        <option value="{{ $employee->employee_id }}">{{ $employee->employee_id }} - {{ $employee->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-shield-alt"></i> Role
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-shield-alt"></i>
                                <select id="role" name="role" class="form-field" required>
                                    <option value="">Select Role</option>
                                    <option value="Admin">Admin</option>
                                    <option value="HR Admin">HR Admin</option>
                                    <option value="Department Admin">Department Admin</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department_id" class="form-label">
                                <i class="fas fa-building"></i> Department
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-building"></i>
                                <select id="department_id" name="department_id" class="form-field" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments ?? [] as $department)
                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-envelope"></i>
                                <input type="email" id="email" name="email" class="form-field" placeholder="Enter email address" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone"></i> Contact Number
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" id="contact_no" name="contact_no" class="form-field" placeholder="Enter contact number" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <div class="input-group">
                                <i class="input-icon fas fa-toggle-on"></i>
                                <select id="status" name="status" class="form-field" required>
                                    <option value="">Select Status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="permissions" class="form-label">
                            <i class="fas fa-key"></i> Permissions
                        </label>
                        <div class="permission-grid">
                            <div class="permission-item">
                                <input type="checkbox" id="perm_users" name="permissions[]" value="manage_users">
                                <label for="perm_users">Manage Users</label>
                            </div>
                            <div class="permission-item">
                                <input type="checkbox" id="perm_reports" name="permissions[]" value="manage_reports">
                                <label for="perm_reports">Manage Reports</label>
                            </div>
                            <div class="permission-item">
                                <input type="checkbox" id="perm_projects" name="permissions[]" value="manage_projects">
                                <label for="perm_projects">Manage Projects</label>
                            </div>
                            <div class="permission-item">
                                <input type="checkbox" id="perm_announcements" name="permissions[]" value="manage_announcements">
                                <label for="perm_announcements">Manage Announcements</label>
                            </div>
                            <div class="permission-item">
                                <input type="checkbox" id="perm_departments" name="permissions[]" value="manage_departments">
                                <label for="perm_departments">Manage Departments</label>
                            </div>
                            <div class="permission-item">
                                <input type="checkbox" id="perm_settings" name="permissions[]" value="manage_settings">
                                <label for="perm_settings">System Settings</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddAdminModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Create Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openAddAdminModal() {
    document.getElementById('addAdminModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeAddAdminModal() {
    document.getElementById('addAdminModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.getElementById('addAdminForm').reset();
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAddAdminModal();
    }
});

// Form submission handling
document.getElementById('addAdminForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const adminId = document.getElementById('admin_id').value;
    const adminName = document.getElementById('admin_name').value;
    const employeeId = document.getElementById('employee_id').value;
    const role = document.getElementById('role').value;
    const email = document.getElementById('email').value;
    const contactNo = document.getElementById('contact_no').value;
    const status = document.getElementById('status').value;
    
    if (!adminId || !adminName || !employeeId || !role || !email || !contactNo || !status) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Email validation
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(email)) {
        alert('Please enter a valid email address');
        return;
    }
    
    // Phone validation
    const phonePattern = /^[\d\s\-\+\(\)]+$/;
    if (!phonePattern.test(contactNo)) {
        alert('Please enter a valid contact number');
        return;
    }
    
    // Submit the form
    this.submit();
});

// Enhanced Admin Management Functions
let currentAdminId = null;

// Search functionality
function searchAdmins() {
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const rows = document.querySelectorAll('#adminsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Filter functionality
function filterAdmins() {
    const roleFilter = document.getElementById('roleFilter').value;
    const statusFilter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#adminsTable tbody tr');
    
    rows.forEach(row => {
        const role = row.getAttribute('data-role');
        const status = row.getAttribute('data-status');
        
        let showRow = true;
        
        if (roleFilter && role !== roleFilter) {
            showRow = false;
        }
        
        if (statusFilter && status !== statusFilter) {
            showRow = false;
        }
        
        row.style.display = showRow ? '' : 'none';
    });
}

// Reset filters
function resetFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('roleFilter').value = '';
    document.getElementById('statusFilter').value = '';
    
    const rows = document.querySelectorAll('#adminsTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
}

// Modal functions
function viewAdmin(adminId) {
    currentAdminId = adminId;
    // Populate modal with admin data
    document.getElementById('viewAdminModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeViewAdminModal() {
    document.getElementById('viewAdminModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function editAdmin(adminId) {
    currentAdminId = adminId;
    // Populate form with admin data
    document.getElementById('editAdminModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeEditAdminModal() {
    document.getElementById('editAdminModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function deleteAdmin(adminId) {
    currentAdminId = adminId;
    // Set admin name and ID in modal
    document.getElementById('deleteAdminModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeDeleteAdminModal() {
    document.getElementById('deleteAdminModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function confirmDeleteAdmin() {
    if (currentAdminId) {
        // Implement delete logic here
        console.log('Delete admin:', currentAdminId);
        alert('Admin deleted successfully');
        closeDeleteAdminModal();
    }
}

function changePassword(adminId) {
    currentAdminId = adminId;
    document.getElementById('changePasswordModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeChangePasswordModal() {
    document.getElementById('changePasswordModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function sendNotification(adminId) {
    currentAdminId = adminId;
    document.getElementById('sendNotificationModal').style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeSendNotificationModal() {
    document.getElementById('sendNotificationModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

function activateAdmin(adminId) {
    if (confirm('Are you sure you want to activate this admin?')) {
        // Implement activation logic here
        console.log('Activate admin:', adminId);
        alert('Admin activated successfully');
    }
}

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    const button = field.nextElementSibling;
    const icon = button.querySelector('i');
    
    if (field.type === 'password') {
        field.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        field.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>

<style>
/* Add Admin Modal Styles */
.admin-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    padding: 20px;
}

.modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(5px);
}

.modal-container {
    background: white;
    border-radius: 1rem;
    width: 100%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    position: relative;
    z-index: 1001;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem 2rem;
    border-bottom: 1px solid var(--divider);
    background: linear-gradient(90deg, var(--primary-light) 60%, #fff 100%);
    border-radius: 1rem 1rem 0 0;
}

.modal-header h3 {
    margin: 0;
    color: var(--text-primary);
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-header h3 i {
    color: var(--primary);
    margin-right: 0.5rem;
}

.modal-close {
    background: rgba(220, 38, 38, 0.1);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--primary);
}

.modal-close:hover {
    background: rgba(220, 38, 38, 0.2);
    transform: scale(1.1);
}

.modal-body {
    padding: 2rem;
}

.form-container {
    display: grid;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    position: relative;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.form-label i {
    color: var(--primary);
    margin-right: 0.5rem;
}

.input-group {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--primary);
    font-size: 16px;
    z-index: 1;
    pointer-events: none;
    transition: all 0.3s ease;
}

.form-field {
    width: 100%;
    padding: 12px 16px 12px 48px;
    border: 2px solid var(--divider);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
    background: white;
    color: var(--text-primary);
}

.form-field:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(211, 47, 47, 0.1);
}

.form-field:focus + .input-icon {
    color: var(--primary);
}

.permission-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-top: 0.5rem;
}

.permission-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem;
    border: 1px solid var(--divider);
    border-radius: 0.5rem;
    background: var(--primary-light);
    transition: all 0.3s ease;
}

.permission-item:hover {
    background: white;
    border-color: var(--primary);
}

.permission-item input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
}

.permission-item label {
    font-size: 0.875rem;
    color: var(--text-primary);
    cursor: pointer;
    margin: 0;
}

.modal-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--divider);
}

/* Responsive Design */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        max-width: none;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .permission-grid {
        grid-template-columns: 1fr;
    }
    
    .modal-header, .modal-body {
        padding: 1rem;
    }
}

/* Scrollbar Styling */
.modal-container::-webkit-scrollbar {
    width: 8px;
}

.modal-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Enhanced Search and Filter Styles */
.search-container {
    position: relative;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    z-index: 1;
}

.search-input {
    padding-left: 40px;
}

/* View Admin Modal Styles */
.admin-details {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.admin-avatar-section {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    padding: 1.5rem;
    background: var(--primary-light);
    border-radius: 1rem;
    margin-bottom: 1rem;
}

.admin-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary) 40%, var(--secondary) 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 2rem;
    font-weight: 700;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.admin-basic-info h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1.5rem;
}

.admin-basic-info p {
    margin: 0 0 0.5rem 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.details-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: white;
    border: 1px solid var(--divider);
    border-radius: 0.75rem;
    transition: all 0.3s ease;
}

.detail-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-2px);
}

.detail-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: var(--primary-light);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary);
    font-size: 1.1rem;
}

.detail-item div {
    display: flex;
    flex-direction: column;
}

.detail-item label {
    font-size: 0.75rem;
    color: var(--text-secondary);
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.05em;
    margin-bottom: 0.25rem;
}

.detail-item span {
    font-weight: 600;
    color: var(--text-primary);
}

.permissions-section {
    margin-top: 1rem;
}

.permissions-section h5 {
    margin-bottom: 1rem;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.permissions-list {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.permission-badge {
    background: var(--primary);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 1rem;
    font-size: 0.875rem;
    font-weight: 500;
}

/* Delete Modal Styles */
.delete-modal {
    max-width: 500px;
}

.delete-warning {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    text-align: left;
}

.warning-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(239, 68, 68, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--danger);
    font-size: 1.5rem;
    flex-shrink: 0;
}

.warning-content h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
}

.warning-content p {
    margin: 0 0 1rem 0;
    color: var(--text-secondary);
}

.admin-to-delete {
    background: var(--primary-light);
    padding: 1rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
}

/* Password Modal Styles */
.admin-info-header {
    text-align: center;
    margin-bottom: 2rem;
    padding: 1rem;
    background: var(--primary-light);
    border-radius: 0.75rem;
}

.admin-info-header h5 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
    font-size: 1.25rem;
}

.admin-info-header p {
    margin: 0;
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.password-toggle {
    position: absolute;
    right: 12px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    z-index: 2;
}

.password-toggle:hover {
    color: var(--primary);
}

.checkbox-group {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-top: 1rem;
}

.checkbox-group input[type="checkbox"] {
    width: 18px;
    height: 18px;
    accent-color: var(--primary);
}

.checkbox-group label {
    color: var(--text-primary);
    font-size: 0.875rem;
    cursor: pointer;
    margin: 0;
}

/* Notification Modal Styles */
.form-field[rows] {
    resize: vertical;
    min-height: 100px;
}

/* Responsive Design */
@media (max-width: 768px) {
    .details-grid {
        grid-template-columns: 1fr;
    }
    
    .admin-avatar-section {
        flex-direction: column;
        text-align: center;
    }
    
    .bulk-actions-bar .flex {
        flex-direction: column;
        gap: 1rem;
    }
    
    .bulk-buttons {
        margin-left: 0;
        justify-content: center;
    }
    
    .delete-warning {
        flex-direction: column;
        text-align: center;
    }
}

/* Animation for modal transitions */
.admin-modal {
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.modal-container {
    animation: slideIn 0.3s ease-out;
}

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateY(-50px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endsection
