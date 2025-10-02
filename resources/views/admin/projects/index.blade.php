
@extends('layouts.admin')



<style>
    :root {
        /* RedCode Solutions Color Palette - Matching Employee Form */
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
        --icon-gap: 28px;

    }
    }

    /* Modern Projects Management Styles */
    .card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
        border: none;
        background: #fff;
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
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
    }
    .btn-primary {
        background: linear-gradient(90deg, #2563eb 60%, #1d4ed8 100%);
        color: #fff;
        border: none;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
    }
    .btn-secondary {
        background: #f3f4f6;
        color: #374151;
        border: none;
    }
    .btn-secondary:hover {
        background: #e5e7eb;
    }
    .btn-warning {
        background: #fbbf24;
        color: #fff;
        border: none;
    }
    .btn-warning:hover {
        background: #f59e42;
    }
    .btn-danger {
        background: #ef4444;
        color: #fff;
        border: none;
    }
    .btn-danger:hover {
        background: #dc2626;
    }
    .btn-success {
        background: #10b981;
        color: #fff;
        border: none;
    }
    .btn-success:hover {
        background: #059669;
    }
    .btn-info {
        background: #0ea5e9;
        color: #fff;
        border: none;
    }
    .btn-info:hover {
        background: #0369a1;
    }
    .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
        display: inline-block;
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
    .card-body > div[style*="display: grid"] > .card {
        border: 1px solid #f3f4f6;
        box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
        transition: box-shadow 0.2s;
    }
    .card-body > div[style*="display: grid"] > .card:hover {
        box-shadow: 0 4px 24px 0 rgba(37,99,235,0.08);
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
    .card-header .flex.gap-1 > button,
    .card-header .flex.gap-1 > a {
        min-width: 2.25rem;
        min-height: 2.25rem;
    }
    .card-body .badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.75rem;
        border-radius: 1rem;
    }
    .card-body > div[style*="display: grid"] > .card .card-body > div[style*="background: var(--gray-200)"] {
        margin-bottom: 0.25rem;
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
        padding: 16px 16px 16px 48px; /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
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

    /* Enhanced styling for edit modal fields with data - matching view modal spacing */
    .form-input:not(:placeholder-shown),
    .form-select:not([value=""]),
    .form-textarea:not(:placeholder-shown) {
        background: rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--text-primary);
        font-weight: 600;
    }

    /* Edit modal populated field styling - exact match with view modal */
    #editProjectModal .form-input[data-populated="true"],
    #editProjectModal .form-select[data-populated="true"],
    #editProjectModal .form-textarea[data-populated="true"] {
        background: rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--text-primary);
        font-weight: 600;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.08);
        padding: 16px 16px 16px 48px; /* Maintain consistent spacing with view modal */


    }

    .form-textarea {
        min-height: 100px;
        resize: vertical;
        font-family: inherit;
        /* Textarea specific positioning: icon centered vertically at top area */
        padding: 16px 16px 16px 48px; /* Slightly more top padding for better icon alignment - matching view modal */
        align-items: flex-start;
        line-height: 1.5;
    }

    /* Special positioning for textarea icons - matching view modal */
    .form-group:has(.form-textarea) .input-icon {
        top: 24px; /* Position icon in the top area of textarea instead of center */
        transform: translateY(0); /* Remove center transform for textarea */
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
        left: 16px; /* Icon Position: 16px from left */
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-light);
        transition: all 0.3s;
        z-index: 3;
        pointer-events: none;
        font-size: 1rem;
        width: 16px; /* Icon Width: 16px */
        text-align: center;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Enhanced icon styling for edit modal populated fields */
    #editProjectModal .form-group:has(.form-input[data-populated="true"]) .input-icon,
    #editProjectModal .form-group:has(.form-select[data-populated="true"]) .input-icon,
    #editProjectModal .form-group:has(.form-textarea[data-populated="true"]) .input-icon {
        color: var(--redcode-green);
        transform: translateY(-50%) scale(1.05);
        text-shadow: 0 0 8px rgba(16, 185, 129, 0.3);
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
        color: #D97706;
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

    .view-field.status-badge.in-progress {
        background: rgba(59, 130, 246, 0.1);
        border-color: rgba(59, 130, 246, 0.3);
        color: var(--redcode-blue);
    }

    .view-field.status-badge.completed {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--redcode-green);
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

<link rel="stylesheet" href="{{ asset('css/admin/projects.css') }}">


@section('title', 'Projects Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-project-diagram"></i> Projects</h2>
        <div class="flex gap-2">
            <button onclick="openModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Project
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Search and Filter Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <input type="text" placeholder="Search projects..." class="form-input" style="width: 300px;">
                <select class="form-select" style="width: 200px;">
                    <option>All Status</option>
                    <option>Active</option>
                    <option>Completed</option>
                    <option>On Hold</option>
                    <option>Cancelled</option>
                </select>
            </div>
            <div class="flex gap-2">
                <button class="btn btn-secondary">
                    <i class="fas fa-filter"></i>
                    Filter
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-sort"></i>
                    Sort
                </button>
            </div>
        </div>

        <!-- Employee Project Assignment Search Section -->
        <div class="card" style="margin-bottom: 1.5rem; border: 1px solid var(--border-light); border-radius: 1rem; background: rgba(255, 255, 255, 0.9);">
            <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-users" style="color: var(--redcode-primary);"></i>
                    Employee Project Assignment Search
                </h3>
            </div>

            <div class="card-body" style="padding: 1.5rem;">
                <!-- Employee Search -->
                <div style="display: flex; gap: 1rem; align-items: end; margin-bottom: 1rem;">
                    <div style="flex: 1;">
                        <label for="employeeSearch" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-search" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Search Employee
                        </label>
                        <input type="text" id="employeeSearch" class="form-input" placeholder="Enter employee name..." style="width: 100%;" onkeyup="searchEmployeeProjects()">
                    </div>
                    <div>
                        <label for="departmentFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-building" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Department
                        </label>
                        <select id="departmentFilter" class="form-select" onchange="filterEmployeeProjects()">
                            <option value="">All Departments</option>
                            <option value="Engineering">Engineering</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Sales">Sales</option>
                            <option value="HR">HR</option>
                            <option value="Finance">Finance</option>
                        </select>
                    </div>
                    <div>
                        <button class="btn btn-primary" onclick="clearEmployeeSearch()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>

                <!-- Quick Search Buttons -->
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
                    <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="searchEmployeeByName('John')">
                        <i class="fas fa-user"></i> John Smith
                    </button>
                    <button class="btn btn-warning" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="searchEmployeeByName('Sarah')">
                        <i class="fas fa-user"></i> Sarah Wilson
                    </button>
                    <button class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="searchEmployeeByName('Mike')">
                        <i class="fas fa-user"></i> Mike Johnson
                    </button>
                    <button class="btn btn-info" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="searchEmployeeByName('Emily')">
                        <i class="fas fa-user"></i> Emily Davis
                    </button>
                </div>
            </div>
        </div>

        <!-- Employee Project Assignments Display Section -->
        <div id="employeeProjectAssignments" class="card" style="margin-bottom: 1.5rem; display: none; border: 1px solid var(--border-light); border-radius: 1rem; background: rgba(255, 255, 255, 0.9);">
            <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-briefcase" style="color: var(--redcode-primary);"></i>
                    <span id="selectedEmployeeName">Employee Project Assignments</span>
                </h3>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="hideEmployeeProjects()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="refreshEmployeeProjects()">
                        <i class="fas fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="card-body" style="padding: 1.5rem;">
                <!-- Project Assignment Filter Controls -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="projectStatusFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-filter" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Project Status
                        </label>
                        <select id="projectStatusFilter" class="form-select" onchange="filterEmployeeProjects()">
                            <option value="">All Statuses</option>
                            <option value="active">Active</option>
                            <option value="completed">Completed</option>
                            <option value="on-hold">On Hold</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <div>
                        <label for="projectRoleFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-user-tag" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Role in Project
                        </label>
                        <select id="projectRoleFilter" class="form-select" onchange="filterEmployeeProjects()">
                            <option value="">All Roles</option>
                            <option value="project-manager">Project Manager</option>
                            <option value="developer">Developer</option>
                            <option value="designer">Designer</option>
                            <option value="tester">Tester</option>
                            <option value="analyst">Analyst</option>
                            <option value="consultant">Consultant</option>
                        </select>
                    </div>

                    <div>
                        <label for="projectDateFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-calendar" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Assignment Period
                        </label>
                        <select id="projectDateFilter" class="form-select" onchange="filterEmployeeProjects()">
                            <option value="">All Time</option>
                            <option value="current">Current Projects</option>
                            <option value="recent">Recent (Last 3 months)</option>
                            <option value="this-year">This Year</option>
                            <option value="last-year">Last Year</option>
                        </select>
                    </div>

                    <div>
                        <label for="projectPriorityFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-exclamation-triangle" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Priority
                        </label>
                        <select id="projectPriorityFilter" class="form-select" onchange="filterEmployeeProjects()">
                            <option value="">All Priorities</option>
                            <option value="high">High</option>
                            <option value="medium">Medium</option>
                            <option value="low">Low</option>
                        </select>
                    </div>
                </div>

                <!-- Quick Filter Buttons for Projects -->
                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 1rem;">
                    <button class="btn btn-primary" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="filterProjectsByStatus('all')">
                        <i class="fas fa-list"></i> All Projects
                    </button>
                    <button class="btn btn-success" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="filterProjectsByStatus('active')">
                        <i class="fas fa-play-circle"></i> Active
                    </button>
                    <button class="btn btn-info" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="filterProjectsByStatus('completed')">
                        <i class="fas fa-check-circle"></i> Completed
                    </button>
                    <button class="btn btn-warning" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="filterProjectsByStatus('on-hold')">
                        <i class="fas fa-pause-circle"></i> On Hold
                    </button>
                    <button class="btn btn-danger" style="padding: 0.5rem 1rem; font-size: 0.875rem;" onclick="filterProjectsByStatus('cancelled')">
                        <i class="fas fa-times-circle"></i> Cancelled
                    </button>
                </div>

                <!-- Project Assignment Statistics -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(120px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--success);" id="activeProjectsCount">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;">Active</div>
                    </div>
                    <div style="background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--info);" id="completedProjectsCount">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;">Completed</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--warning);" id="onHoldProjectsCount">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;">On Hold</div>
                    </div>
                    <div style="background: rgba(107, 114, 128, 0.1); border: 1px solid rgba(107, 114, 128, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--text-secondary);" id="totalProjectsCount">0</div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 500;">Total</div>
                    </div>
                </div>

                <!-- Project Assignments Table -->
                <div class="table-container">
                    <table class="table" id="projectAssignmentsTable">
                        <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Assigned Date</th>
                            <th>Progress</th>
                            <th>Deadline</th>
                        </tr>
                        </thead>
                        <tbody id="projectAssignmentsTableBody">
                        <!-- Project assignments will be populated here -->
                        </tbody>
                    </table>
                    <!-- Card view for mobile -->
                    <div class="assignment-card-list"></div>
                </div>

                <!-- No Assignments Message -->
                <div id="noProjectAssignments" style="display: none; text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <i class="fas fa-briefcase" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                    <p style="margin: 0; font-size: 1.1rem;">No project assignments found</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">This employee has no project assignments matching the current filters</p>
                </div>
            </div>
        </div>


        <div id="projectsGrid" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">

            @forelse($projects as $project)
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">
                        @php
                        // Dynamic icon based on project name/id
                        $icon = 'fas fa-building';
                        $iconColor = 'var(--primary)';

                        $projLower = strtolower($project->project_name ?? $project->project_id);

                        if (str_contains($projLower, 'pos') || str_contains($projLower, 'point of sale')) {
                        $icon = 'fas fa-cash-register';
                        $iconColor = 'var(--primary)';
                        } elseif (str_contains($projLower, 'web') || str_contains($projLower, 'application') || str_contains($projLower, 'website')) {
                        $icon = 'fas fa-globe';
                        $iconColor = 'var(--secondary)';
                        } elseif (str_contains($projLower, 'ecommerce') || str_contains($projLower, 'shop') || str_contains($projLower, 'store')) {
                        $icon = 'fas fa-shopping-cart';
                        $iconColor = 'var(--warning)';
                        } elseif (str_contains($projLower, 'mobile') || str_contains($projLower, 'android') || str_contains($projLower, 'ios')) {
                        $icon = 'fas fa-mobile-alt';
                        $iconColor = 'var(--info)';
                        } elseif (str_contains($projLower, 'inventory') || str_contains($projLower, 'stock')) {
                        $icon = 'fas fa-boxes';
                        $iconColor = 'var(--success)';
                        } elseif (str_contains($projLower, 'analytics') || str_contains($projLower, 'dashboard')) {
                        $icon = 'fas fa-chart-line';
                        $iconColor = 'var(--success)';
                        }

                        // Progress calculation
                        $progress = 0;
                        switch($project->status) {
                        case 'Planning': $progress = 10; break;
                        case 'In Progress': $progress = 50; break;
                        case 'On Hold': $progress = 30; break;
                        case 'Testing': $progress = 70; break;
                        case 'Completed': $progress = 100; break;
                        case 'Cancelled': $progress = 0; break;
                        default: $progress = 0;
                        }
                        @endphp

                        <i class="{{ $icon }}" style="color: {{ $iconColor }};"></i>
                        {{ $project->project_name ?? $project->project_id }}
                    </h3>

                    <div class="flex gap-1">
                        <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="viewProjectDetails('{{ $project->project_id }}', '{{ $project->project_name ?? $project->project_id }}')">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn btn-warning" style="padding: 0.5rem;" title="Edit Project" onclick="openEditProjectModal('{{ $project->project_id }}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem;" title="Delete project" onclick="confirmDeleteProject('{{ $project->project_id }}', '{{ $project->project_name ?? $project->project_id }}')">
                            <i class="fas fa-trash"></i>
                        </button>

                    </div>
                </div>

                <div class="card-body">

                    {{-- Project Manager --}}
                    @if($project->team && $project->team->team_lead)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Project Manager</div>
                        <div style="font-weight: 600;">{{ $project->team->team_lead }}</div>
                    </div>
                    @else
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Project Manager</div>
                        <div style="font-weight: 600; color: #aaa;">Not Assigned</div>
                    </div>
                    @endif




                    <!--                    {{-- Project Manager --}}-->
                    <!--                    <div style="margin-bottom: 1rem;">-->
                    <!--                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Project Manager</div>-->
                    <!---->
                    <!--                        @if($project->team && $project->team->teamLead)-->
                    <!--                        @php-->
                    <!--                        $managerName = $project->team->teamLead->name;-->
                    <!--                        $managerInitials = collect(explode(' ', $managerName))-->
                    <!--                        ->map(fn($w) => strtoupper(substr($w, 0, 1)))-->
                    <!--                        ->implode('');-->
                    <!--                        @endphp-->
                    <!--                        <div style="display: flex; align-items: center; gap: 0.5rem;">-->
                    <!--                            <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">-->
                    <!--                                {{ $managerInitials }}-->
                    <!--                            </div>-->
                    <!--                            <div style="font-weight: 600;">{{ $managerName }}</div>-->
                    <!--                        </div>-->
                    <!--                        @else-->
                    <!--                        <div style="font-weight: 600;">N/A</div>-->
                    <!--                        @endif-->
                    <!--                    </div>-->
                    <!---->


                    @if($project->status)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Progress</div>
                        <div style="background: var(--gray-200); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: {{ $progress }}%; transition: width 0.3s ease;"></div>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            {{ $progress }}% Complete
                        </div>
                    </div>
                    @endif

                    {{-- Team Size --}}
                    @if($project->team)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Size</div>
                        <div style="font-size: 1.25rem; font-weight: 700; color: var(--secondary);">
                            {{ $project->team->employees_count }} Members
                        </div>
                    </div>
                    @endif

                    @if($project->end_date)
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Deadline</div>
                        <div style="font-weight: 600;">{{ \Carbon\Carbon::parse($project->end_date)->format('M d, Y') }}</div>
                    </div>
                    @endif

                    <div class="flex justify-between items-center">
                        @php
                        // Calculate days left
                        $daysLeft = null;
                        if (!empty($project->end_date)) {
                        $now = \Carbon\Carbon::now();
                        $end = \Carbon\Carbon::parse($project->end_date);
                        $daysLeft = (int) $end->diffInDays($now, false); // signed difference
                        }

                        // Map status to colors
                        $statusColors = [
                        'Active' => ['text' => 'success', 'bg' => 'rgba(16, 185, 129, 0.1)'],
                        'In Progress' => ['text' => 'info', 'bg' => 'rgba(59, 130, 246, 0.1)'],
                        'On Hold' => ['text' => 'warning', 'bg' => 'rgba(251, 191, 36, 0.1)'],
                        'Testing' => ['text' => 'info', 'bg' => 'rgba(14, 165, 233, 0.1)'],
                        'Completed' => ['text' => 'success', 'bg' => 'rgba(16, 185, 129, 0.1)'], // green
                        'Cancelled' => ['text' => 'danger', 'bg' => 'rgba(239, 68, 68, 0.1)'],
                        ];

                        // Default
                        $statusTextColor = 'text-secondary';
                        $statusBg = 'rgba(107, 114, 128, 0.1)';

                        // Apply mapped color if status exists
                        if (!empty($project->status) && isset($statusColors[$project->status])) {
                        $statusTextColor = $statusColors[$project->status]['text'];
                        $statusBg = $statusColors[$project->status]['bg'];
                        }

                        // Override for overdue, but do NOT override Completed
                        if ($daysLeft !== null && $daysLeft < 0 && $project->status !== 'Completed') {
                        $statusTextColor = 'danger';
                        $statusBg = 'rgba(239, 68, 68, 0.1)';
                        }
                        @endphp

                        <!-- Status badge -->
                        <span class="badge" style="background: {{ $statusBg }}; color: var(--{{ $statusTextColor }}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
        {{ $project->status ?? 'Active' }}
    </span>

                        <!-- Days left display -->
                        @if(!is_null($daysLeft))
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">
                            <i class="fas fa-calendar"></i>
                            @if($daysLeft > 0)
                            {{ $daysLeft }} days left
                            @elseif($daysLeft === 0)
                            Today
                            @else
                            Overdue by {{ abs($daysLeft) }} days
                            @endif
                        </div>
                        @endif
                    </div>

                </div>
            </div>
            @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <i class="fas fa-building" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">No Projects Found</h3>
                <p style="margin: 0; font-size: 1rem;">Start by creating your first project using the "Add Project" button above.</p>
            </div>
            @endforelse

        </div>


    </div>


    <!-- Project Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                    {{ $projects->count() }}
                </div>
                <div style="color: var(--text-secondary); font-weight: 500;">
                    Total Projects
                </div>


    <!-- Project Statistics -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                    {{ $projects->count() }}
                </div>
                <div style="color: var(--text-secondary); font-weight: 500;">
                    Total Projects
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                    {{ $projects->where('status', 'Completed')->count() }}
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Completed</div>

            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">

                <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                    {{ $projects->where('status', 'Completed')->count() }}
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Completed</div>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;"> {{ $projects->where('status', 'On Hold')->count() }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">On Hold</div>
            </div>
        </div>

        @php
        $totalProjects = $projects->count();
        $completedProjects = $projects->where('status', 'Completed')->count();
        $successRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0;
        @endphp

        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">
                    {{ $successRate }}%
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Success Rate</div>
            </div>
        </div>

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
                <form action="{{ route('admin.projects.store') }}" method="POST" id="projectForm">
                    @csrf
                    <div class="form-container">
                        <!-- Basic Information Row -->
                        <div class="form-row">

                            <div class="form-group">
                                <label for="project_name" class="form-label">
                                    <i class="fas fa-project-diagram"></i>Project Name
                                </label>
                                <div style="position: relative;">

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

                                    <input type="text" id="client" name="client" class="form-input" placeholder="Enter client name">

                <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;"> {{ $projects->where('status', 'On Hold')->count() }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">On Hold</div>
            </div>
        </div>

        @php
        $totalProjects = $projects->count();
        $completedProjects = $projects->where('status', 'Completed')->count();
        $successRate = $totalProjects > 0 ? round(($completedProjects / $totalProjects) * 100) : 0;
        @endphp

        <div class="card">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">
                    {{ $successRate }}%
                </div>
                <div style="color: var(--gray-600); font-weight: 500;">Success Rate</div>
            </div>
        </div>

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
                <form action="{{ route('admin.projects.store') }}" method="POST" id="projectForm">
                    @csrf
                    <div class="form-container">
                        <!-- Basic Information Row -->
                        <div class="form-row">

                            <div class="form-group">
                                <label for="project_name" class="form-label">
                                    <i class="fas fa-project-diagram"></i>Project Name
                                </label>
                                <div style="position: relative;">

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

                                    <input type="text" id="client" name="client" class="form-input" placeholder="Enter client name">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="team_id" class="form-label">
                                    <i class="fas fa-users"></i>Assigned Team
                                </label>
                                <div style="position: relative;">

                                    <select id="team_id" name="team_id" class="form-select" required>
                                        <option value="">Select Team</option>
                                        @if(isset($teams))
                                        @foreach($teams as $team)
                                        <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>

                                </div>
                            </div>


                            <div class="form-group">
                                <label for="team_id" class="form-label">
                                    <i class="fas fa-users"></i>Assigned Team
                                </label>
                                <div style="position: relative;">

                                    <select id="team_id" name="team_id" class="form-select" required>
                                        <option value="">Select Team</option>
                                        @if(isset($teams))
                                        @foreach($teams as $team)
                                        <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>


                        <!-- Status Row -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-tasks"></i>Project Status
                            </label>
                            <div style="position: relative;">
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
                                    <input type="date" id="start_date" name="start_date" class="form-input">
                                </div>
                            </div>


                        <!-- Status Row -->
                        <div class="form-group">
                            <label for="status" class="form-label">
                                <i class="fas fa-tasks"></i>Project Status
                            </label>
                            <div style="position: relative;">
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
                                    <input type="date" id="start_date" name="start_date" class="form-input">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="end_date" class="form-label">
                                    <i class="fas fa-calendar-check"></i>End Date
                                </label>
                                <div style="position: relative;">
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

                                <textarea id="description" name="description" class="form-textarea" placeholder="Enter project description"></textarea>
                            </div>
                        </div>

                        <!-- Milestone Info -->
                        <div class="form-group">
                            <label for="milestone_info" class="form-label">
                                <i class="fas fa-flag"></i>Milestone Information
                            </label>
                            <div style="position: relative;">

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

                            <div class="form-group">
                                <label for="end_date" class="form-label">
                                    <i class="fas fa-calendar-check"></i>End Date
                                </label>
                                <div style="position: relative;">
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

                                <textarea id="description" name="description" class="form-textarea" placeholder="Enter project description"></textarea>
                            </div>
                        </div>

                        <!-- Milestone Info -->
                        <div class="form-group">
                            <label for="milestone_info" class="form-label">
                                <i class="fas fa-flag"></i>Milestone Information
                            </label>
                            <div style="position: relative;">

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

    <!-- Project Edit Modal -->
    <div id="editProjectModal" class="modal-overlay">
        <div class="modal-container">
            <div class="modal-header">
                <div class="modal-title">
                    <i class="fas fa-edit"></i>
                    Edit Project
                </div>
                <div class="modal-subtitle">Update the project details below</div>
                <button class="modal-close" onclick="closeEditModal()">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <div class="modal-body">
                <form action="#" method="POST" id="editProjectForm">
                    @csrf
                    @method('PUT')
                    <div class="form-container">
                        <!-- Basic Information Row -->
                        <div class="form-row">

                            <div class="form-group">
                                <label for="edit_project_id" class="form-label">
                                    <i class="fas fa-hashtag"></i>Project ID
                                </label>
                                <div style="position: relative;">
                                    <i class="input-icon fas fa-hashtag"></i>
                                    <input type="text" id="edit_project_id" name="project_id" class="form-input" placeholder="Enter project ID" required readonly>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="edit_project_name" class="form-label">
                                    <i class="fas fa-project-diagram"></i>Project Name
                                </label>
                                <div style="position: relative;">
                                    <i class="input-icon fas fa-project-diagram"></i>
                                    <input type="text" id="edit_project_name" name="project_name" class="form-input" placeholder="Enter project name" required>
                                </div>
                            </div>
                        </div>


                        <div class="form-group">
                            <label for="edit_team_id" class="form-label">
                                <i class="fas fa-users"></i> Assigned Team
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-users"></i>
                                <select id="edit_team_id" name="team_id" class="form-select" required>
                                    <option value="">Select Team</option>
                                    @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}"
                                            {{ (int) old('team_id', $project->team_id ?? null) === (int) $team->team_id ? 'selected' : '' }}>
                                    {{ $team->team_name }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>



                    </div>

                    <!-- Status Row -->
                    <div class="form-group">
                        <label for="edit_status" class="form-label">
                            <i class="fas fa-tasks"></i>Project Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-tasks"></i>
                            <select id="edit_status" name="status" class="form-select" required>
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
                            <label for="edit_start_date" class="form-label">
                                <i class="fas fa-calendar-plus"></i>Start Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-plus"></i>
                                <input type="date" id="edit_start_date" name="start_date" class="form-input">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="edit_end_date" class="form-label">
                                <i class="fas fa-calendar-check"></i>End Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-check"></i>
                                <input type="date" id="edit_end_date" name="end_date" class="form-input">
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label for="edit_description" class="form-label">
                            <i class="fas fa-file-alt"></i>Project Description
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-file-alt"></i>
                            <textarea id="edit_description" name="description" class="form-textarea" placeholder="Enter project description"></textarea>
                        </div>
                    </div>

                    <!-- Milestone Info -->
                    <div class="form-group">
                        <label for="edit_milestone_info" class="form-label">
                            <i class="fas fa-flag"></i>Milestone Information
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-flag"></i>
                            <textarea id="edit_milestone_info" name="milestone_info" class="form-textarea" placeholder="Enter milestone details"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Project
                        </button>
                    </div>
            </div>
            </form>
        </div>
    </div>
</div>

<!-- Project View Modal -->
<div id="viewProjectModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-eye"></i>
                Project Details
            </div>
            <div class="modal-subtitle">View complete project information</div>
            <button class="modal-close" onclick="closeViewModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="form-container">
                <!-- Basic Information Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-hashtag"></i>Project ID
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-hashtag"></i>
                            <div class="view-field" id="view_project_id"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-project-diagram"></i>Project Name
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-project-diagram"></i>
                            <div class="view-field" id="view_project_name"></div>
                        </div>
                    </div>
                </div>

                <!-- Client and Team Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tie"></i>Client
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tie"></i>
                            <div class="view-field" id="view_client"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i> Assigned Team
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users"></i>
                            <div class="view-field" id="view_team_id">Not Assigned</div>
                        </div>
                    </div>

                </div>

                <!-- Status Row -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-tasks"></i>Project Status
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-tasks"></i>
                        <div class="view-field" id="view_status"></div>
                    </div>
                </div>

                <!-- Date Range Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-plus"></i>Start Date
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-calendar-plus"></i>
                            <div class="view-field" id="view_start_date"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-check"></i>End Date
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-calendar-check"></i>
                            <div class="view-field" id="view_end_date"></div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i>Project Description
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-file-alt"></i>
                        <div class="view-field view-textarea" id="view_description"></div>
                    </div>
                </div>

                <!-- Milestone Info -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-flag"></i>Milestone Information
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-flag"></i>
                        <div class="view-field view-textarea" id="view_milestone_info"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

    function openModal() {
        const modal = document.getElementById('projectModal');
        modal.classList.add('active');

        // Clear form
        document.getElementById('projectForm').reset();

        // Clear any previous error messages
        const errorMessages = modal.querySelectorAll('.error-message');
        errorMessages.forEach(msg => msg.remove());

        // Focus on first input
        setTimeout(() => {
            document.getElementById('project_id').focus();
        }, 300);
    }

    function closeModal() {
        document.getElementById('projectModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openEditProjectModel(projectName, projectId, clientName, teamId, status, startDate, endDate, description, milestone) {

        console.log('Opening edit modal with data:', {
            projectName, projectId, clientName, teamId, status, startDate, endDate, description, milestone
        });

        // Clear any previous validation styles
        document.querySelectorAll('#editProjectModal .form-input, #editProjectModal .form-select, #editProjectModal .form-textarea').forEach(field => {
            field.style.borderColor = '';
            field.style.background = '';
        });

        // Populate form fields with the project data - ensuring values are properly set
        const editProjectName = document.getElementById('edit_project_name');
        const editProjectId = document.getElementById('edit_project_id');
        const editClient = document.getElementById('edit_client');
        const editTeamId = document.getElementById('edit_team_id');
        const editStatus = document.getElementById('edit_status');
        const editStartDate = document.getElementById('edit_start_date');
        const editEndDate = document.getElementById('edit_end_date');
        const editDescription = document.getElementById('edit_description');
        const editMilestone = document.getElementById('edit_milestone_info');


        // Set values with null checks and enhanced visual feedback - matching view modal appearance
        if (editProjectName) {
            editProjectName.value = projectName || '';
            if (editProjectName.value) {
                editProjectName.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editProjectName.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editProjectName.style.color = 'var(--text-primary)';
                editProjectName.style.fontWeight = '500'; // Match view modal font weight
                editProjectName.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editProjectName.setAttribute('data-populated', 'true');
            }
            console.log('Set project name:', editProjectName.value);
        }
        if (editProjectId) {
            editProjectId.value = projectId || '';
            if (editProjectId.value) {
                editProjectId.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editProjectId.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editProjectId.style.color = 'var(--text-primary)';
                editProjectId.style.fontWeight = '500'; // Match view modal font weight
                editProjectId.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editProjectId.setAttribute('data-populated', 'true');
            }
            console.log('Set project ID:', editProjectId.value);
        }
        if (editClient) {
            editClient.value = clientName || '';
            if (editClient.value) {
                editClient.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editClient.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editClient.style.color = 'var(--text-primary)';
                editClient.style.fontWeight = '500'; // Match view modal font weight
                editClient.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editClient.setAttribute('data-populated', 'true');
            }
            console.log('Set client:', editClient.value);
        }
        if (editTeamId) {
            editTeamId.value = teamId || '';
            if (editTeamId.value) {
                editTeamId.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editTeamId.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editTeamId.style.color = 'var(--text-primary)';
                editTeamId.style.fontWeight = '500'; // Match view modal font weight
                editTeamId.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editTeamId.setAttribute('data-populated', 'true');
            }
            console.log('Set team ID:', editTeamId.value);
        }
        if (editStatus) {
            editStatus.value = status || '';
            if (editStatus.value) {
                editStatus.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editStatus.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editStatus.style.color = 'var(--text-primary)';
                editStatus.style.fontWeight = '500'; // Match view modal font weight
                editStatus.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editStatus.setAttribute('data-populated', 'true');
            }
            console.log('Set status:', editStatus.value);
        }
        if (editStartDate) {
            editStartDate.value = startDate || '';
            if (editStartDate.value) {
                editStartDate.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editStartDate.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editStartDate.style.color = 'var(--text-primary)';
                editStartDate.style.fontWeight = '500'; // Match view modal font weight
                editStartDate.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
                editStartDate.setAttribute('data-populated', 'true');
            }
            console.log('Set start date:', editStartDate.value);
        }
        if (editEndDate) {
            editEndDate.value = endDate || '';
            if (editEndDate.value) {
                editEndDate.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editEndDate.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editEndDate.style.color = 'var(--text-primary)';
                editEndDate.style.fontWeight = '500'; // Match view modal font weight
                editEndDate.style.padding = '16px 16px 16px 48px'; // Ensure consistent spacing
                editEndDate.setAttribute('data-populated', 'true');
            }
            console.log('Set end date:', editEndDate.value);
        }
        if (editDescription) {
            editDescription.value = description || '';
            if (editDescription.value) {
                editDescription.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editDescription.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editDescription.style.color = 'var(--text-primary)';
                editDescription.style.fontWeight = '500'; // Match view modal font weight
                editDescription.style.padding = '16px 16px 16px 48px'; // Textarea specific padding
                editDescription.setAttribute('data-populated', 'true');
            }
            console.log('Set description:', editDescription.value);
        }
        if (editMilestone) {
            editMilestone.value = milestone || '';
            if (editMilestone.value) {
                editMilestone.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                editMilestone.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                editMilestone.style.color = 'var(--text-primary)';
                editMilestone.style.fontWeight = '500'; // Match view modal font weight
                editMilestone.style.padding = '16px 16px 16px 48px'; // Textarea specific padding
                editMilestone.setAttribute('data-populated', 'true');
            }
            console.log('Set milestone:', editMilestone.value);
        }

        // Enhanced icon styling for populated fields
        document.querySelectorAll('#editProjectModal .input-icon').forEach(icon => {
            const field = icon.nextElementSibling;
            if (field && field.value && field.value.trim()) {
                icon.style.color = 'var(--redcode-green)';
                icon.style.transform = 'translateY(-50%) scale(1.05)';
            }
        });

        // Trigger change events to ensure proper styling
        [editProjectName, editProjectId, editClient, editTeamId, editStatus, editStartDate, editEndDate, editDescription, editMilestone].forEach(field => {
            if (field && field.value) {
                field.dispatchEvent(new Event('input', { bubbles: true }));
            }
        });

        // Set the form action URL dynamically for the specific project
        const editForm = document.getElementById('editProjectForm');
        editForm.action = `/projects/${projectId}`;


        document.getElementById('editProjectModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editProjectModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('editProjectForm').reset();
    }

    let currentViewProjectId = null;

    function viewProjectDetails(projectId) {
        currentViewProjectId = projectId;
        const modal = document.getElementById('viewProjectModal');

        modal.classList.add('active');

        // Fetch project data from backend
        fetch(`/projects/${projectId}/show`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const project = data.project;

                    // Basic Information
                    document.getElementById('view_project_name').textContent = project.project_name || 'N/A';
                    document.getElementById('view_project_id').textContent = project.project_id || 'N/A';
                    document.getElementById('view_client').textContent = project.client || 'N/A';
                    document.getElementById('view_team_id').textContent = project.team?.team_name || 'Not Assigned';



                    // Status
                    const statusField = document.getElementById('view_status');
                    statusField.textContent = project.status || 'N/A';
                    statusField.className = 'view-field status-badge';
                    if (project.status) {
                        if (project.status.toLowerCase().includes('progress')) {
                            statusField.classList.add('in-progress');
                        } else if (project.status.toLowerCase() === 'completed') {
                            statusField.classList.add('completed');
                        } else if (project.status.toLowerCase().includes('hold')) {
                            statusField.classList.add('on-hold');
                        }
                    }

                    // Dates
                    document.getElementById('view_start_date').textContent = project.start_date
                        ? new Date(project.start_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
                        : 'N/A';
                    document.getElementById('view_end_date').textContent = project.end_date
                        ? new Date(project.end_date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' })
                        : 'N/A';

                    // Description & Milestones
                    document.getElementById('view_description').textContent = project.description || 'No description available';
                    document.getElementById('view_milestone_info').textContent = project.milestone_info || 'Not defined';

                    // Dynamic icon based on project type
                    const iconElement = document.querySelector('#viewProjectModal .modal-title i');
                    const projLower = (project.project_name || project.project_id).toLowerCase();

                    if (projLower.includes('pos') || projLower.includes('point of sale')) {
                        iconElement.className = 'fas fa-cash-register';
                    } else if (projLower.includes('web') || projLower.includes('website') || projLower.includes('application')) {
                        iconElement.className = 'fas fa-globe';
                    } else if (projLower.includes('ecommerce') || projLower.includes('shop') || projLower.includes('store')) {
                        iconElement.className = 'fas fa-shopping-cart';
                    } else if (projLower.includes('mobile') || projLower.includes('android') || projLower.includes('ios')) {
                        iconElement.className = 'fas fa-mobile-alt';
                    } else if (projLower.includes('inventory') || projLower.includes('stock')) {
                        iconElement.className = 'fas fa-boxes';
                    } else if (projLower.includes('analytics') || projLower.includes('dashboard')) {
                        iconElement.className = 'fas fa-chart-line';
                    } else {
                        iconElement.className = 'fas fa-project-diagram';
                    }

                } else {
                    throw new Error(data.message || 'Failed to fetch project data');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading project data: ' + error.message);
                closeViewModal();
            });
    }


    document.getElementById("start_date").addEventListener("change", function() {
        let startDate = this.value;
        let endDateInput = document.getElementById("end_date");

        // set min date as next day
        if (startDate) {
            let date = new Date(startDate);
            date.setDate(date.getDate() + 1);
            endDateInput.min = date.toISOString().split("T")[0];
        } else {
            endDateInput.min = "";
        }
    });

    function closeViewModal() {
        document.getElementById('viewProjectModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openEditModalFromView() {
        // Close view modal first
        closeViewModal();



    document.getElementById("start_date").addEventListener("change", function() {
        let startDate = this.value;
        let endDateInput = document.getElementById("end_date");

        // set min date as next day
        if (startDate) {
            let date = new Date(startDate);
            date.setDate(date.getDate() + 1);
            endDateInput.min = date.toISOString().split("T")[0];
        } else {
            endDateInput.min = "";
        }
    });

    function closeViewModal() {
        document.getElementById('viewProjectModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function openEditModalFromView() {
        // Close view modal first
        closeViewModal();


        // Open edit modal with stored data
        const data = window.currentProjectData;
        if (data) {
            setTimeout(() => {
                openEditModal(data.projectName, data.projectId, data.clientName, data.teamId,
                    data.status, data.startDate, data.endDate, data.description, data.milestone);
            }, 300); // Small delay to allow view modal to close
        }
    }

    // Close modal when clicking outside
    document.getElementById('projectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeModal();
        }
    });

    document.getElementById('editProjectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.getElementById('viewProjectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeViewModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
            closeEditModal();
            closeViewModal();
        }
    });

    // Form validation and enhancements
    document.getElementById('projectForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#ef4444';
                field.style.background = 'rgba(239, 68, 68, 0.05)';
            } else {
                field.style.borderColor = '#10b981';
                field.style.background = 'rgba(16, 185, 129, 0.05)';
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    // Edit form validation and AJAX submission
    document.getElementById('editProjectForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const form = this;
        let isValid = true;

        // Basic required field validation
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = '#ef4444';
                field.style.background = 'rgba(239, 68, 68, 0.05)';
            } else {
                field.style.borderColor = '#10b981';
                field.style.background = 'rgba(16, 185, 129, 0.05)';
            }
        });

        if (!isValid) return alert('Please fill in all required fields.');

        const formData = new FormData(form);
        const submitBtn = form.querySelector('[type="submit"]');
        submitBtn.disabled = true;
        submitBtn.textContent = 'Updating...';

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': formData.get('_token')
            }
        })
            .then(res => res.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Project';

                if (data.success) {
                    closeEditModal();
                    alert(data.message);
                    location.reload(); // Or update DOM dynamically
                } else {
                    alert(data.message || 'Failed to update project.');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                submitBtn.textContent = 'Update Project';
                alert('Error updating project. Please try again.');
            });
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

    // Edit form date validation
    document.getElementById('edit_end_date').addEventListener('change', function() {
        const startDate = document.getElementById('edit_start_date').value;
        const endDate = this.value;

        if (startDate && endDate && endDate < startDate) {
            alert('End date cannot be before start date.');
            this.value = '';
        }
    });

    // Enhanced input interactions with populated data styling
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
                // Check if field has value to maintain populated styling
                if (this.value && this.value.trim()) {
                    icon.style.color = 'var(--redcode-green)';
                    icon.style.transform = 'translateY(-50%) scale(1.05)';
                } else {
                    icon.style.color = 'var(--text-light)';
                    icon.style.transform = 'translateY(-50%) scale(1)';
                }
            }
        });

        // Add visual indication for fields with data - enhanced for edit modal to match view modal
        input.addEventListener('input', function() {
            const icon = this.previousElementSibling;
            if (this.value.trim()) {
                // Style to match view modal appearance
                this.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
                this.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                this.style.color = 'var(--text-primary)';
                this.style.fontWeight = '500'; // Match view modal font weight

                // Ensure proper padding based on field type
                if (this.classList.contains('form-textarea')) {
                    this.style.padding = '16px 16px 16px 48px'; // Textarea specific
                } else {
                    this.style.padding = '12px 16px 12px 48px'; // Standard fields
                }

                this.setAttribute('data-populated', 'true');

                if (icon && icon.classList.contains('input-icon')) {
                    icon.style.color = 'var(--redcode-green)';
                    icon.style.transform = 'translateY(-50%) scale(1.05)';
                    icon.style.textShadow = '0 0 8px rgba(16, 185, 129, 0.3)';
                }
            } else {
                this.style.background = '';
                this.style.borderColor = '';
                this.style.color = '';
                this.style.fontWeight = '';
                this.style.padding = ''; // Reset to CSS default
                this.removeAttribute('data-populated');

                if (icon && icon.classList.contains('input-icon')) {
                    icon.style.color = 'var(--text-light)';
                    icon.style.transform = 'translateY(-50%) scale(1)';
                    icon.style.textShadow = '';
                }
            }
        });

        // Initialize styling for pre-populated fields (edit modal) - match view modal appearance
        if (input.value && input.value.trim()) {
            input.style.background = 'rgba(248, 250, 252, 0.8)'; // Match view modal background
            input.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            input.style.color = 'var(--text-primary)';
            input.style.fontWeight = '500'; // Match view modal font weight

            // Ensure proper padding based on field type
            if (input.classList.contains('form-textarea')) {
                input.style.padding = '16px 16px 16px 48px'; // Textarea specific
            } else {
                input.style.padding = '12px 16px 12px 48px'; // Standard fields
            }

            input.setAttribute('data-populated', 'true');

            const icon = input.previousElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--redcode-green)';
                icon.style.transform = 'translateY(-50%) scale(1.05)';
            }
        }
    });

    // Auto-open modal if there are validation errors
    @if ($errors->any())
        window.addEventListener('load', function() {
            openModal();
        });
    @endif

// Employee Project Assignment Functions
    let currentSelectedEmployee = null;
    let currentProjectAssignments = [];

    function searchEmployeeProjects() {
        const searchTerm = document.getElementById('employeeSearch').value.toLowerCase();
        if (searchTerm.length < 2) {
            hideEmployeeProjects();
            return;
        }

        // Simulate finding an employee (in real implementation, this would be an AJAX call)
        const mockEmployees = [
            { id: 1, name: 'John Smith', department: 'Engineering' },
            { id: 2, name: 'Sarah Wilson', department: 'Marketing' },
            { id: 3, name: 'Mike Johnson', department: 'Engineering' },
            { id: 4, name: 'Emily Davis', department: 'Sales' }
        ];

        const foundEmployee = mockEmployees.find(emp =>
            emp.name.toLowerCase().includes(searchTerm)
        );

        if (foundEmployee) {
            showEmployeeProjectAssignments(foundEmployee.id, foundEmployee.name);
        } else {
            hideEmployeeProjects();
        }
    }

    function searchEmployeeByName(name) {
        document.getElementById('employeeSearch').value = name;
        searchEmployeeProjects();
    }

    function clearEmployeeSearch() {
        document.getElementById('employeeSearch').value = '';
        document.getElementById('departmentFilter').value = '';
        hideEmployeeProjects();
    }

    function showEmployeeProjectAssignments(employeeId, employeeName) {
        currentSelectedEmployee = employeeId;
        document.getElementById('selectedEmployeeName').textContent = `${employeeName} - Project Assignments`;
        document.getElementById('employeeProjectAssignments').style.display = 'block';

        // Load project assignments for the employee
        loadEmployeeProjectAssignments(employeeId);

        // Scroll to the project assignments section
        document.getElementById('employeeProjectAssignments').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }


    function hideEmployeeProjects() {
        document.getElementById('employeeProjectAssignments').style.display = 'none';
        currentSelectedEmployee = null;
        currentProjectAssignments = [];
    }

    function loadEmployeeProjectAssignments(employeeId) {
        // Simulate loading project assignments (in real implementation, this would be an AJAX call)
        const mockProjectAssignments = [
            {
                id: 1,
                project_name: 'E-Commerce Platform',
                role: 'Project Manager',
                status: 'active',
                assigned_date: '2024-01-15',
                progress: 75,
                deadline: '2024-12-15',
                priority: 'high'
            },
            {
                id: 2,
                project_name: 'Mobile App Development',
                role: 'Developer',
                status: 'active',
                assigned_date: '2024-02-01',
                progress: 45,
                deadline: '2025-01-30',
                priority: 'medium'
            },
            {
                id: 3,
                project_name: 'Analytics Dashboard',
                role: 'Analyst',
                status: 'completed',
                assigned_date: '2024-01-01',
                progress: 100,
                deadline: '2024-11-20',
                priority: 'low'
            },
            {
                id: 4,
                project_name: 'Website Redesign',
                role: 'Designer',
                status: 'on-hold',
                assigned_date: '2024-03-01',
                progress: 30,
                deadline: '2024-08-15',
                priority: 'medium'
            }
        ];

        currentProjectAssignments = mockProjectAssignments;
        displayProjectAssignments(mockProjectAssignments);
        updateProjectStatistics(mockProjectAssignments);
    }

    function displayProjectAssignments(assignments) {
        const tbody = document.getElementById('projectAssignmentsTableBody');
        const noAssignmentsDiv = document.getElementById('noProjectAssignments');

        if (assignments.length === 0) {
            tbody.innerHTML = '';
            noAssignmentsDiv.style.display = 'block';
            return;
        }

        noAssignmentsDiv.style.display = 'none';



    function hideEmployeeProjects() {
        document.getElementById('employeeProjectAssignments').style.display = 'none';
        currentSelectedEmployee = null;
        currentProjectAssignments = [];
    }

    function loadEmployeeProjectAssignments(employeeId) {
        // Simulate loading project assignments (in real implementation, this would be an AJAX call)
        const mockProjectAssignments = [
            {
                id: 1,
                project_name: 'E-Commerce Platform',
                role: 'Project Manager',
                status: 'active',
                assigned_date: '2024-01-15',
                progress: 75,
                deadline: '2024-12-15',
                priority: 'high'
            },
            {
                id: 2,
                project_name: 'Mobile App Development',
                role: 'Developer',
                status: 'active',
                assigned_date: '2024-02-01',
                progress: 45,
                deadline: '2025-01-30',
                priority: 'medium'
            },
            {
                id: 3,
                project_name: 'Analytics Dashboard',
                role: 'Analyst',
                status: 'completed',
                assigned_date: '2024-01-01',
                progress: 100,
                deadline: '2024-11-20',
                priority: 'low'
            },
            {
                id: 4,
                project_name: 'Website Redesign',
                role: 'Designer',
                status: 'on-hold',
                assigned_date: '2024-03-01',
                progress: 30,
                deadline: '2024-08-15',
                priority: 'medium'
            }
        ];

        currentProjectAssignments = mockProjectAssignments;
        displayProjectAssignments(mockProjectAssignments);
        updateProjectStatistics(mockProjectAssignments);
    }

    function displayProjectAssignments(assignments) {
        const tbody = document.getElementById('projectAssignmentsTableBody');
        const noAssignmentsDiv = document.getElementById('noProjectAssignments');
        const cardList = document.querySelector('.assignment-card-list');

        if (assignments.length === 0) {
            tbody.innerHTML = '';
            cardList.innerHTML = '';
            noAssignmentsDiv.style.display = 'block';
            return;
        }

        noAssignmentsDiv.style.display = 'none';

        // Table rows for desktop/tablet

        tbody.innerHTML = assignments.map(assignment => `
        <tr data-assignment-id="${assignment.id}" data-status="${assignment.status}" data-role="${assignment.role.toLowerCase()}" data-priority="${assignment.priority}">
            <td>
                <div style="font-weight: 600; color: var(--text-primary);">${assignment.project_name}</div>
            </td>
            <td>
                <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--info); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem;">
                    ${assignment.role}
                </span>
            </td>
            <td>
                ${getProjectStatusBadge(assignment.status)}
            </td>
            <td>
                <div style="font-size: 0.875rem; color: var(--text-secondary);">
                    ${formatDate(assignment.assigned_date)}
                </div>
            </td>
            <td>
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="background: var(--gray-200); height: 8px; border-radius: 4px; overflow: hidden; flex: 1;">
                        <div style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: ${assignment.progress}%; transition: width 0.3s ease;"></div>
                    </div>
                    <span style="font-size: 0.75rem; color: var(--text-secondary); font-weight: 600;">${assignment.progress}%</span>
                </div>
            </td>
            <td>
                <div style="font-weight: 600;">${formatDate(assignment.deadline)}</div>
            </td>
        </tr>
    `).join('');

    }

    function updateProjectStatistics(assignments) {
        const active = assignments.filter(a => a.status === 'active').length;
        const completed = assignments.filter(a => a.status === 'completed').length;
        const onHold = assignments.filter(a => a.status === 'on-hold').length;
        const total = assignments.length;



        // Card view for mobile
        cardList.innerHTML = assignments.map(assignment => `
            <div class="assignment-card">
                <div class="assignment-title">${assignment.project_name}</div>
                <div class="assignment-row">
                    <span>Role:</span>
                    <span class="badge" style="background: rgba(37,99,235,0.1); color: var(--info);">${assignment.role}</span>
                </div>
                <div class="assignment-row">
                    <span>Status:</span>
                    ${getProjectStatusBadge(assignment.status)}
                </div>
                <div class="assignment-row">
                    <span>Assigned:</span>
                    <span>${formatDate(assignment.assigned_date)}</span>
                </div>
                <div class="assignment-row">
                    <span>Progress:</span>
                    <span>${assignment.progress}%</span>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-inner" style="width: ${assignment.progress}%;"></div>
                </div>
                <div class="assignment-row">
                    <span>Deadline:</span>
                    <span>${formatDate(assignment.deadline)}</span>
                </div>
            </div>
        `).join('');
    }

    function updateProjectStatistics(assignments) {
        const active = assignments.filter(a => a.status === 'active').length;
        const completed = assignments.filter(a => a.status === 'completed').length;
        const onHold = assignments.filter(a => a.status === 'on-hold').length;
        const total = assignments.length;


        document.getElementById('activeProjectsCount').textContent = active;
        document.getElementById('completedProjectsCount').textContent = completed;
        document.getElementById('onHoldProjectsCount').textContent = onHold;
        document.getElementById('totalProjectsCount').textContent = total;
    }

    function filterEmployeeProjects() {
        const statusFilter = document.getElementById('projectStatusFilter').value;
        const roleFilter = document.getElementById('projectRoleFilter').value;
        const dateFilter = document.getElementById('projectDateFilter').value;
        const priorityFilter = document.getElementById('projectPriorityFilter').value;


        let filteredAssignments = currentProjectAssignments.filter(assignment => {
            let showAssignment = true;

            // Status filter
            if (statusFilter && assignment.status !== statusFilter) {
                showAssignment = false;
            }

            // Role filter
            if (roleFilter && assignment.role.toLowerCase() !== roleFilter) {
                showAssignment = false;
            }

            // Priority filter
            if (priorityFilter && assignment.priority !== priorityFilter) {
                showAssignment = false;
            }

            // Date filter (simplified implementation)
            if (dateFilter) {
                const assignmentDate = new Date(assignment.assigned_date);
                const now = new Date();

                switch(dateFilter) {
                    case 'current':
                        showAssignment = assignment.status === 'active';
                        break;
                    case 'recent':
                        const threeMonthsAgo = new Date(now.getTime() - 90 * 24 * 60 * 60 * 1000);
                        showAssignment = assignmentDate >= threeMonthsAgo;
                        break;
                    case 'this-year':
                        showAssignment = assignmentDate.getFullYear() === now.getFullYear();
                        break;
                    case 'last-year':
                        showAssignment = assignmentDate.getFullYear() === now.getFullYear() - 1;
                        break;
                }
            }

            return showAssignment;
        });

        displayProjectAssignments(filteredAssignments);
        updateProjectStatistics(filteredAssignments);
    }

    function filterProjectsByStatus(status) {
        document.getElementById('projectStatusFilter').value = status === 'all' ? '' : status;
        filterEmployeeProjects();

        // Update button states
        document.querySelectorAll('[onclick^="filterProjectsByStatus"]').forEach(btn => {
            btn.classList.remove('btn-primary', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger');
            btn.classList.add('btn-secondary');
        });



        let filteredAssignments = currentProjectAssignments.filter(assignment => {
            let showAssignment = true;

            // Status filter
            if (statusFilter && assignment.status !== statusFilter) {
                showAssignment = false;
            }

            // Role filter
            if (roleFilter && assignment.role.toLowerCase() !== roleFilter) {
                showAssignment = false;
            }

            // Priority filter
            if (priorityFilter && assignment.priority !== priorityFilter) {
                showAssignment = false;
            }

            // Date filter (simplified implementation)
            if (dateFilter) {
                const assignmentDate = new Date(assignment.assigned_date);
                const now = new Date();

                switch(dateFilter) {
                    case 'current':
                        showAssignment = assignment.status === 'active';
                        break;
                    case 'recent':
                        const threeMonthsAgo = new Date(now.getTime() - 90 * 24 * 60 * 60 * 1000);
                        showAssignment = assignmentDate >= threeMonthsAgo;
                        break;
                    case 'this-year':
                        showAssignment = assignmentDate.getFullYear() === now.getFullYear();
                        break;
                    case 'last-year':
                        showAssignment = assignmentDate.getFullYear() === now.getFullYear() - 1;
                        break;
                }
            }

            return showAssignment;
        });

        displayProjectAssignments(filteredAssignments);
        updateProjectStatistics(filteredAssignments);
    }

    function filterProjectsByStatus(status) {
        document.getElementById('projectStatusFilter').value = status === 'all' ? '' : status;
        filterEmployeeProjects();

        // Update button states
        document.querySelectorAll('[onclick^="filterProjectsByStatus"]').forEach(btn => {
            btn.classList.remove('btn-primary', 'btn-success', 'btn-info', 'btn-warning', 'btn-danger');
            btn.classList.add('btn-secondary');
        });


        // Highlight active button
        const activeButton = document.querySelector(`[onclick="filterProjectsByStatus('${status}')"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            switch(status) {
                case 'all':
                    activeButton.classList.add('btn-primary');
                    break;
                case 'active':
                    activeButton.classList.add('btn-success');
                    break;
                case 'completed':
                    activeButton.classList.add('btn-info');
                    break;
                case 'on-hold':
                    activeButton.classList.add('btn-warning');
                    break;
                case 'cancelled':
                    activeButton.classList.add('btn-danger');
                    break;
            }
        }
    }

    function refreshEmployeeProjects() {
        if (currentSelectedEmployee) {
            loadEmployeeProjectAssignments(currentSelectedEmployee);
        }
    }

    // Helper functions
    function getProjectStatusBadge(status) {
        const statusColors = {
            'active': 'success',
            'completed': 'info',
            'on-hold': 'warning',
            'cancelled': 'danger'
        };

        const color = statusColors[status] || 'secondary';
        return `<span class="badge" style="background: rgba(${getProjectStatusColor(status)}, 0.1); color: var(--${color}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem; text-transform: capitalize;">${status}</span>`;
    }

    function getProjectStatusColor(status) {
        const colors = {
            'active': '16, 185, 129',
            'completed': '37, 99, 235',
            'on-hold': '245, 158, 11',
            'cancelled': '220, 38, 38'
        };
        return colors[status] || '107, 114, 128';
    }

    function formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }


    // Action functions for project assignments
    function viewProjectAssignment(assignmentId) {
        alert(`Viewing project assignment ${assignmentId}`);
        // Implement view project assignment functionality
    }

    function editProjectAssignment(assignmentId) {
        alert(`Editing project assignment ${assignmentId}`);
        // Implement edit functionality
    }

    function removeProjectAssignment(assignmentId) {
        if (confirm('Are you sure you want to remove this project assignment?')) {
            alert(`Removed project assignment ${assignmentId}`);
            // Implement remove functionality
            refreshEmployeeProjects();
        }
    }



    // Action functions for project assignments
    function viewProjectAssignment(assignmentId) {
        alert(`Viewing project assignment ${assignmentId}`);
        // Implement view project assignment functionality
    }

    function editProjectAssignment(assignmentId) {
        alert(`Editing project assignment ${assignmentId}`);
        // Implement edit functionality
    }

    function removeProjectAssignment(assignmentId) {
        if (confirm('Are you sure you want to remove this project assignment?')) {
            alert(`Removed project assignment ${assignmentId}`);
            // Implement remove functionality
            refreshEmployeeProjects();
        }
    }


    function assignNewProject() {
        alert('Assigning new project to employee');
        // Implement assign new project functionality
    }

    function exportEmployeeProjectReport() {
        alert('Exporting employee project report');
        // Implement export functionality
    }

    function viewProjectTimeline() {
        alert('Viewing project timeline');
        // Implement timeline view functionality
    }

    function printProjectReport() {
        window.print();
    }

    function renderProjects(projectsToRender) {
        const grid = document.getElementById('projectsGrid');

        if (!projectsToRender || projectsToRender.length === 0) {
            grid.innerHTML = `
            <div style="grid-column: 1 / -1; text-align: center; padding: 3rem; color: var(--text-secondary);">
                <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.3;"></i>
                <h3 style="margin: 0 0 0.5rem 0; font-size: 1.25rem;">No Projects Found</h3>
                <p style="margin: 0; font-size: 1rem;">Try adjusting your search terms or clear the search to see all projects.</p>
            </div>
        `;
            return;
        }

        let html = '';

        projectsToRender.forEach(project => {
            // Dynamic icon based on project name/id
            let icon = 'fas fa-building';
            let iconColor = 'var(--primary)';
            const projLower = (project.project_name || project.project_id).toLowerCase();

            if (projLower.includes('pos') || projLower.includes('point of sale')) {
                icon = 'fas fa-cash-register';
                iconColor = 'var(--primary)';
            } else if (projLower.includes('web') || projLower.includes('application') || projLower.includes('website')) {
                icon = 'fas fa-globe';
                iconColor = 'var(--secondary)';
            } else if (projLower.includes('ecommerce') || projLower.includes('shop') || projLower.includes('store')) {
                icon = 'fas fa-shopping-cart';
                iconColor = 'var(--warning)';
            } else if (projLower.includes('mobile') || projLower.includes('android') || projLower.includes('ios')) {
                icon = 'fas fa-mobile-alt';
                iconColor = 'var(--info)';
            } else if (projLower.includes('inventory') || projLower.includes('stock')) {
                icon = 'fas fa-boxes';
                iconColor = 'var(--success)';
            } else if (projLower.includes('analytics') || projLower.includes('dashboard')) {
                icon = 'fas fa-chart-line';
                iconColor = 'var(--info)';
            }


            // Status badge
            const statusClass = project.status === 'Active' ? 'success' : 'text-secondary';
            const statusBg = project.status === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)';

            // Progress calculation
            let progress = 0;
            switch (project.status) {
                case 'Planning': progress = 10; break;
                case 'In Progress': progress = 50; break;
                case 'On Hold': progress = 30; break;
                case 'Testing': progress = 70; break;
                case 'Completed': progress = 100; break;
                case 'Cancelled': progress = 0; break;
            }


            // Status badge
            const statusClass = project.status === 'Active' ? 'success' : 'text-secondary';
            const statusBg = project.status === 'Active' ? 'rgba(16, 185, 129, 0.1)' : 'rgba(107, 114, 128, 0.1)';

            // Progress calculation
            let progress = 0;
            switch (project.status) {
                case 'Planning': progress = 10; break;
                case 'In Progress': progress = 50; break;
                case 'On Hold': progress = 30; break;
                case 'Testing': progress = 70; break;
                case 'Completed': progress = 100; break;
                case 'Cancelled': progress = 0; break;
            }


            html += `
            <div class="card">
                <div class="card-header">
                    <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">
                        <i class="${icon}" style="color: ${iconColor};"></i>
                        ${project.project_name || project.project_id}
                    </h3>
                    <div class="flex gap-1">
                        <button class="btn btn-warning" style="padding: 0.5rem;" title="Edit Project" onclick="openEditProjectModal('${project.project_id}')">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button class="btn btn-danger" style="padding: 0.5rem;" title="Delete Project" onclick="confirmDeleteProject('${project.project_id}')">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">

            <!-- Project Manager -->
        ${project.team && project.team.team_lead ? `
        <div style="margin-bottom: 1rem;">
            <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Project Manager</div>
            <div style="font-weight: 600;">${project.team.team_lead}</div>
        </div>` : `
        <div style="margin-bottom: 1rem;">
            <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Project Manager</div>
            <div style="font-weight: 600; color: #aaa;">Not Assigned</div>
        </div>`}

                    ${project.status ? `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Progress</div>
                        <div style="background: var(--gray-200); height: 8px; border-radius: 4px; overflow: hidden;">
                            <div style="background: linear-gradient(90deg, var(--primary), var(--secondary)); height: 100%; width: ${progress}%; transition: width 0.3s ease;"></div>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            ${progress}% Complete
                        </div>
                    </div>` : ''}

                    ${project.deadline ? `
                    <div style="margin-bottom: 1rem;">
                        <div style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Deadline</div>
                        <div style="font-weight: 600;">${project.deadline}</div>
                    </div>` : ''}

                    <div class="flex justify-between items-center">
                        <span class="badge" style="background: ${statusBg}; color: var(--${statusClass}); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                            ${project.status || 'Active'}
                        </span>
                        <a href="#" class="btn btn-secondary" style="padding: 0.5rem 1rem; font-size: 0.75rem;" onclick="viewProjectDetails('${project.project_id}')">
                            <i class="fas fa-eye"></i> View Details
                        </a>
                    </div>
                </div>
            </div>
        `;
        });

        grid.innerHTML = html;
    }

    // Delete project Function
    function confirmDeleteProject(projectId, projectName) {
        if (confirm(`Are you sure you want to delete the project "${projectName}" (ID: ${projectId})?\n\nThis action cannot be undone and will affect all employees in this project.`)) {
            // Create a form and submit it for deletion
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/projects/${projectId}`;

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

    function openEditProjectModal(projectId) {
        if (!projectId) return alert('Project ID missing!');

        const modal = document.getElementById('editProjectModal');
        const form = document.getElementById('editProjectForm');

        if (!modal || !form) return alert('Edit modal or form not found!');

        modal.classList.add('active');
        form.reset();

        // Helper function to safely set input/select/textarea values
        const setValue = (selector, value) => {
            const el = form.querySelector(selector);
            if (el) el.value = value ?? '';
        };

        fetch(`/projects/${projectId}/edit`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.project) {
                    throw new Error(data.message || 'Failed to fetch project data');
                }

                const project = data.project;
                form.action = `/projects/${projectId}`;

                // Populate all fields safely
                setValue('#edit_project_id', project.project_id);
                setValue('#edit_project_name', project.project_name);
                setValue('#edit_client', project.client);
                setValue('#edit_status', project.status);
                setValue('#edit_start_date', project.start_date);
                setValue('#edit_end_date', project.end_date);
                setValue('#edit_description', project.description);
                setValue('#edit_milestone_info', project.milestone_info);
                setValue('#edit_team_id', project.team_id);

                // Focus first input
                const firstInput = form.querySelector('#edit_project_name');
                if (firstInput) firstInput.focus();

        modal.classList.add('active');
        form.reset();

        fetch(`/projects/${projectId}/edit`, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
            .then(res => res.json())
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Failed to fetch project data');

                const project = data.project;
                form.action = `/projects/${projectId}`;

                document.getElementById("edit_project_id").value = project.project_id;

                // Populate form fields
                form.querySelector('#edit_project_name').value = project.project_name || '';
                form.querySelector('#edit_client').value = project.client || '';
                form.querySelector('#edit_status').value = project.status || '';
                form.querySelector('#edit_start_date').value = project.start_date || '';
                form.querySelector('#edit_end_date').value = project.end_date || '';
                form.querySelector('#edit_description').value = project.description || '';
                form.querySelector('#edit_milestone_info').value = project.milestone_info || '';
                form.querySelector('#edit_team_id').value = project.team_id || '';

                form.querySelector('#edit_project_name').focus();

                //  Set selected Assigned Team
                const teamSelect = document.getElementById('edit_team_id');
                if (teamSelect && project.team_id) {
                    teamSelect.value = project.team_id;
                }


            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error loading project data: ' + error.message);
                closeEditModal();
            });
    }


    function closeEditProjectModal() {
        const modal = document.getElementById('editProjectModal');
        modal.classList.remove('active');
    }

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
