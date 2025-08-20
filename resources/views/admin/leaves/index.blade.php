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
                <div class="stat-number" data-count="{{ $leaveStats['pending'] ?? 12 }}">{{ $leaveStats['pending'] ?? 12 }}</div>
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
                <div class="stat-number" data-count="{{ $leaveStats['approved_today'] ?? 8 }}">{{ $leaveStats['approved_today'] ?? 8 }}</div>
                <p>Processed successfully</p>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-arrow-up"></i> +3 from yesterday
            </div>
        </div>

        <div class="leave-stat-card rejected">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-content">
                <h3>Rejected</h3>
                <div class="stat-number" data-count="{{ $leaveStats['rejected'] ?? 2 }}">{{ $leaveStats['rejected'] ?? 2 }}</div>
                <p>This month</p>
            </div>
            <div class="stat-trend neutral">
                <i class="fas fa-minus"></i> Normal range
            </div>
        </div>

        <div class="leave-stat-card total">
            <div class="stat-icon">
                <i class="fas fa-calendar-check"></i>
            </div>
            <div class="stat-content">
                <h3>Total Requests</h3>
                <div class="stat-number" data-count="{{ $leaveStats['total'] ?? 156 }}">{{ $leaveStats['total'] ?? 156 }}</div>
                <p>This month</p>
            </div>
            <div class="stat-trend positive">
                <i class="fas fa-chart-line"></i> +15% from last month
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
                <span class="tab-badge">{{ $leaveStats['pending'] ?? 12 }}</span>
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
                <input type="text" id="search-input" placeholder="Search by employee name..." class="filter-input">
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
                            <span class="used">8</span> / <span class="total">25</span> days
                            <div class="balance-bar">
                                <div class="balance-fill" style="width: 32%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="balance-item">
                        <div class="balance-type">Sick Leave</div>
                        <div class="balance-info">
                            <span class="used">2</span> / <span class="total">12</span> days
                            <div class="balance-bar">
                                <div class="balance-fill" style="width: 17%"></div>
                            </div>
                        </div>
                    </div>
                    <div class="balance-item">
                        <div class="balance-type">Personal Leave</div>
                        <div class="balance-info">
                            <span class="used">1</span> / <span class="total">5</span> days
                            <div class="balance-bar">
                                <div class="balance-fill" style="width: 20%"></div>
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
                        @php
                        $myLeaves = [
                            [
                                'id' => 101,
                                'leave_type' => 'Annual Leave',
                                'start_date' => '2025-08-15',
                                'end_date' => '2025-08-20',
                                'duration' => 5,
                                'reason' => 'Family vacation planning',
                                'status' => 'approved',
                                'applied_date' => '2025-07-10',
                                'approved_by' => 'Super Admin',
                                'approved_date' => '2025-07-12'
                            ],
                            [
                                'id' => 102,
                                'leave_type' => 'Sick Leave',
                                'start_date' => '2025-07-18',
                                'end_date' => '2025-07-19',
                                'duration' => 2,
                                'reason' => 'Medical appointment',
                                'status' => 'pending',
                                'applied_date' => '2025-07-15',
                                'approved_by' => null,
                                'approved_date' => null
                            ],
                            [
                                'id' => 103,
                                'leave_type' => 'Personal Leave',
                                'start_date' => '2025-06-28',
                                'end_date' => '2025-06-28',
                                'duration' => 1,
                                'reason' => 'Personal matter',
                                'status' => 'rejected',
                                'applied_date' => '2025-06-20',
                                'approved_by' => 'Super Admin',
                                'approved_date' => '2025-06-22',
                                'rejection_reason' => 'Insufficient notice period'
                            ]
                        ];
                        @endphp

                        @foreach($myLeaves as $leave)
                        <tr class="leave-row">
                            <td>
                                <span class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave['leave_type'])) }}">
                                    {{ $leave['leave_type'] }}
                                </span>
                            </td>
                            <td>
                                <div class="duration-cell">
                                    <span class="duration-days">{{ $leave['duration'] }}</span>
                                    <span class="duration-label">{{ $leave['duration'] == 1 ? 'day' : 'days' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-from">{{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}</div>
                                    @if($leave['start_date'] !== $leave['end_date'])
                                    <div class="date-to">to {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}</div>
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
                                    @if($leave['status'] === 'approved')
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
                                    <span class="applied-date">{{ \Carbon\Carbon::parse($leave['applied_date'])->format('M d, Y') }}</span>
                                    <span class="applied-time">{{ \Carbon\Carbon::parse($leave['applied_date'])->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewMyLeaveDetails({{ $leave['id'] }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($leave['status'] === 'pending')
                                    <button class="btn-action btn-edit" onclick="editMyLeave({{ $leave['id'] }})" title="Edit Request">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-cancel" onclick="cancelMyLeave({{ $leave['id'] }})" title="Cancel Request">
                                        <i class="fas fa-times"></i>
                                    </button>
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
                        @php
                        $pendingLeaves = [
                            [
                                'id' => 1,
                                'employee_name' => 'John Smith',
                                'employee_id' => 'EMP001',
                                'department' => 'Engineering',
                                'leave_type' => 'Annual Leave',
                                'start_date' => '2025-07-28',
                                'end_date' => '2025-08-02',
                                'duration' => 5,
                                'reason' => 'Family vacation to Europe',
                                'applied_date' => '2025-07-20',
                                'supporting_doc' => true,
                                'emergency_contact' => '+1234567890'
                            ],
                            [
                                'id' => 2,
                                'employee_name' => 'Sarah Wilson',
                                'employee_id' => 'EMP002',
                                'department' => 'Marketing',
                                'leave_type' => 'Sick Leave',
                                'start_date' => '2025-07-25',
                                'end_date' => '2025-07-26',
                                'duration' => 2,
                                'reason' => 'Medical appointment and recovery',
                                'applied_date' => '2025-07-22',
                                'supporting_doc' => true,
                                'emergency_contact' => '+1234567891'
                            ],
                            [
                                'id' => 3,
                                'employee_name' => 'Mike Johnson',
                                'employee_id' => 'EMP003',
                                'department' => 'Sales',
                                'leave_type' => 'Personal Leave',
                                'start_date' => '2025-08-01',
                                'end_date' => '2025-08-01',
                                'duration' => 1,
                                'reason' => 'Personal matter - family event',
                                'applied_date' => '2025-07-21',
                                'supporting_doc' => false,
                                'emergency_contact' => '+1234567892'
                            ]
                        ];
                        @endphp

                        @foreach($pendingLeaves as $leave)
                        <tr class="leave-row" data-id="{{ $leave['id'] }}">
                            <td>
                                <input type="checkbox" class="leave-checkbox" value="{{ $leave['id'] }}">
                            </td>
                            <td>
                                <div class="employee-cell">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($leave['employee_name'], 0, 1)) }}
                                    </div>
                                    <div class="employee-info">
                                        <h4>{{ $leave['employee_name'] }}</h4>
                                        <p>{{ $leave['employee_id'] }} • {{ $leave['department'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave['leave_type'])) }}">
                                    {{ $leave['leave_type'] }}
                                </span>
                            </td>
                            <td>
                                <div class="duration-cell">
                                    <span class="duration-days">{{ $leave['duration'] }}</span>
                                    <span class="duration-label">{{ $leave['duration'] == 1 ? 'day' : 'days' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-from">{{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}</div>
                                    @if($leave['start_date'] !== $leave['end_date'])
                                    <div class="date-to">to {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="reason-cell">
                                    <p>{{ \Str::limit($leave['reason'], 50) }}</p>
                                    @if($leave['supporting_doc'])
                                    <span class="doc-indicator">
                                        <i class="fas fa-paperclip"></i> Attachment
                                    </span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="applied-cell">
                                    <span class="applied-date">{{ \Carbon\Carbon::parse($leave['applied_date'])->format('M d, Y') }}</span>
                                    <span class="applied-time">{{ \Carbon\Carbon::parse($leave['applied_date'])->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails({{ $leave['id'] }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-approve" onclick="approveLeave({{ $leave['id'] }})" title="Approve">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button class="btn-action btn-reject" onclick="rejectLeave({{ $leave['id'] }})" title="Reject">
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
            
            <div class="approved-message">
                <div class="empty-state">
                    <i class="fas fa-check-circle"></i>
                    <h3>No approved requests to display</h3>
                    <p>Approved requests will appear here once you process pending requests.</p>
                </div>
            </div>
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
            
            <div class="rejected-message">
                <div class="empty-state">
                    <i class="fas fa-times-circle"></i>
                    <h3>No rejected requests to display</h3>
                    <p>Rejected requests will appear here once you process pending requests.</p>
                </div>
            </div>
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
                        @php
                        $allLeaves = [
                            [
                                'id' => 4,
                                'employee_name' => 'Emily Davis',
                                'employee_id' => 'EMP004',
                                'department' => 'HR',
                                'leave_type' => 'Annual Leave',
                                'start_date' => '2025-07-15',
                                'end_date' => '2025-07-20',
                                'duration' => 5,
                                'status' => 'approved',
                                'applied_date' => '2025-07-01'
                            ],
                            [
                                'id' => 5,
                                'employee_name' => 'David Miller',
                                'employee_id' => 'EMP005',
                                'department' => 'Finance',
                                'leave_type' => 'Sick Leave',
                                'start_date' => '2025-07-10',
                                'end_date' => '2025-07-12',
                                'duration' => 3,
                                'status' => 'rejected',
                                'applied_date' => '2025-07-08'
                            ]
                        ];
                        @endphp

                        @foreach($allLeaves as $leave)
                        <tr class="leave-row">
                            <td>
                                <div class="employee-cell">
                                    <div class="employee-avatar">
                                        {{ strtoupper(substr($leave['employee_name'], 0, 1)) }}
                                    </div>
                                    <div class="employee-info">
                                        <h4>{{ $leave['employee_name'] }}</h4>
                                        <p>{{ $leave['employee_id'] }} • {{ $leave['department'] }}</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="leave-type-badge type-{{ strtolower(str_replace(' ', '-', $leave['leave_type'])) }}">
                                    {{ $leave['leave_type'] }}
                                </span>
                            </td>
                            <td>
                                <div class="duration-cell">
                                    <span class="duration-days">{{ $leave['duration'] }}</span>
                                    <span class="duration-label">{{ $leave['duration'] == 1 ? 'day' : 'days' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <div class="date-from">{{ \Carbon\Carbon::parse($leave['start_date'])->format('M d, Y') }}</div>
                                    @if($leave['start_date'] !== $leave['end_date'])
                                    <div class="date-to">to {{ \Carbon\Carbon::parse($leave['end_date'])->format('M d, Y') }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $leave['status'] }}">
                                    @if($leave['status'] === 'approved')
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
                                    <span class="applied-date">{{ \Carbon\Carbon::parse($leave['applied_date'])->format('M d, Y') }}</span>
                                    <span class="applied-time">{{ \Carbon\Carbon::parse($leave['applied_date'])->diffForHumans() }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewLeaveDetails({{ $leave['id'] }})" title="View Details">
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
                    <div class="card-content">
                        <canvas id="leaveDistributionChart" width="400" height="300"></canvas>
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
                            @php
                            $departmentStats = [
                                ['name' => 'Engineering', 'used' => 45, 'total' => 60, 'percentage' => 75],
                                ['name' => 'Marketing', 'used' => 32, 'total' => 45, 'percentage' => 71],
                                ['name' => 'Sales', 'used' => 28, 'total' => 40, 'percentage' => 70],
                                ['name' => 'HR', 'used' => 15, 'total' => 25, 'percentage' => 60],
                                ['name' => 'Finance', 'used' => 18, 'total' => 30, 'percentage' => 60]
                            ];
                            @endphp

                            @foreach($departmentStats as $dept)
                            <div class="dept-stat-item">
                                <div class="dept-info">
                                    <span class="dept-name">{{ $dept['name'] }}</span>
                                    <span class="dept-usage">{{ $dept['used'] }}/{{ $dept['total'] }} days used</span>
                                </div>
                                <div class="dept-progress">
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: {{ $dept['percentage'] }}%"></div>
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
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-day"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">2.3</span>
                                    <span class="stat-label">Avg Processing Time (hours)</span>
                                </div>
                            </div>
                            
                            <div class="quick-stat">
                                <div class="stat-icon">
                                    <i class="fas fa-percentage"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">94%</span>
                                    <span class="stat-label">Approval Rate</span>
                                </div>
                            </div>
                            
                            <div class="quick-stat">
                                <div class="stat-icon">
                                    <i class="fas fa-users"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">87</span>
                                    <span class="stat-label">Employees on Leave Today</span>
                                </div>
                            </div>
                            
                            <div class="quick-stat">
                                <div class="stat-icon">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div class="stat-info">
                                    <span class="stat-value">3</span>
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
            <form id="applyLeaveForm">
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leaveType" class="form-label">
                                <i class="fas fa-tag"></i> Leave Type *
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-tag"></i>
                            </div>
                            <select id="leaveType" class="form-select" required>
                                <option value="">Select leave type</option>
                                <option value="annual">Annual Leave</option>
                                <option value="sick">Sick Leave</option>
                                <option value="personal">Personal Leave</option>
                                <option value="maternity">Maternity Leave</option>
                                <option value="emergency">Emergency Leave</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="leaveDuration" class="form-label">
                                <i class="fas fa-calendar-day"></i> Duration Type *
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <select id="leaveDuration" class="form-select" required>
                                <option value="">Select duration</option>
                                <option value="full-day">Full Day(s)</option>
                                <option value="half-day">Half Day</option>
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
                            <input type="date" id="startDate" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                        
                        <div class="form-group" id="endDateGroup">
                            <label for="endDate" class="form-label">
                                <i class="fas fa-calendar-check"></i> End Date *
                            </label>
                            <div class="input-icon">
                                <i class="fas fa-calendar-check"></i>
                            </div>
                            <input type="date" id="endDate" class="form-input" required min="{{ date('Y-m-d') }}">
                        </div>
                    </div>

                    <div class="form-group" id="halfDayOptions" style="display: none;">
                        <label class="form-label">
                            <i class="fas fa-clock"></i> Half Day Timing
                        </label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="halfDayType" value="morning"> Morning (First Half)
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="halfDayType" value="afternoon"> Afternoon (Second Half)
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="leaveReason" class="form-label">
                            <i class="fas fa-comment-alt"></i> Reason for Leave *
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-comment-alt"></i>
                        </div>
                        <textarea id="leaveReason" class="form-textarea" rows="4" placeholder="Please provide a detailed reason for your leave request..." required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="emergencyContact" class="form-label">
                            <i class="fas fa-phone"></i> Emergency Contact
                        </label>
                        <div class="input-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <input type="tel" id="emergencyContact" class="form-input" placeholder="Emergency contact number">
                    </div>

                    <div class="form-group">
                        <label for="supportingDocument" class="form-label">
                            <i class="fas fa-paperclip"></i> Supporting Documents
                        </label>
                        <div class="file-upload-container">
                            <input type="file" id="supportingDocument" class="file-input" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" multiple>
                            <label for="supportingDocument" class="file-upload-label">
                                <i class="fas fa-cloud-upload-alt"></i>
                                <span>Choose files or drag and drop</span>
                                <small>PDF, DOC, DOCX, JPG, PNG (Max 10MB)</small>
                            </label>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="confirmDeclaration" required>
                            I declare that the information provided is true and accurate. I understand that any false information may result in disciplinary action.
                        </label>
                    </div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeApplyLeaveModal()">Cancel</button>
            <button class="btn btn-primary" onclick="submitLeaveApplication()">
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
<div id="rejectionModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-times-circle"></i> Reject Leave Request</h2>
            <button class="modal-close" onclick="closeRejectionModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div class="form-group">
                <label for="rejectionReason">Reason for Rejection *</label>
                <textarea id="rejectionReason" class="form-control" rows="4" placeholder="Please provide a clear reason for rejecting this leave request..."></textarea>
            </div>
            <div class="form-group">
                <label>
                    <input type="checkbox" id="notifyEmployee"> 
                    Send notification to employee
                </label>
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeRejectionModal()">Cancel</button>
            <button class="btn btn-danger" onclick="confirmRejection()">
                <i class="fas fa-times"></i> Confirm Rejection
            </button>
        </div>
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

    .filter-select, .filter-input {
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.9rem;
        background: rgba(248, 250, 252, 0.5);
        transition: all 0.3s ease;
        color: var(--text-primary);
    }

    .filter-select:focus, .filter-input:focus {
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
        max-height: calc(95vh - 140px); /* Subtract header and footer height */
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
        margin-top: 12px; /* Account for label height */
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

    .radio-label input[type="radio"]:checked + .radio-label,
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
    box-shadow: 0 2px 8px 0 rgba(99,102,241,0.08);
}
.card-body > div[style*="display: grid"] > .card {
    border: 1px solid #f3f4f6;
    box-shadow: 0 1px 8px 0 rgba(0,0,0,0.03);
    transition: box-shadow 0.2s;
}
.card-body > div[style*="display: grid"] > .card:hover {
    box-shadow: 0 4px 24px 0 rgba(37,99,235,0.08);
}
.card-body h4, .card-body h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
}
.card-body p {
    color: #64748b;
    margin-bottom: 1rem;
    line-height: 1.7;
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .table th, .table td { padding: 0.75rem 0.5rem; }
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
    color: #111827;
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.form-label i {
    margin-right: 0.5rem;
    color: var(--redcode-primary);
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 12px 16px 12px 48px; /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
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
    padding: 12px 16px 12px 48px; /* Maintain consistent spacing with project modal */
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
    padding: 16px 16px 16px 48px; /* Slightly more top padding for better icon alignment */
    align-items: flex-start;
    line-height: 1.5;
}

/* Special positioning for textarea icons */
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
    
    .modal-header, .modal-body {
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
        const departmentFilter = document.getElementById('department-filter')?.value || '';
        const leaveTypeFilter = document.getElementById('leave-type-filter')?.value || '';
        const search = document.getElementById('search-input')?.value.toLowerCase() || '';
        
        const rows = document.querySelectorAll('.leave-row');
        
        rows.forEach(row => {
            const employeeName = row.querySelector('.employee-info h4').textContent.toLowerCase();
            const department = row.querySelector('.employee-info p').textContent.toLowerCase();
            const leaveType = row.querySelector('.leave-type-badge').textContent.toLowerCase();
            
            const nameMatch = !search || employeeName.includes(search);
            const deptMatch = !departmentFilter || department.includes(departmentFilter);
            const typeMatch = !leaveTypeFilter || leaveType.includes(leaveTypeFilter.replace('-', ' '));
            
            row.style.display = nameMatch && deptMatch && typeMatch ? '' : 'none';
        });
        
        showNotification('Filters applied successfully!', 'success');
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
            
            const content = document.getElementById('leaveDetailsContent');
            content.innerHTML = `
                <div class="leave-details">
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-user"></i> Employee Name</label>
                                <div class="view-field">John Smith</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-id-card"></i> Employee ID</label>
                                <div class="view-field">EMP001</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-calendar-alt"></i> Leave Type</label>
                                <div class="view-field">Annual Leave</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-building"></i> Department</label>
                                <div class="view-field">Engineering</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-calendar"></i> Start Date</label>
                                <div class="view-field">July 28, 2025</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-calendar"></i> End Date</label>
                                <div class="view-field">August 2, 2025</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-clock"></i> Duration</label>
                                <div class="view-field">5 days</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label"><i class="fas fa-info-circle"></i> Status</label>
                                <div class="view-field status-badge pending">Pending Review</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-comment"></i> Reason</label>
                            <div class="view-field view-textarea">Family vacation to Europe</div>
                        </div>
                        <div class="form-group">
                            <label class="form-label"><i class="fas fa-phone"></i> Emergency Contact</label>
                            <div class="view-field">+1234567890</div>
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
        modal.dataset.currentId = id;
        modal.style.display = 'block';
    }

    function confirmRejection() {
        const reason = document.getElementById('rejectionReason').value;
        const currentId = document.getElementById('rejectionModal').dataset.currentId;
        
        if (!reason.trim()) {
            showNotification('Please provide a reason for rejection', 'error');
            return;
        }
        
        showNotification(`Rejecting leave request ${currentId}...`, 'info');
        
        setTimeout(() => {
            const row = document.querySelector(`[data-id="${currentId}"]`);
            if (row) {
                row.style.background = '#FEE2E2';
                setTimeout(() => {
                    row.remove();
                }, 1000);
            }
            closeRejectionModal();
            showNotification('Leave request rejected successfully!', 'success');
            updateStatCards();
        }, 1000);
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

    // Charts
    function initializeCharts() {
        // Leave Distribution Chart
        const distCtx = document.getElementById('leaveDistributionChart');
        if (distCtx) {
            new Chart(distCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Annual Leave', 'Sick Leave', 'Personal Leave', 'Maternity Leave', 'Emergency Leave'],
                    datasets: [{
                        data: [45, 25, 15, 10, 5],
                        backgroundColor: [
                            '#DC2626',
                            '#2563EB',
                            '#059669',
                            '#D97706',
                            '#7C3AED'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom'
                        }
                    }
                }
            });
        }

        // Leave Trends Chart
        const trendsCtx = document.getElementById('leaveTrendsChart');
        if (trendsCtx) {
            new Chart(trendsCtx, {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                    datasets: [{
                        label: 'Approved',
                        data: [12, 19, 15, 25, 22, 30],
                        borderColor: '#059669',
                        backgroundColor: 'rgba(5, 150, 105, 0.1)',
                        tension: 0.4
                    }, {
                        label: 'Rejected',
                        data: [2, 3, 2, 5, 2, 3],
                        borderColor: '#DC2626',
                        backgroundColor: 'rgba(220, 38, 38, 0.1)',
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
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
        const modal = document.getElementById('applyLeaveModal');
        modal.style.display = 'block';
        resetApplyLeaveForm();
        setupLeaveDurationHandling();
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

        // Simulate API call
        setTimeout(() => {
            // Here you would normally send the data to your Laravel backend
            // For now, we'll just show success and close modal
            
            showNotification('Leave application submitted successfully! You will be notified once it\'s reviewed.', 'success');
            closeApplyLeaveModal();
            
            // Optionally refresh the my leaves tab
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
    function viewMyLeaveDetails(id) {
        showNotification('Loading leave details...', 'info');
        
        setTimeout(() => {
            const modal = document.getElementById('leaveDetailsModal');
            modal.dataset.currentId = id;
            
            const content = document.getElementById('leaveDetailsContent');
            content.innerHTML = `
                <div class="leave-details">
                    <div class="form-container">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Leave Type</label>
                                <div class="view-field">Annual Leave</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Status</label>
                                <div class="view-field status-badge approved">Approved</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Start Date</label>
                                <div class="view-field">August 15, 2025</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">End Date</label>
                                <div class="view-field">August 20, 2025</div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Reason</label>
                            <div class="view-field view-textarea">Family vacation planning</div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Applied Date</label>
                                <div class="view-field">July 10, 2025</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Approved By</label>
                                <div class="view-field">Super Admin</div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Update modal footer for view mode
            const modalFooter = modal.querySelector('.modal-footer');
            modalFooter.innerHTML = `
                <button class="btn btn-secondary" onclick="closeModal()">Close</button>
            `;
            
            modal.style.display = 'block';
            showNotification('Leave details loaded!', 'success');
        }, 800);
    }

    function editMyLeave(id) {
        showNotification('Opening leave editor...', 'info');
        
        setTimeout(() => {
            // Here you would populate the apply leave modal with existing data
            openApplyLeaveModal();
            
            // Populate form with existing data (this would come from backend)
            document.getElementById('leaveType').value = 'sick';
            document.getElementById('leaveDuration').value = 'full-day';
            document.getElementById('startDate').value = '2025-07-18';
            document.getElementById('endDate').value = '2025-07-19';
            document.getElementById('leaveReason').value = 'Medical appointment';
            
            // Change submit button text
            const submitBtn = document.querySelector('#applyLeaveModal .btn-primary');
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Update Application';
            
            showNotification('Edit mode activated', 'success');
        }, 500);
    }

    function cancelMyLeave(id) {
        if (confirm('Are you sure you want to cancel this leave request? This action cannot be undone.')) {
            showNotification('Cancelling leave request...', 'warning');
            
            setTimeout(() => {
                // Here you would send cancel request to backend
                showNotification('Leave request cancelled successfully!', 'success');
                
                // Remove the row from table or refresh
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
</script>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-calendar-times"></i> Leave Management</h2>
        <div class="flex gap-2">
            <button onclick="openLeaveModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Leave Request
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="flex justify-between items-center mb-4">
            <div class="flex gap-2">
                <input type="text" placeholder="Search employees..." class="form-input" style="width: 300px;">
                <select class="form-select" style="width: 200px;">
                    <option>All Status</option>
                    <option>Pending</option>
                    <option>Approved</option>
                    <option>Rejected</option>
                </select>
                <select class="form-select" style="width: 200px;">
                    <option>All Types</option>
                    <option>Annual Leave</option>
                    <option>Sick Leave</option>
                    <option>Personal Leave</option>
                    <option>Emergency Leave</option>
                </select>
            </div>
            <button class="btn btn-secondary">
                <i class="fas fa-filter"></i>
                Filter
            </button>
        </div>

        <!-- Leave Requests Table -->
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Employee</th>
                        <th>Leave Type</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Days</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">SM</div>
                                <div>
                                    <div style="font-weight: 600;">Sarah Miller</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Design Team</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(59, 130, 246, 0.1); color: var(--primary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Annual Leave</span>
                        </td>
                        <td>Dec 20, 2024</td>
                        <td>Dec 27, 2024</td>
                        <td style="font-weight: 600;">7 days</td>
                        <td>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Pending</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-success" style="padding: 0.5rem;">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewLeaveModal('Sarah Miller', 'SM001', 'Design Team', 'Annual Leave', 'Dec 20, 2024', 'Dec 27, 2024', '7', 'Pending', 'Normal', 'John Smith', '+1-555-0123', 'Planning to visit family during holiday season. Have completed all current projects and handover documents are ready.')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">JD</div>
                                <div>
                                    <div style="font-weight: 600;">John Doe</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Development Team</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Sick Leave</span>
                        </td>
                        <td>Nov 15, 2024</td>
                        <td>Nov 17, 2024</td>
                        <td style="font-weight: 600;">3 days</td>
                        <td>
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Approved</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewLeaveModal('John Doe', 'JD001', 'Development Team', 'Sick Leave', 'Nov 15, 2024', 'Nov 17, 2024', '3', 'Approved', 'Normal', 'Jane Smith', '+1-555-0124', 'Experiencing flu symptoms and need time to recover. Doctor recommended 3 days rest.')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditLeaveModal('John Doe', 'JD001', 'Development Team', 'Sick Leave', 'Nov 15, 2024', 'Nov 17, 2024', '3', 'Approved', 'Normal', 'Jane Smith', '+1-555-0124', 'Experiencing flu symptoms and need time to recover. Doctor recommended 3 days rest.', 'Medical certificate provided')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">MJ</div>
                                <div>
                                    <div style="font-weight: 600;">Mike Johnson</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">Marketing Team</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(99, 102, 241, 0.1); color: var(--secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Personal Leave</span>
                        </td>
                        <td>Jan 5, 2025</td>
                        <td>Jan 8, 2025</td>
                        <td style="font-weight: 600;">4 days</td>
                        <td>
                            <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Pending</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-success" style="padding: 0.5rem;">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;">
                                    <i class="fas fa-times"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewLeaveModal('Mike Johnson', 'MJ001', 'Marketing Team', 'Personal Leave', 'Jan 5, 2025', 'Jan 8, 2025', '4', 'Pending', 'Normal', 'Robert Brown', '+1-555-0125', 'Need to attend to personal matters and family obligations. Will ensure all work is completed before leave.')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">LA</div>
                                <div>
                                    <div style="font-weight: 600;">Lisa Anderson</div>
                                    <div style="font-size: 0.75rem; color: var(--gray-500);">HR Team</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge" style="background: rgba(6, 182, 212, 0.1); color: var(--info); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Emergency Leave</span>
                        </td>
                        <td>Oct 28, 2024</td>
                        <td>Oct 29, 2024</td>
                        <td style="font-weight: 600;">2 days</td>
                        <td>
                            <span class="badge" style="background: rgba(239, 68, 68, 0.1); color: var(--danger); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Rejected</span>
                        </td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="openViewLeaveModal('Lisa Anderson', 'LA001', 'HR Team', 'Emergency Leave', 'Oct 28, 2024', 'Oct 29, 2024', '2', 'Rejected', 'Urgent', 'Michael Davis', '+1-555-0126', 'Family emergency requiring immediate attention. Unexpected situation that needs urgent care.')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-warning" style="padding: 0.5rem;" onclick="openEditLeaveModal('Lisa Anderson', 'LA001', 'HR Team', 'Emergency Leave', 'Oct 28, 2024', 'Oct 29, 2024', '2', 'Rejected', 'Urgent', 'Michael Davis', '+1-555-0126', 'Family emergency requiring immediate attention. Unexpected situation that needs urgent care.', 'Insufficient documentation provided')">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing 1 to 4 of 28 leave requests
            </div>
            <div class="flex gap-1">
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;">1</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">2</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">3</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Leave Statistics -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-top: 2rem;">
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;">7</div>
            <div style="color: var(--gray-600); font-weight: 500;">Pending Requests</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;">15</div>
            <div style="color: var(--gray-600); font-weight: 500;">Approved</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--danger); margin-bottom: 0.5rem;">3</div>
            <div style="color: var(--gray-600); font-weight: 500;">Rejected</div>
        </div>
    </div>
    <div class="card">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;">28</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Requests</div>
        </div>
    </div>
</div>

<!-- Leave Request Modal -->
<div id="leaveRequestModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-calendar-plus"></i>
                New Leave Request
            </div>
            <div class="modal-subtitle">Submit your leave request with complete details</div>
            <button class="modal-close" onclick="closeLeaveModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="{{ route('leaves.store') }}" method="POST" id="leaveRequestForm">
                @csrf
                <div class="form-container">
                    <!-- Employee Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_employee_id" class="form-label">
                                <i class="fas fa-user"></i>Employee ID
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="leave_employee_id" name="employee_id" class="form-input" placeholder="Enter employee ID" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="leave_employee_name" class="form-label">
                                <i class="fas fa-user-tag"></i>Employee Name
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-tag"></i>
                                <input type="text" id="leave_employee_name" name="employee_name" class="form-input" placeholder="Enter employee name" required>
                            </div>
                        </div>
                    </div>

                    <!-- Leave Type and Department Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_type" class="form-label">
                                <i class="fas fa-list-ul"></i>Leave Type
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-list-ul"></i>
                                <select id="leave_type" name="leave_type" class="form-select" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="Annual Leave">Annual Leave</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Emergency Leave">Emergency Leave</option>
                                    <option value="Maternity Leave">Maternity Leave</option>
                                    <option value="Paternity Leave">Paternity Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Medical Leave">Medical Leave</option>
                                    <option value="Personal Leave">Personal Leave</option>
                                    <option value="Study Leave">Study Leave</option>
                                    <option value="Unpaid Leave">Unpaid Leave</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="leave_department" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-building"></i>
                                <select id="leave_department" name="department" class="form-select" required>
                                    <option value="">Select Department</option>
                                    <option value="HR">Human Resources</option>
                                    <option value="IT">Information Technology</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Design">Design Team</option>
                                    <option value="Development">Development Team</option>
                                    <option value="Data">Data Team</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_start_date" class="form-label">
                                <i class="fas fa-calendar-alt"></i>Start Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-alt"></i>
                                <input type="date" id="leave_start_date" name="start_date" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="leave_end_date" class="form-label">
                                <i class="fas fa-calendar-check"></i>End Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-check"></i>
                                <input type="date" id="leave_end_date" name="end_date" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    <!-- Duration and Contact Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_duration" class="form-label">
                                <i class="fas fa-clock"></i>Duration (Days)
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-clock"></i>
                                <input type="number" id="leave_duration" name="duration" class="form-input" placeholder="Auto-calculated" min="1" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="leave_contact" class="form-label">
                                <i class="fas fa-phone"></i>Emergency Contact
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-phone"></i>
                                <input type="tel" id="leave_contact" name="emergency_contact" class="form-input" placeholder="Enter contact number">
                            </div>
                        </div>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="leave_status" class="form-label">
                                <i class="fas fa-tasks"></i>Status
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-tasks"></i>
                                <select id="leave_status" name="status" class="form-select" required>
                                    <option value="Pending" selected>Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="leave_priority" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i>Priority
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-exclamation-triangle"></i>
                                <select id="leave_priority" name="priority" class="form-select">
                                    <option value="Normal" selected>Normal</option>
                                    <option value="Urgent">Urgent</option>
                                    <option value="High">High</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Supervisor Information -->
                    <div class="form-group">
                        <label for="leave_supervisor" class="form-label">
                            <i class="fas fa-user-tie"></i>Reporting Supervisor
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tie"></i>
                            <input type="text" id="leave_supervisor" name="supervisor" class="form-input" placeholder="Enter supervisor name">
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="form-group">
                        <label for="leave_reason" class="form-label">
                            <i class="fas fa-comment-alt"></i>Reason for Leave
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-comment-alt"></i>
                            <textarea id="leave_reason" name="reason" class="form-textarea" placeholder="Please provide detailed reason for your leave request" rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="leave_notes" class="form-label">
                            <i class="fas fa-sticky-note"></i>Additional Notes
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-sticky-note"></i>
                            <textarea id="leave_notes" name="additional_notes" class="form-textarea" placeholder="Any additional information or special instructions" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeLeaveModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="button" class="btn btn-warning" onclick="saveDraftLeave()">
                            <i class="fas fa-save"></i> Save as Draft
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i> Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leave Edit Modal -->
<div id="editLeaveModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Leave Request
            </div>
            <div class="modal-subtitle">Update the leave request details below</div>
            <button class="modal-close" onclick="closeEditLeaveModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <form action="#" method="POST" id="editLeaveForm">
                @csrf
                @method('PUT')
                <div class="form-container">
                    <!-- Employee Information Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_employee_name" class="form-label">
                                <i class="fas fa-user"></i>Employee Name
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user"></i>
                                <input type="text" id="edit_employee_name" name="employee_name" class="form-input" placeholder="Enter employee name" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_employee_id" class="form-label">
                                <i class="fas fa-user-tag"></i>Employee ID
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-tag"></i>
                                <input type="text" id="edit_employee_id" name="employee_id" class="form-input" placeholder="Enter employee ID" required>
                            </div>
                        </div>
                    </div>

                    <!-- Department and Leave Type Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-building"></i>
                                <select id="edit_department" name="department" class="form-select" required>
                                    <option value="">Select Department</option>
                                    <option value="HR">Human Resources</option>
                                    <option value="IT">Information Technology</option>
                                    <option value="Finance">Finance</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Operations">Operations</option>
                                    <option value="Sales">Sales</option>
                                    <option value="Design">Design Team</option>
                                    <option value="Development">Development Team</option>
                                    <option value="Data">Data Team</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_leave_type" class="form-label">
                                <i class="fas fa-list-ul"></i>Leave Type
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-list-ul"></i>
                                <select id="edit_leave_type" name="leave_type" class="form-select" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="Annual Leave">Annual Leave</option>
                                    <option value="Sick Leave">Sick Leave</option>
                                    <option value="Emergency Leave">Emergency Leave</option>
                                    <option value="Maternity Leave">Maternity Leave</option>
                                    <option value="Paternity Leave">Paternity Leave</option>
                                    <option value="Casual Leave">Casual Leave</option>
                                    <option value="Medical Leave">Medical Leave</option>
                                    <option value="Personal Leave">Personal Leave</option>
                                    <option value="Study Leave">Study Leave</option>
                                    <option value="Unpaid Leave">Unpaid Leave</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Date Range Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_start_date" class="form-label">
                                <i class="fas fa-calendar-alt"></i>Start Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-alt"></i>
                                <input type="date" id="edit_start_date" name="start_date" class="form-input" required>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_end_date" class="form-label">
                                <i class="fas fa-calendar-check"></i>End Date
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-calendar-check"></i>
                                <input type="date" id="edit_end_date" name="end_date" class="form-input" required>
                            </div>
                        </div>
                    </div>

                    <!-- Duration and Status Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_duration" class="form-label">
                                <i class="fas fa-clock"></i>Duration (Days)
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-clock"></i>
                                <input type="number" id="edit_duration" name="duration" class="form-input" placeholder="Auto-calculated" min="1" readonly>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_status" class="form-label">
                                <i class="fas fa-tasks"></i>Status
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-tasks"></i>
                                <select id="edit_status" name="status" class="form-select" required>
                                    <option value="Pending">Pending</option>
                                    <option value="Approved">Approved</option>
                                    <option value="Rejected">Rejected</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Priority and Supervisor Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_priority" class="form-label">
                                <i class="fas fa-exclamation-triangle"></i>Priority
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-exclamation-triangle"></i>
                                <select id="edit_priority" name="priority" class="form-select">
                                    <option value="Normal">Normal</option>
                                    <option value="Urgent">Urgent</option>
                                    <option value="High">High</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="edit_supervisor" class="form-label">
                                <i class="fas fa-user-tie"></i>Supervisor
                            </label>
                            <div style="position: relative;">
                                <i class="input-icon fas fa-user-tie"></i>
                                <input type="text" id="edit_supervisor" name="supervisor" class="form-input" placeholder="Enter supervisor name">
                            </div>
                        </div>
                    </div>

                    <!-- Emergency Contact -->
                    <div class="form-group">
                        <label for="edit_emergency_contact" class="form-label">
                            <i class="fas fa-phone"></i>Emergency Contact
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-phone"></i>
                            <input type="tel" id="edit_emergency_contact" name="emergency_contact" class="form-input" placeholder="Enter contact number">
                        </div>
                    </div>

                    <!-- Reason -->
                    <div class="form-group">
                        <label for="edit_reason" class="form-label">
                            <i class="fas fa-comment-alt"></i>Reason for Leave
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-comment-alt"></i>
                            <textarea id="edit_reason" name="reason" class="form-textarea" placeholder="Please provide detailed reason for your leave request" rows="4" required></textarea>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="form-group">
                        <label for="edit_notes" class="form-label">
                            <i class="fas fa-sticky-note"></i>Additional Notes
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-sticky-note"></i>
                            <textarea id="edit_notes" name="additional_notes" class="form-textarea" placeholder="Any additional information or special instructions" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditLeaveModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Leave Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Leave View Modal -->
<div id="viewLeaveModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-eye"></i>
                Leave Request Details
            </div>
            <div class="modal-subtitle">View complete leave request information</div>
            <button class="modal-close" onclick="closeViewLeaveModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <div class="modal-body">
            <div class="form-container">
                <!-- Employee Information Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user"></i>Employee Name
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user"></i>
                            <div class="view-field" id="view_employee_name"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tag"></i>Employee ID
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tag"></i>
                            <div class="view-field" id="view_employee_id"></div>
                        </div>
                    </div>
                </div>

                <!-- Department and Leave Type Row -->
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
                            <i class="fas fa-list-ul"></i>Leave Type
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-list-ul"></i>
                            <div class="view-field" id="view_leave_type"></div>
                        </div>
                    </div>
                </div>

                <!-- Date Range Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-calendar-alt"></i>Start Date
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-calendar-alt"></i>
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

                <!-- Duration and Status Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-clock"></i>Duration
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-clock"></i>
                            <div class="view-field" id="view_duration"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-tasks"></i>Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-tasks"></i>
                            <div class="view-field status-badge" id="view_status"></div>
                        </div>
                    </div>
                </div>

                <!-- Priority and Supervisor Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle"></i>Priority
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-exclamation-triangle"></i>
                            <div class="view-field" id="view_priority"></div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tie"></i>Supervisor
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tie"></i>
                            <div class="view-field" id="view_supervisor"></div>
                        </div>
                    </div>
                </div>

                <!-- Emergency Contact -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-phone"></i>Emergency Contact
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-phone"></i>
                        <div class="view-field" id="view_emergency_contact"></div>
                    </div>
                </div>

                <!-- Reason -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-comment-alt"></i>Reason for Leave
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-comment-alt"></i>
                        <div class="view-field view-textarea" id="view_reason"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewLeaveModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openEditLeaveModalFromView()">
                        <i class="fas fa-edit"></i> Edit Leave
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Leave Edit Modal Functions
function openEditLeaveModal(employeeName, employeeId, department, leaveType, startDate, endDate, duration, status, priority, supervisor, emergencyContact, reason, notes) {
    // Debug log to verify data is being passed
    console.log('Opening edit leave modal with data:', {
        employeeName, employeeId, department, leaveType, startDate, endDate, duration, status, priority, supervisor, emergencyContact, reason, notes
    });
    
    // Clear any previous validation styles
    document.querySelectorAll('#editLeaveModal .form-input, #editLeaveModal .form-select, #editLeaveModal .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
    
    // Populate form fields with the leave data
    const editEmployeeName = document.getElementById('edit_employee_name');
    const editEmployeeId = document.getElementById('edit_employee_id');
    const editDepartment = document.getElementById('edit_department');
    const editLeaveType = document.getElementById('edit_leave_type');
    const editStartDate = document.getElementById('edit_start_date');
    const editEndDate = document.getElementById('edit_end_date');
    const editDuration = document.getElementById('edit_duration');
    const editStatus = document.getElementById('edit_status');
    const editPriority = document.getElementById('edit_priority');
    const editSupervisor = document.getElementById('edit_supervisor');
    const editEmergencyContact = document.getElementById('edit_emergency_contact');
    const editReason = document.getElementById('edit_reason');
    const editNotes = document.getElementById('edit_notes');
    
    // Set values with null checks and enhanced visual feedback - matching project modal appearance
    if (editEmployeeName) {
        editEmployeeName.value = employeeName || '';
        if (editEmployeeName.value) {
            editEmployeeName.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editEmployeeName.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editEmployeeName.style.color = 'var(--text-primary)';
            editEmployeeName.style.fontWeight = '600'; // Match project modal font weight
            editEmployeeName.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editEmployeeName.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editEmployeeName.setAttribute('data-populated', 'true');
        }
    }
    
    if (editEmployeeId) {
        editEmployeeId.value = employeeId || '';
        if (editEmployeeId.value) {
            editEmployeeId.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editEmployeeId.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editEmployeeId.style.color = 'var(--text-primary)';
            editEmployeeId.style.fontWeight = '600'; // Match project modal font weight
            editEmployeeId.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editEmployeeId.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editEmployeeId.setAttribute('data-populated', 'true');
        }
    }
    
    if (editDepartment) {
        // Map department values to select options
        const departmentMapping = {
            'Design Team': 'Design',
            'Development Team': 'Development',
            'Marketing Team': 'Marketing',
            'HR Team': 'HR',
            'Human Resources': 'HR',
            'Information Technology': 'IT'
        };
        const mappedDepartment = departmentMapping[department] || department;
        editDepartment.value = mappedDepartment || '';
        if (editDepartment.value) {
            editDepartment.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editDepartment.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editDepartment.style.color = 'var(--text-primary)';
            editDepartment.style.fontWeight = '600'; // Match project modal font weight
            editDepartment.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editDepartment.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editDepartment.setAttribute('data-populated', 'true');
        }
    }
    
    if (editLeaveType) {
        editLeaveType.value = leaveType || '';
        if (editLeaveType.value) {
            editLeaveType.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editLeaveType.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editLeaveType.style.color = 'var(--text-primary)';
            editLeaveType.style.fontWeight = '600'; // Match project modal font weight
            editLeaveType.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editLeaveType.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editLeaveType.setAttribute('data-populated', 'true');
        }
    }
    
    if (editStartDate) {
        // Convert date format from "Dec 20, 2024" to "2024-12-20"
        const formattedStartDate = convertDateToISO(startDate);
        editStartDate.value = formattedStartDate || '';
        if (editStartDate.value) {
            editStartDate.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editStartDate.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editStartDate.style.color = 'var(--text-primary)';
            editStartDate.style.fontWeight = '600'; // Match project modal font weight
            editStartDate.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editStartDate.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editStartDate.setAttribute('data-populated', 'true');
        }
    }
    
    if (editEndDate) {
        // Convert date format from "Dec 27, 2024" to "2024-12-27"
        const formattedEndDate = convertDateToISO(endDate);
        editEndDate.value = formattedEndDate || '';
        if (editEndDate.value) {
            editEndDate.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editEndDate.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editEndDate.style.color = 'var(--text-primary)';
            editEndDate.style.fontWeight = '600'; // Match project modal font weight
            editEndDate.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editEndDate.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editEndDate.setAttribute('data-populated', 'true');
        }
    }
    
    if (editDuration) {
        editDuration.value = duration || '';
        if (editDuration.value) {
            editDuration.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editDuration.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editDuration.style.color = 'var(--text-primary)';
            editDuration.style.fontWeight = '600'; // Match project modal font weight
            editDuration.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editDuration.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editDuration.setAttribute('data-populated', 'true');
        }
    }
    
    if (editStatus) {
        editStatus.value = status || '';
        if (editStatus.value) {
            editStatus.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editStatus.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editStatus.style.color = 'var(--text-primary)';
            editStatus.style.fontWeight = '600'; // Match project modal font weight
            editStatus.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editStatus.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editStatus.setAttribute('data-populated', 'true');
        }
    }
    
    if (editPriority) {
        editPriority.value = priority || '';
        if (editPriority.value) {
            editPriority.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editPriority.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editPriority.style.color = 'var(--text-primary)';
            editPriority.style.fontWeight = '600'; // Match project modal font weight
            editPriority.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editPriority.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editPriority.setAttribute('data-populated', 'true');
        }
    }
    
    if (editSupervisor) {
        editSupervisor.value = supervisor || '';
        if (editSupervisor.value) {
            editSupervisor.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editSupervisor.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editSupervisor.style.color = 'var(--text-primary)';
            editSupervisor.style.fontWeight = '600'; // Match project modal font weight
            editSupervisor.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editSupervisor.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editSupervisor.setAttribute('data-populated', 'true');
        }
    }
    
    if (editEmergencyContact) {
        editEmergencyContact.value = emergencyContact || '';
        if (editEmergencyContact.value) {
            editEmergencyContact.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editEmergencyContact.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editEmergencyContact.style.color = 'var(--text-primary)';
            editEmergencyContact.style.fontWeight = '600'; // Match project modal font weight
            editEmergencyContact.style.padding = '12px 16px 12px 48px'; // Ensure consistent spacing
            editEmergencyContact.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editEmergencyContact.setAttribute('data-populated', 'true');
        }
    }
    
    if (editReason) {
        editReason.value = reason || '';
        if (editReason.value) {
            editReason.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editReason.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editReason.style.color = 'var(--text-primary)';
            editReason.style.fontWeight = '600'; // Match project modal font weight
            editReason.style.padding = '16px 16px 16px 48px'; // Textarea specific padding
            editReason.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editReason.setAttribute('data-populated', 'true');
        }
    }
    
    if (editNotes) {
        editNotes.value = notes || '';
        if (editNotes.value) {
            editNotes.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            editNotes.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            editNotes.style.color = 'var(--text-primary)';
            editNotes.style.fontWeight = '600'; // Match project modal font weight
            editNotes.style.padding = '16px 16px 16px 48px'; // Textarea specific padding
            editNotes.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            editNotes.setAttribute('data-populated', 'true');
        }
    }
    
    // Enhanced icon styling for populated fields - matching project modal
    document.querySelectorAll('#editLeaveModal .input-icon').forEach(icon => {
        const field = icon.nextElementSibling;
        if (field && field.value && field.value.trim()) {
            icon.style.color = 'var(--redcode-green)';
            icon.style.transform = 'translateY(-50%) scale(1.05)';
            icon.style.textShadow = '0 0 8px rgba(16, 185, 129, 0.3)';
        }
    });
    
    // Trigger change events to ensure proper styling
    [editEmployeeName, editEmployeeId, editDepartment, editLeaveType, editStartDate, editEndDate, editDuration, editStatus, editPriority, editSupervisor, editEmergencyContact, editReason, editNotes].forEach(field => {
        if (field && field.value) {
            field.dispatchEvent(new Event('input', { bubbles: true }));
        }
    });
    
    // Setup duration calculation for edit modal
    setupEditLeaveDateCalculation();
    
    // Setup enhanced input interactions
    setupEditInputEnhancements();
    
    // Show modal
    document.getElementById('editLeaveModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeEditLeaveModal() {
    document.getElementById('editLeaveModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    
    // Reset form and clear data-populated attributes
    document.getElementById('editLeaveForm').reset();
    document.querySelectorAll('#editLeaveModal .form-input, #editLeaveModal .form-select, #editLeaveModal .form-textarea').forEach(field => {
        field.removeAttribute('data-populated');
        field.style.borderColor = '';
        field.style.background = '';
    });
}

function setupEditLeaveDateCalculation() {
    const startDateInput = document.getElementById('edit_start_date');
    const endDateInput = document.getElementById('edit_end_date');
    const durationInput = document.getElementById('edit_duration');
    
    function calculateDuration() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate >= startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include both start and end dates
            durationInput.value = dayDiff;
        } else {
            durationInput.value = '';
        }
    }
    
    startDateInput.addEventListener('change', calculateDuration);
    endDateInput.addEventListener('change', calculateDuration);
    
    // Date validation - ensure end date is after start date
    endDateInput.addEventListener('change', function() {
        const startDate = startDateInput.value;
        const endDate = this.value;
        
        if (startDate && endDate && endDate < startDate) {
            alert('End date cannot be before start date.');
            this.value = '';
        }
    });
}

function setupEditInputEnhancements() {
    // Enhanced input interactions for edit modal - matching project modal
    document.querySelectorAll('#editLeaveModal .form-input, #editLeaveModal .form-select, #editLeaveModal .form-textarea').forEach(input => {
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
                    icon.style.textShadow = '0 0 8px rgba(16, 185, 129, 0.3)';
                } else {
                    icon.style.color = 'var(--text-light)';
                    icon.style.transform = 'translateY(-50%) scale(1)';
                    icon.style.textShadow = '';
                }
            }
        });
        
        // Add visual indication for fields with data - enhanced for edit modal to match project modal
        input.addEventListener('input', function() {
            const icon = this.previousElementSibling;
            if (this.value.trim()) {
                // Style to match project modal appearance
                this.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
                this.style.borderColor = 'rgba(16, 185, 129, 0.3)';
                this.style.color = 'var(--text-primary)';
                this.style.fontWeight = '600'; // Match project modal font weight
                this.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
                
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
                this.style.boxShadow = '';
                this.style.padding = ''; // Reset to CSS default
                this.removeAttribute('data-populated');
                
                if (icon && icon.classList.contains('input-icon')) {
                    icon.style.color = 'var(--text-light)';
                    icon.style.transform = 'translateY(-50%) scale(1)';
                    icon.style.textShadow = '';
                }
            }
        });
        
        // Initialize styling for pre-populated fields (edit modal) - match project modal appearance
        if (input.value && input.value.trim()) {
            input.style.background = 'rgba(16, 185, 129, 0.05)'; // Match project modal background
            input.style.borderColor = 'rgba(16, 185, 129, 0.3)';
            input.style.color = 'var(--text-primary)';
            input.style.fontWeight = '600'; // Match project modal font weight
            input.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.08)';
            
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
                icon.style.textShadow = '0 0 8px rgba(16, 185, 129, 0.3)';
            }
        }
    });
}

// Helper function to convert date format
function convertDateToISO(dateString) {
    if (!dateString) return '';
    
    // Handle different date formats
    const months = {
        'Jan': '01', 'Feb': '02', 'Mar': '03', 'Apr': '04', 'May': '05', 'Jun': '06',
        'Jul': '07', 'Aug': '08', 'Sep': '09', 'Oct': '10', 'Nov': '11', 'Dec': '12'
    };
    
    // Format: "Dec 20, 2024"
    const parts = dateString.split(' ');
    if (parts.length === 3) {
        const month = months[parts[0]];
        const day = parts[1].replace(',', '').padStart(2, '0');
        const year = parts[2];
        return `${year}-${month}-${day}`;
    }
    
    return dateString;
}

// Updated openEditLeaveModalFromView function
function openEditLeaveModalFromView() {
    // Close view modal first
    closeViewLeaveModal();
    
    // Get data from view modal and open edit modal
    const employeeName = document.getElementById('view_employee_name').textContent;
    const employeeId = document.getElementById('view_employee_id').textContent;
    const department = document.getElementById('view_department').textContent;
    const leaveType = document.getElementById('view_leave_type').textContent;
    const startDate = document.getElementById('view_start_date').textContent;
    const endDate = document.getElementById('view_end_date').textContent;
    const duration = document.getElementById('view_duration').textContent.replace(' days', '');
    const status = document.getElementById('view_status').textContent;
    const priority = document.getElementById('view_priority').textContent;
    const supervisor = document.getElementById('view_supervisor').textContent;
    const emergencyContact = document.getElementById('view_emergency_contact').textContent;
    const reason = document.getElementById('view_reason').textContent;
    
    // Open edit modal with the data
    openEditLeaveModal(employeeName, employeeId, department, leaveType, startDate, endDate, duration, status, priority, supervisor, emergencyContact, reason, '');
}

// Leave View Modal Functions
function openViewLeaveModal(employeeName, employeeId, department, leaveType, startDate, endDate, duration, status, priority, supervisor, emergencyContact, reason) {
    // Populate view modal fields
    document.getElementById('view_employee_name').textContent = employeeName || 'N/A';
    document.getElementById('view_employee_id').textContent = employeeId || 'N/A';
    document.getElementById('view_department').textContent = department || 'N/A';
    document.getElementById('view_leave_type').textContent = leaveType || 'N/A';
    document.getElementById('view_start_date').textContent = startDate || 'N/A';
    document.getElementById('view_end_date').textContent = endDate || 'N/A';
    document.getElementById('view_duration').textContent = duration ? duration + ' days' : 'N/A';
    document.getElementById('view_priority').textContent = priority || 'N/A';
    document.getElementById('view_supervisor').textContent = supervisor || 'N/A';
    document.getElementById('view_emergency_contact').textContent = emergencyContact || 'N/A';
    document.getElementById('view_reason').textContent = reason || 'No reason provided';
    
    // Set status with appropriate styling
    const statusElement = document.getElementById('view_status');
    statusElement.textContent = status || 'N/A';
    statusElement.className = 'view-field status-badge';
    
    if (status) {
        statusElement.classList.add(status.toLowerCase());
    }
    
    // Show modal
    document.getElementById('viewLeaveModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeViewLeaveModal() {
    document.getElementById('viewLeaveModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openEditLeaveModalFromView() {
    // Close view modal first
    closeViewLeaveModal();
    
    // Get data from view modal and open edit modal
    const employeeName = document.getElementById('view_employee_name').textContent;
    const employeeId = document.getElementById('view_employee_id').textContent;
    const department = document.getElementById('view_department').textContent;
    const leaveType = document.getElementById('view_leave_type').textContent;
    const startDate = document.getElementById('view_start_date').textContent;
    const endDate = document.getElementById('view_end_date').textContent;
    const duration = document.getElementById('view_duration').textContent.replace(' days', '');
    const status = document.getElementById('view_status').textContent;
    const priority = document.getElementById('view_priority').textContent;
    const supervisor = document.getElementById('view_supervisor').textContent;
    const emergencyContact = document.getElementById('view_emergency_contact').textContent;
    const reason = document.getElementById('view_reason').textContent;
    
    // Here you would open the edit modal with the data
    // openEditLeaveModal(employeeName, employeeId, department, leaveType, startDate, endDate, duration, status, priority, supervisor, emergencyContact, reason);
    alert('Edit modal would open here with the leave data');
}
function openLeaveModal() {
    document.getElementById('leaveRequestModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    
    // Set default date values
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('leave_start_date').value = today;
    
    // Auto-calculate duration when dates change
    setupLeaveDateCalculation();
    
    // Enhanced input interactions
    setupInputEnhancements();
}

function closeLeaveModal() {
    document.getElementById('leaveRequestModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('leaveRequestForm').reset();
    
    // Clear any validation styles
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
}

function setupLeaveDateCalculation() {
    const startDateInput = document.getElementById('leave_start_date');
    const endDateInput = document.getElementById('leave_end_date');
    const durationInput = document.getElementById('leave_duration');
    
    function calculateDuration() {
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);
        
        if (startDate && endDate && endDate >= startDate) {
            const timeDiff = endDate.getTime() - startDate.getTime();
            const dayDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Include both start and end dates
            durationInput.value = dayDiff;
        } else {
            durationInput.value = '';
        }
    }
    
    startDateInput.addEventListener('change', calculateDuration);
    endDateInput.addEventListener('change', calculateDuration);
    
    // Date validation - ensure end date is after start date
    endDateInput.addEventListener('change', function() {
        const startDate = startDateInput.value;
        const endDate = this.value;
        
        if (startDate && endDate && endDate < startDate) {
            alert('End date cannot be before start date.');
            this.value = '';
        }
    });
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

function saveDraftLeave() {
    const form = document.getElementById('leaveRequestForm');
    const formData = new FormData(form);
    formData.append('status', 'Draft');
    
    // Here you would typically save to local storage or send to server as draft
    alert('Leave request saved as draft!');
    closeLeaveModal();
}

// Form validation for leave request
document.getElementById('leaveRequestForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = ['employee_id', 'employee_name', 'leave_type', 'department', 'start_date', 'end_date', 'reason'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById('leave_' + fieldName);
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
    
    // Date validation
    const startDate = new Date(document.getElementById('leave_start_date').value);
    const endDate = new Date(document.getElementById('leave_end_date').value);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    
    if (startDate < today) {
        alert('Start date cannot be in the past');
        return;
    }
    
    if (endDate < startDate) {
        alert('End date cannot be before start date');
        return;
    }
    
    // If validation passes, submit the form
    alert('Leave request submitted successfully!');
    closeLeaveModal();
    
    // Here you would typically send the data to your server
    // this.submit(); // Uncomment this to actually submit the form
});

// Form validation for edit leave request
document.getElementById('editLeaveForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Basic validation
    const requiredFields = ['employee_name', 'employee_id', 'leave_type', 'department', 'start_date', 'end_date', 'reason'];
    let isValid = true;
    
    requiredFields.forEach(fieldName => {
        const field = document.getElementById('edit_' + fieldName);
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
    
    // Date validation
    const startDate = new Date(document.getElementById('edit_start_date').value);
    const endDate = new Date(document.getElementById('edit_end_date').value);
    
    if (endDate < startDate) {
        alert('End date cannot be before start date');
        return;
    }
    
    // If validation passes, submit the form
    alert('Leave request updated successfully!');
    closeEditLeaveModal();
    
    // Here you would typically send the data to your server
    // this.submit(); // Uncomment this to actually submit the form
});

// Close leave modal when clicking outside
document.getElementById('leaveRequestModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLeaveModal();
    }
});

// Close edit modal when clicking outside
document.getElementById('editLeaveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditLeaveModal();
    }
});

// Close view modal when clicking outside
document.getElementById('viewLeaveModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewLeaveModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const leaveModal = document.getElementById('leaveRequestModal');
        const editModal = document.getElementById('editLeaveModal');
        const viewModal = document.getElementById('viewLeaveModal');
        
        if (leaveModal.classList.contains('active')) {
            closeLeaveModal();
        }
        if (editModal.classList.contains('active')) {
            closeEditLeaveModal();
        }
        if (viewModal.classList.contains('active')) {
            closeViewLeaveModal();
        }
    }
});
</script>

@endsection
