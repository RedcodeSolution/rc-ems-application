@extends('layouts.admin')

@section('title', 'Projects Management')

@section('content')
<style>
:root {
    /* RedCode Solutions Color Palette */
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
    max-width: 900px;
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

.form-input, .form-select, .form-textarea {
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

.form-textarea {
    min-height: 100px;
    resize: vertical;
    font-family: inherit;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
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
.form-group:has(.form-select:focus) .input-icon,
.form-group:has(.form-textarea:focus) .input-icon {
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
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
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

.btn-info {
    background: var(--redcode-blue);
    color: white;
}

.btn-warning {
    background: var(--redcode-yellow);
    color: white;
}

.btn-danger {
    background: var(--redcode-primary);
    color: white;
}

.btn-sm {
    padding: 8px 16px;
    font-size: 0.8rem;
}

/* Table Styles */
.table-container {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    border-radius: 20px;
    padding: 2rem;
    box-shadow: 0 8px 32px rgba(220,38,38,0.08);
    border: 1px solid var(--border-light);
    overflow-x: auto;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1rem;
}

.table th,
.table td {
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid var(--border-light);
}

.table th {
    background: var(--redcode-light);
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.table tbody tr:hover {
    background: rgba(220, 38, 38, 0.02);
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
    
    .table-container {
        padding: 1rem;
        overflow-x: auto;
    }
}
</style>

<!-- Page Header -->
<div style="background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-radius: 20px; padding: 2rem; margin-bottom: 2rem; box-shadow: 0 8px 32px rgba(220,38,38,0.08); border: 1px solid var(--border-light);">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1 style="font-size: 2.5rem; font-weight: 800; background: var(--gradient-primary); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; margin-bottom: 0.5rem;">
                <i class="fas fa-project-diagram"></i> Projects Management
            </h1>
            <p style="color: var(--text-secondary); font-size: 1.1rem; font-weight: 500;">Manage all projects in the system</p>
        </div>
        <button onclick="openModal()" class="btn btn-primary" style="padding: 14px 28px; font-size: 1rem;">
            <i class="fas fa-plus"></i> Create New Project
        </button>
    </div>
</div>

<!-- Projects Table -->
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th><i class="fas fa-hashtag"></i> Project ID</th>
                <th><i class="fas fa-project-diagram"></i> Project Name</th>
                <th><i class="fas fa-users"></i> Team</th>
                <th><i class="fas fa-user-tie"></i> Client</th>
                <th><i class="fas fa-tasks"></i> Status</th>
                <th><i class="fas fa-calendar"></i> Dates</th>
                <th><i class="fas fa-cogs"></i> Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($projects as $project)
            <tr>
                <td><strong>{{ $project->project_id }}</strong></td>
                <td>{{ $project->project_name }}</td>
                <td>
                    @if($project->team)
                        {{ $project->team->team_name }}
                    @else
                        <span style="color: var(--text-light);">No team assigned</span>
                    @endif
                </td>
                <td>{{ $project->client ?? 'N/A' }}</td>
                <td>
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600; 
                        @if($project->status == 'Completed') background: var(--redcode-green); color: white;
                        @elseif($project->status == 'In Progress') background: var(--redcode-blue); color: white;
                        @elseif($project->status == 'Planning') background: var(--redcode-yellow); color: white;
                        @else background: var(--redcode-gray); color: white; @endif">
                        {{ $project->status ?? 'Not Set' }}
                    </span>
                </td>
                <td>
                    @if($project->start_date)
                        <small>Start: {{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}</small><br>
                    @endif
                    @if($project->end_date)
                        <small>End: {{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}</small>
                    @endif
                </td>
                <td>
                    <div style="display: flex; gap: 0.5rem;">
                        <a href="{{ route('projects.show', $project->project_id) }}" class="btn btn-info btn-sm">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('projects.edit', $project->project_id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('projects.destroy', $project->project_id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this project?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" style="text-align: center; color: var(--text-light); padding: 2rem;">
                    <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 1rem; display: block;"></i>
                    No projects found. Create your first project!
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Project Creation Modal -->
<div id="projectModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-project-diagram"></i>
                Add New Project
            </div>
            <div class="modal-subtitle">Fill in the project details below</div>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="{{ route('projects.store') }}" method="POST" id="projectForm">
                @csrf
                <div class="form-container">
                    <!-- Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="project_id" class="form-label">
                                <i class="fas fa-hashtag"></i>Project ID
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-hashtag"></i>
                                <input type="text" id="project_id" name="project_id" class="form-input" placeholder="Enter project ID" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="project_name" class="form-label">
                                <i class="fas fa-project-diagram"></i>Project Name
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-project-diagram"></i>
                                <input type="text" id="project_name" name="project_name" class="form-input" placeholder="Enter project name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Client and Team Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="client" class="form-label">
                                <i class="fas fa-user-tie"></i>Client
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-tie"></i>
                                <input type="text" id="client" name="client" class="form-input" placeholder="Enter client name">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="team_id" class="form-label">
                                <i class="fas fa-users"></i>Assigned Team
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-users"></i>
                                <select id="team_id" name="team_id" class="form-select" required>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Status Row -->
                    <div class="form-group">
                        <label for="status" class="form-label">
                            <i class="fas fa-tasks"></i>Project Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-tasks"></i>
                            <select id="status" name="status" class="form-select" required>
                                <option value="">Select status</option>
                                <option value="Planning">Planning</option>
                                <option value="In Progress">In Progress</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Testing">Testing</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-plus"></i>Start Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-plus"></i>
                                <input type="date" id="start_date" name="start_date" class="form-input">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-check"></i>End Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-check"></i>
                                <input type="date" id="end_date" name="end_date" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-file-alt"></i>Project Description
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-file-alt"></i>
                            <textarea id="description" name="description" class="form-textarea" placeholder="Enter project description"></textarea>
                        </div>
                    </div>

                    <!-- Milestone Info -->
                    <div class="form-group">
                        <label for="milestone_info" class="form-label">
                            <i class="fas fa-flag"></i>Milestone Information
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-flag"></i>
                            <textarea id="milestone_info" name="milestone_info" class="form-textarea" placeholder="Enter milestone details"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Project
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openModal() {
    document.getElementById('projectModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('projectModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
document.getElementById('projectModal').addEventListener('click', function(e) {
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
document.getElementById('projectForm').addEventListener('submit', function(e) {
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

// Date validation - ensure end date is after start date
document.getElementById('end_date').addEventListener('change', function() {
    const startDate = document.getElementById('start_date').value;
    const endDate = this.value;
    
    if (startDate && endDate && endDate < startDate) {
        alert('End date cannot be before start date.');
        this.value = '';
    }
});

// Enhanced input interactions
document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
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
            
            const form = document.getElementById('projectForm');
            form.insertBefore(errorContainer, form.firstChild);
        });
    </script>
@endif
@endsection

