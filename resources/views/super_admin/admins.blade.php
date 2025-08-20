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
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
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
                        <th>Employee Info</th>
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
                            <div class="employee-info">
                                @if($admin->employee)
                                    <div class="employee-name">{{ $admin->employee->employee_name ?? 'N/A' }}</div>
                                    <div class="employee-id">ID: {{ $admin->employee->employee_id ?? 'N/A' }}</div>
                                @else
                                    <div class="no-employee">No associated employee</div>
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
                            <span class="status-badge status-active">
                                <i class="fas fa-check-circle"></i>
                                Active
                            </span>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="action-btn view-btn" onclick="viewAdmin('{{ $admin->admin_id }}')" title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="action-btn edit-btn" onclick="editAdmin('{{ $admin->admin_id }}')" title="Edit Admin">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="action-btn delete-btn" onclick="deleteAdmin('{{ $admin->admin_id }}')" title="Delete Admin">
                                    <i class="fas fa-trash"></i>
                                </button>
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
            <form id="addAdminForm" onsubmit="submitAddAdminForm(event)">
                <div class="form-group">
                    <label for="newAdminId">Admin ID</label>
                    <input type="text" id="newAdminId" name="admin_id" class="form-input" placeholder="Enter admin ID" required>
                </div>
                <div class="form-group">
                    <label for="newAdminName">Admin Name</label>
                    <input type="text" id="newAdminName" name="admin_name" class="form-input" placeholder="Enter admin name" required>
                </div>
                <div class="form-group">
                    <label for="newAdminEmail">Email (Optional)</label>
                    <input type="email" id="newAdminEmail" name="email" class="form-input" placeholder="Enter email address">
                </div>
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeAddAdminModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Administrator
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    .admin-management-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
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
    function viewAdmin(adminId) {
        showNotification(`Viewing admin: ${adminId}`, 'info');
        // Implement view functionality
    }

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
