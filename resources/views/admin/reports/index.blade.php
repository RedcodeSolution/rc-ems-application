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
    /* Modern Reports UI Styles */
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
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
        border: none;
        outline: none;
    }
    .btn:active {
        transform: scale(0.97);
    }
    .btn-primary {
        background: linear-gradient(90deg, var(--primary) 60%, var(--secondary) 100%);
        color: #fff;
    }
    .btn-primary:hover {
        background: linear-gradient(90deg, var(--secondary) 60%, var(--primary) 100%);
    }
    .btn-secondary {
        background: var(--primary-light);
        color: var(--text-secondary);
    }
    .btn-secondary:hover {
        background: var(--divider);
    }
    .btn-success {
        background: var(--success);
        color: #fff;
    }
    .btn-success:hover {
        background: #388e3c;
    }
    .btn-warning {
        background: var(--warning);
        color: #fff;
    }
    .btn-warning:hover {
        background: #ffb300;
    }
    .btn-info {
        background: var(--info);
        color: #fff;
    }
    .btn-info:hover {
        background: #007c91;
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
    .table-container {
        overflow-x: auto;
        border-radius: 0.75rem;
        box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
        background: #fff;
    }
    .table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        font-size: 0.97rem;
    }
    .table th, .table td {
        padding: 1rem 1.25rem;
        text-align: left;
        vertical-align: middle;
    }
    .table th {
        background: var(--primary-light);
        color: var(--text-primary);
        font-weight: 700;
        border-bottom: 2px solid var(--divider);
        letter-spacing: 0.01em;
    }
    .table tr {
        transition: background 0.15s;
    }
    .table tbody tr:hover {
        background: var(--divider);
    }
    .table td {
        border-bottom: 1px solid var(--divider);
    }
    .table td:last-child {
        min-width: 120px;
    }
    .card-body h2, .card-body h3, .card-body h4 {
        color: var(--text-primary);
        margin: 0;
        font-weight: 700;
    }
    .card-body p {
        color: var(--text-secondary);
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
        color: var(--text-disabled);
        opacity: 1;
    }
    @media (max-width: 900px) {
        .card-body, .card-header { padding: 1rem; }
        .table th, .table td { padding: 0.75rem 0.5rem; }
        .card-body h2 { font-size: 1.1rem; }
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

    /* RedCode Solutions Modal Styles */
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
        padding: 12px 16px;
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
        padding: 16px;
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

    /* View Modal Styles */
    .view-field {
        width: 100%;
        padding: 12px 16px;
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

    .view-field.status-badge.processing {
        background: rgba(245, 158, 11, 0.1);
        border-color: rgba(245, 158, 11, 0.3);
        color: var(--redcode-orange);
    }

    .view-field.status-badge.completed {
        background: rgba(16, 185, 129, 0.1);
        border-color: rgba(16, 185, 129, 0.3);
        color: var(--redcode-green);
    }

    .view-field.status-badge.failed {
        background: rgba(220, 38, 38, 0.1);
        border-color: rgba(220, 38, 38, 0.3);
        color: var(--redcode-primary);
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

    /* Checkbox and Radio Styles */
    .form-checkbox, .form-radio {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-bottom: 0.5rem;
    }

    .form-checkbox input, .form-radio input {
        width: auto;
        margin: 0;
        margin-right: 0.5rem;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .card-body, .card-header { padding: 1rem; }
        .table th, .table td { padding: 0.75rem 0.5rem; }
        .card-body h2 { font-size: 1.1rem; }
    }

    @media (max-width: 600px) {
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

        /* Hide button text on mobile */
        .btn-text {
            display: none;
        }

        /* Adjust header buttons to be icon-only */
        .card-header .btn {
            padding: 0.5rem;
            width: auto;
        }

        /* Force Header Inline */
        .card-header {
            flex-direction: row !important;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
        }
        
        .card-header h2 {
             font-size: 1.1rem;
             margin: 0;
             white-space: nowrap; /* Prevent title wrapping if possible/desired, or allow normal wrap without breaks */
        }
        
        /* Stack search and filter on mobile */
        .card-body .flex.justify-between {
             flex-direction: column;
             align-items: stretch;
             gap: 1rem;
        }
        
        .card-body .flex.gap-2 {
             width: 100%;
             flex-wrap: wrap;
        }
        
        .form-input, .form-select {
             width: 100% !important;
        }
        
         /* Filter buttons full width on mobile */
        .card-body .flex.gap-2 button {
             flex: 1;
             justify-content: center;
        }

        /* Card View for Mobile */
        .table-container {
            border: none;
            box-shadow: none;
            background: transparent;
            overflow-x: visible;
        }

        .table {
            display: block;
            min-width: 0;
        }

        .table thead {
            display: none;
        }

        .table tbody {
            display: grid;
            gap: 1rem;
        }

        .table tbody tr {
            display: flex;
            flex-direction: column;
            background: #fff;
            border-radius: 1rem;
            padding: 1.25rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            border: 1px solid var(--divider);
        }

        .table tbody td {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem 0;
            border-bottom: 1px solid var(--divider);
            text-align: right;
        }

        .table tbody td:last-child {
            border-bottom: none;
            justify-content: flex-end;
            padding-top: 1rem;
        }

        .table tbody td:before {
            content: attr(data-label);
            font-weight: 600;
            color: var(--text-secondary);
            margin-right: auto;
            text-align: left;
            font-size: 0.9rem;
        }

        /* Report Name as Card Header */
        .table tbody td[data-label="Report Name"] {
            border-bottom: 2px solid var(--divider);
            margin-bottom: 0.5rem;
            padding-bottom: 0.75rem;
            font-size: 1.1rem;
        }

        .table tbody td[data-label="Report Name"]:before {
            display: none;
        }

        .table tbody td[data-label="Actions"]:before {
            display: none;
        }
    }

    /* Add this to global styles if not present, or ensure it propagates */
    .table-container {
        /* Default for desktop */
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
        width: 100%;
        display: block;
    }


    /* Custom Scrollbar for Table */
    .table-container::-webkit-scrollbar {
        height: 8px; /* Force height */
        display: block;
    }

    .table-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.05);
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb {
        background: var(--redcode-primary);
        border-radius: 4px;
    }

    .table-container::-webkit-scrollbar-thumb:hover {
        background: var(--redcode-primary-dark);
    }
</style>

@section('title', 'Reports & Analytics')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-chart-bar"></i> Reports & Analytics</h2>
        <div class="flex gap-2">
            <button onclick="openReportModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span class="btn-text">Generate Report</span>
            </button>

        </div>
    </div>
    <div class="card-body">
        <!-- Summary Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">{{ $totalRevenue ?? '$2.4M' }}</div>
                    <div style="color: var(--gray-600); font-weight: 500;">Total Revenue</div>
                </div>
            </div>
            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                        {{ $projects->count() }}
                    </div>
                    <div style="color: var(--gray-600); font-weight: 500;">Active Projects</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">
                        {{ $employees->count() }}
                    </div>
                    <div style="color: var(--gray-600); font-weight: 500;">Employees</div>
                </div>
            </div>

            <div class="card">
                <div class="card-body text-center">
                    <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">
                        {{ $efficiency ?? '94.2%' }}
                    </div>
                    <div style="color: var(--gray-600); font-weight: 500;">Efficiency</div>
                </div>
            </div>

        </div>


        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <input type="text" id="reportSearch" placeholder="Search reports..." class="form-input" style="width: 300px;" value="{{ request('search') }}">

                <select id="typeFilter" class="form-select" style="width: 200px;">
                    <option value="all" {{ request('type') == 'all' ? 'selected' : '' }}>All Types</option>
                    <option value="employee" {{ request('type') == 'employee' ? 'selected' : '' }}>Employee</option>
                    <option value="department" {{ request('type') == 'department' ? 'selected' : '' }}>Department</option>
                    <option value="project" {{ request('type') == 'project' ? 'selected' : '' }}>Project</option>
                    <option value="finance" {{ request('type') == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="attendance" {{ request('type') == 'attendance' ? 'selected' : '' }}>Attendance</option>
                    <option value="performance" {{ request('type') == 'performance' ? 'selected' : '' }}>Performance</option>
                    <option value="payroll" {{ request('type') == 'payroll' ? 'selected' : '' }}>Payroll</option>
                    <option value="leave" {{ request('type') == 'leave' ? 'selected' : '' }}>Leave</option>
                    <option value="team" {{ request('type') == 'team' ? 'selected' : '' }}>Team</option>
                    <option value="custom" {{ request('type') == 'custom' ? 'selected' : '' }}>Custom</option>
                </select>

                <select id="priorityFilter" class="form-select" style="width: 200px;">
                    <option value="all" {{ request('priority') == 'all' ? 'selected' : '' }}>All Priorities</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>High</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>Urgent</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>Normal</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>Low</option>
                </select>
            </div>

            <div class="flex gap-2">
                <button onclick="applyServerFilters()" class="btn btn-secondary">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button onclick="clearFilters()" class="btn btn-light">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>


        <!-- Reports Table -->
        <div class="table-container">
            <table  class="table" id="reportTable">
                <thead>
                <tr>
                    <th>Report Name</th>
                    <th>Type</th>
                    <th>Generated By</th>
                    <th>Date</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody id="reportTable">
                @foreach($reports as $report)
                <tr>
                    <td data-label="Report Name">
                        <div style="font-weight: 600;">{{ $report->report_name }}</div>

                    </td>
                    <td data-label="Type">
                      <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary);">
                            {{ strtolower($report->report_type) }}
                      </span>
                    </td>
                    <td data-label="Generated By">{{ $report->generated_by ?? 'Admin' }}</td>
                    <td data-label="Date">{{ $report->created_at->format('M d, Y') }}</td>
                    <td data-label="Priority">
                        @php
                        $priority = $report->priority ?? 'normal';

                        // Assign colors based on priority
                        $priorityStyles = match(strtolower($priority)) {
                        'high'    => ['bg' => 'rgba(251, 191, 36, 0.1)', 'color' => '#f59e0b'], // amber
                        'urgent'  => ['bg' => 'rgba(220, 38, 38, 0.1)', 'color' => '#dc2626'], // red
                        'low'     => ['bg' => 'rgba(16, 185, 129, 0.1)', 'color' => '#10b981'], // green
                        'normal'  => ['bg' => 'rgba(107, 114, 128, 0.1)', 'color' => '#6b7280'], // gray
                        default   => ['bg' => 'rgba(107, 114, 128, 0.1)', 'color' => '#6b7280'], // fallback
                        };
                        @endphp

                        <span class="badge"
                              style="background: {{ $priorityStyles['bg'] }};
                 color: {{ $priorityStyles['color'] }};
                 padding: 4px 10px;
                 border-radius: 12px;
                 font-weight: 600;
                 font-size: 0.75rem;">
        {{ ucfirst($priority) }}
    </span>
                    </td>

                    <td data-label="Actions">
                        <div class="flex gap-1 justify-end">
                            <button class="btn btn-secondary" style="padding: 0.5rem;"
                                    onclick="window.open('{{ route('admin.reports.show', $report->report_id) }}', '_blank')">
                                <i class="fas fa-eye"></i>
                            </button>

                            <button class="btn btn-primary"
                                    onclick="downloadReportById('{{ $report->report_id }}', '{{ $report->report_name }}', event)">
                                <i class="fas fa-download"></i>
                            </button>


                        </div>
                    </td>
                </tr>
                @endforeach
                </tbody>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <!-- Showing X to Y of Z reports -->
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing {{ $reports->firstItem() }} to {{ $reports->lastItem() }} of {{ $reports->total() }} reports
            </div>

            <!-- Page buttons -->
            <div class="flex gap-1">
                <!-- Previous button -->
                <a href="{{ $reports->previousPageUrl() }}" class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" @if(!$reports->onFirstPage()) @else disabled @endif>
                    <i class="fas fa-chevron-left"></i>
                </a>

                <!-- Page numbers -->
                @foreach ($reports->getUrlRange(1, $reports->lastPage()) as $page => $url)
                <a href="{{ $url }}" class="btn {{ $reports->currentPage() == $page ? 'btn-primary' : 'btn-secondary' }}" style="padding: 0.5rem 0.75rem;">
                    {{ $page }}
                </a>
                @endforeach

                <!-- Next button -->
                <a href="{{ $reports->nextPageUrl() }}" class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" @if($reports->hasMorePages()) @else disabled @endif>
                    <i class="fas fa-chevron-right"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Report Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;">
                {{ $reports->total() }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Reports</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">
                {{ $reports->where('priority', 'high')->count() }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">High</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">
                {{ $reports->where('priority', 'urgent')->count() }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">Urgent</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--danger); margin-bottom: 0.5rem;">
                {{ $reports->where('priority', 'normal')->count() }}
            </div>
            <div style="color: var(--gray-600); font-weight: 500;">Normal</div>
        </div>
    </div>

</div>

<!-- Report View Modal -->
<div id="viewReportModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-eye"></i>
                Report Details
            </div>
            <div class="modal-subtitle">View complete report information</div>
            <button class="modal-close" onclick="closeViewReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="form-container">
                <!-- Basic Information Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-hashtag"></i>Report ID
                        </label>
                        <div class="view-field" id="view_report_id"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-alt"></i>Report Name
                        </label>
                        <div class="view-field" id="view_report_name"></div>
                    </div>
                </div>

                <!-- Type and Status Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-chart-bar"></i>Report Type
                        </label>
                        <div class="view-field" id="view_report_type"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tasks"></i>Status
                        </label>
                        <div class="view-field" id="view_report_status"></div>
                    </div>
                </div>

                <!-- Generated By and Date Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i>Generated By
                        </label>
                        <div class="view-field" id="view_generated_by"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar"></i>Generated Date
                        </label>
                        <div class="view-field" id="view_generated_date"></div>
                    </div>
                </div>

                <!-- Format and Size Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-file-download"></i>Format
                        </label>
                        <div class="view-field" id="view_report_format"></div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-hdd"></i>File Size
                        </label>
                        <div class="view-field" id="view_file_size"></div>
                    </div>
                </div>

                <!-- Period Covered -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-alt"></i>Period Covered
                    </label>
                    <div class="view-field" id="view_period_covered"></div>
                </div>

                <!-- Report Description -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-info-circle"></i>Report Description
                    </label>
                    <div class="view-field view-textarea" id="view_report_description"></div>
                </div>

                <!-- Additional Information -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-sticky-note"></i>Additional Information
                    </label>
                    <div class="view-field view-textarea" id="view_additional_info"></div>
                </div>

                <!-- Modal Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewReportModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="downloadReport()">
                        <i class="fas fa-download"></i> Download Report
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Report Generation Modal -->
<div id="reportModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-chart-line"></i>
                Generate Report
            </div>
            <div class="modal-subtitle">Create custom reports with advanced filtering and analytics</div>
            <button class="modal-close" onclick="closeReportModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('reports.store') }}" method="POST" id="reportForm">
                @csrf
                <div class="form-container">
                    <!-- Report Basic Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="report_name" class="form-label">
                                <i class="fas fa-file-alt"></i>Report Name
                            </label>
                            <input type="text" id="report_name" name="report_name" class="form-input" placeholder="Enter report name" required>
                        </div>

                        <div class="form-group">
                            <label for="report_type" class="form-label">
                                <i class="fas fa-chart-bar"></i>Report Type
                            </label>
                            <select id="report_type" name="report_type" class="form-select" required>
                                <option value="">Select Report Type</option>
                                <option value="employee">Employee Report</option>
                                <option value="department">Department Report</option>
                                <option value="project">Project Report</option>
                                <option value="finance">Finance Report</option>
                                <option value="attendance">Attendance Report</option>
                                <option value="performance">Performance Report</option>
                                <option value="payroll">Payroll Report</option>
                                <option value="leave">Leave Report</option>
                                <option value="team">Team Report</option>
                                <option value="custom">Custom Report</option>
                            </select>
                        </div>
                    </div>

                    <!-- Date Range Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="start_date" class="form-label">
                                <i class="fas fa-calendar-alt"></i>Start Date
                            </label>
                            <input type="date" id="start_date" name="start_date" class="form-input" required>
                        </div>

                        <div class="form-group">
                            <label for="end_date" class="form-label">
                                <i class="fas fa-calendar-check"></i>End Date
                            </label>
                            <input type="date" id="end_date" name="end_date" class="form-input" required>
                        </div>
                    </div>

                    <!-- Department and Employee Filter Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department_filter" class="form-label">
                                <i class="fas fa-building"></i>Department Filter
                            </label>
                            <select id="department_filter" name="department_id" class="form-select">
                                <option value="">All Departments</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->department_id }}">
                                    {{ $department->department_name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="employee_filter" class="form-label">
                                <i class="fas fa-user"></i>Employee Filter
                            </label>
                            <select id="employee_filter" name="employee_id" class="form-select">
                                <option value="">All Employees</option>
                                @foreach($employees as $employee)
                                <option value="{{ $employee->employee_id }}">
                                    {{ $employee->employee_name }} - {{ $employee->role }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Format and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="report_format" class="form-label">
                                <i class="fas fa-file-download"></i>Report Format
                            </label>
                            <select id="report_format" name="report_format" class="form-select" required>
                                <option value="pdf">PDF Document</option>
                                <option value="excel">Excel Spreadsheet</option>
                                <option value="csv">CSV File</option>
                                <option value="json">JSON Data</option>
                                <option value="html">HTML Report</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="priority" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i>Priority
                            </label>
                            <select id="priority" name="priority" class="form-select">
                                <option value="normal" selected>Normal</option>
                                <option value="high">High</option>
                                <option value="urgent">Urgent</option>
                                <option value="low">Low</option>
                            </select>
                        </div>
                    </div>

                    <!-- Email Recipients -->
                    <div class="form-group">
                        <label for="email_recipients" class="form-label">
                            <i class="fas fa-envelope"></i>Email Recipients
                        </label>
                        <input type="email" id="email_recipients" name="email_recipients" class="form-input" placeholder="Enter email addresses separated by commas" multiple>
                    </div>

                    <!-- Report Description -->
                    <div class="form-group">
                        <label for="description" class="form-label">
                            <i class="fas fa-file-alt"></i>Report Description
                        </label>
                        <textarea id="description" name="description" class="form-textarea" placeholder="Describe the purpose and scope of this report" rows="3"></textarea>
                    </div>

                    <!-- Special Instructions -->
                    <div class="form-group">
                        <label for="special_instructions" class="form-label">
                            <i class="fas fa-sticky-note"></i>Special Instructions
                        </label>
                        <textarea id="special_instructions" name="special_instructions" class="form-textarea" placeholder="Any specific requirements or notes for this report" rows="3"></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeReportModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-chart-line"></i> Generate Report
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('reportSearch').addEventListener('keyup', function() {
        let searchValue = this.value.toLowerCase();
        let rows = document.querySelectorAll("#reportTable tr");

        rows.forEach(row => {
            let reportName = row.querySelector("td").textContent.toLowerCase();
            if (reportName.includes(searchValue)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    });

    // Report Modal Functions
    function openReportModal() {
        document.getElementById('reportModal').classList.add('active');
        document.body.style.overflow = 'hidden';

        // Set default dates
        const today = new Date();
        const oneMonthAgo = new Date(today.getFullYear(), today.getMonth() - 1, today.getDate());

        document.getElementById('start_date').value = oneMonthAgo.toISOString().split('T')[0];
        document.getElementById('end_date').value = today.toISOString().split('T')[0];

        // Enhanced input interactions
        setupInputEnhancements();
    }

    function closeReportModal() {
        document.getElementById('reportModal').classList.remove('active');
        document.body.style.overflow = 'auto';
        document.getElementById('reportForm').reset();

        // Clear any validation styles
        document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
            field.style.background = '';
            field.style.borderColor = '';
        });

        // Hide schedule options
        document.getElementById('schedule_options').style.display = 'none';
    }

    function setupInputEnhancements() {
        // Auto-schedule checkbox handler
        document.getElementById('auto_schedule').addEventListener('change', function() {
            const scheduleOptions = document.getElementById('schedule_options');
            if (this.checked) {
                scheduleOptions.style.display = 'block';
            } else {
                scheduleOptions.style.display = 'none';
            }
        });

        // Form validation
        document.getElementById('reportForm').addEventListener('submit', function(e) {
            const startDate = new Date(document.getElementById('start_date').value);
            const endDate = new Date(document.getElementById('end_date').value);

            if (startDate > endDate) {
                e.preventDefault();
                alert('Start date cannot be later than end date');
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating...';
            submitBtn.disabled = true;
        });
    }

    // Modal close on overlay click
    document.getElementById('reportModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeReportModal();
        }
    });

    // Modal close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && document.getElementById('reportModal').classList.contains('active')) {
            closeReportModal();
        }
    });
</script>

<!-- Report View Modal Functions -->
<script>
    // Report View Modal Functions
    function openViewReportModal(reportName, reportId, generatedBy, generatedDate, status, type, periodCovered, description, format, fileSize, additionalInfo) {
        // Debug log to verify data is being passed
        console.log('Opening report view modal with data:', {
            reportName, reportId, generatedBy, generatedDate, status, type, periodCovered, description, format, fileSize, additionalInfo
        });

        // Populate view fields with the report data
        document.getElementById('view_report_name').textContent = reportName || 'N/A';
        document.getElementById('view_report_id').textContent = reportId || 'N/A';
        document.getElementById('view_generated_by').textContent = generatedBy || 'N/A';
        document.getElementById('view_report_type').textContent = type || 'N/A';
        document.getElementById('view_period_covered').textContent = periodCovered || 'N/A';
        document.getElementById('view_report_format').textContent = format || 'N/A';
        document.getElementById('view_file_size').textContent = fileSize || 'N/A';
        document.getElementById('view_report_description').textContent = description || 'N/A';
        document.getElementById('view_additional_info').textContent = additionalInfo || 'N/A';

        // Handle status with special styling
        const statusField = document.getElementById('view_report_status');
        statusField.textContent = status || 'N/A';
        statusField.className = 'view-field status-badge';
        if (status) {
            const statusLower = status.toLowerCase();
            if (statusLower.includes('processing')) {
                statusField.classList.add('processing');
            } else if (statusLower === 'completed') {
                statusField.classList.add('completed');
            } else if (statusLower === 'failed') {
                statusField.classList.add('failed');
            }
        }

        // Format date nicely
        document.getElementById('view_generated_date').textContent = generatedDate ? new Date(generatedDate).toLocaleDateString('en-US', {
            year: 'numeric', month: 'long', day: 'numeric'
        }) : 'N/A';

        // Store data for potential download
        window.currentReportData = {
            reportName, reportId, generatedBy, generatedDate, status, type, periodCovered, description, format, fileSize, additionalInfo
        };

        // Show the view modal
        document.getElementById('viewReportModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeViewReportModal() {
        document.getElementById('viewReportModal').classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    function downloadReport() {
        // Get current report data
        const data = window.currentReportData;
        if (data) {
            // Show loading state
            const downloadBtn = document.querySelector('.btn-primary[onclick="downloadReport()"]');
            if (downloadBtn) {
                downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Downloading...';
                downloadBtn.disabled = true;
            }

            // Create download URL
            const downloadUrl = `/admin/reports/download/${data.reportId}`;

            // Create temporary download link
            const link = document.createElement('a');
            link.href = downloadUrl;
            link.download = `${data.reportName}.${data.format.toLowerCase()}`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Reset button after download
            setTimeout(() => {
                if (downloadBtn) {
                    downloadBtn.innerHTML = '<i class="fas fa-download"></i> Download Report';
                    downloadBtn.disabled = false;
                }
            }, 2000);

            // Show success message
            showNotification('Report download started successfully!', 'success');
        }
    }

    // Download report by ID from table
    function downloadReportById(reportId, reportName, event) {
        if(!reportId) {
            alert('Report ID not found!');
            return;
        }

        const downloadBtn = event.target.closest('button');
        const originalContent = downloadBtn.innerHTML;

        downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        downloadBtn.disabled = true;

        const downloadUrl = `/admin/reports/download/${reportId}`;

        // Trigger the download
        window.location.href = downloadUrl;

        setTimeout(() => {
            downloadBtn.innerHTML = originalContent;
            downloadBtn.disabled = false;
        }, 2000);

        showNotification(`${reportName} download started!`, 'success');
    }



    // Notification function
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

        // Add notification styles
        notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: ${type === 'success' ? '#10B981' : '#3B82F6'};
        color: white;
        padding: 12px 20px;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        z-index: 10000;
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 500;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;

        document.body.appendChild(notification);

        // Animate in
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);

        // Remove after 3 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    // Modal close on overlay click for view modal
    document.addEventListener('DOMContentLoaded', function() {
        const viewModal = document.getElementById('viewReportModal');
        if (viewModal) {
            viewModal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeViewReportModal();
                }
            });
        }
    });

    // Enhanced Modal close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('viewReportModal').classList.contains('active')) {
                closeViewReportModal();
            } else if (document.getElementById('reportModal').classList.contains('active')) {
                closeReportModal();
            }
        }
    });

    function applyServerFilters() {
        const search = document.getElementById('reportSearch').value;
        const type = document.getElementById('typeFilter').value;
        const priority = document.getElementById('priorityFilter').value;

        const params = new URLSearchParams(window.location.search);
        
        if (search) params.set('search', search); else params.delete('search');
        if (type !== 'all') params.set('type', type); else params.delete('type');
        if (priority !== 'all') params.set('priority', priority); else params.delete('priority');
        
        // Reset to page 1 on new filter
        params.delete('page');

        window.location.href = `${window.location.pathname}?${params.toString()}`;
    }

    // Clear all filters
    function clearFilters() {
        window.location.href = window.location.pathname;
    }

    // Live filtering as user types or changes dropdowns (Optional - removed for now to match explicit button click, or can keep)
    // For server-side, it's better to rely on the button or use debounce. 
    // Let's attach 'Enter' key on search input to trigger filter.
    document.getElementById('reportSearch').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') applyServerFilters();
    });

</script>
@endsection
