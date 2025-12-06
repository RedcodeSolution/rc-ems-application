@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin/department.css') }}">

@section('title', 'Departments Management')

@section('content')
    <div class="departments-stats" style="margin-top: 0; margin-bottom: 2rem;">
        <div class="card">
            <div class="card-body text-center">
                <div class="stat-value stat-departments">{{ $departments->count() }}</div>
                <div class="stat-label">Total Departments</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="stat-value stat-employees">{{ $departments->sum('employees_count') }}</div>
                <div class="stat-label">Total Employees</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                @php
                    $totalEmployees = $departments->sum('employees_count');
                    $totalDepartments = $departments->count();
                    $average = $totalDepartments > 0 ? round($totalEmployees / $totalDepartments, 1) : 0;
                @endphp
                <div class="stat-value stat-average">{{ $average }}</div>
                <div class="stat-label">Avg per Department</div>
            </div>
        </div>
        <div class="card">
            <div class="card-body text-center">
                <div class="stat-value stat-largest">{{ $departments->max('employees_count') ?? 0 }}</div>
                <div class="stat-label">Largest Department</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-building"></i> Departments</h2>
            <div class="flex gap-2">
                <button onclick="openDepartmentModal()" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Add Department
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-download"></i>
                    Export
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="flex justify-between items-center mb-4">
                <input type="text" id="departmentSearch" placeholder="Search departments..."
                    class="form-input search-input">
                <button class="btn btn-secondary" onclick="clearSearch()">
                    <i class="fas fa-times"></i>
                    Clear
                </button>
            </div>

            <div id="departmentsGrid" class="departments-grid">
                @forelse($departments as $department)
                    <div class="card">
                        <div class="card-header">
                            <h3 class="department-title">
                                @php
                                    // Dynamic icon based on department name/id
                                    $icon = 'fas fa-building';
                                    $iconColor = 'var(--primary)';

                                    $deptLower = strtolower($department->department_name ?? $department->department_id);
                                    if (
                                        str_contains($deptLower, 'development') ||
                                        str_contains($deptLower, 'it') ||
                                        str_contains($deptLower, 'tech')
                                    ) {
                                        $icon = 'fas fa-code';
                                        $iconColor = 'var(--primary)';
                                    } elseif (
                                        str_contains($deptLower, 'design') ||
                                        str_contains($deptLower, 'creative')
                                    ) {
                                        $icon = 'fas fa-paint-brush';
                                        $iconColor = 'var(--secondary)';
                                    } elseif (
                                        str_contains($deptLower, 'marketing') ||
                                        str_contains($deptLower, 'sales')
                                    ) {
                                        $icon = 'fas fa-bullhorn';
                                        $iconColor = 'var(--warning)';
                                    } elseif (str_contains($deptLower, 'hr') || str_contains($deptLower, 'human')) {
                                        $icon = 'fas fa-users';
                                        $iconColor = 'var(--info)';
                                    } elseif (
                                        str_contains($deptLower, 'finance') ||
                                        str_contains($deptLower, 'accounting')
                                    ) {
                                        $icon = 'fas fa-calculator';
                                        $iconColor = 'var(--success)';
                                    } elseif (str_contains($deptLower, 'support') || str_contains($deptLower, 'help')) {
                                        $icon = 'fas fa-headset';
                                        $iconColor = 'var(--info)';
                                    }
                                @endphp
                                <i class="{{ $icon }} department-icon" style="color: {{ $iconColor }};"></i>
                                {{ $department->department_name ?? $department->department_id }}
                            </h3>
                            <div class="flex gap-1">
                                <button class="btn btn-warning edit-btn" title="Edit Department"
                                    onclick="openEditDepartmentModal('{{ $department->department_id }}')">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger delete-btn" title="Delete Department"
                                    onclick="confirmDeleteDepartment('{{ $department->department_id }}', '{{ $department->department_name ?? $department->department_id }}')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if ($department->employee)
                                <div class="mb-1 department-head">
                                    <div class="label">Department Head</div>
                                    <div class="value">{{ $department->employee->employee_name }}</div>
                                </div>
                            @else
                                <div class="mb-1 department-head">
                                    <div class="label">Department Head</div>
                                    <div class="value not-assigned">Not Assigned</div>
                                </div>
                            @endif

                            <div class="mb-1 total-employees">
                                <div class="label">Total Employees</div>
                                <div class="value" style="color: {{ $iconColor }};">
                                    {{ $department->employees_count ?? 0 }}
                                </div>
                            </div>

                            @if ($department->description)
                                <div class="mb-1 department-description">
                                    <div class="label">Description</div>
                                    <div class="value">{{ Str::limit($department->description, 100) }}</div>
                                </div>
                            @endif

                            @if ($department->location)
                                <div class="mb-1 department-location">
                                    <div class="label">Location</div>
                                    <div class="value location-value">
                                        <i class="fas fa-map-marker-alt location-icon"></i>
                                        {{ $department->location }}
                                    </div>
                                </div>
                            @endif

                            <div class="flex justify-between items-center">
                                @php
                                    $statusClass = $department->status === 'Active' ? 'success' : 'text-secondary';
                                    $statusBg =
                                        $department->status === 'Active'
                                            ? 'rgba(16, 185, 129, 0.1)'
                                            : 'rgba(107, 114, 128, 0.1)';
                                @endphp
                                <span class="badge department-status" data-status="{{ $department->status }}">
                                    {{ $department->status ?? 'Active' }}
                                </span>
                                <a href="#" class="btn btn-secondary view-details-btn"
                                    onclick="viewDepartmentDetails('{{ $department->department_id }}')">
                                    <i class="fas fa-eye"></i>
                                    View Details
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="departments-empty">
                        <i class="fas fa-building empty-icon"></i>
                        <h3 class="empty-title">No Departments Found</h3>
                        <p class="empty-desc">Start by creating your first department using the "Add Department" button
                            above.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>



    <div id="departmentModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-building"></i>
                    Add New Department
                </div>
                <div class="modal-subtitle">Create a comprehensive department profile for your organization</div>
                <button class="modal-close" onclick="closeDepartmentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="departmentForm" action="{{ route('admin.departments.store') }}" method="POST">
                    @csrf
                    <div class="form-container">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="department_name" class="form-label">
                                    <i class="fas fa-building"></i>Department Name
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" id="department_name" name="department_name" class="form-input"
                                        placeholder="Enter department name" required>
                                </div>
                                <small class="form-hint">
                                    Full name of the department
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>Description
                            </label>
                            <div class="input-wrapper">
                                <textarea id="description" name="description" class="form-input"
                                    placeholder="Describe the department's role and responsibilities" rows="3"></textarea>
                            </div>
                            <small class="form-hint">
                                Brief description of the department's purpose
                            </small>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="employee_id" class="form-label">
                                    <i class="fas fa-user-tie"></i> Department Head
                                </label>
                                <div class="input-wrapper">
                                    <select id="employee_id" name="employee_id" class="form-input">
                                        <option value="">-- Select Department Head --</option>
                                        @foreach ($employees as $employee)
                                            <option value="{{ $employee->employee_id }}"
                                                {{ old('employee_id') == $employee->employee_id ? 'selected' : '' }}>
                                                {{ $employee->employee_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <small class="form-hint">
                                    Manager or head of this department (optional)
                                </small>
                                @error('employee_id')
                                    <div class="text-danger error-message">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="location" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>Location
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" id="location" name="location" class="form-input"
                                        placeholder="Office location or floor">
                                </div>
                                <small class="form-hint">
                                    Physical location of the department
                                </small>
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="phone" class="form-label">
                                    <i class="fas fa-phone"></i>Phone Number
                                </label>
                                <div class="input-wrapper">
                                    <input type="tel" id="phone" name="phone" class="form-input"
                                        placeholder="Department phone number">
                                </div>
                                <small class="form-hint">
                                    Contact number for the department
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope"></i>Email Address
                                </label>
                                <div class="input-wrapper">
                                    <input type="email" id="email" name="email" class="form-input"
                                        placeholder="department@company.com">
                                </div>
                                <small class="form-hint">
                                    Department email address
                                </small>
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="budget" class="form-label">
                                    <i class="fas fa-dollar-sign"></i>Annual Budget
                                </label>
                                <div class="input-wrapper">
                                    <input type="number" id="budget" name="budget" class="form-input"
                                        placeholder="0.00" step="0.01" min="0">
                                </div>
                                <small class="form-hint">
                                    Annual budget allocation (optional)
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="status" class="form-label">
                                    <i class="fas fa-toggle-on"></i>Department Status
                                </label>
                                <div class="input-wrapper">
                                    <select id="status" name="status" class="form-input" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <small class="form-hint">
                                    Current operational status
                                </small>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeDepartmentModal()">
                                <i class="fas fa-times"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Create Department
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="editDepartmentModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-edit"></i>
                    Edit Department
                </div>
                <div class="modal-subtitle">Update department information and settings</div>
                <button class="modal-close" onclick="closeEditDepartmentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="editDepartmentForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-container">
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="edit_department_name" class="form-label">
                                    <i class="fas fa-building"></i>Department Name
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" id="edit_department_name" name="department_name"
                                        class="form-input" placeholder="Enter department name" required>
                                </div>
                                <small class="form-hint">
                                    Full name of the department
                                </small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_description" class="form-label">
                                <i class="fas fa-align-left"></i>Description
                            </label>
                            <div class="input-wrapper">
                                <textarea id="edit_description" name="description" class="form-input"
                                    placeholder="Describe the department's role and responsibilities" rows="3"></textarea>
                            </div>
                            <small class="form-hint">
                                Brief description of the department's purpose
                            </small>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="employee_id" class="form-label">
                                    <i class="fas fa-user-tie"></i> Department Head
                                </label>
                                <select id="edit_employee_id" name="employee_id" class="form-input">
                                    <option value="">-- Select Department Head --</option>
                                    @foreach ($employees as $employee)
                                        <option value="{{ $employee->employee_id }}">
                                            {{ $employee->employee_name }}
                                        </option>
                                    @endforeach
                                </select>
                                <small class="form-hint">
                                    Name of the department head or manager
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="edit_location" class="form-label">
                                    <i class="fas fa-map-marker-alt"></i>Location
                                </label>
                                <div class="input-wrapper">
                                    <input type="text" id="edit_location" name="location" class="form-input"
                                        placeholder="Enter department location">
                                </div>
                                <small class="form-hint">
                                    Physical location or office address
                                </small>
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="edit_phone" class="form-label">
                                    <i class="fas fa-phone"></i>Phone Number
                                </label>
                                <div class="input-wrapper">
                                    <input type="tel" id="edit_phone" name="phone" class="form-input"
                                        placeholder="Enter phone number">
                                </div>
                                <small class="form-hint">
                                    Department contact number
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="edit_email" class="form-label">
                                    <i class="fas fa-envelope"></i>Email Address
                                </label>
                                <div class="input-wrapper">
                                    <input type="email" id="edit_email" name="email" class="form-input"
                                        placeholder="Enter email address">
                                </div>
                                <small class="form-hint">
                                    Department email address
                                </small>
                            </div>
                        </div>
                        <div class="form-row-2">
                            <div class="form-group">
                                <label for="edit_budget" class="form-label">
                                    <i class="fas fa-dollar-sign"></i>Budget
                                </label>
                                <div class="input-wrapper">
                                    <input type="number" id="edit_budget" name="budget" class="form-input"
                                        placeholder="Enter budget amount" min="0" step="0.01">
                                </div>
                                <small class="form-hint">
                                    Annual budget allocation
                                </small>
                            </div>
                            <div class="form-group">
                                <label for="edit_status" class="form-label">
                                    <i class="fas fa-toggle-on"></i>Status
                                </label>
                                <div class="input-wrapper">
                                    <select id="edit_status" name="status" class="form-input" required>
                                        <option value="Active">Active</option>
                                        <option value="Inactive">Inactive</option>
                                    </select>
                                </div>
                                <small class="form-hint">
                                    Current operational status
                                </small>
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="button" class="btn btn-secondary" onclick="closeEditDepartmentModal()">
                                <i class="fas fa-times"></i>
                                Cancel
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Update Department
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="viewDepartmentModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-eye"></i>
                    Department Details
                </div>
                <div class="modal-subtitle">View comprehensive department information</div>
                <button class="modal-close" onclick="closeViewDepartmentModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-container">
                    <div class="view-header-info">
                        <div id="view_department_icon" class="view-department-icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h2 id="view_department_name" class="view-department-name"></h2>
                        <p id="view_department_id" class="view-department-id"></p>
                    </div>
                    <div class="view-info-grid">
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-user-tie info-card-icon"></i>
                                <h4 class="info-card-title">Department Head</h4>
                            </div>
                            <p id="view_department_head" class="info-card-value"></p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-map-marker-alt info-card-icon info-card-icon-location"></i>
                                <h4 class="info-card-title">Location</h4>
                            </div>
                            <p id="view_location" class="info-card-value"></p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-phone info-card-icon info-card-icon-phone"></i>
                                <h4 class="info-card-title">Phone Number</h4>
                            </div>
                            <p id="view_phone" class="info-card-value"></p>
                        </div>
                        <div class="info-card">
                            <div class="info-card-header">
                                <i class="fas fa-envelope info-card-icon info-card-icon-email"></i>
                                <h4 class="info-card-title">Email Address</h4>
                            </div>
                            <p id="view_email" class="info-card-value"></p>
                        </div>
                    </div>
                    <div class="info-card view-description-card">
                        <div class="info-card-header">
                            <i class="fas fa-align-left info-card-icon info-card-icon-desc"></i>
                            <h4 class="info-card-title">Description</h4>
                        </div>
                        <p id="view_description" class="info-card-value view-description-value"></p>
                    </div>
                    <div class="view-stats-grid">
                        <div class="stat-card stat-card-employees">
                            <div class="stat-card-value">
                                <span id="view_employees_count">0</span>
                            </div>
                            <div class="stat-card-label">Total Employees</div>
                        </div>
                        <div class="stat-card stat-card-budget">
                            <div class="stat-card-value">
                                <span id="view_budget">$0</span>
                            </div>
                            <div class="stat-card-label">Annual Budget</div>
                        </div>
                        <div class="stat-card" id="view_status_card">
                            <div class="stat-card-value">
                                <i id="view_status_icon" class="fas fa-check-circle"></i>
                            </div>
                            <div class="stat-card-label">
                                <span id="view_status">Active</span>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeViewDepartmentModal()">
                            <i class="fas fa-times"></i>
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: "top-end",
                icon: "success",
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    {{-- Keep JS as is for now --}}
    <script>
        // Modal Functions
        function openDepartmentModal() {
            const modal = document.getElementById('departmentModal');
            modal.classList.add('active');

            // Clear form
            document.getElementById('departmentForm').reset();

            // Clear any previous error messages
            const errorMessages = modal.querySelectorAll('.error-message');
            errorMessages.forEach(msg => msg.remove());

            // Focus on first input
            setTimeout(() => {
                document.getElementById('department_id').focus();
            }, 300);
        }

        function closeDepartmentModal() {
            const modal = document.getElementById('departmentModal');
            modal.classList.remove('active');
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                closeDepartmentModal();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('departmentModal').classList.contains('active')) {
                    closeDepartmentModal();
                }
            }
        });

        // Enhanced input interactions
        document.addEventListener('DOMContentLoaded', function() {
            const inputs = document.querySelectorAll('.form-input');

            inputs.forEach(input => {
                input.addEventListener('focus', function() {
                    const icon = this.nextElementSibling;
                    if (icon && icon.classList.contains('input-icon')) {
                        icon.style.color = 'var(--primary)';
                        icon.style.transform = 'translateY(-50%) scale(1.1)';
                    }
                });

                input.addEventListener('blur', function() {
                    const icon = this.nextElementSibling;
                    if (icon && icon.classList.contains('input-icon')) {
                        icon.style.color = 'var(--text-secondary)';
                        icon.style.transform = 'translateY(-50%) scale(1)';
                    }
                });
            });
        });

        // Delete Department Function
        function confirmDeleteDepartment(departmentId, departmentName) {
            Swal.fire({
                title: "Are you sure?",
                html: `
            <strong>${departmentName}</strong> (ID: ${departmentId}) <br><br>
            This action <b>cannot be undone</b> and will affect all employees related to this department.
        `,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#3085d6",
                confirmButtonText: "Yes, delete it",
                cancelButtonText: "Cancel"
            }).then((result) => {
                if (result.isConfirmed) {

                    // Create a form dynamically and submit DELETE request
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/departments/${departmentId}`;

                    // CSRF Token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]');
                    if (csrfToken) {
                        const csrfInput = document.createElement('input');
                        csrfInput.type = 'hidden';
                        csrfInput.name = '_token';
                        csrfInput.value = csrfToken.getAttribute('content');
                        form.appendChild(csrfInput);
                    }

                    // Method Spoofing for DELETE
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    form.appendChild(methodInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // View Department Details Function
        let currentViewDepartmentId = null;

        function viewDepartmentDetails(departmentId) {
            currentViewDepartmentId = departmentId;
            const modal = document.getElementById('viewDepartmentModal');

            // Show loading / modal
            modal.classList.add('active');

            fetch(`/departments/${departmentId}/show`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    // Ensure we surface JSON errors from server
                    if (!response.ok) return response.json().then(err => {
                        throw new Error(err.message || 'Network error');
                    });
                    return response.json();
                })
                .then(data => {
                    if (!data.success) throw new Error(data.message || 'Failed to fetch department data');

                    const dept = data.department || {};

                    // Basic fields
                    document.getElementById('view_department_name').textContent = dept.department_name || 'N/A';
                    document.getElementById('view_department_id').textContent =
                        `Department ID: ${dept.department_id ?? 'N/A'}`;
                    document.getElementById('view_location').textContent = dept.location || 'Not Specified';
                    document.getElementById('view_phone').textContent = dept.phone || 'Not Provided';
                    document.getElementById('view_email').textContent = dept.email || 'Not Provided';
                    document.getElementById('view_description').textContent = dept.description ||
                        'No description available';
                    document.getElementById('view_employees_count').textContent = dept.employees_count ?? 0;

                    // Budget formatting
                    const budget = (dept.budget !== undefined && dept.budget !== null) ?
                        `$${Number(dept.budget).toLocaleString()}` :
                        'Not Set';
                    document.getElementById('view_budget').textContent = budget;

                    // ----- Department Head: robust logic (checks several possible shapes) -----
                    const headElement = document.getElementById('view_department_head');
                    let headName = null;

                    // Common case: relation 'employee' included as object (preferred)
                    if (dept.employee && typeof dept.employee === 'object') {
                        headName = dept.employee.employee_name || dept.employee.name || dept.employee.full_name || null;
                    }

                    // Convenience fields sometimes added by backend
                    if (!headName) headName = dept.department_head_name || dept.employee_name || null;

                    // Some APIs may include the employee as employee_id object (less common)
                    if (!headName && dept.employee_id && typeof dept.employee_id === 'object') {
                        headName = dept.employee_id.employee_name || dept.employee_id.name || null;
                    }

                    // If still nothing, fallback to Not Assigned
                    headElement.textContent = headName || 'Not Assigned';

                    // ----- Status card (unchanged logic) -----
                    const statusCard = document.getElementById('view_status_card');
                    const statusIcon = document.getElementById('view_status_icon');
                    const statusText = document.getElementById('view_status');

                    if (dept.status === 'Active') {
                        statusCard.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
                        statusCard.style.boxShadow = '0 8px 32px rgba(5, 150, 105, 0.3)';
                        statusIcon.className = 'fas fa-check-circle';
                        statusText.textContent = 'Active';
                    } else {
                        statusCard.style.background = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                        statusCard.style.boxShadow = '0 8px 32px rgba(107, 114, 128, 0.3)';
                        statusIcon.className = 'fas fa-pause-circle';
                        statusText.textContent = dept.status || 'Inactive';
                    }

                    // ----- Department icon based on name -----
                    const iconElement = document.getElementById('view_department_icon').querySelector('i');
                    const deptLower = (dept.department_name || String(dept.department_id || '')).toLowerCase();

                    if (deptLower.includes('development') || deptLower.includes('it') || deptLower.includes('tech')) {
                        iconElement.className = 'fas fa-code';
                    } else if (deptLower.includes('design') || deptLower.includes('creative')) {
                        iconElement.className = 'fas fa-paint-brush';
                    } else if (deptLower.includes('marketing') || deptLower.includes('sales')) {
                        iconElement.className = 'fas fa-bullhorn';
                    } else if (deptLower.includes('hr') || deptLower.includes('human')) {
                        iconElement.className = 'fas fa-users';
                    } else if (deptLower.includes('finance') || deptLower.includes('accounting')) {
                        iconElement.className = 'fas fa-calculator';
                    } else if (deptLower.includes('support') || deptLower.includes('help')) {
                        iconElement.className = 'fas fa-headset';
                    } else {
                        iconElement.className = 'fas fa-building';
                    }
                })
                .catch(error => {
                    console.error('Error loading department data:', error);
                    alert('Error loading department data: ' + error.message);
                    // closeViewDepartmentModal() -- keep your close function if you have one
                    if (typeof closeViewDepartmentModal === 'function') closeViewDepartmentModal();
                });
        }

        function closeViewDepartmentModal() {
            const modal = document.getElementById('viewDepartmentModal');
            modal.classList.remove('active');
            currentViewDepartmentId = null;
        }

        function openEditDepartmentModal(departmentId) {
            console.log(departmentId)
            if (!departmentId) {
                alert('Department ID missing!');
                return;
            }

            const modal = document.getElementById('editDepartmentModal');
            modal.classList.add('active');

            const form = document.getElementById('editDepartmentForm');
            form.reset();

            fetch(`/departments/${departmentId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        const department = data.department;

                        form.action = `/departments/${departmentId}`;

                        document.getElementById('edit_department_name').value = department.department_name || '';
                        document.getElementById('edit_description').value = department.description || '';
                        document.getElementById('employee_id').value = department.employee_id || '';
                        document.getElementById('edit_location').value = department.location || '';
                        document.getElementById('edit_phone').value = department.phone || '';
                        document.getElementById('edit_email').value = department.email || '';
                        document.getElementById('edit_budget').value = department.budget || '';
                        document.getElementById('edit_status').value = department.status || 'Active';


                        // Set selected Department Head
                        const headSelect = document.getElementById('edit_employee_id');
                        if (headSelect && department.employee_id) {
                            headSelect.value = department.employee_id;
                        }

                        const input = document.getElementById('edit_department_name');
                        if (input) input.focus();

                    } else {
                        throw new Error(data.message || 'Failed to fetch department data');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading department data: ' + error.message);
                    closeEditDepartmentModal();
                });
        }

        function closeEditDepartmentModal() {
            const modal = document.getElementById('editDepartmentModal');
            modal.classList.remove('active');

        }


        // Close edit modal when clicking outside
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('modal-overlay')) {
                if (e.target.id === 'editDepartmentModal') {
                    closeEditDepartmentModal();
                } else if (e.target.id === 'viewDepartmentModal') {
                    closeViewDepartmentModal();
                }
            }
        });

        // Close edit modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                if (document.getElementById('editDepartmentModal').classList.contains('active')) {
                    closeEditDepartmentModal();
                } else if (document.getElementById('viewDepartmentModal').classList.contains('active')) {
                    closeViewDepartmentModal();
                }
            }
        });

        // Search Functionality
        let departments = @json($departments);
        let filteredDepartments = departments;

        function renderDepartments(departmentsToRender) {
            const grid = document.getElementById('departmentsGrid');

            if (departmentsToRender.length === 0) {
                grid.innerHTML = `
    <div class="departments-empty">
        <i class="fas fa-building empty-icon"></i>
        <h3 class="empty-title">No Departments Found</h3>
        <p class="empty-desc">Try adjusting your search terms or clear the search to see all departments.</p>
    </div>
    `;
                return;
            }

            let html = '';

            departmentsToRender.forEach(department => {
                // Dynamic icon based on department name/id
                let icon = 'fas fa-building';
                let iconColor = 'var(--primary)';

                const deptLower = (department.department_name || department.department_id).toLowerCase();
                if (deptLower.includes('development') || deptLower.includes('it') || deptLower.includes('tech')) {
                    icon = 'fas fa-code';
                    iconColor = 'var(--primary)';
                } else if (deptLower.includes('design') || deptLower.includes('creative')) {
                    icon = 'fas fa-paint-brush';
                    iconColor = 'var(--secondary)';
                } else if (deptLower.includes('marketing') || deptLower.includes('sales')) {
                    icon = 'fas fa-bullhorn';
                    iconColor = 'var(--warning)';
                } else if (deptLower.includes('hr') || deptLower.includes('human')) {
                    icon = 'fas fa-users';
                    iconColor = 'var(--info)';
                } else if (deptLower.includes('finance') || deptLower.includes('accounting')) {
                    icon = 'fas fa-calculator';
                    iconColor = 'var(--success)';
                } else if (deptLower.includes('support') || deptLower.includes('help')) {
                    icon = 'fas fa-headset';
                    iconColor = 'var(--info)';
                }

                const statusClass = department.status === 'Active' ? 'success' : 'text-secondary';
                const statusBg = department.status === 'Active' ? 'rgba(16, 185, 129, 0.1)' :
                    'rgba(107, 114, 128, 0.1)';

                html += `
    <div class="card">
        <div class="card-header">
            <h3 class="department-title">
                <i class="${icon} department-icon" style="color: ${iconColor};"></i>
                ${department.department_name || department.department_id}
            </h3>
            <div class="flex gap-1">
                <button class="btn btn-warning edit-btn" title="Edit Department"
                    onclick="openEditDepartmentModal('${department.department_id}')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger delete-btn" title="Delete Department"
                    onclick="confirmDeleteDepartment('${department.department_id}', '${department.department_name || department.department_id}')">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
        <div class="card-body">`;

                if (department.employee && department.employee.employee_name) {
                    html += `
            <div class="mb-1 department-head">
                <div class="label">Department Head</div>
                <div class="value">${department.employee.employee_name}</div>
            </div>`;
                } else {
                    html += `
            <div class="mb-1 department-head">
                <div class="label">Department Head</div>
                <div class="value not-assigned">Not Assigned</div>
            </div>`;
                }

                html += `
            <div class="mb-1 total-employees">
                <div class="label">Total Employees</div>
                <div class="value" style="color: ${iconColor};">${department.employees_count || 0}</div>
            </div>`;


                if (department.description) {
                    html += `
            <div class="mb-1 department-description">
                <div class="label">Description</div>
                <div class="value">${department.description.length > 100 ? department.description.substring(0, 100) +
                    '...' : department.description}</div>
            </div>`;
                }

                if (department.location) {
                    html += `
            <div class="mb-1 department-location">
                <div class="label">Location</div>
                <div class="value location-value">
                    <i class="fas fa-map-marker-alt location-icon"></i>
                    ${department.location}
                </div>
            </div>`;
                }

                html += `
            <div class="flex justify-between items-center">
                <span class="badge department-status" data-status="${department.status}"
                    style="background: ${statusBg}; color: var(--${statusClass}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                    ${department.status || 'Active'}
                </span>
                <a href="#" class="btn btn-secondary view-details-btn"
                    onclick="viewDepartmentDetails('${department.department_id}')">
                    <i class="fas fa-eye"></i>
                    View Details
                </a>
            </div>
        </div>
    </div>`;
            });

            grid.innerHTML = html;
        }

        document.getElementById('departmentSearch').addEventListener('keyup', function() {
            let searchValue = this.value.toLowerCase();
            let cards = document.querySelectorAll("#departmentsGrid .card");

            cards.forEach(card => {
                let departmentName = card.querySelector(".department-title")?.textContent.toLowerCase() ||
                    "";
                let departmentHead = card.querySelector(".department-head .value")?.textContent
                    .toLowerCase() || "";
                let departmentLocation = card.querySelector(".department-location .value")?.textContent
                    .toLowerCase() || "";
                let departmentDescription = card.querySelector(".department-description .value")
                    ?.textContent.toLowerCase() || "";
                let departmentStatus = card.querySelector(".department-status")?.textContent
                    .toLowerCase() || "";

                if (
                    departmentName.includes(searchValue) ||
                    departmentHead.includes(searchValue) ||
                    departmentLocation.includes(searchValue) ||
                    departmentDescription.includes(searchValue) ||
                    departmentStatus.includes(searchValue)
                ) {
                    card.style.display = "";
                } else {
                    card.style.display = "none";
                }
            });
        });

        function updateStatistics() {
            // Update statistics based on filtered departments
            const totalDepartments = filteredDepartments.length;
            const totalEmployees = filteredDepartments.reduce((sum, dept) => sum + (dept.employees_count || 0), 0);
            const averageEmployees = totalDepartments > 0 ? (totalEmployees / totalDepartments).toFixed(1) : 0;
            const largestDepartment = filteredDepartments.length > 0 ? Math.max(...filteredDepartments.map(dept => dept
                .employees_count || 0)) : 0;

            // Update statistics cards
            const statsCards = document.querySelectorAll('.card-body.text-center');
            if (statsCards.length >= 4) {
                statsCards[0].querySelector('div[style*="font-size: 2rem"]').textContent = totalDepartments;
                statsCards[1].querySelector('div[style*="font-size: 2rem"]').textContent = totalEmployees;
                statsCards[2].querySelector('div[style*="font-size: 2rem"]').textContent = averageEmployees;
                statsCards[3].querySelector('div[style*="font-size: 2rem"]').textContent = largestDepartment;
            }
        }

        function clearSearch() {
            const input = document.getElementById('departmentSearch');
            input.value = ''; // Clear input field
            // Trigger full list display
            let cards = document.querySelectorAll("#departmentsGrid .card");
            cards.forEach(card => {
                card.style.display = "";
            });
        }

        // Initialize search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');

            // Real-time search as user types
            searchInput.addEventListener('input', function() {
                searchDepartments(this.value);
            });

            // Initial render
            renderDepartments(departments);
        });
    </script>

@endsection
