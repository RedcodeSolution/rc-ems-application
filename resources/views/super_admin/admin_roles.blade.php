@extends('layouts.super_admin')

@section('title', 'Admin Roles Management - Super Admin Dashboard')

@section('content')
<div class="admin-roles-container">
    <!-- Admin Roles Header -->
    <div class="admin-roles-header">
        <div class="header-content">
            <h1><i class="fas fa-users-cog"></i> Admin Roles Management</h1>
            <p>Manage administrator roles, permissions, and access levels</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openCreateRoleModal()">
                <i class="fas fa-plus"></i> Create New Role
            </button>
            <button class="btn btn-secondary" onclick="exportRoleData()">
                <i class="fas fa-download"></i> Export Roles
            </button>
        </div>
    </div>

    <!-- Role Statistics Cards -->
    <div class="role-stats-grid">
        <div class="stat-card total-roles">
            <div class="stat-icon">
                <i class="fas fa-user-tag"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ count($availableRoles) }}</div>
                <div class="stat-label">Total Roles</div>
            </div>
        </div>
        
        <div class="stat-card active-admins">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $admins->count() }}</div>
                <div class="stat-label">Total Admins</div>
            </div>
        </div>
        
        <div class="stat-card permissions">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ count($availablePermissions) }}</div>
                <div class="stat-label">Permissions</div>
            </div>
        </div>
        
        <div class="stat-card security-level">
            <div class="stat-icon">
                <i class="fas fa-lock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">High</div>
                <div class="stat-label">Security Level</div>
            </div>
        </div>
    </div>

    <!-- Roles Overview Section -->
    <div class="roles-overview-section">
        <div class="section-header">
            <h2><i class="fas fa-sitemap"></i> Available Roles</h2>
            <button class="btn btn-outline" onclick="refreshRoleData()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>

        <div class="roles-grid">
            @foreach($availableRoles as $roleKey => $roleName)
            <div class="role-card" data-role="{{ $roleKey }}">
                <div class="role-header">
                    <div class="role-icon">
                        @if($roleKey === 'super_admin')
                            <i class="fas fa-crown"></i>
                        @elseif($roleKey === 'admin')
                            <i class="fas fa-user-shield"></i>
                        @elseif($roleKey === 'manager')
                            <i class="fas fa-user-tie"></i>
                        @else
                            <i class="fas fa-user-cog"></i>
                        @endif
                    </div>
                    <div class="role-info">
                        <h3>{{ $roleName }}</h3>
                        <span class="role-key">{{ $roleKey }}</span>
                    </div>
                    <div class="role-badge">
                        <span class="admin-count">{{ $admins->where('role', $roleKey)->count() ?? 0 }}</span>
                        <span class="admin-label">Admins</span>
                    </div>
                </div>
                
                <div class="role-permissions">
                    <h4>Permissions</h4>
                    <div class="permissions-list">
                        @if($roleKey === 'super_admin')
                            @foreach($availablePermissions as $permKey => $permName)
                                <span class="permission-badge granted">
                                    <i class="fas fa-check"></i> {{ $permName }}
                                </span>
                            @endforeach
                        @elseif($roleKey === 'admin')
                            @foreach(array_slice($availablePermissions, 0, 5, true) as $permKey => $permName)
                                <span class="permission-badge granted">
                                    <i class="fas fa-check"></i> {{ $permName }}
                                </span>
                            @endforeach
                            @foreach(array_slice($availablePermissions, 5, null, true) as $permKey => $permName)
                                <span class="permission-badge denied">
                                    <i class="fas fa-times"></i> {{ $permName }}
                                </span>
                            @endforeach
                        @else
                            @foreach(array_slice($availablePermissions, 0, 3, true) as $permKey => $permName)
                                <span class="permission-badge granted">
                                    <i class="fas fa-check"></i> {{ $permName }}
                                </span>
                            @endforeach
                            @foreach(array_slice($availablePermissions, 3, null, true) as $permKey => $permName)
                                <span class="permission-badge denied">
                                    <i class="fas fa-times"></i> {{ $permName }}
                                </span>
                            @endforeach
                        @endif
                    </div>
                </div>
                
                <div class="role-actions">
                    <button class="btn btn-sm btn-primary edit-role-btn" onclick="editRole('{{ $roleKey }}')" data-role="{{ $roleKey }}">
                        <i class="fas fa-edit"></i> Edit Role
                    </button>
                    @if($roleKey !== 'super_admin')
                    <button class="btn btn-sm btn-outline" onclick="deleteRole('{{ $roleKey }}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Admin Role Assignments -->
    <div class="admin-assignments-section">
        <div class="section-header">
            <h2><i class="fas fa-user-check"></i> Admin Role Assignments</h2>
            <div class="assignment-filters">
                <select id="roleFilter" class="filter-select">
                    <option value="">All Roles</option>
                    @foreach($availableRoles as $roleKey => $roleName)
                    <option value="{{ $roleKey }}">{{ $roleName }}</option>
                    @endforeach
                </select>
                <input type="text" id="adminSearch" placeholder="Search admins..." class="search-input">
            </div>
        </div>

        <div class="assignments-table-container">
            <table class="assignments-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Current Role</th>
                        <th>Employee Info</th>
                        <th>Assigned Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assignmentsTableBody">
                    @forelse($admins as $admin)
                    <tr class="assignment-row" data-admin-id="{{ $admin->admin_id }}">
                        <td>
                            <div class="admin-info">
                                <div class="admin-avatar">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="admin-details">
                                    <div class="admin-name">{{ $admin->admin_name }}</div>
                                    <div class="admin-id">ID: {{ $admin->admin_id }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="current-role">
                                <span class="role-badge role-admin">
                                    <i class="fas fa-user-shield"></i>
                                    Administrator
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="employee-details">
                                @if($admin->employee)
                                    <div class="employee-name">{{ $admin->employee->employee_name ?? 'N/A' }}</div>
                                    <div class="employee-id">{{ $admin->employee->employee_id ?? 'N/A' }}</div>
                                @else
                                    <span class="no-employee">No associated employee</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="assigned-date">
                                <div class="date-primary">{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="date-time">{{ $admin->created_at ? $admin->created_at->format('h:i A') : '' }}</div>
                            </div>
                        </td>
                        <td>
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        </td>
                        <td>
                            <div class="assignment-actions">
                                <button class="action-btn change-role-btn" onclick="changeAdminRole('{{ $admin->admin_id }}')" title="Change Role">
                                    <i class="fas fa-exchange-alt"></i>
                                </button>
                                <button class="action-btn view-permissions-btn" onclick="viewPermissions('{{ $admin->admin_id }}')" title="View Permissions">
                                    <i class="fas fa-shield-alt"></i>
                                </button>
                                <button class="action-btn history-btn" onclick="viewRoleHistory('{{ $admin->admin_id }}')" title="Role History">
                                    <i class="fas fa-history"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="no-data-row">
                        <td colspan="6">
                            <div class="no-data-message">
                                <i class="fas fa-users"></i>
                                <h3>No Admin Role Assignments Found</h3>
                                <p>There are currently no admin role assignments in the system.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div id="createRoleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Create New Role</h3>
            <button class="modal-close" onclick="closeCreateRoleModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="createRoleForm" onsubmit="submitCreateRoleForm(event)">
                <div class="form-group">
                    <label for="newRoleKey">Role Key</label>
                    <input type="text" id="newRoleKey" name="role_key" class="form-input" placeholder="e.g., manager" required>
                </div>
                <div class="form-group">
                    <label for="newRoleName">Role Name</label>
                    <input type="text" id="newRoleName" name="role_name" class="form-input" placeholder="e.g., Manager" required>
                </div>
                <div class="form-group">
                    <label for="roleDescription">Description</label>
                    <textarea id="roleDescription" name="description" class="form-textarea" placeholder="Role description..." rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Permissions</label>
                    <div class="permissions-checkboxes">
                        @foreach($availablePermissions as $permKey => $permName)
                        <label class="checkbox-label">
                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}">
                            <span class="checkbox-text">{{ $permName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeCreateRoleModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Create Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Role Modal -->
<div id="editRoleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-edit"></i> Edit Role</h3>
            <button class="modal-close" onclick="closeEditRoleModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="editRoleForm" onsubmit="submitEditRoleForm(event)">
                <input type="hidden" id="editRoleKey" name="role_key">
                <div class="form-group">
                    <label for="editRoleName">Role Name</label>
                    <input type="text" id="editRoleName" name="role_name" class="form-input" placeholder="e.g., Manager" required>
                </div>
                <div class="form-group">
                    <label for="editRoleDescription">Description</label>
                    <textarea id="editRoleDescription" name="description" class="form-textarea" placeholder="Role description..." rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label>Current Permissions</label>
                    <div id="editPermissionsContainer" class="permissions-checkboxes">
                        @foreach($availablePermissions as $permKey => $permName)
                        <label class="checkbox-label">
                            <input type="checkbox" name="permissions[]" value="{{ $permKey }}" id="edit_perm_{{ $permKey }}">
                            <span class="checkbox-text">{{ $permName }}</span>
                        </label>
                        @endforeach
                    </div>
                </div>
                <div class="role-preview">
                    <h4><i class="fas fa-eye"></i> Permission Preview</h4>
                    <div id="permissionPreview" class="permission-preview-list">
                        <!-- Permissions will be dynamically updated here -->
                    </div>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeEditRoleModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Change Role Modal -->
<div id="changeRoleModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-exchange-alt"></i> Change Admin Role</h3>
            <button class="modal-close" onclick="closeChangeRoleModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="changeRoleForm" onsubmit="submitChangeRoleForm(event)">
                <div class="form-group">
                    <label for="adminSelect">Administrator</label>
                    <select id="adminSelect" name="admin_id" class="form-select" required disabled>
                        <option value="">Select admin...</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="newRole">New Role</label>
                    <select id="newRole" name="new_role" class="form-select" required>
                        <option value="">Select role...</option>
                        @foreach($availableRoles as $roleKey => $roleName)
                        <option value="{{ $roleKey }}">{{ $roleName }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="changeReason">Reason for Change</label>
                    <textarea id="changeReason" name="reason" class="form-textarea" placeholder="Reason for role change..." rows="3"></textarea>
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeChangeRoleModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-exchange-alt"></i> Change Role
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-roles-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .admin-roles-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
        padding: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .header-content h1 i {
        color: var(--redcode-primary);
        font-size: 1.75rem;
    }

    .header-content p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    /* Button Styles */
    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 0.9rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .btn-sm {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .btn-primary {
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        color: white;
        box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
    }

    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.3);
    }

    .btn-secondary {
        background: linear-gradient(135deg, var(--gray-600), var(--gray-700));
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
    }

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
    }

    .btn-outline {
        background: white;
        color: var(--redcode-primary);
        border: 2px solid var(--redcode-primary);
    }

    .btn-outline:hover {
        background: var(--redcode-primary);
        color: white;
        transform: translateY(-2px);
    }

    /* Statistics Cards */
    .role-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-accent));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .stat-card:hover::before {
        transform: scaleX(1);
    }

    .stat-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        flex-shrink: 0;
    }

    .total-roles .stat-icon {
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
    }

    .active-admins .stat-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .permissions .stat-icon {
        background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    }

    .security-level .stat-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .stat-content {
        flex: 1;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        line-height: 1;
        margin-bottom: 0.25rem;
    }

    .stat-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    /* Roles Overview Section */
    .roles-overview-section,
    .admin-assignments-section {
        margin-bottom: 2rem;
    }

    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .section-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .section-header h2 i {
        color: var(--redcode-primary);
    }

    .assignment-filters {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .filter-select,
    .search-input {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s ease;
    }

    .search-input {
        min-width: 200px;
    }

    .filter-select:focus,
    .search-input:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Roles Grid */
    .roles-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 1.5rem;
    }

    .role-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .role-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-accent));
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .role-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .role-card:hover::before {
        transform: scaleX(1);
    }

    .role-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .role-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        flex-shrink: 0;
    }

    .role-info {
        flex: 1;
    }

    .role-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .role-key {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-family: monospace;
        background: var(--bg-secondary);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
    }

    .role-badge {
        text-align: center;
    }

    .admin-count {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--redcode-primary);
    }

    .admin-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
        text-transform: uppercase;
        font-weight: 600;
    }

    .role-permissions {
        margin-bottom: 1.5rem;
    }

    .role-permissions h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .permissions-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .permission-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .permission-badge.granted {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .permission-badge.denied {
        background: rgba(220, 38, 38, 0.1);
        color: var(--redcode-primary);
    }

    .role-actions {
        display: flex;
        gap: 0.75rem;
        justify-content: flex-end;
    }

    /* Assignment Table */
    .assignments-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .assignments-table {
        width: 100%;
        border-collapse: collapse;
    }

    .assignments-table th,
    .assignments-table td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    .assignments-table th {
        background: var(--bg-secondary);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .assignment-row {
        transition: all 0.3s ease;
    }

    .assignment-row:hover {
        background: var(--bg-secondary);
    }

    .admin-info {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .admin-avatar {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
        flex-shrink: 0;
    }

    .admin-name {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .admin-id {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .role-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .role-admin {
        background: rgba(220, 38, 38, 0.1);
        color: var(--redcode-primary);
    }

    .employee-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .employee-id {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .no-employee {
        font-size: 0.85rem;
        color: var(--text-light);
        font-style: italic;
    }

    .assigned-date .date-primary {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .assigned-date .date-time {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.25rem;
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .status-active {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .assignment-actions {
        display: flex;
        gap: 0.5rem;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        border: none;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 0.85rem;
    }

    .change-role-btn {
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
    }

    .change-role-btn:hover {
        background: #2563EB;
        color: white;
        transform: translateY(-2px);
    }

    .view-permissions-btn {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .view-permissions-btn:hover {
        background: #F59E0B;
        color: white;
        transform: translateY(-2px);
    }

    .history-btn {
        background: rgba(107, 114, 128, 0.1);
        color: var(--gray-600);
    }

    .history-btn:hover {
        background: var(--gray-600);
        color: white;
        transform: translateY(-2px);
    }

    .no-data-row td {
        padding: 3rem;
        text-align: center;
    }

    .no-data-message {
        color: var(--text-secondary);
    }

    .no-data-message i {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .no-data-message h3 {
        font-size: 1.5rem;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .no-data-message p {
        margin-bottom: 1.5rem;
    }

    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        backdrop-filter: blur(4px);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 90%;
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-light);
    }

    .modal-header h3 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .modal-header h3 i {
        color: var(--redcode-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 1.5rem;
        color: var(--text-secondary);
        cursor: pointer;
        transition: color 0.3s ease;
    }

    .modal-close:hover {
        color: var(--redcode-primary);
    }

    .modal-body {
        padding: 2rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .permissions-checkboxes {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 0.75rem;
        margin-top: 0.5rem;
    }

    .checkbox-label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        padding: 0.5rem;
        border-radius: 8px;
        transition: background-color 0.3s ease;
    }

    .checkbox-label:hover {
        background: var(--bg-secondary);
    }

    .checkbox-text {
        font-size: 0.9rem;
        color: var(--text-primary);
        font-weight: 500;
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    /* Permission Preview Section */
    .role-preview {
        margin-top: 1.5rem;
        padding: 1rem;
        background: var(--bg-light);
        border-radius: 8px;
        border: 1px solid var(--border-light);
    }

    .role-preview h4 {
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .role-preview h4 i {
        color: var(--redcode-primary);
        font-size: 0.9rem;
    }

    .permission-preview-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .preview-permission {
        padding: 0.25rem 0.75rem;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 0.25rem;
        transition: all 0.2s ease;
    }

    .preview-permission.granted {
        background: rgba(16, 185, 129, 0.1);
        color: #059669;
        border: 1px solid rgba(16, 185, 129, 0.2);
    }

    .preview-permission.denied {
        background: rgba(220, 38, 38, 0.1);
        color: #DC2626;
        border: 1px solid rgba(220, 38, 38, 0.2);
    }

    .preview-permission i {
        font-size: 0.7rem;
    }

    /* Enhanced Modal Styles */
    .modal-content {
        max-height: 90vh;
        overflow-y: auto;
    }

    .modal-body {
        max-height: calc(90vh - 140px);
        overflow-y: auto;
        padding: 1.5rem 2rem 2rem;
    }

    /* Form Input Styles */
    .form-input,
    .form-textarea,
    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        transition: all 0.3s ease;
        font-family: inherit;
    }

    .form-input:focus,
    .form-textarea:focus,
    .form-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-textarea {
        resize: vertical;
        min-height: 80px;
    }

    /* Checkbox Enhancements */
    .permissions-checkboxes {
        max-height: 200px;
        overflow-y: auto;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        padding: 1rem;
        background: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-roles-container {
            padding: 1rem;
        }

        .admin-roles-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .role-stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .section-header {
            flex-direction: column;
            gap: 1rem;
        }

        .assignment-filters {
            justify-content: stretch;
            width: 100%;
        }

        .filter-select,
        .search-input {
            flex: 1;
        }

        .roles-grid {
            grid-template-columns: 1fr;
        }

        .assignments-table-container {
            overflow-x: auto;
        }

        .assignments-table {
            min-width: 800px;
        }

        .form-actions {
            flex-direction: column;
        }

        .permissions-checkboxes {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Initialize page functionality
    document.addEventListener('DOMContentLoaded', function() {
        initializeFilters();
        setupModalHandlers();
    });

    // Filter functionality
    function initializeFilters() {
        const roleFilter = document.getElementById('roleFilter');
        const adminSearch = document.getElementById('adminSearch');

        function filterAssignments() {
            const roleValue = roleFilter.value.toLowerCase();
            const searchTerm = adminSearch.value.toLowerCase();
            const rows = document.querySelectorAll('.assignment-row');

            rows.forEach(row => {
                const adminName = row.querySelector('.admin-name').textContent.toLowerCase();
                const adminId = row.querySelector('.admin-id').textContent.toLowerCase();
                const visible = (adminName.includes(searchTerm) || adminId.includes(searchTerm));
                
                row.style.display = visible ? 'table-row' : 'none';
            });
        }

        roleFilter.addEventListener('change', filterAssignments);
        adminSearch.addEventListener('input', filterAssignments);
    }

    // Modal handlers
    function setupModalHandlers() {
        // Close modals when clicking outside
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeAllModals();
                }
            });
        });
        
        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeAllModals();
            }
        });
    }

    // Create Role Modal
    function openCreateRoleModal() {
        document.getElementById('createRoleModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeCreateRoleModal() {
        document.getElementById('createRoleModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('createRoleForm').reset();
    }

    function submitCreateRoleForm(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const roleData = Object.fromEntries(formData);
        
        console.log('Creating role:', roleData);
        
        showNotification('Role created successfully!', 'success');
        closeCreateRoleModal();
    }

    // Change Role Modal
    function changeAdminRole(adminId) {
        const modal = document.getElementById('changeRoleModal');
        const adminSelect = document.getElementById('adminSelect');
        
        // Find admin name
        const adminRow = document.querySelector(`[data-admin-id="${adminId}"]`);
        const adminName = adminRow.querySelector('.admin-name').textContent;
        
        // Populate admin select
        adminSelect.innerHTML = `<option value="${adminId}">${adminName}</option>`;
        adminSelect.value = adminId;
        
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeChangeRoleModal() {
        document.getElementById('changeRoleModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('changeRoleForm').reset();
    }

    function submitChangeRoleForm(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const changeData = Object.fromEntries(formData);
        
        console.log('Changing role:', changeData);
        
        showNotification('Admin role changed successfully!', 'success');
        closeChangeRoleModal();
    }

    function closeAllModals() {
        document.querySelectorAll('.modal').forEach(modal => {
            modal.classList.remove('show');
        });
        document.body.style.overflow = 'auto';
        
        // Reset all forms
        document.getElementById('createRoleForm').reset();
        document.getElementById('changeRoleForm').reset();
        document.getElementById('editRoleForm').reset();
        
        // Clear permission preview
        document.getElementById('permissionPreview').innerHTML = '';
    }

    // Action functions
    function editRole(roleKey) {
        // Define role data (in a real application, this would come from the server)
        const roleData = {
            'super_admin': {
                name: 'Super Administrator',
                description: 'Full system access with all administrative privileges',
                permissions: ['users.manage', 'admins.manage', 'employees.manage', 'departments.manage', 'reports.view', 'reports.generate', 'system.configure', 'audit.view']
            },
            'admin': {
                name: 'Administrator',
                description: 'Standard administrative access with limited system configuration',
                permissions: ['users.manage', 'employees.manage', 'departments.manage', 'reports.view', 'reports.generate']
            },
            'manager': {
                name: 'Manager',
                description: 'Department-level management access for team oversight',
                permissions: ['employees.manage', 'reports.view', 'departments.manage']
            },
            'hr': {
                name: 'HR Manager',
                description: 'Human resources management with employee and administrative access',
                permissions: ['employees.manage', 'users.manage', 'reports.view']
            }
        };

        const role = roleData[roleKey];
        
        if (!role) {
            showNotification('Role not found!', 'error');
            return;
        }

        // Populate the edit form
        document.getElementById('editRoleKey').value = roleKey;
        document.getElementById('editRoleName').value = role.name;
        document.getElementById('editRoleDescription').value = role.description;

        // Clear and set permissions checkboxes
        const checkboxes = document.querySelectorAll('#editRoleModal input[name="permissions[]"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = role.permissions.includes(checkbox.value);
        });

        // Update permission preview
        updatePermissionPreview();

        // Show the modal
        document.getElementById('editRoleModal').classList.add('show');
        document.body.style.overflow = 'hidden';

        // Add event listeners for dynamic permission preview
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updatePermissionPreview);
        });
    }

    function closeEditRoleModal() {
        document.getElementById('editRoleModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('editRoleForm').reset();
        
        // Clear the permission preview
        document.getElementById('permissionPreview').innerHTML = '';
    }

    function submitEditRoleForm(event) {
        event.preventDefault();
        
        const formData = new FormData(event.target);
        const roleData = {
            roleKey: formData.get('role_key'),
            roleName: formData.get('role_name'),
            description: formData.get('description'),
            permissions: formData.getAll('permissions[]')
        };
        
        console.log('Saving role changes:', roleData);
        
        // In a real application, you would send this data to the server
        // For now, we'll just show a success message
        showNotification(`Role "${roleData.roleName}" updated successfully!`, 'success');
        
        // Update the UI to reflect the changes
        updateRoleCardInUI(roleData);
        
        closeEditRoleModal();
    }

    function updatePermissionPreview() {
        const checkedPermissions = document.querySelectorAll('#editRoleModal input[name="permissions[]"]:checked');
        const uncheckedPermissions = document.querySelectorAll('#editRoleModal input[name="permissions[]"]:not(:checked)');
        const previewContainer = document.getElementById('permissionPreview');
        
        previewContainer.innerHTML = '';
        
        // Add granted permissions
        checkedPermissions.forEach(checkbox => {
            const permissionDiv = document.createElement('div');
            permissionDiv.className = 'preview-permission granted';
            permissionDiv.innerHTML = `<i class="fas fa-check"></i> ${checkbox.nextElementSibling.textContent}`;
            previewContainer.appendChild(permissionDiv);
        });
        
        // Add denied permissions
        uncheckedPermissions.forEach(checkbox => {
            const permissionDiv = document.createElement('div');
            permissionDiv.className = 'preview-permission denied';
            permissionDiv.innerHTML = `<i class="fas fa-times"></i> ${checkbox.nextElementSibling.textContent}`;
            previewContainer.appendChild(permissionDiv);
        });
    }

    function updateRoleCardInUI(roleData) {
        // Find the role card and update its content
        const roleCard = document.querySelector(`[data-role="${roleData.roleKey}"]`);
        if (roleCard) {
            // Update role name in the card
            const roleNameElement = roleCard.querySelector('.role-info h3');
            if (roleNameElement) {
                roleNameElement.textContent = roleData.roleName;
            }
            
            // Update permissions display in the card
            const permissionsContainer = roleCard.querySelector('.permissions-list');
            if (permissionsContainer) {
                permissionsContainer.innerHTML = '';
                
                // Add granted permissions
                roleData.permissions.forEach(permission => {
                    const permissionBadge = document.createElement('span');
                    permissionBadge.className = 'permission-badge granted';
                    permissionBadge.innerHTML = `<i class="fas fa-check"></i> ${getPermissionDisplayName(permission)}`;
                    permissionsContainer.appendChild(permissionBadge);
                });
                
                // Add denied permissions (all available permissions minus granted ones)
                const allPermissions = ['users.manage', 'admins.manage', 'employees.manage', 'departments.manage', 'reports.view', 'reports.generate', 'system.configure', 'audit.view'];
                const deniedPermissions = allPermissions.filter(perm => !roleData.permissions.includes(perm));
                
                deniedPermissions.forEach(permission => {
                    const permissionBadge = document.createElement('span');
                    permissionBadge.className = 'permission-badge denied';
                    permissionBadge.innerHTML = `<i class="fas fa-times"></i> ${getPermissionDisplayName(permission)}`;
                    permissionsContainer.appendChild(permissionBadge);
                });
            }
        }
    }

    function getPermissionDisplayName(permission) {
        const permissionNames = {
            'users.manage': 'User Management',
            'admins.manage': 'Admin Management',
            'employees.manage': 'Employee Management',
            'departments.manage': 'Department Management',
            'reports.view': 'View Reports',
            'reports.generate': 'Generate Reports',
            'system.configure': 'System Configuration',
            'audit.view': 'Audit Logs'
        };
        return permissionNames[permission] || permission;
    }

    function deleteRole(roleKey) {
        if (confirm(`Are you sure you want to delete the ${roleKey} role?`)) {
            showNotification(`Role ${roleKey} deleted successfully!`, 'success');
            // Implement delete role functionality
        }
    }

    function viewPermissions(adminId) {
        showNotification(`Viewing permissions for admin: ${adminId}`, 'info');
        // Implement view permissions functionality
    }

    function viewRoleHistory(adminId) {
        showNotification(`Viewing role history for admin: ${adminId}`, 'info');
        // Implement role history functionality
    }

    function refreshRoleData() {
        showNotification('Refreshing role data...', 'info');
        // Implement refresh functionality
    }

    function exportRoleData() {
        showNotification('Exporting role data...', 'info');
        // Implement export functionality
    }

    // Notification function
    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            border-left: 4px solid ${type === 'success' ? '#10B981' : type === 'error' ? '#DC2626' : '#2563EB'};
            font-weight: 500;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
</script>
@endsection
