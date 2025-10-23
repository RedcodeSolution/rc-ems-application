@extends('layouts.super_admin')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/adminManagement.css') }}">
@section('title', 'Admin Management - Super Admin Dashboard')

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
            <button class="btn btn-secondary" onclick="exportAdminData()">
                <i class="fas fa-download"></i> Export Data
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
                        <td data-label="Select">
                            <input type="checkbox" class="admin-checkbox" value="{{ $admin->admin_id }}">
                        </td>
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
                        <!-- Admin Name -->
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
