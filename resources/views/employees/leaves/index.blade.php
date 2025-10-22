@extends('layouts.employee')

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

    </div>

    <style>
        .leave-management-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0;
        }

        .leave-header {
            background: var(--gradient-hero);
            color: white;
            padding: 2rem;
            border-radius: 1rem;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            animation: gradientShift 18s ease infinite;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header-info h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .header-info p {
            margin: 0.5rem 0 0 0;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1.1rem;
        }

        .header-actions {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
        }

        .btn-primary {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-primary:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border: 2px solid rgba(255, 255, 255, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .btn-outline {
            background: transparent;
            color: var(--text-primary);
            border: 2px solid var(--border-light);
        }

        .btn-outline:hover {
            background: var(--bg-secondary);
            border-color: var(--redcode-primary);
        }

        @keyframes gradientShift {

            0%,
            100% {
                background-position: 0% 50%;
            }

            25% {
                background-position: 100% 50%;
            }

            50% {
                background-position: 100% 100%;
            }

            75% {
                background-position: 0% 100%;
            }
        }

        .leave-stats-section {
            margin-bottom: 2rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
        }

        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--redcode-primary);
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover::before {
            transform: scaleY(1);
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .stat-card.annual-leave::before {
            background: var(--redcode-green);
        }

        .stat-card.sick-leave::before {
            background: var(--redcode-orange);
        }

        .stat-card.personal-leave::before {
            background: var(--redcode-blue);
        }

        .stat-card.pending-requests::before {
            background: var(--redcode-primary);
        }

        .stat-card {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-card.annual-leave .stat-icon {
            background: var(--redcode-green);
        }

        .stat-card.sick-leave .stat-icon {
            background: var(--redcode-orange);
        }

        .stat-card.personal-leave .stat-icon {
            background: var(--redcode-blue);
        }

        .stat-card.pending-requests .stat-icon {
            background: var(--redcode-primary);
        }

        .stat-content {
            flex: 1;
        }

        .stat-content h3 {
            margin: 0 0 0.5rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .stat-numbers {
            display: flex;
            align-items: baseline;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .stat-numbers .used {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--redcode-primary);
        }

        .stat-numbers .separator {
            font-size: 1.2rem;
            color: var(--text-secondary);
        }

        .stat-numbers .total {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--text-secondary);
        }

        .stat-numbers .unit {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .stat-progress {
            margin-top: 0.5rem;
        }

        .progress-bar {
            height: 6px;
            background: var(--bg-secondary);
            border-radius: 3px;
            overflow: hidden;
            margin-bottom: 0.25rem;
        }

        .progress-fill {
            height: 100%;
            background: var(--redcode-primary);
            transition: width 0.3s ease;
        }

        .remaining {
            font-size: 0.8rem;
            color: var(--text-secondary);
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem;
            border-radius: 0.5rem;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .status-indicator.pending {
            background: rgba(217, 119, 6, 0.1);
            color: var(--redcode-orange);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .section-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .quick-actions-section {
            margin-bottom: 2rem;
        }

        .actions-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
        }

        .action-card {
            background: white;
            padding: 1.5rem;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .action-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
            border-color: var(--redcode-primary);
        }

        .action-icon {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gradient-hero);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.2rem;
            flex-shrink: 0;
            animation: gradientShift 18s ease infinite;
        }

        .action-content h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .action-content p {
            margin: 0;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .recent-requests-section {
            margin-bottom: 2rem;
        }

        .requests-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 1.5rem;
        }

        .request-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .request-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .request-card.pending {
            border-left: 4px solid var(--redcode-orange);
        }

        .request-card.approved {
            border-left: 4px solid var(--redcode-green);
        }

        .request-card.rejected {
            border-left: 4px solid var(--redcode-primary);
        }

        .request-header {
            padding: 1.5rem 1.5rem 0 1.5rem;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .request-info h4 {
            margin: 0 0 0.25rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .request-date {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .request-status {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: 1rem;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .request-status.pending {
            background: rgba(217, 119, 6, 0.1);
            color: var(--redcode-orange);
        }

        .request-status.approved {
            background: rgba(5, 150, 105, 0.1);
            color: var(--redcode-green);
        }

        .request-status.rejected {
            background: rgba(220, 38, 38, 0.1);
            color: var(--redcode-primary);
        }

        .request-details {
            padding: 1rem 1.5rem;
        }

        .detail-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .detail-item .label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        .detail-item .value {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text-primary);
        }

        .request-actions {
            padding: 0 1.5rem 1.5rem 1.5rem;
            display: flex;
            gap: 0.5rem;
            justify-content: flex-end;
        }

        .action-btn {
            width: 38px;
            height: 38px;
            border: none;
            border-radius: 0.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            position: relative;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .action-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .action-btn.view {
            background: var(--gradient-hero);
            color: white;
            animation: gradientShift 18s ease infinite;
        }

        .action-btn.view:hover {
            background: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
            animation: none;
        }

        .action-btn.edit {
            background: linear-gradient(135deg, var(--redcode-green) 0%, #047857 100%);
            color: white;
        }

        .action-btn.edit:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
        }

        .action-btn.cancel {
            background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-primary-dark) 100%);
            color: white;
        }

        .action-btn.cancel:hover {
            background: linear-gradient(135deg, var(--redcode-primary-dark) 0%, var(--redcode-accent) 100%);
        }

        .action-btn.download {
            background: linear-gradient(135deg, var(--redcode-blue) 0%, #1d4ed8 100%);
            color: white;
        }

        .action-btn.download:hover {
            background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
        }

        .action-btn.reapply {
            background: linear-gradient(135deg, var(--redcode-orange) 0%, #b45309 100%);
            color: white;
        }

        .action-btn.reapply:hover {
            background: linear-gradient(135deg, #b45309 0%, #92400e 100%);
        }

        /* Tooltip styles */
        .action-btn::before {
            content: attr(title);
            position: absolute;
            top: -35px;
            left: 50%;
            transform: translateX(-50%);
            background: #1f2937;
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            white-space: nowrap;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .action-btn::after {
            content: '';
            position: absolute;
            top: -5px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 0;
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid #1f2937;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 1000;
        }

        .action-btn:hover::before,
        .action-btn:hover::after {
            opacity: 1;
            visibility: visible;
        }
        }

        .action-btn.reapply {
            background: rgba(139, 92, 246, 0.1);
            color: #8B5CF6;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .action-btn.view:hover {
            background: #3B82F6;
            color: white;
        }

        .action-btn.edit:hover {
            background: #F59E0B;
            color: white;
        }

        .action-btn.cancel:hover {
            background: #DC2626;
            color: white;
        }

        .action-btn.download:hover {
            background: #22C55E;
            color: white;
        }

        .action-btn.reapply:hover {
            background: #8B5CF6;
            color: white;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            width: 90%;
            max-width: 600px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 1rem 1rem 0 0;
        }

        .modal-header h3 {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.25rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 1.5rem;
        }

        .form-section {
            margin-bottom: 1.5rem;
            padding-bottom: 1.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        .form-section:last-child {
            border-bottom: none;
            margin-bottom: 0;
        }

        .form-section h4 {
            margin: 0 0 1rem 0;
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .required {
            color: #DC2626;
        }

        .form-control {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid var(--border-light);
            border-radius: 0.5rem;
            font-size: 1rem;
            background: var(--bg-primary);
            transition: all 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: var(--redcode-primary);
            box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.1);
        }

        .duration-display {
            padding: 0.875rem 1rem;
            background: var(--bg-secondary);
            border: 2px solid var(--border-light);
            border-radius: 0.5rem;
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-text {
            font-size: 0.8rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid var(--border-light);
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: var(--bg-secondary);
            border-radius: 0 0 1rem 1rem;
        }

        .balance-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .balance-item {
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 0.5rem;
            border: 1px solid var(--border-light);
        }

        .balance-type {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 1rem;
            font-weight: 600;
            color: var(--text-primary);
        }

        .balance-bar {
            height: 8px;
            background: var(--border-light);
            border-radius: 4px;
            overflow: hidden;
            margin-bottom: 0.5rem;
        }

        .balance-progress {
            height: 100%;
            background: var(--redcode-primary);
            transition: width 0.3s ease;
        }

        .balance-numbers {
            display: flex;
            justify-content: space-between;
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Enhanced Modal Styles for View and Edit */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(5px);
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background: white;
            border-radius: 1rem;
            max-width: 800px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            animation: slideIn 0.3s ease;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            padding: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            border-radius: 1rem 1rem 0 0;
        }

        .modal-header h3 {
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.25rem;
            font-weight: 600;
        }

        .close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: background-color 0.3s ease;
        }

        .close-btn:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }

        .modal-body {
            padding: 2rem;
        }

        .modal-footer {
            padding: 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 1rem;
            background: #f9fafb;
            border-radius: 0 0 1rem 1rem;
        }

        /* View Leave Modal Styles */
        .leave-details-container {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }

        .detail-header {
            text-align: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 1rem;
        }

        .detail-title {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-bottom: 0.5rem;
        }

        .detail-title h3 {
            margin: 0;
            color: #1f2937;
            font-size: 1.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 1rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.pending {
            background: rgba(245, 158, 11, 0.1);
            color: #f59e0b;
        }

        .status-badge.approved {
            background: rgba(16, 185, 129, 0.1);
            color: #10b981;
        }

        .status-badge.rejected {
            background: rgba(239, 68, 68, 0.1);
            color: #ef4444;
        }

        .detail-id {
            color: #6b7280;
            font-size: 0.875rem;
        }

        .detail-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .detail-section {
            background: #f9fafb;
            padding: 1.5rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
        }

        .detail-section.full-width {
            grid-column: 1 / -1;
        }

        .detail-section h4 {
            margin: 0 0 1rem 0;
            color: #1f2937;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.1rem;
        }

        .detail-section h4 i {
            color: #dc2626;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #e5e7eb;
        }

        .detail-row:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .detail-row .label {
            font-weight: 600;
            color: #374151;
        }

        .detail-row .value {
            color: #6b7280;
            text-align: right;
        }

        .reason-text,
        .comments-text {
            background: white;
            padding: 1rem;
            border-radius: 0.5rem;
            border: 1px solid #e5e7eb;
            color: #374151;
            line-height: 1.6;
        }

        /* Edit Leave Modal Styles */
        .form-section {
            margin-bottom: 2rem;
        }

        .form-section h4 {
            margin: 0 0 1rem 0;
            color: #1f2937;
            font-size: 1.1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.5rem;
        }

        .form-group .required {
            color: #dc2626;
        }

        .form-control {
            padding: 0.75rem;
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        .form-control:focus {
            outline: none;
            border-color: #dc2626;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
        }

        .form-control textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Animation */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Modal Responsive Design */
        @media (max-width: 768px) {
            .modal-content {
                width: 95%;
                max-height: 95vh;
            }

            .modal-header {
                padding: 1rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer {
                padding: 1rem;
                flex-direction: column;
            }

            .detail-grid {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .detail-title {
                flex-direction: column;
                gap: 0.5rem;
            }
        }

        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .actions-grid {
                grid-template-columns: 1fr;
            }

            .requests-container {
                grid-template-columns: 1fr;
            }

            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

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


        function viewLeaveHistory() {
            showMessage('Redirecting to leave history...', 'info');
        }

        function viewLeavePolicy() {
            showMessage('Opening leave policy document...', 'info');
        }

        function viewAllLeaves() {
            showMessage('Redirecting to all leaves...', 'info');
        }

        function exportLeaveReport() {
            showMessage('Generating leave report...', 'info');
        }

        // Utility function to show messages
        function showMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message message-${type}`;
            messageDiv.innerHTML = `
            <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
            ${message}
        `;

            const bgColor = type === 'success' ? '#22C55E' : '#3B82F6';

            messageDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${bgColor};
            color: white;
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            z-index: 1001;
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        `;

            document.body.appendChild(messageDiv);

            setTimeout(() => {
                messageDiv.remove();
            }, 3000);
        }

        // Close modal when clicking outside
        document.getElementById('applyLeaveModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeApplyLeaveModal();
            }
        });

        document.getElementById('leaveBalanceModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeLeaveBalanceModal();
            }
        });

        // Add slide-in animation
        const style = document.createElement('style');
        style.textContent = `
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
    `;
        document.head.appendChild(style);

        // Modal event handlers
        document.addEventListener('click', function(e) {
            // Close view modal when clicking outside
            if (e.target.id === 'viewLeaveModal') {
                closeViewLeaveModal();
            }

            // Close edit modal when clicking outside
            if (e.target.id === 'editLeaveModal') {
                closeEditLeaveModal();
            }

            // Close apply modal when clicking outside
            if (e.target.id === 'applyLeaveModal') {
                closeApplyLeaveModal();
            }
        });

        // Close modals on escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeViewLeaveModal();
                closeEditLeaveModal();
                closeApplyLeaveModal();
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
