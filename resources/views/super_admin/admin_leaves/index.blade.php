@extends('layouts.super_admin')

@section('title', 'Admin Leave Management')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-calendar-check"></i>
            Admin Leave Management
        </h1>
        <p class="page-description">
            Manage and approve leave requests from administrative staff
        </p>
    </div>
    <div class="header-actions">
        <button class="btn btn-secondary" onclick="exportReport()">
            <i class="fas fa-download"></i>
            Export Report
        </button>
        <button class="btn btn-primary" onclick="refreshData()">
            <i class="fas fa-sync-alt"></i>
            Refresh
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon pending">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $pendingCount }}</h3>
            <p class="stat-label">Pending Requests</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon approved">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $approvedCount }}</h3>
            <p class="stat-label">Approved Today</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon rejected">
            <i class="fas fa-times-circle"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $rejectedCount }}</h3>
            <p class="stat-label">Rejected</p>
        </div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-list-alt"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $totalLeaves }}</h3>
            <p class="stat-label">Total Requests</p>
        </div>
    </div>
</div>

<!-- Content Tabs -->
<div class="content-tabs">
    <div class="tab-navigation">
        <button class="tab-button active" data-tab="pending">
            <i class="fas fa-clock"></i>
            Pending Requests
            <span class="tab-badge">{{ $pendingCount }}</span>
        </button>
        <button class="tab-button" data-tab="approved">
            <i class="fas fa-check-circle"></i>
            Approved
            <span class="tab-badge">{{ $approvedCount }}</span>
        </button>
        <button class="tab-button" data-tab="rejected">
            <i class="fas fa-times-circle"></i>
            Rejected
            <span class="tab-badge">{{ $rejectedCount }}</span>
        </button>
        <button class="tab-button" data-tab="all">
            <i class="fas fa-list-alt"></i>
            All Requests
            <span class="tab-badge">{{ $totalLeaves }}</span>
        </button>
    </div>

    <!-- Pending Requests Tab -->
    <div class="tab-panel active" id="pending-panel">
        <div class="panel-header">
            <h3>Pending Leave Requests</h3>
            <div class="bulk-actions">
                <button class="btn btn-success" onclick="bulkApprove()">
                    <i class="fas fa-check"></i>
                    Approve Selected
                </button>
                <button class="btn btn-danger" onclick="bulkReject()">
                    <i class="fas fa-times"></i>
                    Reject Selected
                </button>
            </div>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>
                            <input type="checkbox" id="select-all-pending" onchange="toggleSelectAll('pending')">
                        </th>
                        <th>Admin</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pendingLeaves as $leave)
                    <tr>
                        <td>
                            <input type="checkbox" class="leave-checkbox" value="{{ $leave->leave_id }}">
                        </td>
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($leave->employee->employee_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="employee-details">
                                    <div class="employee-name">{{ $leave->employee->employee_name ?? 'Unknown Admin' }}</div>
                                    <div class="employee-id">{{ $leave->employee->employee_id ?? 'N/A' }}</div>
                                    <div class="employee-role">{{ $leave->employee->admin->admin_name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $leave->leave_type }}">
                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="duration-info">
                                <span class="duration-days">{{ $leave->duration }} days</span>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date-range">
                                    {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="reason-text">
                                {{ Str::limit($leave->reason, 50) }}
                                @if($leave->supporting_doc)
                                    <i class="fas fa-paperclip attachment-icon"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="applied-info">
                                <div class="applied-date">{{ $leave->applied_date->format('M d, Y') }}</div>
                                <div class="applied-time">{{ $leave->applied_date->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-sm btn-success" onclick="approveLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="rejectLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-inbox"></i>
                                <h4>No Pending Requests</h4>
                                <p>All admin leave requests have been processed.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approved Requests Tab -->
    <div class="tab-panel" id="approved-panel">
        <div class="panel-header">
            <h3>Approved Leave Requests</h3>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Approved By</th>
                        <th>Approved Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($approvedLeaves as $leave)
                    <tr>
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($leave->employee->employee_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="employee-details">
                                    <div class="employee-name">{{ $leave->employee->employee_name ?? 'Unknown Admin' }}</div>
                                    <div class="employee-id">{{ $leave->employee->employee_id ?? 'N/A' }}</div>
                                    <div class="employee-role">{{ $leave->employee->admin->admin_name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $leave->leave_type }}">
                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="duration-info">
                                <span class="duration-days">{{ $leave->duration }} days</span>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date-range">
                                    {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="reason-text">
                                {{ Str::limit($leave->reason, 50) }}
                                @if($leave->supporting_doc)
                                    <i class="fas fa-paperclip attachment-icon"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="approver-info">
                                <div class="approver-name">{{ $leave->approvedBy->admin_name ?? 'System' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="approved-info">
                                <div class="approved-date">{{ $leave->approved_date->format('M d, Y') }}</div>
                                <div class="approved-time">{{ $leave->approved_date->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-check-circle"></i>
                                <h4>No Approved Requests</h4>
                                <p>No admin leave requests have been approved yet.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Rejected Requests Tab -->
    <div class="tab-panel" id="rejected-panel">
        <div class="panel-header">
            <h3>Rejected Leave Requests</h3>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Dates</th>
                        <th>Reason</th>
                        <th>Rejected By</th>
                        <th>Rejection Reason</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rejectedLeaves as $leave)
                    <tr>
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($leave->employee->employee_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="employee-details">
                                    <div class="employee-name">{{ $leave->employee->employee_name ?? 'Unknown Admin' }}</div>
                                    <div class="employee-id">{{ $leave->employee->employee_id ?? 'N/A' }}</div>
                                    <div class="employee-role">{{ $leave->employee->admin->admin_name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $leave->leave_type }}">
                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="duration-info">
                                <span class="duration-days">{{ $leave->duration }} days</span>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date-range">
                                    {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="reason-text">
                                {{ Str::limit($leave->reason, 50) }}
                                @if($leave->supporting_doc)
                                    <i class="fas fa-paperclip attachment-icon"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="rejecter-info">
                                <div class="rejecter-name">{{ $leave->rejectedBy->admin_name ?? 'System' }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="rejection-reason">
                                {{ Str::limit($leave->rejection_reason, 50) }}
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-times-circle"></i>
                                <h4>No Rejected Requests</h4>
                                <p>No admin leave requests have been rejected.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- All Requests Tab -->
    <div class="tab-panel" id="all-panel">
        <div class="panel-header">
            <h3>All Admin Leave Requests</h3>
        </div>
        
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Admin</th>
                        <th>Leave Type</th>
                        <th>Duration</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Reason</th>
                        <th>Applied</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($adminLeaves as $leave)
                    <tr>
                        <td>
                            <div class="employee-info">
                                <div class="employee-avatar">
                                    {{ strtoupper(substr($leave->employee->employee_name ?? 'A', 0, 1)) }}
                                </div>
                                <div class="employee-details">
                                    <div class="employee-name">{{ $leave->employee->employee_name ?? 'Unknown Admin' }}</div>
                                    <div class="employee-id">{{ $leave->employee->employee_id ?? 'N/A' }}</div>
                                    <div class="employee-role">{{ $leave->employee->admin->admin_name ?? 'Admin' }}</div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $leave->leave_type }}">
                                {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                            </span>
                        </td>
                        <td>
                            <div class="duration-info">
                                <span class="duration-days">{{ $leave->duration }} days</span>
                            </div>
                        </td>
                        <td>
                            <div class="date-info">
                                <div class="date-range">
                                    {{ $leave->start_date->format('M d') }} - {{ $leave->end_date->format('M d, Y') }}
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $leave->status }}">
                                {{ ucfirst($leave->status) }}
                            </span>
                        </td>
                        <td>
                            <div class="reason-text">
                                {{ Str::limit($leave->reason, 50) }}
                                @if($leave->supporting_doc)
                                    <i class="fas fa-paperclip attachment-icon"></i>
                                @endif
                            </div>
                        </td>
                        <td>
                            <div class="applied-info">
                                <div class="applied-date">{{ $leave->applied_date->format('M d, Y') }}</div>
                                <div class="applied-time">{{ $leave->applied_date->format('h:i A') }}</div>
                            </div>
                        </td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm btn-info" onclick="viewLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-eye"></i>
                                </button>
                                @if($leave->status === 'pending')
                                <button class="btn btn-sm btn-success" onclick="approveLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-sm btn-danger" onclick="rejectLeave('{{ $leave->leave_id }}')">
                                    <i class="fas fa-times"></i>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <div class="empty-content">
                                <i class="fas fa-inbox"></i>
                                <h4>No Leave Requests</h4>
                                <p>No admin leave requests found.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Leave Details Modal -->
<div class="modal" id="leaveDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Leave Request Details</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="leaveDetailsContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content h1 i {
    color: var(--primary);
}

.page-description {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
    font-size: 0.875rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
}

.stat-icon.pending { background: var(--warning); }
.stat-icon.approved { background: var(--success); }
.stat-icon.rejected { background: var(--danger); }
.stat-icon.total { background: var(--info); }

.stat-number {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.stat-label {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.875rem;
}

/* Content Tabs */
.content-tabs {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tab-navigation {
    display: flex;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.tab-button {
    flex: 1;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.2s ease;
    position: relative;
}

.tab-button:hover {
    background: rgba(220, 38, 38, 0.05);
    color: var(--primary);
}

.tab-button.active {
    background: #fff;
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
}

.tab-badge {
    background: var(--primary);
    color: #fff;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.tab-panel {
    display: none;
    padding: 1.5rem;
}

.tab-panel.active {
    display: block;
}

/* Panel Header */
.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.panel-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.bulk-actions {
    display: flex;
    gap: 0.75rem;
}

/* Table Styles */
.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
}

.data-table th {
    background: var(--bg-secondary);
    padding: 1rem;
    text-align: left;
    font-weight: 600;
    color: var(--text-primary);
    border-bottom: 1px solid var(--border-light);
}

.data-table td {
    padding: 1rem;
    border-bottom: 1px solid var(--border-light);
    vertical-align: top;
}

.data-table tbody tr:hover {
    background: var(--bg-secondary);
}

/* Employee Info */
.employee-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.employee-avatar {
    width: 2.5rem;
    height: 2.5rem;
    border-radius: 50%;
    background: var(--gradient-primary);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.875rem;
}

.employee-name {
    font-weight: 600;
    color: var(--text-primary);
    margin-bottom: 0.25rem;
}

.employee-id {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

.employee-role {
    font-size: 0.75rem;
    color: var(--primary);
    font-weight: 500;
}

/* Badges */
.badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-pending { background: #FEF3C7; color: #92400E; }
.badge-approved { background: #D1FAE5; color: #065F46; }
.badge-rejected { background: #FEE2E2; color: #991B1B; }
.badge-sick { background: #DBEAFE; color: #1E40AF; }
.badge-casual { background: #F3E8FF; color: #7C3AED; }
.badge-annual { background: #FEF3C7; color: #92400E; }

/* Duration and Date Info */
.duration-info, .date-info {
    font-weight: 500;
    color: var(--text-primary);
}

.duration-days {
    font-size: 0.875rem;
}

.date-range {
    font-size: 0.875rem;
}

/* Reason Text */
.reason-text {
    max-width: 200px;
    color: var(--text-secondary);
    position: relative;
}

.attachment-icon {
    color: var(--primary);
    margin-left: 0.5rem;
}

/* Applied Info */
.applied-info, .approved-info {
    font-size: 0.75rem;
}

.applied-date, .approved-date {
    font-weight: 600;
    color: var(--text-primary);
}

.applied-time, .approved-time {
    color: var(--text-secondary);
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.5rem;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 0.375rem;
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-content {
    color: var(--text-secondary);
}

.empty-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-content h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
}

.empty-content p {
    margin: 0;
    font-size: 0.875rem;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-body {
    padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .tab-navigation {
        flex-wrap: wrap;
    }
    
    .tab-button {
        flex: 1 1 50%;
    }
    
    .panel-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .bulk-actions {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');
        
        // Remove active class from all tabs and panels
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));
        
        // Add active class to clicked tab and corresponding panel
        button.classList.add('active');
        document.getElementById(tabName + '-panel').classList.add('active');
    });
});

// Table functionality
function toggleSelectAll(tabName) {
    const checkboxes = document.querySelectorAll(`#${tabName}-panel .leave-checkbox`);
    const selectAllCheckbox = document.getElementById(`select-all-${tabName}`);
    
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAllCheckbox.checked;
    });
}

// Leave management functions
function viewLeave(leaveId) {
    // Load leave details via AJAX
    fetch(`/super_admin/admin-leaves/${leaveId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('leaveDetailsContent').innerHTML = html;
            document.getElementById('leaveDetailsModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading leave details:', error);
            alert('Error loading leave details');
        });
}

function approveLeave(leaveId) {
    if (confirm('Are you sure you want to approve this leave request?')) {
        fetch(`/super_admin/admin-leaves/${leaveId}/approve`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                comments: prompt('Add approval comments (optional):') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Leave request approved successfully');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error approving leave request');
        });
    }
}

function rejectLeave(leaveId) {
    const rejectionReason = prompt('Please provide a reason for rejection:');
    if (rejectionReason !== null) {
        fetch(`/super_admin/admin-leaves/${leaveId}/reject`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                rejection_reason: rejectionReason,
                comments: prompt('Add rejection comments (optional):') || ''
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Leave request rejected successfully');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting leave request');
        });
    }
}

function bulkApprove() {
    const selectedLeaves = Array.from(document.querySelectorAll('.leave-checkbox:checked')).map(cb => cb.value);
    
    if (selectedLeaves.length === 0) {
        alert('Please select leave requests to approve');
        return;
    }
    
    if (confirm(`Are you sure you want to approve ${selectedLeaves.length} leave requests?`)) {
        fetch('/super_admin/admin-leaves/bulk-approve', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                leave_ids: selectedLeaves
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error approving leave requests');
        });
    }
}

function bulkReject() {
    const selectedLeaves = Array.from(document.querySelectorAll('.leave-checkbox:checked')).map(cb => cb.value);
    
    if (selectedLeaves.length === 0) {
        alert('Please select leave requests to reject');
        return;
    }
    
    const rejectionReason = prompt('Please provide a reason for rejection:');
    if (rejectionReason !== null) {
        fetch('/super_admin/admin-leaves/bulk-reject', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                leave_ids: selectedLeaves,
                rejection_reason: rejectionReason
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error rejecting leave requests');
        });
    }
}

function closeModal() {
    document.getElementById('leaveDetailsModal').style.display = 'none';
}

function exportReport() {
    alert('Export functionality will be implemented here');
}

function refreshData() {
    location.reload();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('leaveDetailsModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endsection 