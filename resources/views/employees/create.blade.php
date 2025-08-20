@extends('layouts.admin')

@section('title', 'Add New Employee')

@section('content')
<style>
:root {
    /* RedCode Solutions Color Palette - Matching Register Page */
    --redcode-primary: #DC2626; /* RedCode Brand Red */
    --redcode-primary-dark: #991B1B; /* Deep Red */
    --redcode-primary-light: #FEE2E2; /* Light Red Background */
    --redcode-accent: #B91C1C; /* Accent Red */
    --redcode-dark: #1F2937; /* Charcoal for headers/nav */
    --redcode-gray: #6B7280; /* Medium Gray for text */
    --redcode-light: #F9FAFB; /* Light Background */
    --redcode-white: #FFFFFF; /* Pure White */
    --redcode-blue: #2563EB; /* Links, buttons */
    --redcode-green: #059669; /* Success states */
    --redcode-orange: #D97706; /* Warnings */
    --redcode-yellow: #F59E0B; /* Alerts */
    --text-primary: #111827; /* Almost Black */
    --text-secondary: #6B7280; /* Medium Gray */
    --text-light: #9CA3AF; /* Light Gray */
    --text-white: #FFFFFF; /* White Text */
    --border-light: #E5E7EB;
    --border-medium: #D1D5DB;
    --border-dark: #6B7280;
    --gradient-primary: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
    --gradient-hero: linear-gradient(135deg, #DC2626 0%, #1F2937 50%, #991B1B 100%);
    --gradient-glass: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
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
    border-radius: 2rem;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow:
        0 32px 64px rgba(220,38,38,0.15),
        0 0 0 1px rgba(255,255,255,0.05),
        inset 0 1px 0 rgba(255,255,255,0.1);
    border: 1px solid var(--border-light);
    position: relative;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 2rem 2rem 0 2rem;
    border-bottom: 1px solid var(--border-light);
    margin-bottom: 2rem;
    position: relative;
}

.modal-title {
    font-size: 2rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-subtitle {
    color: var(--text-secondary);
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
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
    color: var(--redcode-primary);
}

.modal-close:hover {
    background: rgba(220, 38, 38, 0.2);
    transform: scale(1.1);
}

.modal-body {
    padding: 0 2rem 2rem 2rem;
}

/* Enhanced Form Styles */
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
    margin-right: 0.5rem;
    color: var(--redcode-primary);
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
    box-sizing: border-box;
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

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    transition: all 0.3s;
    z-index: 3;
    pointer-events: none;
    font-size: 1rem;
}

.form-group:has(.form-input:focus) .input-icon,
.form-group:has(.form-select:focus) .input-icon {
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

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.btn {
    padding: 12px 24px;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    position: relative;
    overflow: hidden;
    letter-spacing: 0.025em;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
    box-shadow:
        0 8px 25px rgba(220,38,38,0.18),
        0 3px 10px rgba(153,27,27,0.12);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow:
        0 15px 35px rgba(220,38,38,0.22),
        0 5px 15px rgba(153,27,27,0.18);
}

.btn-secondary {
    background: var(--border-light);
    color: var(--text-secondary);
    border: 1px solid var(--border-medium);
}

.btn-secondary:hover {
    background: var(--border-medium);
    transform: translateY(-2px);
}

/* Custom Scrollbar */
.modal-container::-webkit-scrollbar {
    width: 8px;
}

.modal-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb {
    background: rgba(220,38,38,0.2);
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb:hover {
    background: rgba(220,38,38,0.4);
}

/* Error Messages */
.error-message {
    background: rgba(217, 119, 6, 0.1);
    border: 1px solid rgba(217, 119, 6, 0.2);
    color: var(--redcode-orange);
    padding: 12px 16px;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* Profile Photo Upload Styles */
.profile-photo-upload {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
}

.photo-preview {
    width: 120px;
    height: 120px;
    border: 3px dashed var(--border-light);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    overflow: hidden;
    position: relative;
    background: rgba(248, 250, 252, 0.8);
}

.photo-preview:hover {
    border-color: var(--redcode-primary);
    background: rgba(220, 38, 38, 0.05);
    transform: scale(1.02);
}

.photo-placeholder {
    display: flex;
    flex-direction: column;
    align-items: center;
    color: var(--text-light);
    font-size: 0.875rem;
}

.photo-placeholder i {
    font-size: 2.5rem;
    margin-bottom: 0.5rem;
    color: var(--redcode-primary);
}

.photo-preview img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.photo-input {
    position: absolute;
    opacity: 0;
    width: 1px;
    height: 1px;
    cursor: pointer;
}

.photo-upload-info {
    text-align: center;
    max-width: 250px;
}

.photo-upload-info small {
    color: var(--text-light);
    font-size: 0.75rem;
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 1rem;
        border-radius: 1.5rem;
    }
    
    .form-row {
        grid-template-columns: 1fr;
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
}
</style>

<!-- Page Header -->
<div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 20px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 8px 32px rgba(220,38,38,0.08); border: 1px solid var(--border-light);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">
                <i class="fas fa-user-plus"></i> Add New Employee
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 500;">Create a new employee profile in the system</p>
        </div>
        <button onclick="openModal()" class="btn btn-primary" style="padding: 14px 28px; font-size: 1rem;">
            <i class="fas fa-plus"></i> Open Employee Form
        </button>
    </div>
</div>

<!-- Employee Creation Modal -->
<div id="employeeModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-user-plus"></i>
                Add New Employee
            </div>
            <div class="modal-subtitle">Fill in the employee details below</div>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="{{ route('employees.store') }}" method="POST" id="employeeForm" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <!-- Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_id" class="form-label">
                                <i class="fas fa-id-badge"></i>Employee ID
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-id-badge"></i>
                                <input type="text" id="employee_id" name="employee_id" class="form-input" placeholder="Enter employee ID" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="employee_name" class="form-label">
                                <i class="fas fa-user"></i>Full Name
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="employee_name" name="employee_name" class="form-input" placeholder="Enter full name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone"></i>Contact Number
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" id="contact_no" name="contact_no" class="form-input" placeholder="Enter contact number" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="employee_type" class="form-label">
                                <i class="fas fa-briefcase"></i>Employee Type
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-briefcase"></i>
                                <select id="employee_type" name="employee_type" class="form-select" required>
                                    <option value="">Select employee type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Intern">Intern</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Department and Role Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department_id" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-building"></i>
                                <select id="department_id" name="department_id" class="form-select" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name ?? $department->department_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i>Role/Position
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-tag"></i>
                                <input type="text" id="role" name="role" class="form-input" placeholder="Enter role or position" required>
                            </div>
                        </div>
                    </div>

                    <!-- Teams Selection -->
                    <!-- <div class="form-group">
                        <label for="team_ids" class="form-label">
                            <i class="fas fa-users"></i>Teams (Hold Ctrl/Cmd to select multiple)
                        </label>
                        <div style="position: relative;">
                            <select id="team_ids" name="team_ids[]" class="form-select" multiple>
                                @foreach($teams as $team)
                                <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div> -->

                    <!-- Status and Admin Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Employee Status
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-toggle-on"></i>
                                <select id="employee_status" name="employee_status" class="form-select" required>
                                    <option value="">Select status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Leave">On Leave</option>
                                    <option value="Terminated">Terminated</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="admin_id" class="form-label">
                                <i class="fas fa-user-shield"></i>Reporting Manager
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-shield"></i>
                                <select id="admin_id" name="admin_id" class="form-select">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->admin_id }}">{{ $admin->admin_name ?? $admin->admin_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Status -->
                    <div class="form-group">
                        <label for="paid_status" class="form-label">
                            <i class="fas fa-credit-card"></i>Payment Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-credit-card"></i>
                            <select id="paid_status" name="paid_status" class="form-select" required>
                                <option value="">Select payment status</option>
                                <option value="Paid">Paid</option>
                                <option value="Pending">Pending</option>
                                <option value="Overdue">Overdue</option>
                                <option value="Not Applicable">Not Applicable</option>
                            </select>
                        </div>
                    </div>

                    <!-- Profile Photo -->
                    <div class="form-group">
                        <label for="profile_photo" class="form-label">
                            <i class="fas fa-camera"></i>Profile Photo
                        </label>
                        <div class="profile-photo-upload">
                            <div class="photo-preview" id="photoPreview">
                                <div class="photo-placeholder">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Choose Photo</span>
                                </div>
                            </div>
                            <input type="file" id="profile_photo" name="profile_photo" class="photo-input" accept="image/*">
                            <div class="photo-upload-info">
                                <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('employeeModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('employeeModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('employeeModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeModal();
    }
});

// Form validation and enhancements
document.getElementById('employeeForm').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            isValid = false;
            field.style.borderColor = 'var(--redcode-primary)';
            field.style.background = 'rgba(220, 38, 38, 0.05)';
        } else {
            field.style.borderColor = 'var(--redcode-green)';
            field.style.background = 'rgba(5, 150, 105, 0.05)';
        }
    });
    
    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});

// Phone number formatting
document.getElementById('contact_no').addEventListener('input', function() {
    let value = this.value.replace(/\D/g, '');
    if (value.length > 15) {
        value = value.substring(0, 15);
    }
    this.value = value;
});

// Enhanced input interactions
document.querySelectorAll('.form-input, .form-select').forEach(input => {
    input.addEventListener('focus', function() {
        const icon = this.previousElementSibling;
        if (icon && icon.classList.contains('input-icon')) {
            icon.style.color = 'var(--redcode-primary)';
            icon.style.transform = 'translateY(-50%) scale(1.1)';
        }
    });

    input.addEventListener('blur', function() {
        const icon = this.previousElementSibling;
        if (icon && icon.classList.contains('input-icon')) {
            icon.style.color = 'var(--text-light)';
            icon.style.transform = 'translateY(-50%) scale(1)';
        }
    });
});

// Profile Photo Preview
document.getElementById('photoPreview').addEventListener('click', function() {
    document.getElementById('profile_photo').click();
});

document.getElementById('profile_photo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('photoPreview');
    
    if (file) {
        // Validate file size (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('File size must be less than 2MB');
            this.value = '';
            return;
        }
        
        // Validate file type
        if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
            alert('Please select a valid image file (JPG, PNG, GIF)');
            this.value = '';
            return;
        }
        
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = `
            <div class="photo-placeholder">
                <i class="fas fa-user-circle"></i>
                <span>Choose Photo</span>
            </div>
        `;
    }
});

// Auto-open modal if there are validation errors
@if ($errors->any())
    window.addEventListener('load', function() {
        openModal();
    });
@endif
</script>

@if ($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            openModal();
            
            // Show errors in modal
            const errorContainer = document.createElement('div');
            errorContainer.innerHTML = `
                <div class="error-message" style="margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                    <strong>Please correct the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1rem; list-style: disc;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `;
            
            const form = document.getElementById('employeeForm');
            form.insertBefore(errorContainer, form.firstChild);
        });
    </script>
@endif
@endsection
