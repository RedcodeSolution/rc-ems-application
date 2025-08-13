@extends('layouts.admin')

<style>
/* Modern Teams Management Styles */
.card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 6px 32px 0 rgba(37,99,235,0.10);
}
.card-header {
    border-bottom: 1px solid #f1f1f1;
    background: linear-gradient(90deg, #f8fafc 60%, #e0e7ef 100%);
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
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
    border: none;
    outline: none;
}
.btn:active {
    transform: scale(0.97);
}
.btn-primary {
    background: linear-gradient(90deg, #2563eb 60%, #1d4ed8 100%);
    color: #fff;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
}
.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}
.btn-secondary:hover {
    background: #e5e7eb;
}
.btn-warning {
    background: #fbbf24;
    color: #fff;
}
.btn-warning:hover {
    background: #f59e42;
}
.btn-info {
    background: #0ea5e9;
    color: #fff;
}
.btn-info:hover {
    background: #0369a1;
}
.badge {
    font-weight: 600;
    letter-spacing: 0.02em;
    display: inline-block;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.03);
}
.form-input, .form-select {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    background: #f9fafb;
    transition: border 0.2s, box-shadow 0.2s;
}
.form-input:focus, .form-select:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px #2563eb22;
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
.card-header h3, .card-header h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-body h3, .card-body h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
}
.card-body p {
    color: #64748b;
    margin-bottom: 1rem;
    line-height: 1.7;
}
.card .text-center > div:first-child {
    letter-spacing: 0.01em;
}
.card .text-center > div:last-child {
    font-size: 0.95rem;
}
input[type="text"].form-input::placeholder {
    color: #a0aec0;
    opacity: 1;
}
.card-body .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
}
.card-body .flex[style*="display: flex; -webkit-box-orient: horizontal"] > div {
    box-shadow: 0 1px 4px 0 rgba(37,99,235,0.04);
}
.card-body .flex[style*="display: flex; -webkit-box-orient: horizontal"] > div:hover {
    box-shadow: 0 2px 8px 0 rgba(37,99,235,0.08);
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .card-header h3, .card-header h2 { font-size: 1rem; }
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

/* RedCode Solutions Color Palette */
:root {
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
    min-height: 48px;
    display: flex;
    align-items: center;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
    font-family: inherit;
    padding: 16px 16px 16px 48px;
    align-items: flex-start;
    line-height: 1.5;
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
    width: 16px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-group:has(.form-textarea) .input-icon {
    top: 24px;
    transform: translateY(0);
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

/* Custom Scrollbar for Modal */
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

/* View Modal Styles */
.view-field {
    width: 100%;
    padding: 12px 16px 12px 48px; /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
    border: 2px solid var(--border-light);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    background: rgba(248, 250, 252, 0.8);
    color: var(--text-primary);
    font-weight: 500;
    box-sizing: border-box;
    min-height: 48px;
    display: flex;
    align-items: center;
    position: relative;
    cursor: default;
    transition: all 0.3s ease;
}

.view-field:hover {
    background: rgba(220, 38, 38, 0.02);
    border-color: rgba(220, 38, 38, 0.1);
}

.view-textarea {
    min-height: 100px;
    align-items: flex-start;
    padding-top: 16px;
    padding-bottom: 16px;
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.5;
}

/* Special positioning for view modal textarea icons */
.form-group:has(.view-textarea) .input-icon {
    top: 24px; /* Position icon in the top area of textarea instead of center */
    transform: translateY(0); /* Remove center transform for textarea */
}

.view-field:empty::before {
    content: 'No data available';
    color: var(--text-light);
    font-style: italic;
}

/* Status badge styling in view modal */
.view-field.status-badge {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--redcode-green);
    font-weight: 600;
}

.view-field.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--redcode-green);
}

.view-field.status-badge.inactive {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: var(--redcode-primary);
}

.view-field.status-badge.on-hold {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
    color: var(--redcode-orange);
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

@section('title', 'Teams Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users-cog"></i> Teams</h2>
        <div class="flex gap-2">
            <button onclick="openTeamModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Team
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
            <input type="text" placeholder="Search teams..." class="form-input" style="width: 300px;">
            <button class="btn btn-secondary">
                <i class="fas fa-search"></i>
                Search
            </button>
        </div>

        <!-- Teams Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-code" style="color: var(--primary);"></i>
                        Development Team
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewTeamModal('Development Team', 'DEV01', 'Engineering', 'John Smith', 8, 50000, 'Active', 'Normal', 'Head Office', 'On-site', 'Full-stack development team focused on building scalable web applications', 'Deliver high-quality software solutions on time', 'JavaScript, React, Node.js, Python, Docker')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditTeamModal('Development Team', 'DEV01', 'Engineering', 'John Smith', 8, 50000, 'Active', 'Normal', 'Head Office', 'On-site', 'Full-stack development team focused on building scalable web applications', 'Deliver high-quality software solutions on time', 'JavaScript, React, Node.js, Python, Docker')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem; background: #dc3545; color: white;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Lead</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">JS</div>
                            <div style="font-weight: 600;">John Smith</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Members</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: reverse; flex-direction: row-reverse; justify-content: flex-end;">
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--success), var(--info)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">AB</div>
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--warning), var(--danger)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">CD</div>
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--secondary), var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">EF</div>
                                <div style="width: 2rem; height: 2rem; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">+5</div>
                            </div>
                        </div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">8 Members</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Active Projects</div>
                        <div style="font-size: 1.25rem; font-weight: 700, color: var(--success);">3 Projects</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Department</div>
                        <div style="font-weight: 600;">Engineering</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        <a href="{{ route('teams.assignEmployeesForm', 1) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                            <i class="fas fa-users"></i>
                            Manage Members
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-paint-brush" style="color: var(--secondary);"></i>
                        Design Team
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewTeamModal('Design Team', 'DES01', 'Creative', 'Sarah Wilson', 5, 35000, 'Active', 'Normal', 'Head Office', 'Hybrid', 'Creative design team focused on user experience and visual design', 'Create engaging and intuitive user interfaces', 'Adobe Creative Suite, Figma, Sketch, UI/UX Design')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditTeamModal('Design Team', 'DES01', 'Creative', 'Sarah Wilson', 5, 35000, 'Active', 'Normal', 'Head Office', 'Hybrid', 'Creative design team focused on user experience and visual design', 'Create engaging and intuitive user interfaces', 'Adobe Creative Suite, Figma, Sketch, UI/UX Design')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem; background: #dc3545; color: white;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Lead</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--secondary), var(--primary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">SW</div>
                            <div style="font-weight: 600;">Sarah Wilson</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Members</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: reverse; flex-direction: row-reverse; justify-content: flex-end;">
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--info), var(--success)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">GH</div>
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--danger), var(--warning)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">IJ</div>
                                <div style="width: 2rem; height: 2rem; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">+3</div>
                            </div>
                        </div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--secondary);">5 Members</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Active Projects</div>
                        <div style="font-size: 1.25rem; font-weight: 700, color: var(--success);">2 Projects</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Department</div>
                        <div style="font-weight: 600;">Creative</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        <a href="{{ route('teams.assignEmployeesForm', 2) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                            <i class="fas fa-users"></i>
                            Manage Members
                        </a>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                        <i class="fas fa-bullhorn" style="color: var(--warning);"></i>
                        Marketing Team
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewTeamModal('Marketing Team', 'MKT01', 'Marketing', 'Mike Johnson', 6, 40000, 'Active', 'High', 'Branch Office A', 'Flexible', 'Digital marketing team focused on brand growth and customer acquisition', 'Increase brand awareness and drive customer engagement', 'Digital Marketing, SEO, Social Media, Analytics')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditTeamModal('Marketing Team', 'MKT01', 'Marketing', 'Mike Johnson', 6, 40000, 'Active', 'High', 'Branch Office A', 'Flexible', 'Digital marketing team focused on brand growth and customer acquisition', 'Increase brand awareness and drive customer engagement', 'Digital Marketing, SEO, Social Media, Analytics')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem; background: #dc3545; color: white;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Lead</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                            <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--warning), var(--danger)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">MJ</div>
                            <div style="font-weight: 600;">Mike Johnson</div>
                        </div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Members</div>
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <div style="display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: reverse; flex-direction: row-reverse; justify-content: flex-end;">
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--primary), var(--info)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">KL</div>
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--success), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">MN</div>
                                <div style="width: 2rem; height: 2rem; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">+4</div>
                            </div>
                        </div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--warning);">6 Members</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Active Projects</div>
                        <div style="font-size: 1.25rem; font-weight: 700, color: var(--success);">4 Projects</div>
                    </div>
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Department</div>
                        <div style="font-weight: 600;">Marketing</div>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        <a href="{{ route('teams.assignEmployeesForm', 3) }}" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;">
                            <i class="fas fa-users"></i>
                            Manage Members
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Team Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">8</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Teams</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">67</div>
            <div style="color: var(--gray-600); font-weight: 500;">Team Members</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">8.4</div>
            <div style="color: var(--gray-600); font-weight: 500;">Avg Team Size</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">15</div>
            <div style="color: var(--gray-600); font-weight: 500;">Active Projects</div>
        </div>
    </div>
</div>

<!-- Team Creation Modal -->
<div id="teamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-users-cog"></i>
                Create New Team
            </div>
            <div class="modal-subtitle">Build your team with the right people and structure</div>
            <button class="modal-close" onclick="closeTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="{{ route('teams.store') }}" method="POST" id="teamForm">
                @csrf
                <div class="form-container">
                    <!-- Team Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_name" class="form-label">
                                <i class="fas fa-users"></i>Team Name
                            </label>
                            <div style="position: relative;">
                                
                                <input type="text" id="team_name" name="team_name" class="form-input" placeholder="Enter team name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Department and Team Lead Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                
                                <select id="department" name="department" class="form-select" required>
                                    <option value="">Select Department</option>
                                    <option value="Engineering">Engineering</option>
                                    <option value="Creative">Creative</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Human Resources">Human Resources</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Quality Assurance">Quality Assurance</option>
                                    <option value="Customer Support">Customer Support</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="team_lead" class="form-label">
                                <i class="fas fa-user-tie"></i>Team Lead
                            </label>
                            <div style="position: relative;">
                                
                                <select id="team_lead" name="team_lead" class="form-select">
                                    <option value="">Select Team Lead</option>
                                    <option value="1">John Smith - Senior Developer</option>
                                    <option value="2">Sarah Wilson - UI/UX Designer</option>
                                    <option value="3">Mike Johnson - Marketing Manager</option>
                                    <option value="4">Emily Davis - HR Specialist</option>
                                    <option value="5">David Brown - Finance Manager</option>
                                    <option value="6">Lisa Anderson - Operations Lead</option>
                                    <option value="7">Robert Miller - IT Manager</option>
                                    <option value="8">Jennifer Taylor - QA Lead</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Team Size and Budget Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="max_size" class="form-label">
                                <i class="fas fa-users-cog"></i>Maximum Team Size
                            </label>
                            <div style="position: relative;">
                                
                                <input type="number" id="max_size" name="max_size" class="form-input" placeholder="Enter maximum team size" min="1" max="50" value="10">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Monthly Budget
                            </label>
                            <div style="position: relative;">
                              
                                <input type="number" id="budget" name="budget" class="form-input" placeholder="Enter monthly budget" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Team Status
                            </label>
                            <div style="position: relative;">
                                
                                <select id="status" name="status" class="form-select" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Disbanded">Disbanded</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="priority" class="form-label">
                                <i class="fas fa-star"></i>Team Priority
                            </label>
                            <div style="position: relative;">
                                
                                <select id="priority" name="priority" class="form-select">
                                    <option value="Normal" selected>Normal</option>
                                    <option value="High">High</option>
                                    <option value="Critical">Critical</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Team Location and Work Mode Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="location" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Team Location
                            </label>
                            <div style="position: relative;">
                               
                                <select id="location" name="location" class="form-select">
                                    <option value="">Select Location</option>
                                    <option value="Head Office">Head Office</option>
                                    <option value="Branch Office A">Branch Office A</option>
                                    <option value="Branch Office B">Branch Office B</option>
                                    <option value="Remote">Remote</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Client Site">Client Site</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="work_mode" class="form-label">
                                <i class="fas fa-laptop-house"></i>Work Mode
                            </label>
                            <div style="position: relative;">
                                
                                <select id="work_mode" name="work_mode" class="form-select">
                                    <option value="On-site" selected>On-site</option>
                                    <option value="Remote">Remote</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Flexible">Flexible</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Team Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-file-alt"></i>Team Description
                        </label>
                        <div style="position: relative;">
                            
                            <textarea id="description" name="description" class="form-textarea" placeholder="Describe the team's purpose, goals, and responsibilities" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Team Goals and Objectives -->
                    <div class="form-group">
                        <label for="goals" class="form-label">
                            <i class="fas fa-target"></i>Team Goals & Objectives
                        </label>
                        <div style="position: relative;">
                            
                            <textarea id="goals" name="goals" class="form-textarea" placeholder="Define the team's key goals and objectives" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Skills Required -->
                    <div class="form-group">
                        <label for="skills_required" class="form-label">
                            <i class="fas fa-cogs"></i>Skills Required
                        </label>
                        <div style="position: relative;">
                            
                            <textarea id="skills_required" name="skills_required" class="form-textarea" placeholder="List the key skills and technologies required for this team" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeTeamModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Team
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team Edit Modal -->
<div id="editTeamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Team
            </div>
            <div class="modal-subtitle">Update team information and settings</div>
            <button class="modal-close" onclick="closeEditTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="{{ route('teams.update', ['team' => 1]) }}" method="POST" id="editTeamForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_team_id" name="team_id">
                
                <div class="form-container">
                    <!-- Team Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_team_name" class="form-label">
                                <i class="fas fa-users"></i>Team Name
                            </label>
                            <input type="text" id="edit_team_name" name="team_name" class="form-input" placeholder="Enter team name" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_team_code" class="form-label">
                                <i class="fas fa-hashtag"></i>Team Code
                            </label>
                            <input type="text" id="edit_team_code" name="team_code" class="form-input" placeholder="Enter team code (e.g., DEV01)" required>
                        </div>
                    </div>

                    <!-- Department and Team Lead Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <select id="edit_department" name="department" class="form-select" required>
                                <option value="">Select Department</option>
                                <option value="Engineering">Engineering</option>
                                <option value="Creative">Creative</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Sales">Sales</option>
                                <option value="Human Resources">Human Resources</option>
                                <option value="Finance">Finance</option>
                                <option value="Operations">Operations</option>
                                <option value="Information Technology">Information Technology</option>
                                <option value="Quality Assurance">Quality Assurance</option>
                                <option value="Customer Support">Customer Support</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_team_lead" class="form-label">
                                <i class="fas fa-user-tie"></i>Team Lead
                            </label>
                            <select id="edit_team_lead" name="team_lead" class="form-select">
                                <option value="">Select Team Lead</option>
                                <option value="1">John Smith - Senior Developer</option>
                                <option value="2">Sarah Wilson - UI/UX Designer</option>
                                <option value="3">Mike Johnson - Marketing Manager</option>
                                <option value="4">Emily Davis - HR Specialist</option>
                                <option value="5">David Brown - Finance Manager</option>
                                <option value="6">Lisa Anderson - Operations Lead</option>
                                <option value="7">Robert Miller - IT Manager</option>
                                <option value="8">Jennifer Taylor - QA Lead</option>
                            </select>
                        </div>
                    </div>

                    <!-- Team Size and Budget Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_max_size" class="form-label">
                                <i class="fas fa-users-cog"></i>Maximum Team Size
                            </label>
                            <input type="number" id="edit_max_size" name="max_size" class="form-input" placeholder="Enter maximum team size" min="1" max="50">
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Monthly Budget
                            </label>
                            <input type="number" id="edit_budget" name="budget" class="form-input" placeholder="Enter monthly budget" min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Team Status
                            </label>
                            <select id="edit_status" name="status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Disbanded">Disbanded</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_priority" class="form-label">
                                <i class="fas fa-star"></i>Team Priority
                            </label>
                            <select id="edit_priority" name="priority" class="form-select">
                                <option value="Low">Low</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <!-- Team Location and Work Mode Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_location" class="form-label">
                                <i class="fas fa-map-marker-alt"></i>Team Location
                            </label>
                            <select id="edit_location" name="location" class="form-select">
                                <option value="">Select Location</option>
                                <option value="Head Office">Head Office</option>
                                <option value="Branch Office A">Branch Office A</option>
                                <option value="Branch Office B">Branch Office B</option>
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Client Site">Client Site</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_work_mode" class="form-label">
                                <i class="fas fa-laptop-house"></i>Work Mode
                            </label>
                            <select id="edit_work_mode" name="work_mode" class="form-select">
                                <option value="On-site">On-site</option>
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Flexible">Flexible</option>
                            </select>
                        </div>
                    </div>

                    <!-- Team Description -->
                    <div class="form-group">
                        <label for="edit_description" class="form-label">
                            <i class="fas fa-file-alt"></i>Team Description
                        </label>
                        <textarea id="edit_description" name="description" class="form-textarea" placeholder="Describe the team's purpose, goals, and responsibilities" rows="4"></textarea>
                    </div>

                    <!-- Team Goals and Objectives -->
                    <div class="form-group">
                        <label for="edit_goals" class="form-label">
                            <i class="fas fa-target"></i>Team Goals & Objectives
                        </label>
                        <textarea id="edit_goals" name="goals" class="form-textarea" placeholder="Define the team's key goals and objectives" rows="3"></textarea>
                    </div>

                    <!-- Skills Required -->
                    <div class="form-group">
                        <label for="edit_skills_required" class="form-label">
                            <i class="fas fa-cogs"></i>Skills Required
                        </label>
                        <textarea id="edit_skills_required" name="skills_required" class="form-textarea" placeholder="List the key skills and technologies required for this team" rows="3"></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditTeamModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Team
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team View Modal -->
<div id="viewTeamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-eye"></i>
                Team Details
            </div>
            <div class="modal-subtitle">View complete team information</div>
            <button class="modal-close" onclick="closeViewTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="form-container">
                <!-- Team Basic Information Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i>Team Name
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users"></i>
                            <div class="view-field" id="view_team_name"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-hashtag"></i>Team Code
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-hashtag"></i>
                            <div class="view-field" id="view_team_code"></div>
                        </div>
                    </div>
                </div>

                <!-- Department and Team Lead Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i>Department
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-building"></i>
                            <div class="view-field" id="view_department"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tie"></i>Team Lead
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tie"></i>
                            <div class="view-field" id="view_team_lead"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Size and Budget Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users-cog"></i>Maximum Team Size
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users-cog"></i>
                            <div class="view-field" id="view_max_size"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign"></i>Monthly Budget
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-dollar-sign"></i>
                            <div class="view-field" id="view_budget"></div>
                        </div>
                    </div>
                </div>

                <!-- Status and Priority Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on"></i>Team Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-toggle-on"></i>
                            <div class="view-field" id="view_status"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-star"></i>Team Priority
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-star"></i>
                            <div class="view-field" id="view_priority"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Location and Work Mode Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-map-marker-alt"></i>Team Location
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-map-marker-alt"></i>
                            <div class="view-field" id="view_location"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-laptop-house"></i>Work Mode
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-laptop-house"></i>
                            <div class="view-field" id="view_work_mode"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Description -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i>Team Description
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-file-alt"></i>
                        <div class="view-field view-textarea" id="view_description"></div>
                    </div>
                </div>

                <!-- Team Goals and Objectives -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-target"></i>Team Goals & Objectives
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-target"></i>
                        <div class="view-field view-textarea" id="view_goals"></div>
                    </div>
                </div>

                <!-- Skills Required -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-cogs"></i>Skills Required
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-cogs"></i>
                        <div class="view-field view-textarea" id="view_skills_required"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewTeamModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openEditTeamModalFromView()">
                        <i class="fas fa-edit"></i> Edit Team
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Team Modal Functions
function openTeamModal() {
    document.getElementById('teamModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Enhanced input interactions
    setupInputEnhancements();
}

function closeTeamModal() {
    document.getElementById('teamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('teamForm').reset();
    
    // Clear any validation styles
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
}

// Edit Team Modal Functions
function openEditTeamModal(teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills) {
    document.getElementById('editTeamModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Populate form fields with existing data
    document.getElementById('edit_team_name').value = teamName;
    document.getElementById('edit_team_code').value = teamCode;
    document.getElementById('edit_department').value = department;
    document.getElementById('edit_team_lead').value = teamLead;
    document.getElementById('edit_max_size').value = maxSize;
    document.getElementById('edit_budget').value = budget;
    document.getElementById('edit_status').value = status;
    document.getElementById('edit_priority').value = priority;
    document.getElementById('edit_location').value = location;
    document.getElementById('edit_work_mode').value = workMode;
    document.getElementById('edit_description').value = description;
    document.getElementById('edit_goals').value = goals;
    document.getElementById('edit_skills_required').value = skills;
    
    // Enhanced input interactions
    setupInputEnhancements();
}

function closeEditTeamModal() {
    document.getElementById('editTeamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('editTeamForm').reset();
    
    // Clear any validation styles
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
}

// View Team Modal Functions
function openViewTeamModal(teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills) {
    // Debug log to verify data is being passed
    console.log('Opening view team modal with data:', {
        teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills
    });
    
    // Populate view fields with the team data
    document.getElementById('view_team_name').textContent = teamName || 'N/A';
    document.getElementById('view_team_code').textContent = teamCode || 'N/A';
    document.getElementById('view_department').textContent = department || 'N/A';
    document.getElementById('view_team_lead').textContent = teamLead || 'N/A';
    document.getElementById('view_max_size').textContent = maxSize || 'N/A';
    
    // Format budget with currency symbol
    document.getElementById('view_budget').textContent = budget ? `$${parseInt(budget).toLocaleString()}` : 'N/A';
    
    // Handle status with special styling
    const statusField = document.getElementById('view_status');
    statusField.textContent = status || 'N/A';
    statusField.className = 'view-field status-badge';
    if (status) {
        if (status.toLowerCase() === 'active') {
            statusField.classList.add('active');
        } else if (status.toLowerCase() === 'inactive') {
            statusField.classList.add('inactive');
        } else if (status.toLowerCase().includes('hold')) {
            statusField.classList.add('on-hold');
        }
    }
    
    document.getElementById('view_priority').textContent = priority || 'N/A';
    document.getElementById('view_location').textContent = location || 'N/A';
    document.getElementById('view_work_mode').textContent = workMode || 'N/A';
    document.getElementById('view_description').textContent = description || 'N/A';
    document.getElementById('view_goals').textContent = goals || 'N/A';
    document.getElementById('view_skills_required').textContent = skills || 'N/A';
    
    // Store data for potential edit modal opening
    window.currentTeamData = {
        teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills
    };
    
    // Show the view modal
    document.getElementById('viewTeamModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeViewTeamModal() {
    document.getElementById('viewTeamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openEditTeamModalFromView() {
    // Close view modal first
    closeViewTeamModal();
    
    // Open edit modal with stored data
    const data = window.currentTeamData;
    if (data) {
        setTimeout(() => {
            openEditTeamModal(data.teamName, data.teamCode, data.department, data.teamLead, 
                         data.maxSize, data.budget, data.status, data.priority, data.location, data.workMode, data.description, data.goals, data.skills);
        }, 300); // Small delay to allow view modal to close
    }
}

function setupInputEnhancements() {
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
}

// Form validation for team creation
document.getElementById('teamForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = ['team_name', 'team_code', 'department', 'status'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            field.style.borderColor = 'var(--redcode-primary)';
            field.style.background = 'rgba(220, 38, 38, 0.05)';
            isValid = false;
        } else if (field) {
            field.style.borderColor = 'var(--redcode-green)';
            field.style.background = 'rgba(5, 150, 105, 0.05)';
        }
    });
    
    if (!isValid) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Team code validation (should be unique)
    const teamCode = document.getElementById('team_code').value.trim();
    if (teamCode.length < 3) {
        alert('Team code must be at least 3 characters long');
        return;
    }
    
    // Max size validation
    const maxSize = document.getElementById('max_size').value;
    if (maxSize && (maxSize < 1 || maxSize > 50)) {
        alert('Maximum team size must be between 1 and 50');
        return;
    }
    
    // If validation passes, submit the form
    alert('Team created successfully!');
    closeTeamModal();
    
    // Here you would typically send the data to your server
    // this.submit(); // Uncomment this to actually submit the form
});

// Form validation for team editing
document.getElementById('editTeamForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = ['edit_team_name', 'edit_team_code', 'edit_department', 'edit_status'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            field.style.borderColor = 'var(--redcode-primary)';
            field.style.background = 'rgba(220, 38, 38, 0.05)';
            isValid = false;
        } else if (field) {
            field.style.borderColor = 'var(--redcode-green)';
            field.style.background = 'rgba(5, 150, 105, 0.05)';
        }
    });
    
    if (!isValid) {
        alert('Please fill in all required fields');
        return;
    }
    
    // Team code validation (should be unique)
    const teamCode = document.getElementById('edit_team_code').value.trim();
    if (teamCode.length < 3) {
        alert('Team code must be at least 3 characters long');
        return;
    }
    
    // Max size validation
    const maxSize = document.getElementById('edit_max_size').value;
    if (maxSize && (maxSize < 1 || maxSize > 50)) {
        alert('Maximum team size must be between 1 and 50');
        return;
    }
    
    // If validation passes, submit the form
    alert('Team updated successfully!');
    closeEditTeamModal();
    
    // Here you would typically send the data to your server
    // this.submit(); // Uncomment this to actually submit the form
});

// Auto-generate team code based on team name
document.getElementById('team_name').addEventListener('input', function() {
    const teamName = this.value.trim();
    const teamCodeField = document.getElementById('team_code');
    
    if (teamName && !teamCodeField.value) {
        // Generate team code from team name
        const code = teamName
            .split(' ')
            .map(word => word.charAt(0).toUpperCase())
            .join('')
            .substring(0, 3) + '01';
        teamCodeField.value = code;
    }
});

// Close modal when clicking outside
document.getElementById('teamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTeamModal();
    }
});

document.getElementById('editTeamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditTeamModal();
    }
});

document.getElementById('viewTeamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewTeamModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const teamModal = document.getElementById('teamModal');
        const editTeamModal = document.getElementById('editTeamModal');
        const viewTeamModal = document.getElementById('viewTeamModal');
        
        if (teamModal.classList.contains('active')) {
            closeTeamModal();
        }
        if (editTeamModal.classList.contains('active')) {
            closeEditTeamModal();
        }
        if (viewTeamModal.classList.contains('active')) {
            closeViewTeamModal();
        }
    }
});
</script>
@endsection
