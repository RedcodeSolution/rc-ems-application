@extends('layouts.employee')
<link rel="stylesheet" href="{{ asset('css/Employee/myLeave.css') }}">
@section('title', 'Leave Management')

@section('content')
    <div class="leave-management-container">
        <!-- Header Section -->
        <div class="leave-header">
            <div class="header-content">
                <div class="header-info">
                    <h1><i class="fas fa-calendar-alt"></i> Leave Management</h1>
                    <p>Manage your leave requests and track your leave balance</p>
                </div>
                <div class="header-actions">
                    <button class="btn btn-primary" onclick="openApplyLeaveModal()">
                        <i class="fas fa-plus"></i>
                        Apply for Leave
                    </button>
                    <button class="btn btn-secondary" onclick="exportLeaveReport()">
                        <i class="fas fa-download"></i>
                        Export Report
                    </button>
                </div>
            </div>
        </div>

        <!-- Leave Statistics Dashboard -->
        <div class="leave-stats-section">
            <div class="stats-grid">
                <div class="stat-card annual-leave">
                    <div class="stat-icon">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Annual Leave</h3>
                        <div class="stat-numbers">
                            <span class="used">{{ $annualUsed }}</span>
                            <span class="separator">/</span>
                            <span class="total">{{ $annualTotal }}</span>
                            <span class="unit">days</span>
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($annualUsed / $annualTotal) * 100 }}%"></div>
                            </div>
                            <span class="remaining">{{ $annualTotal - $annualUsed }} days remaining</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card sick-leave">
                    <div class="stat-icon">
                        <i class="fas fa-thermometer-half"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Sick Leave</h3>
                        <div class="stat-numbers">
                            <span class="used">{{ $sickUsed }}</span>
                            <span class="separator">/</span>
                            <span class="total">{{ $sickTotal }}</span>
                            <span class="unit">days</span>
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($sickUsed / $sickTotal) * 100 }}%"></div>
                            </div>
                            <span class="remaining">{{ $sickTotal - $sickUsed }} days remaining</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card personal-leave">
                    <div class="stat-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Personal Leave</h3>
                        <div class="stat-numbers">
                            <span class="used">{{ $personalUsed }}</span>
                            <span class="separator">/</span>
                            <span class="total">{{ $personalTotal }}</span>
                            <span class="unit">days</span>
                        </div>
                        <div class="stat-progress">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: {{ ($personalUsed / $personalTotal) * 100 }}%">
                                </div>
                            </div>
                            <span class="remaining">{{ $personalTotal - $personalUsed }} days remaining</span>
                        </div>
                    </div>
                </div>

                <div class="stat-card pending-requests">
                    <div class="stat-icon">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="stat-content">
                        <h3>Pending Requests</h3>
                        <div class="stat-numbers">
                            <span class="used">{{ $pendingCount }}</span>
                            <span class="unit">requests</span>
                        </div>
                        <div class="stat-progress">
                            <div class="status-indicator pending">
                                <i class="fas fa-clock"></i>
                                <span>Awaiting approval</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions Section -->
        <div class="quick-actions-section">
            <div class="section-header">
                <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
            </div>
            <div class="actions-grid">
                <div class="action-card" onclick="openApplyLeaveModal()">
                    <div class="action-icon">
                        <i class="fas fa-plus-circle"></i>
                    </div>
                    <div class="action-content">
                        <h4>Apply for Leave</h4>
                        <p>Submit a new leave request</p>
                    </div>
                </div>
                <div class="action-card" onclick="viewLeaveHistory()">
                    <div class="action-icon">
                        <i class="fas fa-history"></i>
                    </div>
                    <div class="action-content">
                        <h4>Leave History</h4>
                        <p>View all your leave records</p>
                    </div>
                </div>
                <div class="action-card" onclick="checkLeaveBalance()">
                    <div class="action-icon">
                        <i class="fas fa-balance-scale"></i>
                    </div>
                    <div class="action-content">
                        <h4>Check Balance</h4>
                        <p>View detailed leave balance</p>
                    </div>
                </div>
                <div class="action-card" onclick="viewLeavePolicy()">
                    <div class="action-icon">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div class="action-content">
                        <h4>Leave Policy</h4>
                        <p>Read company leave policies</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Leave Requests -->
        <div class="recent-requests-section">
            <div class="section-header">
                <h2><i class="fas fa-calendar-day"></i> Recent Leave Requests</h2>
                <button class="btn btn-outline" onclick="viewAllLeaves()">
                    <i class="fas fa-eye"></i>
                    View All
                </button>
            </div>

            <div class="requests-container">
                @forelse ($recentLeaves as $leave)
                    <div class="request-card {{ $leave->status }}">
                        <div class="request-header">
                            <div class="request-info">
                                <h4>{{ $leave->leave_type }}</h4>
                                <span class="request-date">{{ \Carbon\Carbon::parse($leave->start_date)->format('F d') }}
                                    -
                                    {{ \Carbon\Carbon::parse($leave->end_date)->format('F d, Y') }}</span>
                            </div>
                            <div class="request-status {{ $leave->status }}">
                                @if ($leave->status === 'pending')
                                    <i class="fas fa-clock"></i>
                                @elseif ($leave->status === 'approved')
                                    <i class="fas fa-check-circle"></i>
                                @elseif ($leave->status === 'rejected')
                                    <i class="fas fa-times-circle"></i>
                                @endif
                                <span>{{ ucfirst($leave->status) }}</span>
                            </div>
                        </div>
                        <div class="request-details">
                            <div class="detail-item">
                                <span class="label">Duration:</span>
                                <span class="value">{{ $leave->duration }} days</span>
                            </div>
                            @if ($leave->status === 'rejected')
                                <div class="detail-item">
                                    <span class="label">Rejected by:</span>
                                    <span class="value">{{ $leave->rejectedBy->admin_name ?? 'N/A' }}</span>
                                </div>
                            @elseif ($leave->status === 'approved')
                                <div class="detail-item">
                                    <span class="label">Approved by:</span>
                                    <span class="value">{{ $leave->approvedBy->admin_name ?? 'N/A' }}</span>
                                </div>
                            @endif
                            <div class="detail-item">
                                <span class="label">Reason:</span>
                                <span class="value">{{ $leave->reason }} </span>
                            </div>
                        </div>
                        <div class="request-actions">
                            <button class="action-btn view" onclick="viewLeaveRequest('{{ $leave->leave_id }}')"
                                title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            @if ($leave->status === 'pending')
                                <button class="action-btn edit" onclick="editLeaveRequest('{{ $leave->leave_id }}')"
                                    title="Edit Request">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <!-- Cancel Button -->
                                <button class="action-btn cancel" onclick="cancelLeaveRequest('{{ $leave->leave_id }}')"
                                    title="Cancel Request">
                                    <i class="fas fa-times"></i>
                                </button>

                                <!-- Hidden Form -->
                                <form id="cancel-form-{{ $leave->leave_id }}"
                                    action="{{ route('employee.leaves.destroy', $leave->leave_id) }}" method="POST"
                                    style="display: none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @elseif ($leave->status === 'rejected')
                                <button class="action-btn reapply" onclick="reapplyLeave(this)" title="Reapply Leave"
                                    data-id="{{ $leave->leave_id }}" data-type="{{ $leave->leave_type }}"
                                    data-start="{{ $leave->start_date }}" data-end="{{ $leave->end_date }}"
                                    data-reason="{{ $leave->reason }}" data-contact="{{ $leave->contact_number }}"
                                    data-doc="{{ $leave->supporting_doc ? asset('storage/leaves/' . $leave->supporting_doc) : '' }}">
                                    <i class="fas fa-redo"></i>
                                </button>
                            @endif
                        </div>
                    </div>
                @empty
                    <p>No recent leave requests found.</p>
                @endforelse
            </div>
        </div>

        <!-- View Leave Request Modal -->
        <div id="viewLeaveModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-eye"></i> Leave Request Details</h3>
                    <button class="close-btn" onclick="closeViewLeaveModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="leaveDetailsContent">
                        <!-- Content will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeViewLeaveModal()">Close</button>
                    <button id="editFromViewBtn" class="btn btn-primary" onclick="editFromView()"
                        style="display: none;">
                        <i class="fas fa-edit"></i> Edit Request
                    </button>
                </div>
            </div>
        </div>

        <!-- Edit Leave Request Modal -->
        <div id="editLeaveModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-edit"></i> Edit Leave Request</h3>
                    <button class="close-btn" onclick="closeEditLeaveModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="editLeaveForm" method="POST" action="">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-section">
                            <h4>Leave Information</h4>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="editLeaveType">Leave Type <span class="required">*</span></label>
                                    <select id="editLeaveType" name="leave_type" class="form-control" required>
                                        <option value="">Select Leave Type</option>
                                        <option value="annual">Annual Leave</option>
                                        <option value="sick">Sick Leave</option>
                                        <option value="personal">Personal Leave</option>
                                        <option value="maternity">Maternity Leave</option>
                                        <option value="paternity">Paternity Leave</option>
                                        <option value="emergency">Emergency Leave</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="editStartDate">Start Date <span class="required">*</span></label>
                                    <input type="date" id="editStartDate" name="start_date" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="editEndDate">End Date <span class="required">*</span></label>
                                    <input type="date" id="editEndDate" name="end_date" class="form-control"
                                        required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="editReason">Reason <span class="required">*</span></label>
                                <textarea id="editReason" name="reason" class="form-control" rows="4"
                                    placeholder="Please provide a detailed reason for your leave request..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="editContactInfo">Contact Information</label>
                                <input type="text" id="editContactInfo" name="contact_info" class="form-control"
                                    placeholder="Emergency contact number or email">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeEditLeaveModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Request
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Apply Leave Modal -->
        <div id="applyLeaveModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-calendar-plus"></i> Apply for Leave</h3>
                    <button class="close-btn" onclick="closeApplyLeaveModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="leaveApplicationForm" method="POST" action="{{ route('employee.leaves.store') }}"
                    enctype="multipart/form-data">

                    @csrf
                    <div class="modal-body">
                        <div class="form-section">
                            <h4>Leave Information</h4>
                            <div class="form-group">
                                <label for="leaveType">Leave Type <span class="required">*</span></label>
                                <select id="leaveType" name="leave_type" class="form-control" required>
                                    <option value="">Select Leave Type</option>
                                    <option value="annual">Annual Leave</option>
                                    <option value="sick">Sick Leave</option>
                                    <option value="personal">Personal Leave</option>
                                    <option value="emergency">Emergency Leave</option>
                                    <option value="maternity">Maternity Leave</option>
                                    <option value="paternity">Paternity Leave</option>
                                </select>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="startDate">Start Date <span class="required">*</span></label>
                                    <input type="date" id="startDate" name="start_date" class="form-control"
                                        required>
                                </div>
                                <div class="form-group">
                                    <label for="endDate">End Date <span class="required">*</span></label>
                                    <input type="date" id="endDate" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="duration">Duration</label>
                                <div class="duration-display">
                                    <input type="hidden" id="duration" name="duration" value="0">
                                    <span id="durationValue">0</span> day(s)
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <h4>Details</h4>
                            <div class="form-group">
                                <label for="reason">Reason for Leave <span class="required">*</span></label>
                                <textarea id="reason" name="reason" class="form-control" rows="4"
                                    placeholder="Please provide a detailed reason for your leave request..." required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="contactNumber">Emergency Contact</label>
                                <input type="tel" id="contactNumber" name="contact_number" class="form-control"
                                    placeholder="Contact number during leave">
                            </div>
                            <div class="form-group">
                                <label for="supportingDoc">Supporting Document</label>
                                <input type="file" id="supportingDoc" name="supporting_doc" class="form-control"
                                    accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                <small class="form-text">Upload medical certificate or other supporting documents (Max:
                                    5MB)</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" onclick="closeApplyLeaveModal()">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Leave Balance Modal -->
        <div id="leaveBalanceModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3><i class="fas fa-balance-scale"></i> Leave Balance Details</h3>
                    <button class="close-btn" onclick="closeLeaveBalanceModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="balance-details">
                        <!-- Annual Leave -->
                        <div class="balance-item">
                            <div class="balance-type">
                                <i class="fas fa-calendar-check"></i>
                                <span>Annual Leave</span>
                            </div>
                            <div class="balance-info">
                                @php
                                    $annualPercent = $annualTotal > 0 ? round(($annualUsed / $annualTotal) * 100) : 0;
                                @endphp
                                <div class="balance-bar">
                                    <div class="balance-progress" style="width: {{ $annualPercent }}%"></div>
                                </div>
                                <div class="balance-numbers">
                                    <span>Used: {{ $annualUsed }} days</span>
                                    <span>Remaining: {{ $annualTotal - $annualUsed }} days</span>
                                    <span>Total: {{ $annualTotal }} days</span>
                                </div>
                            </div>
                        </div>

                        <!-- Sick Leave -->
                        <div class="balance-item">
                            <div class="balance-type">
                                <i class="fas fa-thermometer-half"></i>
                                <span>Sick Leave</span>
                            </div>
                            <div class="balance-info">
                                @php
                                    $sickPercent = $sickTotal > 0 ? round(($sickUsed / $sickTotal) * 100) : 0;
                                @endphp
                                <div class="balance-bar">
                                    <div class="balance-progress" style="width: {{ $sickPercent }}%"></div>
                                </div>
                                <div class="balance-numbers">
                                    <span>Used: {{ $sickUsed }} days</span>
                                    <span>Remaining: {{ $sickTotal - $sickUsed }} days</span>
                                    <span>Total: {{ $sickTotal }} days</span>
                                </div>
                            </div>
                        </div>

                        <!-- Personal Leave -->
                        <div class="balance-item">
                            <div class="balance-type">
                                <i class="fas fa-user-clock"></i>
                                <span>Personal Leave</span>
                            </div>
                            <div class="balance-info">
                                @php
                                    $personalPercent =
                                        $personalTotal > 0 ? round(($personalUsed / $personalTotal) * 100) : 0;
                                @endphp
                                <div class="balance-bar">
                                    <div class="balance-progress" style="width: {{ $personalPercent }}%"></div>
                                </div>
                                <div class="balance-numbers">
                                    <span>Used: {{ $personalUsed }} days</span>
                                    <span>Remaining: {{ $personalTotal - $personalUsed }} days</span>
                                    <span>Total: {{ $personalTotal }} days</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeLeaveBalanceModal()">Close</button>
                </div>
            </div>
        </div>

        <!-- Leave History Modal -->
        <div id="leaveHistoryModal" class="modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="leaveHistoryTitle">
                <div class="modal-header">
                    <h3 id="leaveHistoryTitle"><i class="fas fa-history"></i> Leave History</h3>
                    <button class="close-btn" onclick="closeLeaveHistoryModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="leave-history-section">
                        <table class="history-table" aria-label="Leave history table">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Type</th>
                                    <th>Dates</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th class="text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leaves as $leave)
                                    <tr>
                                        <td>{{ $leave->leave_id }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}</td>
                                        <td>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</td>
                                        <td>{{ $leave->duration }} day{{ $leave->duration != 1 ? 's' : '' }}</td>
                                        <td><span class="status-pill {{ $leave->status }}">{{ ucfirst($leave->status) }}</span></td>
                                        <td class="history-actions">
                                            <button class="action-btn view" onclick="viewLeaveRequest('{{ $leave->leave_id }}')" title="View Details"><i class="fas fa-eye"></i></button>
                                            @if($leave->status === 'pending')
                                                <button class="action-btn edit" onclick="editLeaveRequest('{{ $leave->leave_id }}')" title="Edit Request"><i class="fas fa-edit"></i></button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="6">No leave history found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>

                        <!-- Mobile stacked cards -->
                        <div class="history-cards" aria-hidden="true" style="margin-top:1rem;">
                            @forelse ($leaves as $leave)
                                <div class="history-card">
                                    <div class="hc-top">
                                        <h4>{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }} <small style="color:var(--text-secondary)">#{{ $leave->leave_id }}</small></h4>
                                        <span class="status-pill {{ $leave->status }}">{{ ucfirst($leave->status) }}</span>
                                    </div>
                                    <div class="hc-body">
                                        <div><strong>Dates</strong><br>{{ \Carbon\Carbon::parse($leave->start_date)->format('M d') }} – {{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}</div>
                                        <div><strong>Duration</strong><br>{{ $leave->duration }} day{{ $leave->duration != 1 ? 's' : '' }}</div>
                                    </div>
                                    <div style="margin-top:0.5rem;display:flex;justify-content:flex-end;gap:0.5rem;">
                                        <button class="action-btn view" onclick="viewLeaveRequest('{{ $leave->leave_id }}')" title="View Details"><i class="fas fa-eye"></i></button>
                                        @if($leave->status === 'pending')
                                            <button class="action-btn edit" onclick="editLeaveRequest('{{ $leave->leave_id }}')" title="Edit Request"><i class="fas fa-edit"></i></button>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p>No leave history found.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeLeaveHistoryModal()">Close</button>
                </div>
            </div>
        </div>

        <!-- Leave Policy Modal -->
        <div id="leavePolicyModal" class="modal policy-modal" aria-hidden="true">
            <div class="modal-content" role="dialog" aria-modal="true" aria-labelledby="leavePolicyTitle">
                <div class="modal-header">
                    <h3 id="leavePolicyTitle"><i class="fas fa-file-contract"></i> Leave Policy</h3>
                    <button class="close-btn" onclick="closeLeavePolicyModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="policy-content">
                        <div class="policy-section">
                            <h4>Overview</h4>
                            <p>This document summarises the company's leave policy. It explains types of leave available, eligibility, entitlements, and the process for applying and approving leave requests.</p>
                        </div>

                        <div class="policy-section">
                            <h4>Eligibility</h4>
                            <ul class="policy-list">
                                <li>All regular full-time employees are eligible for Annual, Sick and Personal leave.</li>
                                <li>Pro-rated entitlements apply for employees with less than one year of service.</li>
                            </ul>
                        </div>

                        <div class="policy-section">
                            <h4>Entitlements</h4>
                            <ul class="policy-list">
                                <li>Annual Leave: Refer to your contract for total days per year.</li>
                                <li>Sick Leave: Medical certificate may be required for absences longer than 2 days.</li>
                                <li>Personal / Emergency Leave: Limited per year, subject to approval.</li>
                            </ul>
                        </div>

                        <div class="policy-section">
                            <h4>Procedure</h4>
                            <ol class="policy-list">
                                <li>Submit leave request via the "Apply for Leave" form in this portal.</li>
                                <li>Manager reviews and approves or rejects; you will be notified by email.</li>
                                <li>For urgent leave, contact your manager directly and follow up with a portal request.</li>
                            </ol>
                        </div>

                        <div class="policy-section">
                            <h4>Contacts</h4>
                            <p class="policy-meta">
                                <span>HR: hr@company.com</span>
                                <span>Phone: +94 76 881 0159</span>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeLeavePolicyModal()">Close</button>
                </div>
            </div>
        </div>

    </div>

    <script>
        // Modal functions
        function openApplyLeaveModal() {
            document.getElementById('applyLeaveModal').classList.add('active');
            // Set minimum date to today
            document.getElementById('startDate').min = new Date().toISOString().split('T')[0];
        }

        function closeApplyLeaveModal() {
            document.getElementById('applyLeaveModal').classList.remove('active');
            document.getElementById('leaveApplicationForm').reset();
        }

        function checkLeaveBalance() {
            document.getElementById('leaveBalanceModal').classList.add('active');
        }

        function closeLeaveBalanceModal() {
            document.getElementById('leaveBalanceModal').classList.remove('active');
        }

        // Calculate duration when dates change
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


        // Update end date minimum when start date changes
        document.getElementById('startDate').addEventListener('change', function() {
            document.getElementById('endDate').min = this.value;
        });

        // Form submission
        document.getElementById('leaveApplicationForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const form = document.getElementById('leaveApplicationForm');
            // Show success message
            showMessage('Leave application submitted successfully!', 'success');
            form.submit();
            closeApplyLeaveModal();

            // In a real application, you would send the data to the server
            console.log('Form data:', Object.fromEntries(formData));
        });


        // Sample leave requests data
        const leaveRequests = {
            @foreach ($leaves as $leave)
                '{{ $leave->leave_id }}': {
                    id: '{{ $leave->leave_id }}',

                    // For display in view modal
                    typeDisplay: '{{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}',
                    status: '{{ strtolower($leave->status) }}',
                    statusDisplay: '{{ ucfirst($leave->status) }}',
                    startDateDisplay: '{{ \Carbon\Carbon::parse($leave->start_date)->format('M d, Y') }}',
                    endDateDisplay: '{{ \Carbon\Carbon::parse($leave->end_date)->format('M d, Y') }}',
                    duration: {{ \Carbon\Carbon::parse($leave->start_date)->diffInDays(\Carbon\Carbon::parse($leave->end_date)) + 1 }},
                    appliedDateDisplay: '{{ \Carbon\Carbon::parse($leave->created_at)->format('M d, Y') }}',
                    contactInfo: '{{ $leave->contact_number ?? 'N/A' }}',
                    approver: '{{ $leave->approvedBy ? $leave->approvedBy->admin_name : 'Admin' }}',
                    approvedDate: '{{ $leave->approved_at }}',
                    approvedDateDisplay: '{{ $leave->approved_at ? \Carbon\Carbon::parse($leave->approved_at)->format('M d, Y') : '' }}',
                    rejectedDate: '{{ $leave->rejected_at }}',
                    rejectedDateDisplay: '{{ $leave->rejected_at ? \Carbon\Carbon::parse($leave->rejected_at)->format('M d, Y') : '' }}',
                    reason: '{{ addslashes($leave->reason) }}',
                    comments: '{{ addslashes($leave->comments ?? '') }}',

                    leaveType: '{{ $leave->leave_type }}',
                    type: '{{ $leave->leave_type }}',
                    startDate: '{{ \Carbon\Carbon::parse($leave->start_date)->format('Y-m-d') }}',
                    endDate: '{{ \Carbon\Carbon::parse($leave->end_date)->format('Y-m-d') }}',

                },
            @endforeach
        };
        // Action functions
        function viewLeaveRequest(id) {
            const request = leaveRequests[id];
            console.log(id);

            if (!request) {
                showMessage('Leave request not found', 'error');
                return;
            }

            const modal = document.getElementById('viewLeaveModal');
            const content = document.getElementById('leaveDetailsContent');
            const editBtn = document.getElementById('editFromViewBtn');

            // Show/hide edit button based on status
            if (request.status === 'pending') {
                editBtn.style.display = 'inline-flex';
                editBtn.onclick = () => {
                    closeViewLeaveModal();
                    editLeaveRequest(id);
                };
            } else {
                editBtn.style.display = 'none';
            }

            content.innerHTML = `
            <div class="leave-details-container">
                <div class="detail-header">
                    <div class="detail-title">
                        <h3>${request.typeDisplay}</h3>
                        <span class="status-badge ${request.status}">${request.statusDisplay}</span>
                    </div>
                    <div class="detail-id">Request ID: ${request.id}</div>
                </div>

                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-calendar"></i> Duration</h4>
                        <div class="detail-row">
                            <span class="label">Start Date:</span>
                            <span class="value">${request.startDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">End Date:</span>
                            <span class="value">${request.endDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Duration:</span>
                            <span class="value">${request.duration} day${request.duration !== 1 ? 's' : ''}</span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-info-circle"></i> Request Information</h4>
                        <div class="detail-row">
                            <span class="label">Applied Date:</span>
                            <span class="value">${request.appliedDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Contact Info:</span>
                            <span class="value">${request.contactInfo}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Approver:</span>
                            <span class="value">${request.approver}</span>
                        </div>
                        ${request.approvedDate ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="detail-row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="label">Approved Date:</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="value">${request.approvedDateDisplay}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}
                        ${request.rejectedDate ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="detail-row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="label">Rejected Date:</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="value">${request.rejectedDateDisplay}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}
                    </div>
                </div>

                <div class="detail-section full-width">
                    <h4><i class="fas fa-comment"></i> Reason</h4>
                    <div class="reason-text">${request.reason}</div>
                </div>

                ${request.comments ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="detail-section full-width">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <h4><i class="fas fa-comments"></i> Comments</h4>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="comments-text">${request.comments}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ` : ''}
            </div>
        `;

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }


        function closeViewLeaveModal() {
            const modal = document.getElementById('viewLeaveModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function closeEditLeaveModal() {
            const modal = document.getElementById('editLeaveModal');
            modal.style.display = 'none';
            document.body.style.overflow = 'auto';
        }

        function updateLeaveCard(requestId) {
            // This function would update the visual card on the page
            // For now, we'll just show a message
            showMessage('Please refresh the page to see updated information', 'info');
        }

        // Action functions (keeping existing ones)
        function viewLeaveRequest(id) {
            const request = leaveRequests[id];
            if (!request) {
                showMessage('Leave request not found', 'error');
                return;
            }

            const modal = document.getElementById('viewLeaveModal');
            const content = document.getElementById('leaveDetailsContent');
            const editBtn = document.getElementById('editFromViewBtn');

            // Show/hide edit button based on status
            if (request.status === 'pending') {
                editBtn.style.display = 'inline-flex';
                editBtn.onclick = () => {
                    closeViewLeaveModal();
                    editLeaveRequest(id);
                };
            } else {
                editBtn.style.display = 'none';
            }

            content.innerHTML = `
            <div class="leave-details-container">
                <div class="detail-header">
                    <div class="detail-title">
                        <h3>${request.typeDisplay}</h3>
                        <span class="status-badge ${request.status}">${request.statusDisplay}</span>
                    </div>
                    <div class="detail-id">Request ID: ${request.id}</div>
                </div>

                <div class="detail-grid">
                    <div class="detail-section">
                        <h4><i class="fas fa-calendar"></i> Duration</h4>
                        <div class="detail-row">
                            <span class="label">Start Date:</span>
                            <span class="value">${request.startDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">End Date:</span>
                            <span class="value">${request.endDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Duration:</span>
                            <span class="value">${request.duration} day${request.duration !== 1 ? 's' : ''}</span>
                        </div>
                    </div>

                    <div class="detail-section">
                        <h4><i class="fas fa-info-circle"></i> Request Information</h4>
                        <div class="detail-row">
                            <span class="label">Applied Date:</span>
                            <span class="value">${request.appliedDateDisplay}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Contact Info:</span>
                            <span class="value">${request.contactInfo}</span>
                        </div>
                        <div class="detail-row">
                            <span class="label">Approver:</span>
                            <span class="value">${request.approver}</span>
                        </div>
                        ${request.approvedDate ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="detail-row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="label">Approved Date:</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="value">${request.approvedDateDisplay}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}
                        ${request.rejectedDate ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                <div class="detail-row">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="label">Rejected Date:</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    <span class="value">${request.rejectedDateDisplay}</span>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                ` : ''}
                    </div>
                </div>

                <div class="detail-section full-width">
                    <h4><i class="fas fa-comment"></i> Reason</h4>
                    <div class="reason-text">${request.reason}</div>
                </div>

                ${request.comments ? `
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        <div class="detail-section full-width">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <h4><i class="fas fa-comments"></i> Comments</h4>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            <div class="comments-text">${request.comments}</div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        </div>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                        ` : ''}
            </div>
        `;

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function editLeaveRequest(id) {
            const request = leaveRequests[id];
            if (!request) {
                showMessage('Leave request not found', 'error');
                return;
            }

            if (request.status !== 'pending') {
                showMessage('Only pending leave requests can be edited', 'error');
                return;
            }

            const modal = document.getElementById('editLeaveModal');
            const form = document.getElementById('editLeaveForm');

            form.action = `{{ url('/employees/leaves') }}/${id}`;

            document.getElementById('editLeaveType').value = request.type;
            document.getElementById('editStartDate').value = request.startDate;
            document.getElementById('editEndDate').value = request.endDate;
            document.getElementById('editReason').value = request.reason;
            document.getElementById('editContactInfo').value = request.contactInfo ?? '';

            form.dataset.requestId = id;

            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function cancelLeaveRequest(id) {
            if (confirm('Are you sure you want to cancel this leave request?')) {
                showMessage(`Leave request ${id} cancelled`, 'success');
                document.getElementById(`cancel-form-${id}`).submit();
            }
        }

        function downloadCertificate(id) {
            showMessage(`Downloading certificate for ${id}`, 'info');
        }

        function reapplyLeave(button) {
            // Get old data from button attributes
            const id = button.getAttribute('data-id');
            const type = button.getAttribute('data-type');
            const start = button.getAttribute('data-start');
            const end = button.getAttribute('data-end');
            const reason = button.getAttribute('data-reason');
            const contact = button.getAttribute('data-contact');
            const doc = button.getAttribute('data-doc');

            showMessage(`Reapplying leave request: ${id}`, 'info');

            // Open modal
            openApplyLeaveModal();

            // Prefill fields
            document.getElementById('leaveType').value = type;
            document.getElementById('startDate').value = start;
            document.getElementById('endDate').value = end;
            document.getElementById('reason').value = reason;
            document.getElementById('contactNumber').value = contact;

            // Calculate duration automatically if needed
            updateDuration();

            // Show previous document (if any)
            let docPreview = document.getElementById('previousDocPreview');
            if (!docPreview) {
                const docContainer = document.createElement('div');
                docContainer.id = 'previousDocPreview';
                docContainer.classList.add('form-group');
                document
                    .getElementById('supportingDoc')
                    .insertAdjacentElement('afterend', docContainer);
                docPreview = docContainer;
            }

            if (doc) {
                docPreview.innerHTML = `
            <small>Previously uploaded document:</small><br>
            <a href="${doc}" target="_blank" class="btn-link">View Supporting Document</a>
        `;
            } else {
                docPreview.innerHTML = `<small>No previous document attached.</small>`;
            }

            // Switch form to update mode (PUT)
            const form = document.getElementById('leaveApplicationForm');
            form.action = `/employee/leaves/${id}`; // adjust if route prefix differs
            let methodInput = form.querySelector('input[name="_method"]');
            if (!methodInput) {
                methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                form.appendChild(methodInput);
            }
            methodInput.value = 'PUT';
            // Change submit button text
            const submitBtn = form.querySelector('button[type="submit"]');
            submitBtn.innerHTML = `<i class="fas fa-sync"></i> Reapply Leave`;
        }

        // Replace stub: open Leave History modal
        function viewLeaveHistory() {
            const modal = document.getElementById('leaveHistoryModal');
            if (!modal) {
                showMessage('Leave history not available', 'error');
                return;
            }
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeLeaveHistoryModal() {
            const modal = document.getElementById('leaveHistoryModal');
            if (!modal) return;
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        // Open Leave Policy modal
        function viewLeavePolicy() {
            const modal = document.getElementById('leavePolicyModal');
            if (!modal) {
                showMessage('Leave policy not available', 'error');
                return;
            }
            modal.classList.add('active');
            modal.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
        }

        function closeLeavePolicyModal() {
            const modal = document.getElementById('leavePolicyModal');
            if (!modal) return;
            modal.classList.remove('active');
            modal.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = 'auto';
        }

        // close Leave Policy modal when clicking on overlay
        document.getElementById('leavePolicyModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeLeavePolicyModal();
        });

        // also close on ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLeavePolicyModal();
            }
        });

        // Enhanced action button styles
        document.addEventListener('DOMContentLoaded', function() {
            // Add tooltips to action buttons
            const viewButtons = document.querySelectorAll('.action-btn.view');
            const editButtons = document.querySelectorAll('.action-btn.edit');

            viewButtons.forEach(btn => {
                btn.title = 'View details';
            });

            editButtons.forEach(btn => {
                btn.title = 'Edit request';
            });
        });
    </script>

@endsection
