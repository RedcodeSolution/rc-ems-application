@extends('layouts.admin')

@section('title', 'Leave Management - Admin Dashboard')

@section('content')
    <div class="leave-management-container">
        <!-- Leave Management Header -->
        <div class="leave-header">
            <div class="header-content">
                <h1><i class="fas fa-calendar-alt"></i> Leave Management</h1>
                <p>Review, approve, and manage employee leave requests</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-success" onclick="openApplyLeaveModal()">
                    <i class="fas fa-plus-circle"></i> Apply for Leave
                </button>
                <button class="btn btn-outline" onclick="exportLeaveReport()">
                    <i class="fas fa-download"></i> Export Report
                </button>
                <button class="btn btn-secondary" onclick="refreshLeaves()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-primary" onclick="showLeaveAnalytics()">
                    <i class="fas fa-chart-bar"></i> Analytics
                </button>
            </div>
        </div>

        <!-- Leave Statistics Cards -->
        <div class="leave-stats-grid">
            <div class="leave-stat-card pending">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Requests</h3>
                    <div class="stat-number" data-count="{{ $employeePendingMonthlyCount }}">
                        {{ $employeePendingMonthlyCount }}</div>
                    <p>Awaiting your review</p>
                </div>
                <div class="stat-trend urgent">
                    <i class="fas fa-exclamation"></i> Action Required
                </div>
            </div>

            <div class="leave-stat-card approved">
                <div class="stat-icon">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Approved Today</h3>
                    <div class="stat-number" data-count="{{ $employeeApprovedTodayCount }}">
                        {{ $employeeApprovedTodayCount }}
                    </div>
                    <p>Processed successfully</p>
                </div>
                <div class="stat-trend {{ $approvedTrendDiff >= 0 ? 'positive' : 'negative' }}">
                    <i class="fas {{ $approvedTrendDiff >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    {{ $approvedTrendDiff >= 0 ? '+' : '' }}{{ $approvedTrendDiff }} from yesterday
                </div>
            </div>

            <div class="leave-stat-card rejected">
                <div class="stat-icon">
                    <i class="fas fa-times-circle"></i>
                </div>
                <div class="stat-content">
                    <h3>Rejected</h3>
                    <div class="stat-number" data-count="{{ $employeeRejectedMonthlyCount }}">
                        {{ $employeeRejectedMonthlyCount }}
                    </div>
                    <p>This month</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i> {{ $rejectedStatus }}
                </div>
            </div>

            <div class="leave-stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Requests</h3>
                    <div class="stat-number" data-count="{{ $employeeMonthlyCount }}">
                        {{ $employeeMonthlyCount }}
                    </div>
                    <p>This month</p>
                </div>
                <div class="stat-trend {{ $monthlyTrendPercent >= 0 ? 'positive' : 'negative' }}">
                    <i
                        class="fas {{ $monthlyTrendPercent >= 0 ? 'fa-chart-line' : 'fa-chart-line fa-flip-horizontal' }}"></i>
                    {{ $monthlyTrendPercent >= 0 ? '+' : '' }}{{ $monthlyTrendPercent }}% from last month
                </div>
            </div>

        </div>

        <!-- Leave Management Tabs -->
        <div class="leave-tabs">
            <div class="tab-nav">
                <button class="tab-btn" data-tab="my-leaves" onclick="switchTab('my-leaves')">
                    <i class="fas fa-user-calendar"></i> My Leave Requests
                </button>
                <button class="tab-btn active" data-tab="pending" onclick="switchTab('pending')">
                    <i class="fas fa-clock"></i> Pending Requests
                    <span class="tab-badge">{{ $employeePendingMonthlyCount }}</span>
                </button>
                <button class="tab-btn" data-tab="approved" onclick="switchTab('approved')">
                    <i class="fas fa-check-circle"></i> Approved
                </button>
                <button class="tab-btn" data-tab="rejected" onclick="switchTab('rejected')">
                    <i class="fas fa-times-circle"></i> Rejected
                </button>
                <button class="tab-btn" data-tab="all" onclick="switchTab('all')">
                    <i class="fas fa-list"></i> All Requests
                </button>
                <button class="tab-btn" data-tab="analytics" onclick="switchTab('analytics')">
                    <i class="fas fa-chart-bar"></i> Analytics
                </button>
            </div>
        </div>

        <!-- Leave Filters -->
        <div class="leave-filters">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="date-filter">Date Range</label>
                    <select id="date-filter" class="filter-select">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month" selected>This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="custom">Custom Range</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="department-filter">Department</label>
                    <select id="department-filter" class="filter-select">
                        <option value="">All Departments</option>
                        <option value="engineering">Engineering</option>
                        <option value="marketing">Marketing</option>
                        <option value="sales">Sales</option>
                        <option value="hr">Human Resources</option>
                        <option value="finance">Finance</option>
                    </select>
                </div>

                <div class="filter-group">
                    <label for="leave-type-filter">Leave Type</label>
                    <select id="leave-type-filter" class="filter-select">
                        <option value="">All Types</option>
                        <option value="annual">Annual Leave</option>
                        <option value="sick">Sick Leave</option>
                        <option value="personal">Personal Leave</option>
                        <option value="maternity">Maternity Leave</option>
                        <option value="emergency">Emergency Leave</option>
                    </select>
                </div>

                <div class="filter-group">
                    <input type="text" id="search-input" placeholder="Search by employee name..."
                        class="filter-input">
                </div>

                <div class="filter-group">
                    <button class="btn btn-primary" onclick="applyFilters()">
                        <i class="fas fa-filter"></i> Filter
                    </button>
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="fas fa-times"></i> Clear
                    </button>
                </div>
            </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">

            <!-- My Leave Requests Tab -->
            <div id="my-leaves-tab" class="tab-panel">
                <div class="panel-header">
                    <h2><i class="fas fa-user-calendar"></i> My Leave Requests</h2>
                    <div class="header-actions">
                        <button class="btn btn-primary" onclick="openApplyLeaveModal()">
                            <i class="fas fa-plus-circle"></i> Apply for Leave
                        </button>
                        <button class="btn btn-outline" onclick="exportMyLeaveReport()">
                            <i class="fas fa-download"></i> Export My Leaves
                        </button>
                    </div>
                </div>

                <!-- Admin's Leave Balance Card -->
                <div class="leave-balance-card">
                    <div class="balance-header">
                        <h3><i class="fas fa-calendar-check"></i> Leave Balance Overview</h3>
                    </div>
                    <div class="balance-grid">

                        <div class="balance-item">
                            <div class="balance-type">Annual Leave</div>
                            <div class="balance-info">
                                <span class="used">{{ $annualUsed }}</span> /
                                <span class="total">{{ $annualTotal }}</span> days
                                <div class="balance-bar">
                                    <div class="balance-fill 
    {{ $annualPercent < 50 ? 'low' : ($annualPercent < 80 ? 'medium' : 'high') }}"
                                        style="width: {{ $annualPercent }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="balance-item">
                            <div class="balance-type">Sick Leave</div>
                            <div class="balance-info">
                                <span class="used">{{ $sickUsed }}</span> /
                                <span class="total">{{ $sickTotal }}</span> days
                                <div class="balance-bar">
                                    <div class="balance-fill 
    {{ $annualPercent < 50 ? 'low' : ($annualPercent < 80 ? 'medium' : 'high') }}"
                                        style="width: {{ $annualPercent }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="balance-item">
                            <div class="balance-type">Personal Leave</div>
                            <div class="balance-info">
                                <span class="used">{{ $personalUsed }}</span> /
                                <span class="total">{{ $personalTotal }}</span> days
                                <div class="balance-bar">
                                    <div class="balance-fill 
    {{ $annualPercent < 50 ? 'low' : ($annualPercent < 80 ? 'medium' : 'high') }}"
                                        style="width: {{ $annualPercent }}%">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>


                <!-- Admin's Leave Requests Table -->
                <div class="my-leave-table-container">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Dates</th>
                                <th>Reason</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($leaves as $leave)
                                <tr class="leave-row" data-id="{{ $leave->id }}"
                                    data-type="{{ strtolower($leave->leave_type) }}"
                                    data-status="{{ strtolower($leave->status) }}"
                                    data-start="{{ \Carbon\Carbon::parse($leave->start_date)->toDateString() }}"
                                    data-end="{{ \Carbon\Carbon::parse($leave->end_date)->toDateString() }}"
                                    data-department="{{ strtolower($leave->employee->department ?? '') }}">
                                    <td>
                                        <span class="leave-type-badge type-{{ strtolower($leave->leave_type) }}">
                                            {{ ucfirst($leave->leave_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="duration-cell">
                                            <span class="duration-days">{{ $leave['duration'] }}</span>
                                            <span
                                                class="duration-label">{{ $leave['duration'] == 1 ? 'day' : 'days' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date-from">
                                                {{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}</div>
                                            @if ($leave['start_date'] !== $leave['end_date'])
                                                <div class="date-to">to
                                                    {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="reason-cell">
                                            <p>{{ $leave['reason'] }}</p>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $leave['status'] }}">
                                            @if ($leave['status'] === 'approved')
                                                <i class="fas fa-check-circle"></i> Approved
                                            @elseif($leave['status'] === 'pending')
                                                <i class="fas fa-clock"></i> Pending
                                            @else
                                                <i class="fas fa-times-circle"></i> Rejected
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applied-cell">
                                            <span
                                                class="applied-date">{{ \Carbon\Carbon::parse($leave['applied_date'])->format('M d, Y') }}</span>
                                            <span
                                                class="applied-time">{{ \Carbon\Carbon::parse($leave['applied_date'])->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-view" onclick="viewMyLeaveDetails(this)"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            @if ($leave['status'] === 'pending')
                                                <button class="btn-action btn-edit" onclick="editMyLeave(this)"
                                                    title="Edit Request" data-id="{{ $leave->leave_id }}"
                                                    data-type="{{ $leave->leave_type }}"
                                                    data-start="{{ $leave->start_date }}"
                                                    data-end="{{ $leave->end_date }}" data-reason="{{ $leave->reason }}"
                                                    data-contact="{{ $leave->contact_info }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <form action="{{ route('admin.leaves.destroy', $leave) }}" method="POST"
                                                    style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn-action btn-cancel"
                                                        onclick="return confirm('Are you sure you want to cancel this leave request? This action cannot be undone.')"
                                                        title="Cancel Request">
                                                        <i class="fas fa-times"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pending Requests Tab -->
            <div id="pending-tab" class="tab-panel active">
                <div class="panel-header">
                    <h2><i class="fas fa-clock"></i> Pending Leave Requests</h2>
                    <div class="bulk-actions">
                        <button class="btn btn-success" onclick="bulkApprove()" disabled id="bulk-approve">
                            <i class="fas fa-check"></i> Approve Selected
                        </button>
                        <button class="btn btn-danger" onclick="bulkReject()" disabled id="bulk-reject">
                            <i class="fas fa-times"></i> Reject Selected
                        </button>
                    </div>
                </div>

                <div class="leave-table-container">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                                </th>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Dates</th>
                                <th>Reason</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pendingLeaves as $leave)
                                <tr class="leave-row" data-id="{{ $leave->leave_id }}"
                                    data-type="{{ strtolower($leave->leave_type) }}"
                                    data-department="{{ strtolower($leave->employee->department->department_name ?? '') }}"
                                    data-name="{{ strtolower($leave->employee->employee_name) }}"
                                    data-start="{{ \Carbon\Carbon::parse($leave->start_date)->toDateString() }}"
                                    data-end="{{ \Carbon\Carbon::parse($leave->end_date)->toDateString() }}"
                                    data-status="pending">
                                    <td>
                                        <input type="checkbox" class="leave-checkbox" value="{{ $leave->leave_id }}">
                                    </td>
                                    <td>
                                        <div class="employee-cell">
                                            <div class="employee-avatar">
                                                {{ strtoupper(substr($leave->employee->employee_name, 0, 1)) }}
                                            </div>
                                            <div class="employee-info">
                                                <h4>{{ $leave->employee->employee_name }}</h4>
                                                <p>{{ $leave->employee->employee_id }} •
                                                    {{ $leave->employee->department->department_name }}
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave->leave_type)) }}">
                                            {{ $leave->leave_type }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="duration-cell">
                                            <span class="duration-days">{{ $leave->duration }}</span>
                                            <span
                                                class="duration-label">{{ $leave->duration == 1 ? 'day' : 'days' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date-from">
                                                {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                            </div>
                                            @if ($leave->start_date !== $leave->end_date)
                                                <div class="date-to">to
                                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="reason-cell">
                                            <p>{{ \Str::limit($leave->reason, 50) }}</p>
                                            @if ($leave->supporting_doc)
                                                <span class="doc-indicator">
                                                    <i class="fas fa-paperclip"></i> Attachment
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="applied-cell">
                                            <span class="applied-date">
                                                {{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}
                                            </span>
                                            <span class="applied-time">
                                                {{ \Carbon\Carbon::parse($leave->created_at)->diffForHumans() }}
                                            </span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <div class="action-buttons">
                                                <button class="btn-action btn-view"
                                                    onclick="viewLeaveDetails('{{ $leave->leave_id }}')"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <form action="{{ route('admin.leaves.updateLeaveStatus', $leave->leave_id) }}"
                                                method="POST" style="display:inline;">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="status" value="approved">
                                                <button type="submit" class="btn-action btn-approve" title="Approve">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            </form>
                                            <button type="button" class="btn-action btn-reject"
                                                onclick="rejectLeave('{{ $leave->leave_id }}')" title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach


                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Approved Requests Tab -->
            <div id="approved-tab" class="tab-panel">
                <div class="panel-header">
                    <h2><i class="fas fa-check-circle"></i> Approved Leave Requests</h2>
                    <div class="header-actions">
                        <button class="btn btn-outline" onclick="exportApprovedReport()">
                            <i class="fas fa-download"></i> Export Approved
                        </button>
                    </div>
                </div>

                @if ($approvedLeaves->isEmpty())
                    <div class="approved-message">
                        <div class="empty-state">
                            <i class="fas fa-check-circle"></i>
                            <h3>No approved requests to display</h3>
                            <p>Approved requests will appear here once you process pending requests.</p>
                        </div>
                    </div>
                @else
                    <div class="leave-table-container">
                        <table class="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Duration</th>
                                    <th>Dates</th>
                                    <th>Reason</th>
                                    <th>Approved On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedLeaves as $leave)
                                    <tr class="leave-row" data-id="{{ $leave->leave_id }}"
                                        data-name="{{ strtolower($leave->employee->employee_name) }}"
                                        data-department="{{ strtolower($leave->employee->department->department_name ?? '') }}"
                                        data-type="{{ strtolower($leave->leave_type) }}" data-status="approved"
                                        data-start="{{ \Carbon\Carbon::parse($leave->start_date)->toDateString() }}"
                                        data-end="{{ \Carbon\Carbon::parse($leave->end_date)->toDateString() }}">
                                        <td>
                                            <div class="employee-cell">
                                                <div class="employee-avatar">
                                                    {{ strtoupper(substr($leave->employee->employee_name, 0, 1)) }}
                                                </div>
                                                <div class="employee-info">
                                                    <h4>{{ $leave->employee->employee_name }}</h4>
                                                    <p>{{ $leave->employee->employee_id }} •
                                                        {{ $leave->employee->department->department_name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave->leave_type)) }}">
                                                {{ $leave->leave_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="duration-cell">
                                                <span class="duration-days">{{ $leave->duration }}</span>
                                                <span
                                                    class="duration-label">{{ $leave->duration == 1 ? 'day' : 'days' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-cell">
                                                <div class="date-from">
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                                </div>
                                                @if ($leave->start_date !== $leave->end_date)
                                                    <div class="date-to">to
                                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reason-cell">
                                                <p>{{ \Str::limit($leave->reason, 50) }}</p>
                                                @if ($leave->supporting_doc)
                                                    <span class="doc-indicator">
                                                        <i class="fas fa-paperclip"></i> Attachment
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="applied-cell">
                                                <span class="applied-date">
                                                    {{ \Carbon\Carbon::parse($leave->updated_at)->format('M d, Y') }}
                                                </span>
                                                <span class="applied-time">
                                                    {{ \Carbon\Carbon::parse($leave->updated_at)->diffForHumans() }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-view"
                                                    onclick="viewLeaveDetails('{{ $leave->leave_id }}')"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Rejected Requests Tab -->
            <div id="rejected-tab" class="tab-panel">
                <div class="panel-header">
                    <h2><i class="fas fa-times-circle"></i> Rejected Leave Requests</h2>
                    <div class="header-actions">
                        <button class="btn btn-outline" onclick="exportRejectedReport()">
                            <i class="fas fa-download"></i> Export Rejected
                        </button>
                    </div>
                </div>

                @if ($rejectedLeaves->isEmpty())
                    <div class="rejected-message">
                        <div class="empty-state">
                            <i class="fas fa-times-circle"></i>
                            <h3>No rejected requests to display</h3>
                            <p>Rejected requests will appear here once you process pending requests.</p>
                        </div>
                    </div>
                @else
                    <div class="leave-table-container">
                        <table class="leave-table">
                            <thead>
                                <tr>
                                    <th>Employee</th>
                                    <th>Leave Type</th>
                                    <th>Duration</th>
                                    <th>Dates</th>
                                    <th>Reason</th>
                                    <th>Rejected On</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejectedLeaves as $leave)
                                    <tr class="leave-row" data-id="{{ $leave->leave_id }}"
                                        data-name="{{ strtolower($leave->employee->employee_name) }}"
                                        data-department="{{ strtolower($leave->employee->department->department_name ?? '') }}"
                                        data-type="{{ strtolower($leave->leave_type) }}" data-status="rejected"
                                        data-start="{{ \Carbon\Carbon::parse($leave->start_date)->toDateString() }}"
                                        data-end="{{ \Carbon\Carbon::parse($leave->end_date)->toDateString() }}">
                                        <td>
                                            <div class="employee-cell">
                                                <div class="employee-avatar">
                                                    {{ strtoupper(substr($leave->employee->employee_name, 0, 1)) }}
                                                </div>
                                                <div class="employee-info">
                                                    <h4>{{ $leave->employee->employee_name }}</h4>
                                                    <p>{{ $leave->employee->employee_id }} •
                                                        {{ $leave->employee->department->department_name }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span
                                                class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave->leave_type)) }}">
                                                {{ $leave->leave_type }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="duration-cell">
                                                <span class="duration-days">{{ $leave->duration }}</span>
                                                <span
                                                    class="duration-label">{{ $leave->duration == 1 ? 'day' : 'days' }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="date-cell">
                                                <div class="date-from">
                                                    {{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}
                                                </div>
                                                @if ($leave->start_date !== $leave->end_date)
                                                    <div class="date-to">to
                                                        {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="reason-cell">
                                                <p>{{ \Str::limit($leave->reason, 50) }}</p>
                                                @if ($leave->supporting_doc)
                                                    <span class="doc-indicator">
                                                        <i class="fas fa-paperclip"></i> Attachment
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <div class="applied-cell">
                                                <span class="applied-date">
                                                    {{ \Carbon\Carbon::parse($leave->updated_at)->format('M d, Y') }}
                                                </span>
                                                <span class="applied-time">
                                                    {{ \Carbon\Carbon::parse($leave->updated_at)->diffForHumans() }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="action-buttons">
                                                <button class="btn-action btn-view"
                                                    onclick="viewLeaveDetails('{{ $leave->leave_id }}')"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>


            <!-- All Requests Tab -->
            <div id="all-tab" class="tab-panel">
                <div class="panel-header">
                    <h2><i class="fas fa-list"></i> All Leave Requests</h2>
                    <div class="header-actions">
                        <button class="btn btn-outline" onclick="exportAllReport()">
                            <i class="fas fa-download"></i> Export All
                        </button>
                    </div>
                </div>

                <div class="all-requests-table">
                    <table class="leave-table">
                        <thead>
                            <tr>
                                <th>Employee</th>
                                <th>Leave Type</th>
                                <th>Duration</th>
                                <th>Dates</th>
                                <th>Status</th>
                                <th>Applied</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($employeeLeaves as $leave)
                                <tr class="leave-row" data-id="{{ $leave->leave_id }}"
                                    data-name="{{ $leave->employee->employee_name }}"
                                    data-empid="{{ $leave->employee->employee_id }}"
                                    data-department="{{ $leave->employee->department->department_name ?? '' }}"
                                    data-type="{{ $leave->leave_type }}" data-status="{{ $leave->status }}"
                                    data-start="{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}"
                                    data-end="{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}"
                                    data-duration="{{ $leave->duration }}" data-reason="{{ $leave->reason }}"
                                    data-contact="{{ $leave->emergency_contact }}">
                                    <td>
                                        <div class="employee-cell">
                                            <div class="employee-avatar">
                                                {{ strtoupper(substr($leave->employee->employee_name, 0, 1)) }}
                                            </div>
                                            <div class="employee-info">
                                                <h4>{{ $leave->employee->employee_name }}</h4>
                                                <p>{{ $leave->employee->employee_id }} <br />
                                                    {{ $leave->employee->department->department_name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span
                                            class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave['leave_type'])) }}">
                                            {{ $leave['leave_type'] }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="duration-cell">
                                            <span class="duration-days">{{ $leave['duration'] }}</span>
                                            <span
                                                class="duration-label">{{ $leave['duration'] == 1 ? 'day' : 'days' }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="date-cell">
                                            <div class="date-from">
                                                {{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}</div>
                                            @if ($leave['start_date'] !== $leave['end_date'])
                                                <div class="date-to">to
                                                    {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $leave['status'] }}">
                                            @if ($leave['status'] === 'approved')
                                                <i class="fas fa-check-circle"></i> Approved
                                            @elseif($leave['status'] === 'rejected')
                                                <i class="fas fa-times-circle"></i> Rejected
                                            @else
                                                <i class="fas fa-clock"></i> Pending
                                            @endif
                                        </span>
                                    </td>
                                    <td>
                                        <div class="applied-cell">
                                            <span
                                                class="applied-date">{{ \Carbon\Carbon::parse($leave['applied_date'])->format('M d, Y') }}</span>
                                            <span
                                                class="applied-time">{{ \Carbon\Carbon::parse($leave['applied_date'])->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn-action btn-view"
                                                onclick="viewLeaveDetails('{{ $leave->leave_id }}')"
                                                title="View Details">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Analytics Tab -->
            <div id="analytics-tab" class="tab-panel">
                <div class="analytics-grid">
                    <div class="analytics-card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-pie"></i> Leave Distribution</h3>
                        </div>
                        <div class="card-content" style="height:300px;"> <!-- add fixed height -->
                            <canvas id="leaveDistributionChart"></canvas>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-header">
                            <h3><i class="fas fa-chart-line"></i> Monthly Trends</h3>
                        </div>
                        <div class="card-content">
                            <canvas id="leaveTrendsChart" width="400" height="300"></canvas>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-header">
                            <h3><i class="fas fa-building"></i> Department-wise Leave Usage</h3>
                        </div>
                        <div class="card-content">
                            <div class="department-stats">
                                @foreach ($departmentStats as $dept)
                                    <div class="dept-stat-item">
                                        <div class="dept-info">
                                            <span class="dept-name">{{ $dept['name'] }}</span>
                                            <span class="dept-usage">{{ $dept['used'] }}/{{ $dept['total'] }} days
                                                used</span>
                                        </div>
                                        <div class="dept-progress">
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: {{ $dept['percentage'] }}%">
                                                </div>
                                            </div>
                                            <span class="progress-percent">{{ $dept['percentage'] }}%</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="analytics-card">
                        <div class="card-header">
                            <h3><i class="fas fa-trophy"></i> Quick Stats</h3>
                        </div>
                        <div class="card-content">
                            <div class="quick-stats">
                                <div class="quick-stat">
                                    <div class="stat-icon"><i class="fas fa-calendar-day"></i></div>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ $avgProcessingTime }}</span>
                                        <span class="stat-label">Avg Processing Time (hours)</span>
                                    </div>
                                </div>

                                <div class="quick-stat">
                                    <div class="stat-icon"><i class="fas fa-percentage"></i></div>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ $approvalRate }}%</span>
                                        <span class="stat-label">Approval Rate</span>
                                    </div>
                                </div>

                                <div class="quick-stat">
                                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ $employeesOnLeaveToday }}</span>
                                        <span class="stat-label">Employees on Leave Today</span>
                                    </div>
                                </div>

                                <div class="quick-stat">
                                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                                    <div class="stat-info">
                                        <span class="stat-value">{{ $overdueRequests }}</span>
                                        <span class="stat-label">Overdue Requests</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Apply for Leave Modal -->
    <div id="applyLeaveModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-calendar-plus"></i> Apply for Leave</h2>
                <button class="modal-close" onclick="closeApplyLeaveModal()">&times;</button>
            </div>
            <div class="modal-body">
                <form id="applyLeaveForm" method="POST" action="{{ route('leaves.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="leaveType" class="form-label">
                                    <i class="fas fa-tag"></i> Leave Type *
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-tag"></i>
                                </div>
                                <select id="leaveType" name="leave_type" class="form-select" required>
                                    <option value="">Select leave type</option>
                                    <option value="annual">Annual Leave</option>
                                    <option value="sick">Sick Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="maternity">Maternity Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group">
                                <label for="startDate" class="form-label">
                                    <i class="fas fa-calendar-alt"></i> Start Date *
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <input type="date" id="startDate" name="start_date" class="form-input" required
                                    min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="form-group" id="endDateGroup">
                                <label for="endDate" class="form-label">
                                    <i class="fas fa-calendar-check"></i> End Date *
                                </label>
                                <div class="input-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <input type="date" id="endDate" name="end_date" class="form-input" required
                                    min="{{ date('Y-m-d') }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <div class="duration-display">
                                <input type="hidden" id="duration" name="duration" value="0">
                                <span id="durationValue">0</span> day(s)
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="leaveReason" class="form-label">
                                <i class="fas fa-comment-alt"></i> Reason for Leave *
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-comment-alt"></i>
                            </div>
                            <textarea id="leaveReason" name="reason" class="form-textarea" rows="4"
                                placeholder="Please provide a detailed reason for your leave request..." required></textarea>
                        </div>

                        <div class="form-group">
                            <label for="emergencyContact" class="form-label">
                                <i class="fas fa-phone"></i> Emergency Contact
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-phone"></i>
                            </div>
                            <input type="tel" id="emergencyContact" name="contact_number" class="form-input"
                                placeholder="Emergency contact number">
                        </div>

                        <div class="form-group">
                            <label for="supportingDocument" class="form-label">
                                <i class="fas fa-paperclip"></i> Supporting Documents
                            </label>
                            <div class="file-upload-container">
                                <input type="file" id="supportingDocument" name="supporting_doc" class="file-input"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                                <label for="supportingDocument" class="file-upload-label">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span>Choose files or drag and drop</span>
                                    <small>PDF, DOC, DOCX, JPG, PNG (Max 10MB)</small>
                                </label>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" id="confirmDeclaration" name="confirm_declaration" required>
                                I declare that the information provided is true and accurate. I understand that any false
                                information may result in disciplinary action.
                            </label>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeApplyLeaveModal()">Cancel</button>
                <button type="submit" form="applyLeaveForm" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit Application
                </button>
            </div>
        </div>
    </div>


    <!-- Leave Details Modal -->
    <div id="leaveDetailsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-calendar-alt"></i> Leave Request Details</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <div class="modal-body">
                <div id="leaveDetailsContent">
                    <!-- Content will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal()">Close</button>
                <button class="btn btn-danger" onclick="rejectFromModal()">
                    <i class="fas fa-times"></i> Reject
                </button>
                <button class="btn btn-success" onclick="approveFromModal()">
                    <i class="fas fa-check"></i> Approve
                </button>
            </div>
        </div>
    </div>

    <!-- Rejection Reason Modal -->
    <div id="rejectionModal" class="modal" style="display:none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2><i class="fas fa-times-circle"></i> Reject Leave Request</h2>
                <button type="button" class="modal-close" onclick="closeRejectionModal()">&times;</button>
            </div>

            <form id="rejectionForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="status" value="rejected">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="rejectionReason">Reason for Rejection *</label>
                        <textarea id="rejectionReason" name="rejection_reason" class="form-control" rows="4"
                            placeholder="Please provide a clear reason for rejecting this leave request..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="notifyEmployee" name="notify_employee">
                            Send notification to employee
                        </label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeRejectionModal()">Cancel</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-times"></i> Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>


    <style>
        :root {
            /* RedCode Solutions Color Palette */
            --redcode-primary: #DC2626;
            --redcode-primary-dark: #991B1B;
            --redcode-primary-light: #FEE2E2;
            --redcode-accent: #B91C1C;
            --redcode-dark: #1F2937;
            --redcode-gray: #6B7280;
            --redcode-light: #F9FAFB;
            --redcode-white: #FFFFFF;
            --redcode-blue: #2563EB;
            --redcode-green: #059669;
            --redcode-orange: #D97706;
            --redcode-yellow: #F59E0B;
            --text-primary: #111827;
            --text-secondary: #6B7280;
            --text-light: #9CA3AF;
            --text-white: #FFFFFF;
            --bg-primary: #FFFFFF;
            --bg-secondary: #F9FAFB;
            --bg-dark: #1F2937;
            --border-light: #E5E7EB;
            --border-medium: #D1D5DB;
            --border-dark: #6B7280;
        }

        .leave-management-container {
            padding: 2rem;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            min-height: 100vh;
        }

        /* Header */
        .leave-header {
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

        /* Button Styles */
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
            background: linear-gradient(135deg, var(--redcode-gray), var(--redcode-dark));
            color: white;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
        }

        .btn-success {
            background: linear-gradient(135deg, var(--redcode-green), #047857);
            color: white;
            box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
        }

        .btn-danger {
            background: linear-gradient(135deg, #EF4444, #DC2626);
            color: white;
            box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
        }

        .btn-outline {
            background: white;
            color: var(--redcode-primary);
            border: 2px solid var(--redcode-primary);
        }

        .btn-outline:hover {
            background: var(--redcode-primary);
            color: white;
        }

        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Leave Stats Grid */
        .leave-stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .leave-stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .leave-stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .leave-stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: var(--redcode-primary);
        }

        .leave-stat-card.pending::before {
            background: var(--redcode-orange);
        }

        .leave-stat-card.approved::before {
            background: var(--redcode-green);
        }

        .leave-stat-card.rejected::before {
            background: #EF4444;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1rem;
            background: rgba(220, 38, 38, 0.1);
            color: var(--redcode-primary);
        }

        .pending .stat-icon {
            background: rgba(217, 119, 6, 0.1);
            color: var(--redcode-orange);
        }

        .approved .stat-icon {
            background: rgba(5, 150, 105, 0.1);
            color: var(--redcode-green);
        }

        .rejected .stat-icon {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .stat-content h3 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            line-height: 1;
        }

        .stat-content p {
            color: var(--text-light);
            font-size: 0.85rem;
            margin: 0;
        }

        .stat-trend {
            margin-top: 1rem;
            padding: 0.5rem 0.75rem;
            border-radius: 8px;
            font-size: 0.75rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .stat-trend.urgent {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .stat-trend.positive {
            background: rgba(5, 150, 105, 0.1);
            color: var(--redcode-green);
        }

        .stat-trend.neutral {
            background: rgba(107, 114, 128, 0.1);
            color: var(--redcode-gray);
        }

        /* Leave Tabs */
        .leave-tabs {
            margin-bottom: 2rem;
        }

        .tab-nav {
            display: flex;
            background: white;
            border-radius: 12px;
            padding: 0.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            overflow-x: auto;
        }

        .tab-btn {
            padding: 0.75rem 1.5rem;
            border: none;
            background: transparent;
            color: var(--text-secondary);
            font-weight: 600;
            font-size: 0.9rem;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            white-space: nowrap;
            position: relative;
        }

        .tab-btn:hover {
            background: rgba(220, 38, 38, 0.05);
            color: var(--redcode-primary);
        }

        .tab-btn.active {
            background: var(--redcode-primary);
            color: white;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
        }

        .tab-badge {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            padding: 0.25rem 0.5rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 700;
            min-width: 20px;
            text-align: center;
        }

        .tab-btn:not(.active) .tab-badge {
            background: var(--redcode-primary);
            color: white;
        }

        /* Tab Content */
        .tab-content {
            margin-bottom: 2rem;
        }

        .tab-panel {
            display: none;
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .tab-panel.active {
            display: block;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        .panel-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .panel-header h2 i {
            color: var(--redcode-primary);
        }

        /* Leave Filters */
        .leave-filters {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .filter-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .filter-group label {
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .filter-select,
        .filter-input {
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 0.9rem;
            background: rgba(248, 250, 252, 0.5);
            transition: all 0.3s ease;
            color: var(--text-primary);
        }

        .filter-select:focus,
        .filter-input:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Leave Table */
        .leave-table-container {
            overflow-x: auto;
            border-radius: 12px;
            border: 1px solid var(--border-light);
        }

        .leave-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
        }

        .leave-table th,
        .leave-table td {
            padding: 1rem 1.5rem;
            text-align: left;
            border-bottom: 1px solid var(--border-light);
        }

        .leave-table th {
            background: rgba(248, 250, 252, 0.8);
            font-weight: 700;
            color: var(--text-primary);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .leave-table tbody tr {
            transition: all 0.3s ease;
        }

        .leave-table tbody tr:hover {
            background: rgba(220, 38, 38, 0.02);
        }

        /* Table Cell Styles */
        .employee-cell {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .employee-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.9rem;
        }

        .employee-info h4 {
            font-size: 0.95rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0 0 0.25rem 0;
        }

        .employee-info p {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .duration-cell {
            text-align: center;
        }

        .duration-days {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--redcode-primary);
            line-height: 1;
        }

        .duration-label {
            font-size: 0.75rem;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .date-cell {
            font-size: 0.85rem;
        }

        .date-from {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .date-to {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        .reason-cell p {
            margin: 0 0 0.5rem 0;
            color: var(--text-primary);
            font-size: 0.85rem;
            line-height: 1.4;
        }

        .doc-indicator {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
            padding: 0.25rem 0.5rem;
            background: rgba(5, 150, 105, 0.1);
            color: var(--redcode-green);
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 12px;
        }

        .applied-cell {
            text-align: center;
        }

        .applied-date {
            display: block;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .applied-time {
            color: var(--text-secondary);
            font-size: 0.75rem;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }

        .btn-action {
            width: 36px;
            height: 36px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            font-size: 0.85rem;
        }

        .btn-view {
            background: rgba(37, 99, 235, 0.1);
            color: var(--redcode-blue);
        }

        .btn-view:hover {
            background: var(--redcode-blue);
            color: white;
            transform: scale(1.1);
        }

        .btn-approve {
            background: rgba(5, 150, 105, 0.1);
            color: var(--redcode-green);
        }

        .btn-approve:hover {
            background: var(--redcode-green);
            color: white;
            transform: scale(1.1);
        }

        .btn-reject {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .btn-reject:hover {
            background: #EF4444;
            color: white;
            transform: scale(1.1);
        }

        /* Leave Type Badges */
        .leave-type-badge {
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .type-annual-leave {
            background: rgba(37, 99, 235, 0.1);
            color: var(--redcode-blue);
        }

        .type-sick-leave {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        .type-personal-leave {
            background: rgba(217, 119, 6, 0.1);
            color: var(--redcode-orange);
        }

        .type-maternity-leave {
            background: rgba(168, 85, 247, 0.1);
            color: #A855F7;
        }

        .type-emergency-leave {
            background: rgba(239, 68, 68, 0.1);
            color: #EF4444;
        }

        /* Bulk Actions */
        .bulk-actions {
            display: flex;
            gap: 1rem;
        }

        .bulk-actions .btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
        }

        /* Analytics Section */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 2rem;
        }

        .analytics-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .analytics-card .card-header {
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-light);
        }

        .analytics-card .card-header h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0;
        }

        .analytics-card .card-header h3 i {
            color: var(--redcode-primary);
        }

        /* Department Stats */
        .department-stats {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .dept-stat-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            background: rgba(248, 250, 252, 0.5);
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .dept-info {
            flex: 1;
        }

        .dept-name {
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .dept-usage {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        .dept-progress {
            display: flex;
            align-items: center;
            gap: 1rem;
            flex: 1;
            max-width: 200px;
        }

        .progress-bar {
            flex: 1;
            height: 8px;
            background: var(--border-light);
            border-radius: 4px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-accent));
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        .progress-percent {
            font-weight: 600;
            color: var(--redcode-primary);
            font-size: 0.85rem;
            min-width: 40px;
            text-align: right;
        }

        /* Quick Stats */
        .quick-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .quick-stat {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: rgba(248, 250, 252, 0.5);
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .quick-stat .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(220, 38, 38, 0.1);
            color: var(--redcode-primary);
            font-size: 1.2rem;
        }

        .quick-stat .stat-info {
            flex: 1;
        }

        .quick-stat .stat-value {
            display: block;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            line-height: 1;
        }

        .quick-stat .stat-label {
            color: var(--text-secondary);
            font-size: 0.8rem;
            margin-top: 0.25rem;
            display: block;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(8px);
        }

        .modal-content {
            background: white;
            margin: 2% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 700px;
            max-height: 95vh;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
            border: 1px solid var(--border-light);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        .modal-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            position: relative;
            flex-shrink: 0;
        }

        .modal-header h2 {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-header h2 i {
            color: var(--redcode-primary);
        }

        .modal-close {
            position: absolute;
            top: 1rem;
            right: 1.5rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-secondary);
            transition: all 0.3s ease;
        }

        .modal-close:hover {
            color: var(--redcode-primary);
            transform: scale(1.1);
        }

        .modal-body {
            padding: 2rem;
            overflow-y: auto;
            flex: 1;
            max-height: calc(95vh - 140px);
            /* Subtract header and footer height */
        }

        /* Custom scrollbar for modal */
        .modal-body::-webkit-scrollbar {
            width: 8px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: var(--redcode-primary);
            border-radius: 4px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: var(--redcode-primary-dark);
        }

        /* Form styling within modal */
        .modal-body .form-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .modal-body .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
        }

        .modal-body .form-group {
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .modal-body .form-label {
            margin-bottom: 0.75rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .modal-body .form-input,
        .modal-body .form-select,
        .modal-body .form-textarea {
            width: 100%;
            padding: 12px 16px 12px 48px;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 0.9rem;
            background: rgba(248, 250, 252, 0.5);
            transition: all 0.3s ease;
            color: var(--text-primary);
            box-sizing: border-box;
        }

        .modal-body .form-input:focus,
        .modal-body .form-select:focus,
        .modal-body .form-textarea:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .modal-body .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            font-size: 1rem;
            pointer-events: none;
            z-index: 2;
            margin-top: 12px;
            /* Account for label height */
        }

        .modal-body .form-textarea {
            min-height: 100px;
            resize: vertical;
            padding-top: 16px;
        }

        .modal-body .form-group:has(.form-textarea) .input-icon {
            top: 40px;
            transform: none;
        }

        /* Mobile responsive */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                margin: 1% auto;
                max-height: 98vh;
            }

            .modal-body .form-row {
                grid-template-columns: 1fr;
            }

            .modal-body {
                padding: 1.5rem;
                max-height: calc(98vh - 120px);
            }
        }

        .modal-footer {
            padding: 1rem 2rem;
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
            border-top: 1px solid var(--border-light);
            background: white;
            flex-shrink: 0;
        }

        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid var(--border-light);
        }

        /* Form Controls */
        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            font-size: 0.9rem;
            transition: all 0.3s ease;
            background: rgba(248, 250, 252, 0.5);
            color: var(--text-primary);
            resize: vertical;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        /* Leave Balance Card */
        .leave-balance-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            margin-bottom: 2rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
        }

        .balance-header h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin: 0 0 1.5rem 0;
        }

        .balance-header h3 i {
            color: var(--redcode-primary);
        }

        .balance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .balance-item {
            padding: 1.5rem;
            background: rgba(248, 250, 252, 0.5);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .balance-item:hover {
            background: rgba(220, 38, 38, 0.02);
            border-color: rgba(220, 38, 38, 0.1);
            transform: translateY(-2px);
        }

        .balance-type {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1rem;
            font-size: 0.9rem;
        }

        .balance-info {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .balance-info .used {
            color: var(--redcode-primary);
            font-weight: 700;
        }

        .balance-info .total {
            color: var(--text-primary);
            font-weight: 600;
        }

        .balance-bar {
            height: 8px;
            background: var(--border-light);
            border-radius: 4px;
            overflow: hidden;
            margin-top: 0.5rem;
        }

        .balance-fill {
            height: 100%;
            background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-accent));
            border-radius: 4px;
            transition: width 0.3s ease;
        }

        /* Radio Button Styles */
        .radio-group {
            display: flex;
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .radio-label {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 8px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
            background: rgba(248, 250, 252, 0.5);
        }

        .radio-label:hover {
            border-color: var(--redcode-primary);
            background: rgba(220, 38, 38, 0.02);
        }

        .radio-label input[type="radio"] {
            margin: 0;
            accent-color: var(--redcode-primary);
        }

        .radio-label input[type="radio"]:checked+.radio-label,
        .radio-label:has(input[type="radio"]:checked) {
            border-color: var(--redcode-primary);
            background: rgba(220, 38, 38, 0.1);
            color: var(--redcode-primary);
            font-weight: 600;
        }

        /* Checkbox Styles */
        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            font-size: 0.9rem;
            line-height: 1.5;
            color: var(--text-secondary);
        }

        .checkbox-label input[type="checkbox"] {
            margin: 0;
            margin-top: 0.2rem;
            accent-color: var(--redcode-primary);
            transform: scale(1.2);
        }

        /* File Upload Styles */
        .file-upload-container {
            position: relative;
        }

        .file-input {
            position: absolute;
            opacity: 0;
            width: 100%;
            height: 100%;
            cursor: pointer;
            z-index: 1;
        }

        .file-upload-label {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 2rem;
            border: 2px dashed var(--border-medium);
            border-radius: 8px;
            background: rgba(248, 250, 252, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
        }

        .file-upload-label:hover {
            border-color: var(--redcode-primary);
            background: rgba(220, 38, 38, 0.02);
        }

        .file-upload-label i {
            font-size: 2rem;
            color: var(--redcode-primary);
        }

        .file-upload-label span {
            font-weight: 600;
            color: var(--text-primary);
        }

        .file-upload-label small {
            color: var(--text-secondary);
            font-size: 0.8rem;
        }

        /* Edit Button Styles */
        .btn-edit {
            background: rgba(245, 158, 11, 0.1);
            color: var(--redcode-orange);
        }

        .btn-edit:hover {
            background: var(--redcode-orange);
            color: white;
            transform: scale(1.1);
        }

        .btn-cancel {
            background: rgba(107, 114, 128, 0.1);
            color: var(--redcode-gray);
        }

        .btn-cancel:hover {
            background: var(--redcode-gray);
            color: white;
            transform: scale(1.1);
        }

        /* Notification Styles */
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            z-index: 1001;
            animation: slideIn 0.3s ease;
        }

        .notification-success {
            background: var(--redcode-green);
        }

        .notification-error {
            background: #EF4444;
        }

        .notification-warning {
            background: var(--redcode-orange);
        }

        .notification-info {
            background: var(--redcode-blue);
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }

            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .badge {
            font-weight: 600;
            letter-spacing: 0.02em;
            display: inline-block;
        }

        .form-input,
        .form-select {
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            padding: 0.5rem 1rem;
            font-size: 1rem;
            background: #f9fafb;
            transition: border 0.2s, box-shadow 0.2s;
        }

        .form-input:focus,
        .form-select:focus {
            border-color: #2563eb;
            outline: none;
            box-shadow: 0 0 0 2px #2563eb22;
        }

        .flex {
            display: flex;
        }

        .gap-1 {
            gap: 0.25rem;
        }

        .gap-2 {
            gap: 0.75rem;
        }

        .gap-3 {
            gap: 1.25rem;
        }

        .justify-between {
            justify-content: space-between;
        }

        .items-center {
            align-items: center;
        }

        .text-center {
            text-align: center;
        }

        .mt-4 {
            margin-top: 1.5rem;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }

        .table-container {
            overflow-x: auto;
            border-radius: 0.75rem;
            box-shadow: 0 1px 8px 0 rgba(0, 0, 0, 0.03);
            background: #fff;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 0.97rem;
        }

        .table th,
        .table td {
            padding: 1rem 1.25rem;
            text-align: left;
            vertical-align: middle;
        }

        .table th {
            background: #f8fafc;
            color: #374151;
            font-weight: 700;
            border-bottom: 2px solid #e5e7eb;
        }

        .table tr {
            transition: background 0.15s;
        }

        .table tbody tr:hover {
            background: #f1f5f9;
        }

        .user-avatar {
            background: linear-gradient(135deg, #6366f1 40%, #2563eb 100%);
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            box-shadow: 0 2px 8px 0 rgba(99, 102, 241, 0.08);
        }

        .card-body>div[style*="display: grid"]>.card {
            border: 1px solid #f3f4f6;
            box-shadow: 0 1px 8px 0 rgba(0, 0, 0, 0.03);
            transition: box-shadow 0.2s;
        }

        .card-body>div[style*="display: grid"]>.card:hover {
            box-shadow: 0 4px 24px 0 rgba(37, 99, 235, 0.08);
        }

        .card-body h4,
        .card-body h2 {
            margin: 0;
            font-weight: 700;
            color: #1e293b;
        }

        .card-body p {
            color: #64748b;
            margin-bottom: 1rem;
            line-height: 1.7;
        }

        .balance-fill.low {
            background: #16a34a;
        }

        /* green */
        .balance-fill.medium {
            background: #facc15;
        }

        /* yellow */
        .balance-fill.high {
            background: #dc2626;
        }

        /* red */

        @media (max-width: 900px) {

            .card-body,
            .card-header {
                padding: 1rem;
            }

            .table th,
            .table td {
                padding: 0.75rem 0.5rem;
            }
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

        /* RedCode Solutions Color Palette - Matching Project Modal */
        :root {
            --redcode-primary: #DC2626;
            /* RedCode Brand Red */
            --redcode-primary-dark: #991B1B;
            /* Deep Red */
            --redcode-primary-light: #FEE2E2;
            /* Light Red Background */
            --redcode-accent: #B91C1C;
            /* Accent Red */
            --redcode-dark: #1F2937;
            /* Charcoal for headers/nav */
            --redcode-gray: #6B7280;
            /* Medium Gray for text */
            --redcode-light: #F9FAFB;
            /* Light Background */
            --redcode-white: #FFFFFF;
            /* Pure White */
            --redcode-blue: #2563EB;
            /* Links, buttons */
            --redcode-green: #059669;
            /* Success states */
            --redcode-orange: #D97706;
            /* Warnings */
            --redcode-yellow: #F59E0B;
            /* Alerts */
            --text-primary: #111827;
            /* Almost Black */
            --text-secondary: #6B7280;
            /* Medium Gray */
            --text-light: #9CA3AF;
            /* Light Gray */
            --text-white: #FFFFFF;
            /* White Text */
            --border-light: #E5E7EB;
            --border-medium: #D1D5DB;
            --border-dark: #6B7280;
            --gradient-primary: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            --gradient-hero: linear-gradient(135deg, #DC2626 0%, #1F2937 50%, #991B1B 100%);
            --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
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
                0 32px 64px rgba(220, 38, 38, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.05),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
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
            color: #111827;
            font-size: 0.875rem;
            letter-spacing: 0.025em;
        }

        .form-label i {
            margin-right: 0.5rem;
            color: var(--redcode-primary);
        }

        .form-input,
        .form-select,
        .form-textarea {
            width: 100%;
            padding: 12px 16px 12px 48px;
            /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
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

        /* Enhanced styling for edit modal fields with data - matching project modal spacing */
        .form-input:not(:placeholder-shown),
        .form-select:not([value=""]),
        .form-textarea:not(:placeholder-shown) {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Edit modal populated field styling - exact match with project modal */
        #editLeaveModal .form-input[data-populated="true"],
        #editLeaveModal .form-select[data-populated="true"],
        #editLeaveModal .form-textarea[data-populated="true"] {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--text-primary);
            font-weight: 600;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.08);
            padding: 12px 16px 12px 48px;
            /* Maintain consistent spacing with project modal */
        }

        /* Enhanced icon styling for edit modal populated fields */
        #editLeaveModal .form-group:has(.form-input[data-populated="true"]) .input-icon,
        #editLeaveModal .form-group:has(.form-select[data-populated="true"]) .input-icon,
        #editLeaveModal .form-group:has(.form-textarea[data-populated="true"]) .input-icon {
            color: var(--redcode-green);
            transform: translateY(-50%) scale(1.05);
            text-shadow: 0 0 8px rgba(16, 185, 129, 0.3);
        }

        .form-textarea {
            min-height: 100px;
            resize: vertical;
            font-family: inherit;
            padding: 16px 16px 16px 48px;
            /* Slightly more top padding for better icon alignment */
            align-items: flex-start;
            line-height: 1.5;
        }

        /* Special positioning for textarea icons */
        .form-group:has(.form-textarea) .input-icon {
            top: 24px;
            /* Position icon in the top area of textarea instead of center */
            transform: translateY(0);
            /* Remove center transform for textarea */
        }

        .form-input:focus,
        .form-select:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--redcode-primary);
            background: rgba(255, 255, 255, 0.9);
            box-shadow:
                0 0 0 4px rgba(220, 38, 38, 0.08),
                0 8px 25px rgba(220, 38, 38, 0.12);
            transform: translateY(-2px);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            /* Icon Position: 16px from left */
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-light);
            transition: all 0.3s;
            z-index: 3;
            pointer-events: none;
            font-size: 1rem;
            width: 16px;
            /* Icon Width: 16px */
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
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
            border-top: 1px solid #e5e7eb;
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
                0 8px 25px rgba(220, 38, 38, 0.18),
                0 3px 10px rgba(153, 27, 27, 0.12);
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .btn-primary:hover::before {
            left: 100%;
        }

        .btn-primary:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow:
                0 15px 35px rgba(220, 38, 38, 0.22),
                0 5px 15px rgba(153, 27, 27, 0.18);
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

        .btn-warning {
            background: linear-gradient(135deg, var(--redcode-orange) 0%, var(--redcode-yellow) 100%);
            color: white;
            box-shadow:
                0 8px 25px rgba(217, 119, 6, 0.18),
                0 3px 10px rgba(245, 158, 11, 0.12);
        }

        .btn-warning:hover {
            transform: translateY(-2px) scale(1.02);
            box-shadow:
                0 15px 35px rgba(217, 119, 6, 0.22),
                0 5px 15px rgba(245, 158, 11, 0.18);
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
            background: rgba(220, 38, 38, 0.2);
            border-radius: 4px;
        }

        .modal-container::-webkit-scrollbar-thumb:hover {
            background: rgba(220, 38, 38, 0.4);
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
            padding: 12px 16px 12px 48px;
            /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
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
            top: 24px;
            /* Position icon in the top area of textarea instead of center */
            transform: translateY(0);
            /* Remove center transform for textarea */
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

        .view-field.status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            color: var(--redcode-orange);
        }

        .view-field.status-badge.approved {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: var(--redcode-green);
        }

        .view-field.status-badge.rejected {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: var(--redcode-primary);
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

            .modal-header,
            .modal-body {
                padding: 1.5rem;
            }

            .modal-title {
                font-size: 1.5rem;
            }

            .form-actions {
                flex-direction: column;
            }

            /* Empty States */
            .empty-state {
                text-align: center;
                padding: 3rem 2rem;
                color: var(--text-secondary);
            }

            .empty-state i {
                font-size: 4rem;
                color: var(--border-medium);
                margin-bottom: 1rem;
            }

            .empty-state h3 {
                font-size: 1.25rem;
                font-weight: 600;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
            }

            .empty-state p {
                font-size: 1rem;
                margin: 0;
            }

            /* Status Badges */
            .status-badge {
                padding: 0.25rem 0.75rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 600;
                text-transform: uppercase;
                display: inline-flex;
                align-items: center;
                gap: 0.25rem;
            }

            .status-approved {
                background: #ECFDF5;
                color: #065F46;
            }

            .status-rejected {
                background: #FEE2E2;
                color: #991B1B;
            }

            .status-pending {
                background: #FEF3C7;
                color: #92400E;
            }
        }
        }
    </style>
    <script>
        window.leaveLabels = @json($leaveLabels);
        window.leaveData = @json($leaveData);
        window.trendLabels = @json($trendLabels);
        window.trendData = @json($trendData);
    </script>

    <script>
        // Initialize leave management
        document.addEventListener('DOMContentLoaded', function() {
            setupTabs();
            setupTableFeatures();
            setupFilters();
            initializeCharts();
        });

        // Tab functionality
        function setupTabs() {
            const tabs = document.querySelectorAll('.tab-btn');
            tabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const targetTab = this.dataset.tab;
                    switchTab(targetTab);
                });
            });
        }

        function switchTab(tabName) {
            // Remove active class from all tabs and panels
            document.querySelectorAll('.tab-btn').forEach(tab => tab.classList.remove('active'));
            document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

            // Add active class to selected tab and panel
            document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
            document.getElementById(`${tabName}-tab`).classList.add('active');
        }

        // Table functionality
        function setupTableFeatures() {
            const selectAllCheckbox = document.getElementById('select-all');
            if (selectAllCheckbox) {
                selectAllCheckbox.addEventListener('change', toggleSelectAll);
            }

            const checkboxes = document.querySelectorAll('.leave-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.addEventListener('change', updateBulkActions);
            });
        }

        function toggleSelectAll() {
            const selectAllCheckbox = document.getElementById('select-all');
            const checkboxes = document.querySelectorAll('.leave-checkbox');

            checkboxes.forEach(checkbox => {
                checkbox.checked = selectAllCheckbox.checked;
            });

            updateBulkActions();
        }

        function updateBulkActions() {
            const checkedBoxes = document.querySelectorAll('.leave-checkbox:checked');
            const bulkButtons = ['bulk-approve', 'bulk-reject'];

            bulkButtons.forEach(buttonId => {
                const button = document.getElementById(buttonId);
                if (button) {
                    button.disabled = checkedBoxes.length === 0;
                }
            });
        }

        // Filter functionality
        function setupFilters() {
            const searchInput = document.getElementById('search-input');
            if (searchInput) {
                searchInput.addEventListener('input', debounce(applyFilters, 300));
            }
        }

        function applyFilters() {
            const dateFilter = document.getElementById('date-filter')?.value || '';
            const departmentFilter = document.getElementById('department-filter')?.value.toLowerCase() || '';
            const leaveTypeFilter = document.getElementById('leave-type-filter')?.value.toLowerCase() || '';
            const search = document.getElementById('search-input')?.value.toLowerCase() || '';

            console.log('Applying filters:', {
                dateFilter,
                departmentFilter,
                leaveTypeFilter,
                search
            });

            const today = new Date();
            const startOfWeek = new Date(today);
            startOfWeek.setDate(today.getDate() - today.getDay());
            startOfWeek.setHours(0, 0, 0, 0);
            const endOfWeek = new Date(startOfWeek);
            endOfWeek.setDate(startOfWeek.getDate() + 6);
            endOfWeek.setHours(23, 59, 59, 999);

            const startOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
            const endOfMonth = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            endOfMonth.setHours(23, 59, 59, 999);

            const startOfQuarter = new Date(today.getFullYear(), Math.floor(today.getMonth() / 3) * 3, 1);
            const endOfQuarter = new Date(startOfQuarter.getFullYear(), startOfQuarter.getMonth() + 3, 0);
            endOfQuarter.setHours(23, 59, 59, 999);

            const rows = document.querySelectorAll('.leave-row');
            let filteredData = [];
            let visibleCount = 0;
            const processedLeaveIds = new Set();

            rows.forEach(row => {
                const leaveId = row.dataset.leaveId || row.dataset.id || row.id;

                if (leaveId && processedLeaveIds.has(leaveId)) {
                    console.warn('Duplicate leave ID found, skipping:', leaveId);
                    row.style.display = 'none';
                    return;
                }
                const employeeNameElement = row.querySelector('.employee-info h4') ||
                    row.querySelector('.employee-name') ||
                    row.querySelector('[data-employee]');
                const employeeName = employeeNameElement?.textContent.trim() ||
                    row.dataset.employee?.trim() || '';
                let department = row.dataset.department?.trim() || '';
                if (department.startsWith('{')) {
                    try {
                        const parsed = JSON.parse(row.dataset.department);
                        department = parsed.department_name?.trim() || '';
                    } catch (e) {
                        console.warn('Invalid department JSON:', row.dataset.department);
                    }
                }

                const leaveType = row.dataset.type?.toLowerCase().trim() || '';
                const status = row.dataset.status?.toLowerCase().trim() || '';
                let leaveStart, leaveEnd;
                try {
                    leaveStart = row.dataset.start ? new Date(row.dataset.start + 'T00:00:00') : new Date();
                    leaveEnd = row.dataset.end ? new Date(row.dataset.end + 'T23:59:59') : new Date();

                    if (isNaN(leaveStart.getTime()) || isNaN(leaveEnd.getTime())) {
                        throw new Error('Invalid date');
                    }
                } catch (e) {
                    console.warn('Invalid date format in row:', row.dataset);
                    leaveStart = new Date();
                    leaveEnd = new Date();
                }

                // Apply filters
                const nameMatch = !search || employeeName.toLowerCase().includes(search);
                const deptMatch = !departmentFilter || department.toLowerCase().includes(departmentFilter);
                const typeMatch = !leaveTypeFilter || leaveType.includes(leaveTypeFilter);

                let dateMatch = true;
                if (dateFilter === 'today') {
                    dateMatch = leaveStart <= today && leaveEnd >= today;
                } else if (dateFilter === 'week') {
                    dateMatch = leaveStart <= endOfWeek && leaveEnd >= startOfWeek;
                } else if (dateFilter === 'month') {
                    dateMatch = leaveStart <= endOfMonth && leaveEnd >= startOfMonth;
                } else if (dateFilter === 'quarter') {
                    dateMatch = leaveStart <= endOfQuarter && leaveEnd >= startOfQuarter;
                }

                const visible = nameMatch && deptMatch && typeMatch && dateMatch;
                row.style.display = visible ? '' : 'none';

                if (visible) {
                    // Mark this leave ID as processed
                    if (leaveId) {
                        processedLeaveIds.add(leaveId);
                    }

                    visibleCount++;

                    // Get duration from dataset first, then calculate if needed
                    let finalDuration;
                    if (row.dataset.duration && !isNaN(parseInt(row.dataset.duration))) {
                        finalDuration = parseInt(row.dataset.duration);
                    } else {
                        // Calculate duration
                        const diffTime = Math.abs(leaveEnd - leaveStart);
                        finalDuration = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                    }

                    console.log(
                        `Processing leave ID ${leaveId}: ${employeeName} - ${department} - ${finalDuration} days`
                    );

                    filteredData.push({
                        leaveId: leaveId, // Include leave ID for tracking
                        employee: employeeName,
                        department: department,
                        type: leaveType,
                        status: status,
                        start: leaveStart,
                        end: leaveEnd,
                        duration: finalDuration
                    });
                }
            });

            console.log(`Filter results: ${visibleCount} rows visible, ${filteredData.length} unique data points`);
            console.log('Processed leave IDs:', Array.from(processedLeaveIds));

            // Update charts with filtered data
            updateCharts(filteredData);

            // Show notification
            if (typeof showNotification === 'function') {
                showNotification(`Filters applied! Showing ${visibleCount} results.`, 'success');
            } else {
                console.log(`Filters applied! Showing ${visibleCount} results.`);
            }
        }

        function clearFilters() {
            document.getElementById('date-filter').value = 'month';
            document.getElementById('department-filter').value = '';
            document.getElementById('leave-type-filter').value = '';
            document.getElementById('search-input').value = '';

            document.querySelectorAll('.leave-row').forEach(row => {
                row.style.display = '';
            });

            showNotification('Filters cleared!', 'info');
        }

        // Leave actions
        function viewLeaveDetails(id) {
            showNotification(`Loading leave details...`, 'info');

            setTimeout(() => {
                const modal = document.getElementById('leaveDetailsModal');
                modal.dataset.currentId = id;

                // get the row using the id
                const row = document.querySelector(`tr[data-id="${id}"]`);
                if (!row) return;

                const content = document.getElementById('leaveDetailsContent');

                content.innerHTML = `
            <div class="leave-details">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-user"></i> Employee Name</label>
                            <div class="view-field">${row.dataset.name}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-id-card"></i> Employee ID</label>
                            <div class="view-field">${row.dataset.empid}</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar-alt"></i> Leave Type</label>
                            <div class="view-field">${row.dataset.type}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-building"></i> Department</label>
                            <div class="view-field">${row.dataset.department}</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar"></i> Start Date</label>
                            <div class="view-field">${row.dataset.start}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-calendar"></i> End Date</label>
                            <div class="view-field">${row.dataset.end}</div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-clock"></i> Duration</label>
                            <div class="view-field">${row.dataset.duration} days</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                            <div class="view-field status-badge ${row.dataset.status}">
                                ${row.dataset.status.charAt(0).toUpperCase() + row.dataset.status.slice(1)}
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-comment"></i> Reason</label>
                        <div class="view-field view-textarea">${row.dataset.reason ?? '-'}</div>
                    </div>
                    <div class="form-group">
                        <label class="form-label"><i class="fas fa-phone"></i> Emergency Contact</label>
                        <div class="view-field">${row.dataset.contact ?? '-'}</div>
                    </div>
                </div>
            </div>
        `;

                modal.style.display = 'block';
                showNotification('Leave details loaded!', 'success');
            }, 800);
        }


        function approveLeave(id) {
            showNotification(`Approving leave request ${id}...`, 'info');

            setTimeout(() => {
                const row = document.querySelector(`[data-id="${id}"]`);
                if (row) {
                    row.style.background = '#ECFDF5';
                    setTimeout(() => {
                        row.remove();
                    }, 1000);
                }
                showNotification('Leave request approved successfully!', 'success');
                updateStatCards();
            }, 1000);
        }

        function rejectLeave(id) {
            const modal = document.getElementById('rejectionModal');
            const form = document.getElementById('rejectionForm');

            form.action = `/admin/leaves/${id}/status`;
            modal.style.display = 'block';
        }

        // Modal functions
        function closeModal() {
            document.getElementById('leaveDetailsModal').style.display = 'none';
        }

        function closeRejectionModal() {
            const modal = document.getElementById('rejectionModal');
            modal.style.display = 'none';
            document.getElementById('rejectionReason').value = '';
        }

        function approveFromModal() {
            const currentId = document.getElementById('leaveDetailsModal').dataset.currentId;
            if (currentId) {
                approveLeave(parseInt(currentId));
                closeModal();
            }
        }

        function rejectFromModal() {
            const currentId = document.getElementById('leaveDetailsModal').dataset.currentId;
            if (currentId) {
                closeModal();
                rejectLeave(parseInt(currentId));
            }
        }

        // Bulk actions
        function bulkApprove() {
            const checkedBoxes = document.querySelectorAll('.leave-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            showNotification(`Approving ${checkedBoxes.length} leave requests...`, 'info');

            setTimeout(() => {
                checkedBoxes.forEach(checkbox => {
                    const row = checkbox.closest('.leave-row');
                    row.style.background = '#ECFDF5';
                    setTimeout(() => row.remove(), 1000);
                });
                showNotification(`${checkedBoxes.length} leave requests approved successfully!`, 'success');
                updateStatCards();
            }, 1500);
        }

        function bulkReject() {
            const checkedBoxes = document.querySelectorAll('.leave-checkbox:checked');
            if (checkedBoxes.length === 0) return;

            if (confirm(`Are you sure you want to reject ${checkedBoxes.length} leave requests?`)) {
                showNotification(`Rejecting ${checkedBoxes.length} leave requests...`, 'warning');

                setTimeout(() => {
                    checkedBoxes.forEach(checkbox => {
                        const row = checkbox.closest('.leave-row');
                        row.style.background = '#FEE2E2';
                        setTimeout(() => row.remove(), 1000);
                    });
                    showNotification(`${checkedBoxes.length} leave requests rejected!`, 'success');
                    updateStatCards();
                }, 1500);
            }
        }

        // Other functions
        function refreshLeaves() {
            showNotification('Refreshing leave requests...', 'info');
            setTimeout(() => {
                showNotification('Leave requests refreshed!', 'success');
                location.reload();
            }, 1000);
        }

        function exportLeaveReport() {
            showNotification('Generating leave report...', 'info');
            setTimeout(() => {
                showNotification('Leave report exported successfully!', 'success');
            }, 2000);
        }

        function exportApprovedReport() {
            showNotification('Generating approved requests report...', 'info');
            setTimeout(() => {
                showNotification('Approved requests report exported!', 'success');
            }, 2000);
        }

        function exportRejectedReport() {
            showNotification('Generating rejected requests report...', 'info');
            setTimeout(() => {
                showNotification('Rejected requests report exported!', 'success');
            }, 2000);
        }

        function exportAllReport() {
            showNotification('Generating complete leave report...', 'info');
            setTimeout(() => {
                showNotification('Complete leave report exported!', 'success');
            }, 2000);
        }

        function showLeaveAnalytics() {
            switchTab('analytics');
            showNotification('Switched to analytics view', 'info');
        }

        function updateStatCards() {
            // Update stat card numbers
            const pendingCount = document.querySelectorAll('.leave-row').length;
            const statNumbers = document.querySelectorAll('.stat-number');

            if (statNumbers[0]) {
                statNumbers[0].textContent = pendingCount;
            }
        }

        let distChart, trendsChart;

        function initializeCharts() {
            // Get PHP variables safely
            const leaveLabels = typeof window.leaveLabels !== 'undefined' ? window.leaveLabels : [];
            const leaveData = typeof window.leaveData !== 'undefined' ? window.leaveData : [];
            const trendLabels = typeof window.trendLabels !== 'undefined' ? window.trendLabels : [];
            const trendData = typeof window.trendData !== 'undefined' ? window.trendData : {};

            console.log('Initializing charts with data:', {
                leaveLabels,
                leaveData,
                trendLabels,
                trendData
            });

            // --- Leave Distribution Chart (Doughnut) ---
            const distCanvas = document.getElementById('leaveDistributionChart');
            if (distCanvas) {
                // Destroy existing chart if it exists
                if (typeof distChart !== 'undefined' && distChart) {
                    distChart.destroy();
                }

                const distCtx = distCanvas.getContext('2d');
                const hasData = leaveData && leaveData.some(value => value > 0);

                window.distChart = new Chart(distCtx, {
                    type: 'doughnut',
                    data: {
                        labels: hasData ? leaveLabels : ['No Data'],
                        datasets: [{
                            data: hasData ? leaveData : [1],
                            backgroundColor: hasData ? [
                                '#DC2626', // Annual - Red
                                '#2563EB', // Sick - Blue  
                                '#059669' // Personal - Green
                            ] : ['#E5E7EB'], // Gray for no data
                            borderWidth: 2,
                            borderColor: '#FFFFFF'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 20,
                                    usePointStyle: true
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        if (!hasData) return 'No leave data available';
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? Math.round((value / total) * 100) : 0;
                                        return `${label}: ${value} days (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // --- Leave Trends Chart (Line) ---
            const trendsCanvas = document.getElementById('leaveTrendsChart');
            if (trendsCanvas) {

                if (typeof trendsChart !== 'undefined' && trendsChart) {
                    trendsChart.destroy();
                }

                const trendsCtx = trendsCanvas.getContext('2d');

                window.trendsChart = new Chart(trendsCtx, {
                    type: 'line',
                    data: {
                        labels: trendLabels || ['Approved', 'Rejected', 'Pending'],
                        datasets: [{
                            label: 'Current Period',
                            data: [
                                trendData?.approved || 0,
                                trendData?.rejected || 0,
                                trendData?.pending || 0
                            ],
                            borderColor: '#2563EB',
                            backgroundColor: 'rgba(37, 99, 235, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#2563EB',
                            pointBorderColor: '#FFFFFF',
                            pointBorderWidth: 2,
                            pointRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'top'
                            }
                        }
                    }
                });
            }
        }

        function updateCharts(filteredData) {
            console.log('Updating charts with filtered data:', filteredData);

            // === Leave Distribution Chart Update ===
            const typeCounts = {
                'annual': 0,
                'sick': 0,
                'personal': 0
            };

            // Count leave DURATION (days) for approved leaves only
            filteredData.forEach(item => {
                if (item.status !== "approved") return; // Only approved leaves

                const type = item.type.toLowerCase();
                if (typeCounts.hasOwnProperty(type)) {
                    // Use duration instead of count for more accurate representation
                    typeCounts[type] += item.duration || 1;
                }
            });

            const distCanvas = document.getElementById('leaveDistributionChart');
            if (distCanvas && distChart) {
                const hasData = Object.values(typeCounts).some(value => value > 0);

                distChart.data.labels = hasData ? ['Annual Leave', 'Sick Leave', 'Personal Leave'] : ['No Data'];
                distChart.data.datasets[0].data = hasData ? Object.values(typeCounts) : [1];
                distChart.data.datasets[0].backgroundColor = hasData ? [
                    '#DC2626', // Annual - Red
                    '#2563EB', // Sick - Blue
                    '#059669' // Personal - Green
                ] : ['#E5E7EB']; // Gray for no data

                distChart.update();
            }

            // === Monthly Trends Chart Update ===
            const statusCounts = {
                'approved': 0,
                'rejected': 0,
                'pending': 0
            };

            // Count statuses from all filtered data
            filteredData.forEach(item => {
                const status = item.status.toLowerCase();
                if (statusCounts.hasOwnProperty(status)) {
                    statusCounts[status]++;
                }
            });

            if (trendsChart) {
                trendsChart.data.datasets[0].data = Object.values(statusCounts);
                trendsChart.update();
            }

            // Update department stats
            updateDepartmentStats(filteredData);
        }

        function updateDepartmentStats(filteredData) {
            console.log('Raw filtered data for department stats:', filteredData);

            const deptMap = {};
            const processedLeaveIds = new Set(); // Track by leave ID, not constructed identifier

            filteredData.forEach((item, index) => {
                // Use actual leave ID if available
                const leaveId = item.leaveId;

                // Skip if we've already processed this leave ID
                if (leaveId && processedLeaveIds.has(leaveId)) {
                    console.warn('Duplicate leave ID in filtered data, skipping:', leaveId);
                    return;
                }

                // Only count approved leaves
                if (item.status !== "approved") return;

                if (leaveId) {
                    processedLeaveIds.add(leaveId);
                }

                let deptKey = item.department || "Unknown";

                // Handle JSON department values
                if (typeof deptKey === "string" && deptKey.trim().startsWith("{")) {
                    try {
                        const parsed = JSON.parse(deptKey);
                        if (parsed.department_name) {
                            deptKey = parsed.department_name;
                        }
                    } catch (e) {
                        console.warn("Invalid department JSON:", deptKey);
                        deptKey = "Unknown";
                    }
                }

                // Clean and normalize the department name
                deptKey = deptKey.toString().trim();
                const normalizedKey = deptKey.toLowerCase();

                if (!deptMap[normalizedKey]) {
                    deptMap[normalizedKey] = {
                        name: deptKey.charAt(0).toUpperCase() + deptKey.slice(1).toLowerCase(),
                        used: 0,
                        total: 60,
                        leaves: []
                    };
                }

                console.log(`Processing leave ID ${leaveId} for ${deptKey}: ${item.duration} days`);

                deptMap[normalizedKey].used += item.duration - 1;
                deptMap[normalizedKey].leaves.push({
                    leaveId: leaveId,
                    employee: item.employee,
                    days: item.duration,
                    dates: `${item.start.toDateString()} to ${item.end.toDateString()}`
                });
            });

            console.log('Final department map:', deptMap);
            console.log('Processed leave IDs in department stats:', Array.from(processedLeaveIds));

            const container = document.querySelector('.department-stats');
            if (container) {
                container.innerHTML = '';

                if (Object.keys(deptMap).length === 0) {
                    container.innerHTML = '<div class="no-data">No department data available for current filters</div>';
                    return;
                }

                Object.values(deptMap).forEach(stats => {
                    const percent = Math.min(100, Math.round((stats.used / stats.total) * 100));

                    console.log(`Department ${stats.name}: ${stats.used}/${stats.total} days (${percent}%)`);
                    console.log('Individual leaves:', stats.leaves);

                    container.innerHTML += `
                <div class="dept-stat-item">
                    <div class="dept-info">
                        <span class="dept-name">${stats.name}</span>
                        <span class="dept-usage">${stats.used}/${stats.total} days used</span>
                    </div>
                    <div class="dept-progress">
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: ${percent}%"></div>
                        </div>
                        <span class="progress-percent">${percent}%</span>
                    </div>
                </div>
            `;
                });
            }
        }



        // Utility functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        // Apply Leave Modal Functions
        function openApplyLeaveModal() {
            const form = document.getElementById('applyLeaveForm');
            form.action = "{{ route('admin.leaves.create') }}"; // create route
            document.getElementById('formMethod').value = 'POST';

            // reset form
            form.reset();

            // reset button
            const submitBtn = document.querySelector('#applyLeaveModal .btn-primary');
            submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Application';

            document.getElementById('applyLeaveModal').style.display = 'block';
        }

        function closeApplyLeaveModal() {
            const modal = document.getElementById('applyLeaveModal');
            modal.style.display = 'none';
            resetApplyLeaveForm();
        }

        function resetApplyLeaveForm() {
            document.getElementById('applyLeaveForm').reset();
            document.getElementById('halfDayOptions').style.display = 'none';
            document.getElementById('endDateGroup').style.display = 'block';
        }

        function setupLeaveDurationHandling() {
            const durationSelect = document.getElementById('leaveDuration');
            const halfDayOptions = document.getElementById('halfDayOptions');
            const endDateGroup = document.getElementById('endDateGroup');
            const endDateInput = document.getElementById('endDate');

            durationSelect.addEventListener('change', function() {
                if (this.value === 'half-day') {
                    halfDayOptions.style.display = 'block';
                    endDateGroup.style.display = 'none';
                    endDateInput.removeAttribute('required');

                    // Set end date same as start date for half day
                    const startDate = document.getElementById('startDate').value;
                    if (startDate) {
                        endDateInput.value = startDate;
                    }
                } else {
                    halfDayOptions.style.display = 'none';
                    endDateGroup.style.display = 'block';
                    endDateInput.setAttribute('required', 'required');
                }
            });

            // Sync end date with start date for half day
            document.getElementById('startDate').addEventListener('change', function() {
                if (durationSelect.value === 'half-day') {
                    endDateInput.value = this.value;
                } else {
                    // Set minimum end date to start date
                    endDateInput.min = this.value;
                    if (endDateInput.value < this.value) {
                        endDateInput.value = this.value;
                    }
                }
            });
        }

        function submitLeaveApplication() {
            const form = document.getElementById('applyLeaveForm');
            const formData = new FormData(form);

            // Validate form
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }

            // Additional validation for half day
            const durationType = document.getElementById('leaveDuration').value;
            if (durationType === 'half-day') {
                const halfDayType = document.querySelector('input[name="halfDayType"]:checked');
                if (!halfDayType) {
                    showNotification('Please select half day timing', 'error');
                    return;
                }
            }

            // Check if confirmation is checked
            const confirmation = document.getElementById('confirmDeclaration');
            if (!confirmation.checked) {
                showNotification('Please confirm the declaration', 'error');
                return;
            }

            showNotification('Submitting leave application...', 'info');


            setTimeout(() => {

                showNotification(
                    'Leave application submitted successfully! You will be notified once it\'s reviewed.',
                    'success');
                closeApplyLeaveModal();
                if (document.querySelector('.tab-btn[data-tab="my-leaves"]').classList.contains('active')) {
                    // Refresh the page or reload the tab content
                    setTimeout(() => {
                        showNotification('Redirecting to your leave requests...', 'info');
                        switchTab('my-leaves');
                    }, 1500);
                }
            }, 2000);
        }


        // My Leave Functions
        function viewMyLeaveDetails(button) {
            showNotification('Loading leave details...', 'info');

            setTimeout(() => {
                const row = button.closest('.leave-row');

                const modal = document.getElementById('leaveDetailsModal');
                const content = document.getElementById('leaveDetailsContent');

                content.innerHTML = `
            <div class="leave-details">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Leave Type</label>
                            <div class="view-field">${row.dataset.type}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Status</label>
                            <div class="view-field status-badge ${row.dataset.status}">
                                ${row.dataset.status.charAt(0).toUpperCase() + row.dataset.status.slice(1)}
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <div class="view-field">${row.dataset.start}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <div class="view-field">${row.dataset.end}</div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Reason</label>
                        <div class="view-field view-textarea">${row.dataset.reason}</div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label class="form-label">Applied Date</label>
                            <div class="view-field">${row.dataset.applied}</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Approved By</label>
                            <div class="view-field">${row.dataset.approvedby}</div>
                        </div>
                    </div>
                </div>
            </div>
        `;

                // Modal footer
                const modalFooter = modal.querySelector('.modal-footer');
                modalFooter.innerHTML =
                    `<button class="btn btn-secondary" onclick="closeModal()">Close</button>`;

                modal.style.display = 'block';
                showNotification('Leave details loaded!', 'success');
            }, 500);
        }

        function editMyLeave(button) {
            showNotification('Opening leave editor...', 'info');

            setTimeout(() => {
                const form = document.getElementById('applyLeaveForm');

                // point form action to update route
                const id = button.dataset.id;

                form.action = `/admin/leaves/${id}`;
                document.getElementById('formMethod').value = 'PUT';

                const formatDate = (dateStr) => {
                    if (!dateStr) return '';
                    const d = new Date(dateStr);
                    if (isNaN(d)) return ''; // invalid date
                    return d.toISOString().split('T')[0]; // YYYY-MM-DD
                };

                // load data from button attributes
                document.getElementById('leaveType').value = button.dataset.type;
                document.getElementById('startDate').value = formatDate(button.dataset.start);
                document.getElementById('endDate').value = formatDate(button.dataset.end);
                document.getElementById('leaveReason').value = button.dataset.reason;
                document.getElementById('emergencyContact').value = button.dataset.contact || '';

                // update button text
                const submitBtn = document.querySelector('#applyLeaveModal .btn-primary');
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Application';

                document.getElementById('applyLeaveModal').style.display = 'block';

                showNotification('Edit mode activated', 'success');
            }, 500);
        }



        function cancelMyLeave(id) {
            if (confirm('Are you sure you want to cancel this leave request? This action cannot be undone.')) {
                showNotification('Cancelling leave request...', 'warning');

                setTimeout(() => {

                    showNotification('Leave request cancelled successfully!', 'success');


                    const row = document.querySelector(`[onclick="cancelMyLeave(${id})"]`).closest('tr');
                    if (row) {
                        row.style.background = '#FEE2E2';
                        setTimeout(() => {
                            row.remove();
                        }, 1000);
                    }
                }, 1500);
            }
        }

        function exportMyLeaveReport() {
            showNotification('Generating your leave report...', 'info');
            setTimeout(() => {
                showNotification('Your leave report has been exported!', 'success');
            }, 2000);
        }

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
            border-left: 4px solid ${type === 'success' ? '#10B981' : type === 'error' ? '#DC2626' : type === 'warning' ? '#F59E0B' : '#2563EB'};
            font-weight: 500;
            max-width: 350px;
        `;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 4000);
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const leaveModal = document.getElementById('leaveDetailsModal');
            const rejectionModal = document.getElementById('rejectionModal');
            const applyLeaveModal = document.getElementById('applyLeaveModal');

            if (event.target === leaveModal) {
                closeModal();
            }
            if (event.target === rejectionModal) {
                closeRejectionModal();
            }
            if (event.target === applyLeaveModal) {
                closeApplyLeaveModal();
            }
        }

        // Load Chart.js if not already loaded
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => initializeCharts();
            document.head.appendChild(script);
        }
        document.getElementById('startDate').addEventListener('change', calculateDuration);
        document.getElementById('endDate').addEventListener('change', calculateDuration);

        function calculateDuration() {
            const startDate = new Date(document.getElementById('startDate').value);
            const endDate = new Date(document.getElementById('endDate').value);

            if (startDate && endDate && endDate >= startDate) {
                const duration = Math.ceil((endDate - startDate) / (1000 * 60 * 60 * 24)) + 1;
                document.getElementById('durationValue').textContent = duration;
                document.getElementById('duration').value = duration;
            } else {
                document.getElementById('durationValue').textContent = '0';
                document.getElementById('duration').value = 0;
            }
        }
    </script>

@endsection
