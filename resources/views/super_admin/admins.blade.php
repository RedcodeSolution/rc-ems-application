@extends('layouts.super_admin')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/adminManagement.css') }}">
@section('title', 'Admin Management')

@section('content')
<div class="admin-management-container">
    <div class="admin-management-header">
        <div class="header-content">
            <h1><i class="fas fa-user-shield"></i> Admin Management</h1>
            <p>Manage all administrators and their permissions</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openAddAdminModal()">
                <i class="fas fa-plus"></i> Add New Admin
            </button>
        </div>
    </div>
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

    <div class="admins-table-container">
        <div class="table-header">
            <h2><i class="fas fa-table"></i> Administrators List</h2>

        </div>

        <div class="table-wrapper">
            <table class="admins-table">
                <thead>
                    <tr>

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
                    <tr class="admin-row" data-admin-id="{{ $admin->admin_id }}" data-status="{{ $admin->status }}" data-role="{{ $admin->role }}">

                        <td data-label="Admin ID">
                            <div class="admin-id">
                                <span class="id-text">{{ $admin->admin_id }}</span>
                            </div>
                        </td>
                        <td data-label="Admin Name">
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
                        <td data-label="Department Info">
                            <div class="department-info">
                                @if($admin->department)
                                <div class="department-name">{{ $admin->department->department_name ?? 'N/A' }}</div>
                                <div class="department-id">ID: {{ $admin->department->department_id ?? 'N/A' }}</div>
                                @else
                                <div class="no-department">No assigned department</div>
                                @endif
                            </div>

                        </td>
                        <td data-label="Created Date">
                            <div class="created-date">
                                <div class="date-primary">{{ $admin->created_at ? $admin->created_at->format('M d, Y') : 'N/A' }}</div>
                                <div class="date-secondary">{{ $admin->created_at ? $admin->created_at->format('h:i A') : '' }}</div>
                            </div>
                        </td>
                        <td data-label="Status">
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

                        <td data-label="Actions">
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
                                    <button type="button" class="action-btn delete-btn" title="Delete Admin"
                                            onclick="confirmDeleteForm(this.closest('form'))">
                                        <i class="fas fa-trash"></i>
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

        <div class="table-pagination">
            <div class="pagination-info">
                Showing {{ $admins->count() }} of {{ $admins->count() }} administrators
            </div>
            <div class="pagination-controls">
            </div>
        </div>
    </div>
</div>

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
                        <div class="form-group">
                            <label for="admin_name" class="form-label">
                                <i class="fas fa-user"></i> Full Name
                            </label>
                            <input type="text" id="admin_name" name="admin_name" class="form-input"
                                   value="{{ old('admin_name') }}" placeholder="Enter full name" required>
                        </div>

                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i> Role/Position
                            </label>
                            <input type="text" id="role" name="role" class="form-input"
                                   value="{{ old('role') }}" placeholder="Enter role or position" required>
                        </div>
                    </div>

                    <div class="form-row">
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

                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="email" name="email" class="form-input"
                                   value="{{ old('email') }}" placeholder="Enter email" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone"></i> Contact Number
                            </label>
                            <input type="tel" id="contact_no" name="contact_no" class="form-input"
                                   value="{{ old('contact_no') }}" placeholder="Enter contact number" required>
                        </div>

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

<div id="editAdminModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-user-edit"></i> Edit Admin</h3>
            <button class="modal-close" onclick="closeEditAdminModal()">&times;</button>
        </div>

        <div class="modal-body">
            <form id="editAdminForm" action="" method="POST" onsubmit="confirmUpdateForm(event)">
                @csrf
                @method('PUT')

                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_admin_name" class="form-label">
                                <i class="fas fa-user"></i> Admin Name
                            </label>
                            <input type="text" id="edit_admin_name" name="admin_name" class="form-input" placeholder="Enter admin name" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_role" class="form-label">
                                <i class="fas fa-user-shield"></i> Role
                            </label>
                            <input type="text" id="edit_role" name="role" class="form-input" placeholder="Enter role" required>
                        </div>

                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope"></i> Email
                            </label>
                            <input type="email" id="edit_email" name="email" class="form-input" placeholder="Enter email" required>
                        </div>

                        <div class="form-group">
                            <label for="edit_contact_no" class="form-label">
                                <i class="fas fa-phone"></i> Contact
                            </label>
                            <input type="tel" id="edit_contact_no" name="contact_no" class="form-input" placeholder="Enter contact number" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department_id" class="form-label">
                                <i class="fas fa-building"></i> Department
                            </label>
                            <select id="edit_department_id" name="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>

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

<div id="viewAdminModal" class="modal" role="dialog" aria-modal="true" aria-label="View Administrator">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-eye"></i> Admin Details</h3>
            <button class="modal-close" onclick="closeViewAdminModal()" aria-label="Close view dialog">&times;</button>
        </div>

        <div class="modal-body">
            <div class="profile-card">
                <div class="profile-section">
                    <div class="avatar" id="view_admin_initials" aria-hidden="true">—</div>
                    <div class="profile-info">
                        <h2 id="view_admin_name">—</h2>
                        <div class="profile-sub">
                            <span class="role-text" id="view_admin_role">—</span>
                            <span class="profile-id" id="view_admin_id">—</span>
                        </div>
                    </div>
                </div>

                <div class="details-container">
                    <div class="details-grid">
                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-envelope detail-icon"></i>
                                <span class="detail-label">Email</span>
                            </div>
                            <div class="detail-value" id="view_admin_email">—</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-phone detail-icon"></i>
                                <span class="detail-label">Contact</span>
                            </div>
                            <div class="detail-value" id="view_admin_contact">—</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-building detail-icon"></i>
                                <span class="detail-label">Department</span>
                            </div>
                            <div class="detail-value" id="view_admin_department">—</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-toggle-on detail-icon"></i>
                                <span class="detail-label">Status</span>
                            </div>
                            <div class="detail-value" id="view_admin_status">—</div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-calendar-plus detail-icon"></i>
                                <span class="detail-label">Created</span>
                            </div>
                            <div class="detail-value">
                                <div id="view_admin_created_date">—</div>
                                <div id="view_admin_created_time" class="muted small">—</div>
                            </div>
                        </div>

                        <div class="detail-item">
                            <div class="detail-header">
                                <i class="fas fa-clock detail-icon"></i>
                                <span class="detail-label">Last Login</span>
                            </div>
                            <div class="detail-value" id="view_admin_last_login">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="button" class="btn btn-outline" onclick="openEditAdminModal(document.getElementById('view_admin_id').textContent)">
                <i class="fas fa-edit"></i> Edit
            </button>
            <button type="button" class="btn btn-secondary" onclick="closeViewAdminModal()">
                <i class="fas fa-times"></i> Close
            </button>
        </div>
    </div>
</div>

<script>
    function viewAdmin(adminId) {
        if (!adminId) return alert('Admin ID missing!');
        const modal = document.getElementById('viewAdminModal');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        const fields = [
            'view_admin_name','view_admin_id','view_admin_role','view_admin_initials',
            'view_admin_email','view_admin_contact','view_admin_department','view_admin_status',
            'view_admin_created_date','view_admin_created_time','view_admin_last_login'
        ];
        fields.forEach(id => {
            const el = document.getElementById(id);
            if (!el) return;
            el.textContent = id === 'view_admin_name' ? 'Loading...' : '—';
            el.className = el.className.replace(/\btext-(green|gray|yellow|red)-\d{3}\b/g, '');
        });

        fetch(`/super-admin/${adminId}/show`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Failed to fetch admin data');
            const admin = data.admin;

            document.getElementById('view_admin_name').textContent = admin.admin_name || 'N/A';
            document.getElementById('view_admin_id').textContent = admin.admin_id || 'N/A';
            document.getElementById('view_admin_role').textContent = admin.role || 'N/A';

            const initials = admin.admin_name
                ? admin.admin_name.split(' ').map(n => n[0]).join('').toUpperCase()
                : 'NA';
            const avatar = document.getElementById('view_admin_initials');
            if (avatar) avatar.textContent = initials;

            document.getElementById('view_admin_email').textContent = admin.email || 'N/A';
            document.getElementById('view_admin_contact').textContent = admin.contact_no || 'N/A';
            document.getElementById('view_admin_department').textContent = admin.department?.department_name || 'Not Assigned';

            const statusField = document.getElementById('view_admin_status');
            if (statusField) {
                statusField.textContent = admin.status || 'N/A';
                statusField.className = 'detail-value';
                if (admin.status) {
                    const s = admin.status.toLowerCase();
                    if (s === 'active') statusField.classList.add('text-green-600');
                    else if (s === 'inactive') statusField.classList.add('text-gray-500');
                    else if (s.includes('leave')) statusField.classList.add('text-yellow-600');
                    else if (s.includes('terminated')) statusField.classList.add('text-red-600');
                }
            }

            if (admin.created_at) {
                const created = new Date(admin.created_at);
                document.getElementById('view_admin_created_date').textContent =
                    created.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                document.getElementById('view_admin_created_time').textContent =
                    created.toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hour12: true });
            } else {
                document.getElementById('view_admin_created_date').textContent = 'N/A';
                document.getElementById('view_admin_created_time').textContent = '';
            }

            document.getElementById('view_admin_last_login').textContent = admin.last_login
                ? new Date(admin.last_login).toLocaleString()
                : 'N/A';
        })
        .catch(err => {
            console.error('Error loading admin data:', err);
            alert('Error loading admin data.');
            closeViewAdminModal();
        });
    }

    function closeViewAdminModal() {
        const modal = document.getElementById('viewAdminModal');
        if (!modal) return;
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
    }

    function openAddAdminModal() {
        const modal = document.getElementById('addAdminModal');
        if (!modal) return console.warn('addAdminModal not found');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeAddAdminModal() {
        const modal = document.getElementById('addAdminModal');
        if (!modal) return;
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
        const form = document.getElementById('addAdminForm');
        if (form) form.reset();
    }

    document.addEventListener('DOMContentLoaded', function () {
        const viewModal = document.getElementById('viewAdminModal');
        if (viewModal) {
            viewModal.addEventListener('click', function (e) {
                if (e.target === this) closeViewAdminModal();
            });
        }

        const addModal = document.getElementById('addAdminModal');
        if (addModal) {
            addModal.addEventListener('click', function (e) {
                if (e.target === this) closeAddAdminModal();
            });
        }

        const editModal = document.getElementById('editAdminModal');
        if (editModal) {
            editModal.addEventListener('click', function (e) {
                if (e.target === this) closeEditAdminModal();
            });
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' || e.key === 'Esc') {
                closeViewAdminModal();
                const add = document.getElementById('addAdminModal'); if (add) add.classList.remove('show');
                const edit = document.getElementById('editAdminModal'); if (edit) edit.classList.remove('show');
                document.body.style.overflow = 'auto';
            }
        });
    });

    function openEditAdminModal(adminId) {
        closeViewAdminModal();
        if (!adminId) return alert('Admin ID missing!');
        const modal = document.getElementById('editAdminModal');
        if (!modal) return console.warn('editAdminModal not found');
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';

        const form = document.getElementById('editAdminForm');
        if (form) {
            form.action = `/super-admin/admins/${adminId}`;
        }

        ['edit_admin_name','edit_role','edit_email','edit_contact_no','edit_status','edit_department_id'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                if (el.tagName === 'SELECT') el.value = '';
                else el.value = '';
            }
        });

        fetch(`/super-admin/${adminId}/show`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(res => res.json())
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Failed to fetch admin data');
            const admin = data.admin;

            document.getElementById('edit_admin_name').value = admin.admin_name || '';
            document.getElementById('edit_role').value = admin.role || '';
            document.getElementById('edit_email').value = admin.email || '';
            document.getElementById('edit_contact_no').value = admin.contact_no || '';
            if (admin.status) document.getElementById('edit_status').value = admin.status;

            const deptSelect = document.getElementById('edit_department_id');
            if (deptSelect) {
                if (admin.department && admin.department.department_id) {
                    const opt = deptSelect.querySelector(`option[value="${admin.department.department_id}"]`);
                    if (opt) opt.selected = true;
                    else deptSelect.value = admin.department.department_id;
                } else {
                    deptSelect.value = '';
                }
            }
        })
        .catch(err => {
            console.error('Error loading admin for edit:', err);
            alert('Error loading admin data.');
            closeEditAdminModal();
        });
    }

    function closeEditAdminModal() {
        const modal = document.getElementById('editAdminModal');
        if (!modal) return;
        modal.classList.remove('show');
        document.body.style.overflow = 'auto';
        const form = document.getElementById('editAdminForm');
        if (form) form.reset();
    }

    function confirmUpdateForm(e) {
        e.preventDefault();
        const form = e.target;
        
        Swal.fire({
            title: 'Update Admin?',
            text: "Save changes to this administrator?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, update it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    }

    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.getElementById("adminSearch");
        const statusFilter = document.getElementById("statusFilter");
        const roleFilter = document.getElementById("roleFilter");
        const tableBody = document.getElementById("adminsTableBody");

        function filterAdmins() {
            const searchValue = searchInput.value.toLowerCase().trim();
            const selectedStatus = statusFilter.value.toLowerCase();
            const selectedRole = roleFilter.value.toLowerCase();

            const rows = tableBody.querySelectorAll(".admin-row");

            rows.forEach(row => {
                const adminId = row.dataset.adminId.toLowerCase();
                const adminName = row.querySelector("td[data-label='Admin Name'] .name-primary")?.textContent.toLowerCase().trim() || "";
                const status = row.dataset.status ? row.dataset.status.toLowerCase() : "";
                const role = row.dataset.role ? row.dataset.role.toLowerCase() : "";

                const matchesSearch = adminName.includes(searchValue) || adminId.includes(searchValue);
                const matchesStatus = !selectedStatus || status === selectedStatus;
                const matchesRole = !selectedRole || role.includes(selectedRole);

                // Show or hide row based on match
                if (matchesSearch && matchesStatus && matchesRole) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });

            // Show "No results" message if nothing is visible
            const visibleRows = Array.from(rows).some(row => row.style.display !== "none");
            let noDataRow = tableBody.querySelector(".no-data-row");
            if (!visibleRows) {
                if (!noDataRow) {
                    noDataRow = document.createElement("tr");
                    noDataRow.classList.add("no-data-row");
                    noDataRow.innerHTML = `
                        <td colspan="7">
                            <div class="no-data-message">
                                <i class="fas fa-search"></i>
                                <h3>No matching administrators found</h3>
                                <p>Try adjusting your search or filters.</p>
                            </div>
                        </td>`;
                    tableBody.appendChild(noDataRow);
                }
            } else {
                if (noDataRow) noDataRow.remove();
            }
        }

        // Attach listeners
        searchInput.addEventListener("keyup", filterAdmins);
        statusFilter.addEventListener("change", filterAdmins);
        roleFilter.addEventListener("change", filterAdmins);
    });
</script>

@endsection
