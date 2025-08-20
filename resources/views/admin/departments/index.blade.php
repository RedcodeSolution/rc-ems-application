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
/* Modern Departments Styles */
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
.card-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-body > div[style*="display: grid"] > .card {
    border: 1px solid var(--divider);
    box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
    transition: box-shadow 0.2s;
}
.card-body > div[style*="display: grid"] > .card:hover {
    box-shadow: 0 4px 24px 0 rgba(37,99,235,0.08);
}
.card-body div[style*="margin-bottom: 1rem;"] > div:first-child {
    color: var(--text-secondary);
}
.card-body div[style*="margin-bottom: 1rem;"] > div:last-child {
    color: var(--text-primary);
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .card-header h3 { font-size: 1rem; }
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

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 1.5rem;
    width: 90%;
    max-width: 700px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow:
        0 32px 64px rgba(211,47,47,0.15),
        0 0 0 1px rgba(255,255,255,0.05),
        inset 0 1px 0 rgba(255,255,255,0.1);
    border: 1px solid var(--divider);
    position: relative;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 2rem 2rem 0 2rem;
    border-bottom: 1px solid var(--divider);
    margin-bottom: 2rem;
    position: relative;
}

.modal-title {
    font-size: 1.75rem;
    font-weight: 800;
    background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin: 0 0 0.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-subtitle {
    color: var(--text-secondary);
    font-size: 1rem;
    margin: 0;
    font-weight: 400;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: none;
    border: none;
    font-size: 1.5rem;
    color: var(--text-secondary);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 0.5rem;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    width: 2.5rem;
    height: 2.5rem;
}

.modal-close:hover {
    background: rgba(211,47,47,0.1);
    color: var(--primary);
    transform: scale(1.1);
}

.modal-body {
    padding: 0 2rem 2rem 2rem;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    text-transform: uppercase;
    letter-spacing: 0.025em;
}

.form-label i {
    color: var(--primary);
    font-size: 1rem;
}

.form-input {
    padding: 1rem 1rem 1rem 3rem;
    border: 2px solid var(--divider);
    border-radius: 0.75rem;
    font-size: 1rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(10px);
    position: relative;
    width: 100%;
    box-sizing: border-box;
}

.form-input:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 4px rgba(211,47,47,0.1);
    background: rgba(255, 255, 255, 0.95);
    transform: translateY(-1px);
}

/* Textarea specific styles */
.form-input[rows] {
    padding-top: 1rem;
    line-height: 1.5;
}

/* Select specific styles */
.form-input option {
    padding: 0.5rem;
    background: white;
    color: var(--text-primary);
}

.input-icon {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    font-size: 1rem;
    transition: all 0.3s ease;
    pointer-events: none;
    z-index: 2;
}

.form-group {
    position: relative;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid var(--divider);
}

.error-message {
    background: rgba(230, 74, 25, 0.1);
    border: 1px solid rgba(230, 74, 25, 0.2);
    color: var(--danger);
    padding: 12px 16px;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.success-message {
    background: rgba(67, 160, 71, 0.1);
    border: 1px solid rgba(67, 160, 71, 0.2);
    color: var(--success);
    padding: 12px 16px;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 1rem;
        border-radius: 1.25rem;
    }
    
    .modal-header, .modal-body {
        padding: 1.5rem;
    }
    
    .modal-title {
        font-size: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
    }
    
    /* Make grid layouts single column on mobile */
    div[style*="grid-template-columns: 1fr 1fr"] {
        display: grid !important;
        grid-template-columns: 1fr !important;
        gap: 1.5rem !important;
    }
}
</style>

@section('title', 'Departments Management')

@section('content')
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
        <!-- Search Section -->
        <div class="flex justify-between items-center mb-4">
            <input type="text" id="searchInput" placeholder="Search departments..." class="form-input" style="width: 300px;">
            <button class="btn btn-secondary" onclick="clearSearch()">
                <i class="fas fa-times"></i>
                Clear
            </button>
        </div>

        <!-- Departments Grid -->
        <div id="departmentsGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem;">
            @forelse($departments as $department)
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">
                        @php
                            // Dynamic icon based on department name/id
                            $icon = 'fas fa-building';
                            $iconColor = 'var(--primary)';
                            
                            $deptLower = strtolower($department->department_name ?? $department->department_id);
                            if (str_contains($deptLower, 'development') || str_contains($deptLower, 'it') || str_contains($deptLower, 'tech')) {
                                $icon = 'fas fa-code';
                                $iconColor = 'var(--primary)';
                            } elseif (str_contains($deptLower, 'design') || str_contains($deptLower, 'creative')) {
                                $icon = 'fas fa-paint-brush';
                                $iconColor = 'var(--secondary)';
                            } elseif (str_contains($deptLower, 'marketing') || str_contains($deptLower, 'sales')) {
                                $icon = 'fas fa-bullhorn';
                                $iconColor = 'var(--warning)';
                            } elseif (str_contains($deptLower, 'hr') || str_contains($deptLower, 'human')) {
                                $icon = 'fas fa-users';
                                $iconColor = 'var(--info)';
                            } elseif (str_contains($deptLower, 'finance') || str_contains($deptLower, 'accounting')) {
                                $icon = 'fas fa-calculator';
                                $iconColor = 'var(--success)';
                            } elseif (str_contains($deptLower, 'support') || str_contains($deptLower, 'help')) {
                                $icon = 'fas fa-headset';
                                $iconColor = 'var(--info)';
                            }
                        @endphp
                        <i class="{{ $icon }}" style="color: {{ $iconColor }};"></i>
                        {{ $department->department_name ?? $department->department_id }}
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-warning" style="padding: 0.5rem;" title="Edit Department" onclick="openEditDepartmentModal('{{ $department->department_id }}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem;" title="Delete Department" onclick="confirmDeleteDepartment('{{ $department->department_id }}', '{{ $department->department_name ?? $department->department_id }}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    @if($department->department_head)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Department Head</div>
                        <div style="font-weight: 600;">{{ $department->department_head }}</div>
                    </div>
                    @endif
                    
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Total Employees</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: {{ $iconColor }};">{{ $department->employees_count ?? 0 }}</div>
                    </div>
                    
                    @if($department->description)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Description</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">{{ Str::limit($department->description, 100) }}</div>
                    </div>
                    @endif
                    
                    @if($department->location)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Location</div>
                        <div style="font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="color: var(--text-secondary);"></i>
                            {{ $department->location }}
                        </div>
                    </div>
                    @endif
                    
                    <div class="flex justify-between items-center">
                        @php
                            $statusClass = $department->status === 'Active' ? 'success' : 'text-secondary';
                            $statusBg = $department->status === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)';
                        @endphp
                        <span class="badge" style="background: {{ $statusBg }}; color: var(--{{ $statusClass }}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                            {{ $department->status ?? 'Active' }}
                        </span>
                        <a href="#" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;" onclick="viewDepartmentDetails('{{ $department->department_id }}')">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <i class="fas fa-building" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">No Departments Found</h3>
                <p style="margin: 0; font-size: 1rem;">Start by creating your first department using the "Add Department" button above.</p>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Department Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">{{ $departments->count() }}</div>
            <div style="color: var(--text-secondary); font-weight: 500;">Total Departments</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">{{ $departments->sum('employees_count') }}</div>
            <div style="color: var(--text-secondary); font-weight: 500;">Total Employees</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            @php
                $totalEmployees = $departments->sum('employees_count');
                $totalDepartments = $departments->count();
                $average = $totalDepartments > 0 ? round($totalEmployees / $totalDepartments, 1) : 0;
            @endphp
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">{{ $average }}</div>
            <div style="color: var(--text-secondary); font-weight: 500;">Avg per Department</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">{{ $departments->max('employees_count') ?? 0 }}</div>
            <div style="color: var(--text-secondary); font-weight: 500;">Largest Department</div>
        </div>
    </div>
</div>

<!-- Add Department Modal -->
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
            <form id="departmentForm" action="{{ route('departments.store') }}" method="POST">
                @csrf
                <div class="form-container">
                    <!-- Basic Information Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="department_id" class="form-label">
                                <i class="fas fa-id-badge"></i>Department ID
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="department_id" name="department_id" class="form-input" placeholder="Enter unique department ID" required>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Use a unique identifier like "IT", "HR", "SALES", etc.
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="department_name" class="form-label">
                                <i class="fas fa-building"></i>Department Name
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="department_name" name="department_name" class="form-input" placeholder="Enter department name" required>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Full name of the department
                            </small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-align-left"></i>Description
                        </label>
                        <div style="position: relative;">
                            <textarea id="description" name="description" class="form-input" placeholder="Describe the department's role and responsibilities" rows="3" style="resize: vertical; min-height: 80px;"></textarea>
                        </div>
                        <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                            Brief description of the department's purpose
                        </small>
                    </div>

                    <!-- Department Head and Location Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="department_head" class="form-label">
                                <i class="fas fa-user-tie"></i>Department Head
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="department_head" name="department_head" class="form-input" placeholder="Name of department head">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Manager or head of this department
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Location
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="location" name="location" class="form-input" placeholder="Office location or floor">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Physical location of the department
                            </small>
                        </div>
                    </div>

                    <!-- Contact Information Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="phone" class="form-label">
                                <i class="fas fa-phone"></i>Phone Number
                            </label>
                            <div style="position: relative;">
                                <input type="tel" id="phone" name="phone" class="form-input" placeholder="Department phone number">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Contact number for the department
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>Email Address
                            </label>
                            <div style="position: relative;">
                                <input type="email" id="email" name="email" class="form-input" placeholder="department@company.com">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Department email address
                            </small>
                        </div>
                    </div>

                    <!-- Budget and Status Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Annual Budget
                            </label>
                            <div style="position: relative;">
                                <input type="number" id="budget" name="budget" class="form-input" placeholder="0.00" step="0.01" min="0">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Annual budget allocation (optional)
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Department Status
                            </label>
                            <div style="position: relative;">
                                <select id="status" name="status" class="form-input" required style="background-image: url('data:image/svg+xml;utf8,<svg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 4 5\"><path fill=\"%23666\" d=\"m2 0-2 2h4zm0 5 2-2h-4z\"/></svg>'); background-repeat: no-repeat; background-position: right 1rem center; background-size: 12px; padding-right: 3rem;">
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
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

<!-- Edit Department Modal -->
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
                    <!-- Basic Information Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="edit_department_id" class="form-label">
                                <i class="fas fa-id-badge"></i>Department ID
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_department_id" name="department_id" class="form-input" readonly style="background-color: #f8f9fa; cursor: not-allowed;">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Department ID cannot be changed
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_department_name" class="form-label">
                                <i class="fas fa-building"></i>Department Name
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_department_name" name="department_name" class="form-input" placeholder="Enter department name" required>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Full name of the department
                            </small>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="edit_description" class="form-label">
                            <i class="fas fa-align-left"></i>Description
                        </label>
                        <div style="position: relative;">
                            <textarea id="edit_description" name="description" class="form-input" placeholder="Describe the department's role and responsibilities" rows="3" style="resize: vertical; min-height: 80px;"></textarea>
                        </div>
                        <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                            Brief description of the department's purpose
                        </small>
                    </div>

                    <!-- Department Head and Location Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="edit_department_head" class="form-label">
                                <i class="fas fa-user-tie"></i>Department Head
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_department_head" name="department_head" class="form-input" placeholder="Enter department head name">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Name of the department head or manager
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_location" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Location
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_location" name="location" class="form-input" placeholder="Enter department location">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Physical location or office address
                            </small>
                        </div>
                    </div>

                    <!-- Contact Information Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="edit_phone" class="form-label">
                                <i class="fas fa-phone"></i>Phone Number
                            </label>
                            <div style="position: relative;">
                                <input type="tel" id="edit_phone" name="phone" class="form-input" placeholder="Enter phone number">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Department contact number
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope"></i>Email Address
                            </label>
                            <div style="position: relative;">
                                <input type="email" id="edit_email" name="email" class="form-input" placeholder="Enter email address">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Department email address
                            </small>
                        </div>
                    </div>

                    <!-- Budget and Status Row -->
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div class="form-group">
                            <label for="edit_budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Budget
                            </label>
                            <div style="position: relative;">
                                <input type="number" id="edit_budget" name="budget" class="form-input" placeholder="Enter budget amount" min="0" step="0.01">
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Annual budget allocation
                            </small>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Status
                            </label>
                            <div style="position: relative;">
                                <select id="edit_status" name="status" class="form-input" required>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                </select>
                            </div>
                            <small style="color: var(--text-secondary); font-size: 0.75rem; margin-top: 0.25rem;">
                                Current operational status
                            </small>
                        </div>
                    </div>

                    <!-- Form Actions -->
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

<!-- View Department Modal -->
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
                <!-- Department Header Info -->
                <div style="text-align: center; margin-bottom: 2rem; padding: 1.5rem; border-radius: 1rem; background: linear-gradient(135deg, var(--primary-light) 0%, rgba(255,255,255,0.8) 100%);">
                    <div id="view_department_icon" style="font-size: 3rem; margin-bottom: 1rem; color: var(--primary);">
                        <i class="fas fa-building"></i>
                    </div>
                    <h2 id="view_department_name" style="margin: 0 0 0.5rem 0; color: var(--text-primary); font-size: 1.75rem; font-weight: 700;"></h2>
                    <p id="view_department_id" style="margin: 0; color: var(--text-secondary); font-size: 1rem; font-weight: 500;"></p>
                </div>

                <!-- Department Information Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Department Head -->
                    <div class="info-card" style="background: rgba(255,255,255,0.8); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--divider);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <i class="fas fa-user-tie" style="color: var(--primary); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; color: var(--text-primary); font-size: 1rem; font-weight: 600;">Department Head</h4>
                        </div>
                        <p id="view_department_head" style="margin: 0; color: var(--text-secondary); font-size: 1.125rem; font-weight: 500;"></p>
                    </div>

                    <!-- Location -->
                    <div class="info-card" style="background: rgba(255,255,255,0.8); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--divider);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <i class="fas fa-map-marker-alt" style="color: var(--info); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; color: var(--text-primary); font-size: 1rem; font-weight: 600;">Location</h4>
                        </div>
                        <p id="view_location" style="margin: 0; color: var(--text-secondary); font-size: 1.125rem; font-weight: 500;"></p>
                    </div>

                    <!-- Phone -->
                    <div class="info-card" style="background: rgba(255,255,255,0.8); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--divider);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <i class="fas fa-phone" style="color: var(--success); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; color: var(--text-primary); font-size: 1rem; font-weight: 600;">Phone Number</h4>
                        </div>
                        <p id="view_phone" style="margin: 0; color: var(--text-secondary); font-size: 1.125rem; font-weight: 500;"></p>
                    </div>

                    <!-- Email -->
                    <div class="info-card" style="background: rgba(255,255,255,0.8); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--divider);">
                        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                            <i class="fas fa-envelope" style="color: var(--warning); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; color: var(--text-primary); font-size: 1rem; font-weight: 600;">Email Address</h4>
                        </div>
                        <p id="view_email" style="margin: 0; color: var(--text-secondary); font-size: 1.125rem; font-weight: 500;"></p>
                    </div>
                </div>

                <!-- Description -->
                <div class="info-card" style="background: rgba(255,255,255,0.8); padding: 1.5rem; border-radius: 1rem; border: 1px solid var(--divider); margin-bottom: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <i class="fas fa-align-left" style="color: var(--secondary); font-size: 1.25rem;"></i>
                        <h4 style="margin: 0; color: var(--text-primary); font-size: 1rem; font-weight: 600;">Description</h4>
                    </div>
                    <p id="view_description" style="margin: 0; color: var(--text-secondary); font-size: 1rem; line-height: 1.6;"></p>
                </div>

                <!-- Statistics Grid -->
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                    <!-- Total Employees -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%); padding: 1.5rem; border-radius: 1rem; text-align: center; color: white; box-shadow: 0 8px 32px rgba(220, 38, 38, 0.3);">
                        <div style="font-size: 2.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                            <span id="view_employees_count">0</span>
                        </div>
                        <div style="font-size: 0.875rem; font-weight: 500; opacity: 0.95;">Total Employees</div>
                    </div>

                    <!-- Budget -->
                    <div class="stat-card" style="background: linear-gradient(135deg, #1e40af 0%, #1d4ed8 100%); padding: 1.5rem; border-radius: 1rem; text-align: center; color: white; box-shadow: 0 8px 32px rgba(30, 64, 175, 0.3);">
                        <div style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;">
                            <span id="view_budget">$0</span>
                        </div>
                        <div style="font-size: 0.875rem; font-weight: 500; opacity: 0.95;">Annual Budget</div>
                    </div>

                    <!-- Status -->
                    <div class="stat-card" id="view_status_card" style="padding: 1.5rem; border-radius: 1rem; text-align: center; color: white; box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);">
                        <div style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                            <i id="view_status_icon" class="fas fa-check-circle"></i>
                        </div>
                        <div style="font-size: 0.875rem; font-weight: 500; opacity: 0.95;">
                            <span id="view_status">Active</span>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewDepartmentModal()">
                        <i class="fas fa-times"></i>
                        Close
                    </button>
                    <button type="button" class="btn btn-warning" onclick="closeViewDepartmentModal(); openEditDepartmentModal(currentViewDepartmentId);">
                        <i class="fas fa-edit"></i>
                        Edit Department
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

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

// Form submission handling
document.getElementById('departmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating...';
    submitButton.disabled = true;
    
    // Clear previous error messages
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message and reload page
            closeDepartmentModal();
            location.reload();
        } else {
            // Show error messages
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById(field);
                    if (input) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.errors[field][0];
                        input.parentNode.parentNode.appendChild(errorDiv);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show general error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> An error occurred. Please try again.';
        this.querySelector('.form-container').insertBefore(errorDiv, this.querySelector('.form-actions'));
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

// Delete Department Function
function confirmDeleteDepartment(departmentId, departmentName) {
    if (confirm(`Are you sure you want to delete the department "${departmentName}" (ID: ${departmentId})?\n\nThis action cannot be undone and will affect all employees in this department.`)) {
        // Create a form and submit it for deletion
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/departments/${departmentId}`;
        
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken.getAttribute('content');
            form.appendChild(csrfInput);
        }
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);
        
        document.body.appendChild(form);
        form.submit();
    }
}

// View Department Details Function
let currentViewDepartmentId = null;

function viewDepartmentDetails(departmentId) {
    currentViewDepartmentId = departmentId;
    const modal = document.getElementById('viewDepartmentModal');
    
    // Show loading state
    modal.classList.add('active');
    
    // Fetch department data
    fetch(`/departments/${departmentId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const department = data.department;
            
            // Set department basic info
            document.getElementById('view_department_name').textContent = department.department_name || 'N/A';
            document.getElementById('view_department_id').textContent = `Department ID: ${department.department_id}`;
            
            // Set department details
            document.getElementById('view_department_head').textContent = department.department_head || 'Not Assigned';
            document.getElementById('view_location').textContent = department.location || 'Not Specified';
            document.getElementById('view_phone').textContent = department.phone || 'Not Provided';
            document.getElementById('view_email').textContent = department.email || 'Not Provided';
            document.getElementById('view_description').textContent = department.description || 'No description available';
            
            // Set statistics
            document.getElementById('view_employees_count').textContent = department.employees_count || 0;
            
            // Format budget
            const budget = department.budget ? `$${parseFloat(department.budget).toLocaleString()}` : 'Not Set';
            document.getElementById('view_budget').textContent = budget;
            
            // Set status with appropriate styling
            const statusCard = document.getElementById('view_status_card');
            const statusIcon = document.getElementById('view_status_icon');
            const statusText = document.getElementById('view_status');
            
            if (department.status === 'Active') {
                statusCard.style.background = 'linear-gradient(135deg, #059669 0%, #047857 100%)';
                statusCard.style.boxShadow = '0 8px 32px rgba(5, 150, 105, 0.3)';
                statusIcon.className = 'fas fa-check-circle';
                statusText.textContent = 'Active';
            } else {
                statusCard.style.background = 'linear-gradient(135deg, #6b7280 0%, #4b5563 100%)';
                statusCard.style.boxShadow = '0 8px 32px rgba(107, 114, 128, 0.3)';
                statusIcon.className = 'fas fa-pause-circle';
                statusText.textContent = 'Inactive';
            }
            
            // Set dynamic icon based on department name
            const iconElement = document.getElementById('view_department_icon').querySelector('i');
            const deptLower = (department.department_name || department.department_id).toLowerCase();
            
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
        } else {
            throw new Error(data.message || 'Failed to fetch department data');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading department data: ' + error.message);
        closeViewDepartmentModal();
    });
}

function closeViewDepartmentModal() {
    const modal = document.getElementById('viewDepartmentModal');
    modal.classList.remove('active');
    currentViewDepartmentId = null;
}

// Edit Department Modal Functions
function openEditDepartmentModal(departmentId) {
    const modal = document.getElementById('editDepartmentModal');
    
    // Show loading state
    modal.classList.add('active');
    
    // Clear form and show loading
    const form = document.getElementById('editDepartmentForm');
    form.reset();
    
    // Clear any previous error messages
    const errorMessages = modal.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    // Fetch department data
    fetch(`/departments/${departmentId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const department = data.department;
            
            // Set form action
            form.action = `/departments/${departmentId}`;
            
            // Populate form fields
            document.getElementById('edit_department_id').value = department.department_id || '';
            document.getElementById('edit_department_name').value = department.department_name || '';
            document.getElementById('edit_description').value = department.description || '';
            document.getElementById('edit_department_head').value = department.department_head || '';
            document.getElementById('edit_location').value = department.location || '';
            document.getElementById('edit_phone').value = department.phone || '';
            document.getElementById('edit_email').value = department.email || '';
            document.getElementById('edit_budget').value = department.budget || '';
            document.getElementById('edit_status').value = department.status || 'Active';
            
            // Focus on first editable input
            setTimeout(() => {
                document.getElementById('edit_department_name').focus();
            }, 300);
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

// Handle Edit Department Form Submission
document.getElementById('editDepartmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitButton = this.querySelector('button[type="submit"]');
    const originalText = submitButton.innerHTML;
    
    // Show loading state
    submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Updating...';
    submitButton.disabled = true;
    
    // Clear any previous error messages
    const errorMessages = this.querySelectorAll('.error-message');
    errorMessages.forEach(msg => msg.remove());
    
    // Submit form
    fetch(this.action, {
        method: 'POST',
        body: new FormData(this),
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message
            const successDiv = document.createElement('div');
            successDiv.className = 'success-message';
            successDiv.innerHTML = '<i class="fas fa-check-circle"></i> ' + data.message;
            this.querySelector('.form-container').insertBefore(successDiv, this.querySelector('.form-actions'));
            
            // Close modal and reload page after a short delay
            setTimeout(() => {
                closeEditDepartmentModal();
                window.location.reload();
            }, 1500);
        } else {
            // Handle validation errors
            if (data.errors) {
                Object.keys(data.errors).forEach(field => {
                    const input = document.getElementById('edit_' + field);
                    if (input) {
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'error-message';
                        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> ' + data.errors[field][0];
                        input.parentNode.parentNode.appendChild(errorDiv);
                    }
                });
            }
        }
    })
    .catch(error => {
        console.error('Error:', error);
        // Show general error message
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.innerHTML = '<i class="fas fa-exclamation-triangle"></i> An error occurred. Please try again.';
        this.querySelector('.form-container').insertBefore(errorDiv, this.querySelector('.form-actions'));
    })
    .finally(() => {
        // Reset button state
        submitButton.innerHTML = originalText;
        submitButton.disabled = false;
    });
});

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
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">No Departments Found</h3>
                <p style="margin: 0; font-size: 1rem;">Try adjusting your search terms or clear the search to see all departments.</p>
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
        const statusBg = department.status === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)';
        
        html += `
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">
                        <i class="${icon}" style="color: ${iconColor};"></i>
                        ${department.department_name || department.department_id}
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-warning" style="padding: 0.5rem;" title="Edit Department" onclick="openEditDepartmentModal('${department.department_id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem;" title="Delete Department" onclick="confirmDeleteDepartment('${department.department_id}', '${department.department_name || department.department_id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">`;
        
        if (department.department_head) {
            html += `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Department Head</div>
                        <div style="font-weight: 600;">${department.department_head}</div>
                    </div>`;
        }
        
        html += `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Total Employees</div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: ${iconColor};">${department.employees_count || 0}</div>
                    </div>`;
        
        if (department.description) {
            html += `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Description</div>
                        <div style="font-size: 0.875rem; line-height: 1.5;">${department.description.length > 100 ? department.description.substring(0, 100) + '...' : department.description}</div>
                    </div>`;
        }
        
        if (department.location) {
            html += `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Location</div>
                        <div style="font-size: 0.875rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-map-marker-alt" style="color: var(--text-secondary);"></i>
                            ${department.location}
                        </div>
                    </div>`;
        }
        
        html += `
                    <div class="flex justify-between items-center">
                        <span class="badge" style="background: ${statusBg}; color: var(--${statusClass}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                            ${department.status || 'Active'}
                        </span>
                        <a href="#" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;" onclick="viewDepartmentDetails('${department.department_id}')">
                            <i class="fas fa-eye"></i>
                            View Details
                        </a>
                    </div>
                </div>
            </div>`;
    });
    
    grid.innerHTML = html;
}

function searchDepartments(searchTerm) {
    if (!searchTerm.trim()) {
        filteredDepartments = departments;
    } else {
        const term = searchTerm.toLowerCase();
        filteredDepartments = departments.filter(department => {
            return (
                (department.department_id && department.department_id.toLowerCase().includes(term)) ||
                (department.department_name && department.department_name.toLowerCase().includes(term)) ||
                (department.description && department.description.toLowerCase().includes(term)) ||
                (department.department_head && department.department_head.toLowerCase().includes(term)) ||
                (department.location && department.location.toLowerCase().includes(term)) ||
                (department.status && department.status.toLowerCase().includes(term))
            );
        });
    }
    
    renderDepartments(filteredDepartments);
    updateStatistics();
}

function updateStatistics() {
    // Update statistics based on filtered departments
    const totalDepartments = filteredDepartments.length;
    const totalEmployees = filteredDepartments.reduce((sum, dept) => sum + (dept.employees_count || 0), 0);
    const averageEmployees = totalDepartments > 0 ? (totalEmployees / totalDepartments).toFixed(1) : 0;
    const largestDepartment = filteredDepartments.length > 0 ? Math.max(...filteredDepartments.map(dept => dept.employees_count || 0)) : 0;
    
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
    document.getElementById('searchInput').value = '';
    searchDepartments('');
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
