@extends('layouts.super_admin')

@section('title', 'Admin Management - Super Admin Dashboard')

@section('content')
<div class="admin-management-container">
    <!-- Admin Management Header -->
    <div class="admin-management-header">
        <div class="header-content">
            <h1><i class="fas fa-user-shield"></i> Admin Management</h1>
            <p>Manage all administrators and their permissions</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openAddAdminModal()">
                <i class="fas fa-plus"></i> Add New Admin
            </button>
            <button class="btn btn-secondary" onclick="exportAdminData()">
                <i class="fas fa-download"></i> Export Data
            </button>
        </div>
    </div>

    <!-- Admin Statistics Cards -->
    <div class="admin-stats-grid">
        <div class="stat-card total-admins">
            <div class="stat-icon">
                <i class="fas fa-users"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $admins->count() }}</div>
                <div class="stat-label">Total Admins</div>
            </div>
        </div>

        <div class="stat-card active-admins">
            <div class="stat-icon">
                <i class="fas fa-user-check"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $admins->count() }}</div>
                <div class="stat-label">Active Admins</div>
            </div>
        </div>

        <div class="stat-card recent-activity">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">{{ $admins->where('created_at', '>=', now()->subDays(7))->count() }}</div>
                <div class="stat-label">Added This Week</div>
            </div>
        </div>

        <div class="stat-card permissions">
            <div class="stat-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <div class="stat-content">
                <div class="stat-number">100%</div>
                <div class="stat-label">Access Level</div>
            </div>
        </div>
    </div>

    <!-- Search and Filter Section -->
    <div class="search-filter-section">
        <div class="search-container">
            <div class="search-input-wrapper">
                <i class="fas fa-search"></i>
                <input type="text" id="adminSearch" placeholder="Search admins by name or ID..." class="search-input">
            </div>
        </div>
        <div class="filter-container">
            <select id="statusFilter" class="filter-select">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Inactive">Inactive</option>
                <option value="On Leave">On Leave</option>
                <option value="Terminated">Terminated</option>
            </select>

            <select id="roleFilter" class="filter-select">
                <option value="">All Roles</option>
                <option value="admin">Admin</option>
                <option value="super_admin">Super Admin</option>
            </select>
        </div>
    </div>

    <!-- Admins Table -->
    <div class="admins-table-container">
        <div class="table-header">
            <h2><i class="fas fa-table"></i> Administrators List</h2>
            <div class="table-actions">
                <button class="btn btn-outline" onclick="refreshAdminData()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <table class="admins-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th>Admin ID</th>
                        <th>Admin Name</th>
                        <th>Department Info</th>
                        <th>Created Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="adminsTableBody">
                    @forelse($admins as $admin)
                    <tr class="admin-row" data-admin-id="{{ $admin->admin_id }}">
                        <td>
                            <input type="checkbox" class="admin-checkbox" value="{{ $admin->admin_id }}">
                        </td>
                        <td>
                            <div class="admin-id">
                                <span class="id-text">{{ $admin->admin_id }}</span>
                            </div>
                        </td>
                        <td>
                            <div class="admin-name">
                                <div class="name-avatar">
                                    <i class="fas fa-user-shield"></i>
                                </div>
                                <div class="name-info">
                                    <div class="name-primary">{{ $admin->admin_name }}</div>
                                    <div class="name-secondary">Administrator</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="department-info">
                                @if($admin->department)
                                <div class="department-name">{{ $admin->department->department_name ?? 'N/A' }}</div>
                                <div class="department-id">ID: {{ $admin->department->department_id ?? 'N/A' }}</div>
                                @else
                                <div class="no-department">No assigned department</div>
                                @endif
                            </div>

                        </td>
                        <td>
                            <div class="created-date">
                                <div class="date-primary">{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="date-secondary">{{ $admin->created_at ? $admin->created_at->format('h:i A') : '' }}</div>
                            </div>
                        </td>
                        <td>
                            @php
                            $statusClass = match($admin->status) {
                            'Active' => 'status-active',
                            'Inactive' => 'status-inactive',
                            'On Leave' => 'status-on-leave',
                            'Terminated' => 'status-terminated',
                            default => 'status-default',
                            };
                            $statusIcon = match($admin->status) {
                            'Active' => 'fa-check-circle',
                            'Inactive' => 'fa-times-circle',
                            'On Leave' => 'fa-clock',
                            'Terminated' => 'fa-ban',
                            default => 'fa-question-circle',
                            };
                            @endphp

                            <span class="status-badge {{ $statusClass }}">
                          <i class="fas {{ $statusIcon }}"></i>
                           {{ $admin->status }}
                            </span>
                        </td>

                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view-btn" onclick="viewAdmin('{{ $admin->admin_id }}')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>

                                <button class="action-btn edit-btn" onclick="openEditAdminModal('{{ $admin->admin_id }}')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>

                                <form action="{{ route('super_admin.destroy', $admin->admin_id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="action-btn delete-btn" title="Delete Admin"
                                            onclick="return confirm('Are you sure you want to delete this admin?');">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="no-data-row">
                        <td colspan="7">
                            <div class="no-data-message">
                                <i class="fas fa-users"></i>
                                <h3>No Administrators Found</h3>
                                <p>There are currently no administrators in the system.</p>
                                <button class="btn btn-primary" onclick="openAddAdminModal()">
                                    <i class="fas fa-plus"></i> Add First Admin
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $admins->count() }} of {{ $admins->count() }} administrators
            </div>
            <div class="pagination-controls">
                <!-- Pagination controls would go here -->
            </div>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div id="addAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i> Add New Administrator</h3>
            <button class="modal-close" onclick="closeAddAdminModal()">&times;</button>
        </div>
        <div class="modal-body">

            @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
            @endif

            <form id="addAdminForm" action="{{ route('super_admin.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <div class="form-row">
                        <!-- Admin Name -->
                        <div class="form-group">
                            <label for="admin_name" class="form-label">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input type="text" id="admin_name" name="admin_name" class="form-input"
                                   value="{{ old('admin_name') }}" placeholder="Enter full name" required>
                        </div>

                        <!-- Role -->
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i> Role/Position
                            </label>
                            <input type="text" id="role" name="role" class="form-input"
                                   value="{{ old('role') }}" placeholder="Enter role or position" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Department -->
                        <div class="form-group">
                            <label for="department_id" class="form-label">
                                <i class="fas fa-building"></i> Department
                            </label>
                            <select id="department_id" name="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->department_id }}"
                                        {{ old('department_id') == $department->department_id ? 'selected' : '' }}>
                                {{ $department->department_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>


                        <!-- Email -->
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" class="form-input"
                                   value="{{ old('email') }}" placeholder="Enter email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Contact Number -->
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone"></i> Contact Number
                            </label>
                            <input type="tel" id="contact_no" name="contact_no" class="form-input"
                                   value="{{ old('contact_no') }}" placeholder="Enter contact number" required>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select status</option>
                                <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                <option value="Inactive" {{ old('status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="On Leave" {{ old('status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                <option value="Terminated" {{ old('status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeAddAdminModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add Administrator
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<!-- Edit Admin Modal -->
<div id="editAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Admin</h3>
            <button class="modal-close" onclick="closeEditAdminModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="editAdminForm" action="" method="POST">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="form-row">
                        <!-- Admin Name -->
                        <div class="form-group">
                            <label for="edit_admin_name" class="form-label">
                                <i class="fas fa-user"></i> Admin Name
                            </label>
                            <input type="text" id="edit_admin_name" name="admin_name" class="form-input" placeholder="Enter admin name" required>
                        </div>

                        <!-- Role as Input Field -->
                        <div class="form-group">
                            <label for="edit_role" class="form-label">
                                <i class="fas fa-user-shield"></i> Role
                            </label>
                            <input type="text" id="edit_role" name="role" class="form-input" placeholder="Enter role" required>
                        </div>

                    </div>

                    <div class="form-row">
                        <!-- Email -->
                        <div class="form-group">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="edit_email" name="email" class="form-input" placeholder="Enter email" required>
                        </div>

                        <!-- Contact -->
                        <div class="form-group">
                            <label for="edit_contact_no" class="form-label">
                                <i class="fas fa-phone"></i> Contact
                            </label>
                            <input type="tel" id="edit_contact_no" name="contact_no" class="form-input" placeholder="Enter contact number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <!-- Department -->
                        <div class="form-group">
                            <label for="edit_department_id" class="form-label">
                                <i class="fas fa-building"></i> Department
                            </label>
                            <select id="edit_department_id" name="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="form-group">
                            <label for="edit_status" class="form-label">
                                <i class="fas fa-toggle-on"></i> Status
                            </label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Leave">On Leave</option>
                                <option value="Terminated">Terminated</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditAdminModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-save" style="color: white; margin-right:5px;"></i>Update Admin
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Admin Modal -->
<div id="viewAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> View Admin Details</h3>
            <button class="modal-close" onclick="closeViewAdminModal()">&times;</button>
        </div>

        <div class="modal-body">
            <div class="profile-card">
                <!-- Profile Section -->
                <div class="profile-section">
                    <div class="avatar" id="view_admin_initials"></div>
                    <div class="profile-info">
                        <h2 id="view_admin_name"></h2>
                        <p class="admin-id" id="view_admin_id"></p>
                        <p class="role-text" id="view_admin_role"></p>
                    </div>
                </div>

                <!-- Details -->
                <div class="details-container">
                    <div class="details-grid">

                    <div class="detail-item">
                        <div class="detail-header">
                            <i class="fas fa-envelope detail-icon"></i>
                            <span class="detail-label">Email</span>
                        </div>
                        <div class="detail-value" id="view_admin_email"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-header">
                            <i class="fas fa-phone detail-icon"></i>
                            <span class="detail-label">Contact</span>
                        </div>
                        <div class="detail-value" id="view_admin_contact"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-header">
                            <i class="fas fa-building detail-icon"></i>
                            <span class="detail-label">Department</span>
                        </div>
                        <div class="detail-value" id="view_admin_department"></div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-header">
                            <i class="fas fa-clock detail-icon"></i>
                            <span class="detail-label">Last Login</span>
                        </div>
                        <div class="detail-value" id="view_admin_last_login">1 day ago</div>
                    </div>

                    <div class="detail-item">
                        <div class="detail-header">
                            <i class="fas fa-toggle-on detail-icon"></i>
                            <span class="detail-label">Status</span>
                        </div>
                        <div class="detail-value status-active" id="view_admin_status"></div>
                    </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-calendar-plus detail-icon"></i>
                                <span class="detail-label">Created</span>
                            </div>
                            <div class="detail-value" id="view_admin_created">
                                <div class="created-date">
                                    <div class="date-primary" id="view_admin_created_date"></div>
                                    <div class="date-secondary" id="view_admin_created_time"></div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>


<style>

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background-color: #f8fafc;
        padding: 20px;
        line-height: 1.5;
    }

    .modal{
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        display: none; /* hidden by default */
        justify-content: center;
        align-items: center;
        z-index: 1000;
    }

    .modal-overlay.active {
        display: flex;
    }

    /* Modal content */
    .modal-content {
        background: #fff;
        border-radius: 10px;
        padding: 2rem;
        width: 100%;
        max-width: 1000px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 5px 30px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 24px 24px 0;
    }

    .header-title {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #dc2626;
        font-size: 16px;
        font-weight: 500;
        margin-bottom: 32px;
    }

    .header-icon {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
    }

    .profile-section {
        display: flex;
        align-items: center;
        gap: 20px;
        margin-bottom: 40px;
        padding: 0 24px;
    }

    .avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, #dc2626 0%, #7c2d12 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        font-weight: 700;
        color: white;
        flex-shrink: 0;
    }

    .profile-info h2 {
        font-size: 24px;
        font-weight: 600;
        color: #1f2937;
        margin-bottom: 4px;
    }

    .admin-id {
        font-size: 14px;
        color: #9ca3af;
        margin-bottom: 8px;
    }

    .role-text {
        font-size: 16px;
        font-weight: 500;
        color: #1f2937;
    }

    .details-container {
        padding: 0 5px 32px;
    }

    .details-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
        margin-bottom: 32px;
    }

    .detail-item {
        background: #ffffff;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 15px;
        transition: all 0.2s ease;

    }

    .detail-item:hover {
        border-color: #d1d5db;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .detail-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
    }

    .detail-icon {
        color: #dc2626;
        font-size: 16px;
        width: 16px;
    }

    .detail-label {
        font-size: 12px;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .detail-value {
        font-size: 16px;
        font-weight: 500;
        color: #1f2937;
        word-break: break-word;
        border-radius: 12px;
        display: inline-block;

    }
    /* Tailwind-like colors (if you are not using Tailwind, replace with your colors) */
    .text-green-600 { color: #16a34a; background-color: #d1fae5; }
    .text-gray-500 { color: #6b7280; background-color: #f3f4f6; }
    .text-yellow-600 { color: #ca8a04; background-color: #fef3c7; }
    .text-red-600 { color: #dc2626; background-color: #fee2e2; }


    .status-active {
        color: #059669;
    }

    .close-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        background: none;
        border: none;
        color: #6b7280;
        font-size: 24px;
        cursor: pointer;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .close-btn:hover {
        background: #f3f4f6;
        color: #374151;
    }

    @media (max-width: 640px) {
        .modal-content {
            margin: 20px;
            max-width: calc(100vw - 40px);
        }

        .profile-section {
            flex-direction: column;
            text-align: center;
            gap: 16px;
        }

        .details-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .permissions-list {
            justify-content: center;
        }
    }


    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.3rem;
        padding: 0.2rem 0.5rem;
        border-radius: 0.25rem;
        font-size: 0.875rem;
        font-weight: 500;
        color: #fff;
    }

    .status-active { color: #16a34a; background-color: #d1fae5; }      /* green */
    .status-inactive { color: #6b7280; background-color: #f3f4f6; }    /* gray */
    .status-on-leave { color: #ca8a04; background-color: #fef3c7;}    /* yellow */
    .status-terminated {color: #dc2626; background-color: #fee2e2; }  /* red */
    .status-default { background-color: #17a2b8; }     /* blue for unknown */


    .form-label i {
        color: red;
        margin-right: 5px;
    }


    button.btn-danger {
        background-color: var(--redcode-primary);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 10px;
        cursor: pointer;
    }

    /* Optional: hover effect */
    button.btn-danger:hover {
        background-color: darkred;
    }

    .modal {
        display: none;
        position: fixed;
        top:0; left:0;
        width:100%;
        height:100%;
        /*background: rgba(0,0,0,0.5);*/
        color: red;
    }

    .modal.active {
        display: block;
    }

    .modal-content {
        background: #fff;
        margin: 5% auto;
        padding: 20px;
        border-radius: 8px;
        width: 600px;
        position: relative;
    }
    .modal-close { position: absolute; top: 10px; right: 10px; border: none; background: transparent; font-size: 24px; cursor: pointer; }

    .admin-management-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }
    .form-container {
        display: grid;
        gap: 1.5rem;
        width: 100%;
        max-width: 900px;
        margin: auto;


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

    .form-input, .form-select {
        width: 100%;
        padding: 12px 16px 12px 48px;
        border: 2px solid var(--border-light);
        border-radius: 0.75rem;
        font-size: 0.9rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        background: rgba(248, 250, 252, 0.5);
        backdrop-filter: blur(10px);
        color: var(--text-primary);
        font-weight: 500;
    }

    .form-input:focus, .form-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        background: rgba(255, 255, 255, 0.9);
        box-shadow:
            0 0 0 4px rgba(220,38,38,0.08),
            0 8px 25px rgba(220,38,38,0.12);
        transform: translateY(-2px);
    }

    .form-input:focus + .input-icon,
    .form-select:focus + .input-icon {
        color: var(--redcode-primary);
        transform: translateY(-50%) scale(1.1);
    }

    .form-select {
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
        background-position: right 12px center;
        background-repeat: no-repeat;
        background-size: 16px;
    }

    .form-select[multiple] {
        background-image: none;
        min-height: 120px;
        padding: 12px 16px;
    }

    .form-select[multiple] option {
        padding: 8px;
        border-radius: 4px;
        margin: 2px 0;
    }
    /* Header Section */
    .admin-management-header {
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
    .admin-stats-grid {
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

    .total-admins .stat-icon {
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
    }

    .active-admins .stat-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .recent-activity .stat-icon {
        background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    }

    .permissions .stat-icon {
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

    /* Search and Filter Section */
    .search-filter-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        gap: 2rem;
    }

    .search-container {
        flex: 1;
        max-width: 400px;
    }

    .search-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .search-input-wrapper i {
        position: absolute;
        left: 1rem;
        color: var(--text-secondary);
        z-index: 1;
    }

    .search-input {
        width: 100%;
        padding: 0.75rem 1rem 0.75rem 3rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .search-input:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .filter-container {
        display: flex;
        gap: 1rem;
    }

    .filter-select {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        background: white;
        color: var(--text-primary);
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-select:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    /* Table Section */
    .admins-table-container {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .table-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .table-header h2 {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .table-header h2 i {
        color: var(--redcode-primary);
    }

    .table-actions {
        display: flex;
        gap: 0.75rem;
    }

    .table-wrapper {
        overflow-x: auto;
    }

    .admins-table {
        width: 100%;
        border-collapse: collapse;
    }

    .admins-table th,
    .admins-table td {
        padding: 1rem 1.5rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    .admins-table th {
        background: var(--bg-secondary);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
        position: sticky;
        top: 0;
        z-index: 10;
    }

    .admin-row {
        transition: all 0.3s ease;
    }

    .admin-row:hover {
        background: var(--bg-secondary);
    }

    .admin-id .id-text {
        font-family: monospace;
        font-weight: 600;
        color: var(--text-primary);
        background: var(--bg-secondary);
        padding: 0.25rem 0.5rem;
        border-radius: 6px;
        font-size: 0.85rem;
    }

    .admin-name {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .name-avatar {
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

    .name-primary {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.95rem;
    }

    .name-secondary {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .employee-info .employee-name {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .employee-info .employee-id {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .employee-info .no-employee {
        font-size: 0.85rem;
        color: var(--text-light);
        font-style: italic;
    }

    .created-date .date-primary {
        font-weight: 500;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .created-date .date-secondary {
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

    .action-buttons {
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

    .view-btn {
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
    }

    .view-btn:hover {
        background: #2563EB;
        color: white;
        transform: translateY(-2px);
    }

    .edit-btn {
        background: rgba(245, 158, 11, 0.1);
        color: #F59E0B;
    }

    .edit-btn:hover {
        background: #F59E0B;
        color: white;
        transform: translateY(-2px);
    }

    .delete-btn {
        background: rgba(220, 38, 38, 0.1);
        color: var(--redcode-primary);
    }

    .delete-btn:hover {
        background: var(--redcode-primary);
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

    /* Pagination */
    .table-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem 2rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    .pagination-info {
        font-size: 0.9rem;
        color: var(--text-secondary);
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
        max-width: 500px;
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

    .form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-actions {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        margin-top: 2rem;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .admin-management-container {
            padding: 1rem;
        }

        .admin-management-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .admin-stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .search-filter-section {
            flex-direction: column;
            gap: 1rem;
        }

        .filter-container {
            justify-content: stretch;
        }

        .filter-select {
            flex: 1;
        }

        .table-wrapper {
            overflow-x: scroll;
        }

        .admins-table {
            min-width: 800px;
        }

        .form-actions {
            flex-direction: column;
        }
    }
</style>

<script>
    function viewAdmin(adminId) {
        const modal = document.getElementById('viewAdminModal');
        modal.classList.add('active');

        // Fetch admin data from backend
        fetch(`/super-admin/${adminId}/show`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const admin = data.admin;

                    // Basic Information
                    document.getElementById('view_admin_name').textContent = admin.admin_name || 'N/A';
                    document.getElementById('view_admin_id').textContent = admin.admin_id || 'N/A';
                    document.getElementById('view_admin_role').textContent = admin.role || 'N/A';

                    // Initials for avatar
                    const initials = admin.admin_name
                        ? admin.admin_name.split(" ").map(n => n[0]).join("").toUpperCase()
                        : "NA";
                    document.getElementById('view_admin_initials').textContent = initials;

                    // Contact & Email
                    document.getElementById('view_admin_email').textContent = admin.email || 'N/A';
                    document.getElementById('view_admin_contact').textContent = admin.contact_no || 'N/A';

                    // Department
                    document.getElementById('view_admin_department').textContent =
                        admin.department?.department_name || 'Not Assigned';

                    // Status
                    const statusField = document.getElementById('view_admin_status');
                    statusField.textContent = admin.status || 'N/A';
                    statusField.className = 'detail-value font-medium'; // reset any previous classes

                    if (admin.status) {
                        const status = admin.status.toLowerCase();

                        if (status === 'active') {
                            statusField.classList.add('text-green-600'); // green
                        } else if (status === 'inactive') {
                            statusField.classList.add('text-gray-500'); // gray
                        } else if (status.includes('leave')) {
                            statusField.classList.add('text-yellow-600'); // yellow
                        } else if (status.includes('terminated')) {
                            statusField.classList.add('text-red-600'); // red
                        }
                    }

                    if(admin.created_at) {
                        const created = new Date(admin.created_at);
                        document.getElementById('view_admin_created_date').textContent =
                            created.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        document.getElementById('view_admin_created_time').textContent =
                            created.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
                    } else {
                        document.getElementById('view_admin_created_date').textContent = 'N/A';
                        document.getElementById('view_admin_created_time').textContent = '';
                    }


                } else {
                    throw new Error(data.message || 'Failed to fetch admin data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading admin data: ' + error.message);
                closeViewAdminModal();
            });
    }

    function closeViewAdminModal() {
        document.getElementById('viewAdminModal').classList.remove('active');
    }



    const modal = document.getElementById('editAdminModal');
    const form = document.getElementById('editAdminForm');


    function openEditAdminModal(adminId) {
        if (!adminId) return alert('Admin ID missing!');

        const modal = document.getElementById('editAdminModal');
        const form = document.getElementById('editAdminForm');

        // Reset form and set action
        form.reset();
        form.action = `/super-admin/admins/${adminId}`; // PUT route

        // Show modal
        modal.classList.add('active');

        // Fetch admin data
        fetch(`/super-admin/admins/${adminId}/edit`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error('Failed to fetch admin data');

                const admin = data.admin;
                const departments = data.departments;

                // Populate form fields
                document.getElementById('edit_admin_name').value = admin.admin_name;
                document.getElementById('edit_role').value = admin.role;
                document.getElementById('edit_email').value = admin.email;
                document.getElementById('edit_contact_no').value = admin.contact_no;
                document.getElementById('edit_status').value = admin.status;

                // Populate department select
                const deptSelect = document.getElementById('edit_department_id');
                deptSelect.innerHTML = '<option value="">Select Department</option>';
                departments.forEach(dep => {
                    const option = document.createElement('option');
                    option.value = dep.department_id;
                    option.textContent = dep.department_name;
                    if (dep.department_id === admin.department_id) option.selected = true;
                    deptSelect.appendChild(option);
                });
            })
            .catch(err => {
                console.error(err);
                alert('Error loading admin data');
                modal.classList.remove('active');
            });
    }

    function closeEditAdminModal() {
        const modal = document.getElementById('editAdminModal');
        modal.classList.remove('active');
    }

    // Search functionality
    function initializeSearch() {
        const searchInput = document.getElementById('adminSearch');
        const statusFilter = document.getElementById('statusFilter');
        const roleFilter = document.getElementById('roleFilter');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const statusValue = statusFilter.value.toLowerCase();
            const roleValue = roleFilter.value.toLowerCase();

            const rows = document.querySelectorAll('.admin-row');

            rows.forEach(row => {
                const adminName = row.querySelector('.name-primary').textContent.toLowerCase();
                const adminId = row.querySelector('.id-text').textContent.toLowerCase();
                const visible = adminName.includes(searchTerm) || adminId.includes(searchTerm);

                row.style.display = visible ? 'table-row' : 'none';
            });
        }

        searchInput.addEventListener('input', filterTable);
        statusFilter.addEventListener('change', filterTable);
        roleFilter.addEventListener('change', filterTable);
    }

    // Select all functionality
    function toggleSelectAll() {
        const selectAll = document.getElementById('selectAll');
        const checkboxes = document.querySelectorAll('.admin-checkbox');

        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAll.checked;
        });
    }

    // Modal functions
    function openAddAdminModal() {
        document.getElementById('addAdminModal').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeAddAdminModal() {
        document.getElementById('addAdminModal').classList.remove('show');
        document.body.style.overflow = 'auto';
        document.getElementById('addAdminForm').reset();
    }

    function submitAddAdminForm(event) {
        event.preventDefault();

        // Get form data
        const formData = new FormData(event.target);
        const adminData = Object.fromEntries(formData);

        // Here you would normally send the data to your backend
        console.log('Adding admin:', adminData);

        // Show success message
        showNotification('Admin added successfully!', 'success');

        // Close modal
        closeAddAdminModal();

        // Refresh table (in a real app, you'd reload data from server)
        // refreshAdminData();
    }

    // Action functions
    // function viewAdmin(adminId) {
    //     showNotification(`Viewing admin: ${adminId}`, 'info');
    //     // Implement view functionality
    // }

    function editAdmin(adminId) {
        showNotification(`Editing admin: ${adminId}`, 'info');
        // Implement edit functionality
    }

    function deleteAdmin(adminId) {
        if (confirm(`Are you sure you want to delete admin ${adminId}?`)) {
            showNotification(`Admin ${adminId} deleted successfully!`, 'success');
            // Implement delete functionality
        }
    }

    function refreshAdminData() {
        showNotification('Refreshing admin data...', 'info');
        // Implement refresh functionality
    }

    function exportAdminData() {
        showNotification('Exporting admin data...', 'info');
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

    // Initialize when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeSearch();

        // Close modal when clicking outside
        document.getElementById('addAdminModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeAddAdminModal();
            }
        });
    });
</script>
@endsection
