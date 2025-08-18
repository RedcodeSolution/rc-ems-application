@extends('layouts.employee')

@section('title', 'Leave Management')

@section('content')
<div class="leave-management-container">
    <!-- Header Section -->
    <div class="leave-header">
        <div class="header-content">
            <div class="header-info">
                <h1><i class="fas fa-calendar-alt"></i> Leave Management</h1>
                <p>Manage your leave requests and track your balance</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="openApplyLeaveModal()">
                    <i class="fas fa-plus"></i>
                    Apply Leave
                </button>
                <button class="btn btn-secondary" onclick="openLeaveBalanceModal()">
                    <i class="fas fa-chart-pie"></i>
                    View Balance
                </button>
            </div>
        </div>
    </div>

    <!-- Statistics Section -->
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $totalLeaves ?? 0 }}</h3>
                <p>Total Requests</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $approvedLeaves ?? 0 }}</h3>
                <p>Approved</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $pendingLeaves ?? 0 }}</h3>
                <p>Pending</p>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon">
                <i class="fas fa-times-circle"></i>
            </div>
            <div class="stat-info">
                <h3>{{ $rejectedLeaves ?? 0 }}</h3>
                <p>Rejected</p>
            </div>
        </div>
    </div>

    <!-- Leave Balance Section -->
    <div class="leave-balance-grid">
        <div class="balance-card">
            <div class="balance-header">
                <h3>Annual Leave</h3>
                <div class="balance-progress">
                    <div class="progress-bar" style="width: {{ ($annualLeavesTaken ?? 0) / 21 * 100 }}%"></div>
                </div>
            </div>
            <div class="balance-info">
                <span class="used">{{ $annualLeavesTaken ?? 0 }} used</span>
                <span class="remaining">{{ 21 - ($annualLeavesTaken ?? 0) }} remaining</span>
            </div>
        </div>
        <div class="balance-card">
            <div class="balance-header">
                <h3>Casual Leave</h3>
                <div class="balance-progress">
                    <div class="progress-bar" style="width: {{ ($casualLeavesTaken ?? 0) / 12 * 100 }}%"></div>
                </div>
            </div>
            <div class="balance-info">
                <span class="used">{{ $casualLeavesTaken ?? 0 }} used</span>
                <span class="remaining">{{ 12 - ($casualLeavesTaken ?? 0) }} remaining</span>
            </div>
        </div>
        <div class="balance-card">
            <div class="balance-header">
                <h3>Sick Leave</h3>
                <div class="balance-progress">
                    <div class="progress-bar" style="width: {{ ($sickLeavesTaken ?? 0) / 10 * 100 }}%"></div>
                </div>
            </div>
            <div class="balance-info">
                <span class="used">{{ $sickLeavesTaken ?? 0 }} used</span>
                <span class="remaining">{{ 10 - ($sickLeavesTaken ?? 0) }} remaining</span>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="actions-grid">
            <div class="action-card" onclick="openApplyLeaveModal()">
                <i class="fas fa-plus"></i>
                <h3>Apply Leave</h3>
            </div>
            <div class="action-card" onclick="openLeaveBalanceModal()">
                <i class="fas fa-chart-pie"></i>
                <h3>View Balance</h3>
            </div>
        </div>
    </div>

    <!-- Recent Requests -->
    <div class="recent-requests">
        <h2>Recent Leave Requests</h2>
        <div class="requests-grid">
            @forelse($recentLeaves ?? [] as $leave)
            <div class="request-card {{ strtolower($leave->status ?? 'pending') }}">
                <div class="request-header">
                    <div class="request-info">
                        <h4>{{ $leave->formatted_leave_type ?? 'Leave Request' }}</h4>
                        <span class="request-date">{{ $leave->start_date->format('M d') ?? 'N/A' }} - {{ $leave->end_date->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="request-status {{ strtolower($leave->status ?? 'pending') }}">
                        <i class="fas fa-{{ ($leave->status ?? 'pending') == 'approved' ? 'check-circle' : (($leave->status ?? 'pending') == 'rejected' ? 'times-circle' : 'clock') }}"></i>
                        <span>{{ $leave->formatted_status ?? 'Pending' }}</span>
                    </div>
                </div>
                <div class="request-details">
                    <div class="detail-item">
                        <span class="label">Duration:</span>
                        <span class="value">{{ $leave->duration ?? 1 }} day{{ ($leave->duration ?? 1) > 1 ? 's' : '' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Applied:</span>
                        <span class="value">{{ $leave->applied_date->format('M d, Y') ?? 'N/A' }}</span>
                    </div>
                    <div class="detail-item">
                        <span class="label">Reason:</span>
                        <span class="value">{{ Str::limit($leave->reason ?? 'No reason provided', 50) }}</span>
                    </div>
                </div>
                <div class="request-actions">
                    <button class="action-btn view" onclick="viewLeaveRequest('{{ $leave->leave_id ?? '' }}')">
                        <i class="fas fa-eye"></i>
                    </button>
                    @if($leave->can_be_edited ?? false)
                    <button class="action-btn edit" onclick="editLeaveRequest('{{ $leave->leave_id ?? '' }}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    @endif
                    @if($leave->can_be_cancelled ?? false)
                    <button class="action-btn cancel" onclick="cancelLeaveRequest('{{ $leave->leave_id ?? '' }}')">
                        <i class="fas fa-times"></i>
                    </button>
                    @endif
                </div>
            </div>
            @empty
            <div class="no-requests">
                <i class="fas fa-calendar-times"></i>
                <h3>No Leave Requests</h3>
                <p>You haven't applied for any leave yet.</p>
                <button class="btn btn-primary" onclick="openApplyLeaveModal()">
                    <i class="fas fa-plus"></i>
                    Apply for Leave
                </button>
            </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Apply Leave Modal -->
<div id="applyLeaveModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-plus"></i> Apply for Leave</h3>
            <button class="close-btn" onclick="closeApplyLeaveModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form id="leaveApplicationForm">
            <div class="modal-body">
                <div class="form-section">
                    <h4>Leave Information</h4>
                    <div class="form-group">
                        <label for="leaveType">Leave Type</label>
                        <select id="leaveType" name="leave_type" class="form-control" required>
                            <option value="">Select leave type</option>
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
                            <label for="startDate">Start Date</label>
                            <input type="date" id="startDate" name="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="endDate">End Date</label>
                            <input type="date" id="endDate" name="end_date" class="form-control" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="reason">Reason</label>
                        <textarea id="reason" name="reason" class="form-control" rows="3" placeholder="Please provide reason for leave" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="contactNumber">Contact Number</label>
                        <input type="tel" id="contactNumber" name="contact_number" class="form-control" placeholder="Emergency contact number">
                    </div>
                    <div class="form-group">
                        <label for="documents">Supporting Documents (Optional)</label>
                        <input type="file" id="documents" name="documents[]" class="form-control" multiple accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                        <small class="form-text text-muted">Upload medical certificates, travel documents, etc.</small>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-cancel" onclick="closeApplyLeaveModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Submit Request
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Leave Balance Modal -->
<div id="leaveBalanceModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3><i class="fas fa-chart-pie"></i> Leave Balance</h3>
            <button class="close-btn" onclick="closeLeaveBalanceModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <div class="balance-overview">
                <div class="balance-item">
                    <h4>Annual Leave</h4>
                    <div class="balance-details">
                        <span class="allocated">21 days allocated</span>
                        <span class="used">{{ $annualLeavesTaken ?? 0 }} days used</span>
                        <span class="remaining">{{ 21 - ($annualLeavesTaken ?? 0) }} days remaining</span>
                    </div>
                    <div class="balance-progress">
                        <div class="progress-bar" style="width: {{ ($annualLeavesTaken ?? 0) / 21 * 100 }}%"></div>
                    </div>
                </div>
                <div class="balance-item">
                    <h4>Casual Leave</h4>
                    <div class="balance-details">
                        <span class="allocated">12 days allocated</span>
                        <span class="used">{{ $casualLeavesTaken ?? 0 }} days used</span>
                        <span class="remaining">{{ 12 - ($casualLeavesTaken ?? 0) }} days remaining</span>
                    </div>
                    <div class="balance-progress">
                        <div class="progress-bar" style="width: {{ ($casualLeavesTaken ?? 0) / 12 * 100 }}%"></div>
                    </div>
                </div>
                <div class="balance-item">
                    <h4>Sick Leave</h4>
                    <div class="balance-details">
                        <span class="allocated">10 days allocated</span>
                        <span class="used">{{ $sickLeavesTaken ?? 0 }} days used</span>
                        <span class="remaining">{{ 10 - ($sickLeavesTaken ?? 0) }} days remaining</span>
                    </div>
                    <div class="balance-progress">
                        <div class="progress-bar" style="width: {{ ($sickLeavesTaken ?? 0) / 10 * 100 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" onclick="closeLeaveBalanceModal()">Close</button>
        </div>
    </div>
</div>

<script>
    // Modal Functions
    function openApplyLeaveModal() {
        document.getElementById('applyLeaveModal').classList.add('show');
        document.getElementById('applyLeaveModal').classList.add('active');
    }

    function closeApplyLeaveModal() {
        document.getElementById('applyLeaveModal').classList.remove('show');
        document.getElementById('applyLeaveModal').classList.remove('active');
    }

    function openLeaveBalanceModal() {
        document.getElementById('leaveBalanceModal').classList.add('show');
        document.getElementById('leaveBalanceModal').classList.add('active');
    }

    function closeLeaveBalanceModal() {
        document.getElementById('leaveBalanceModal').classList.remove('show');
        document.getElementById('leaveBalanceModal').classList.remove('active');
    }

    // Leave Request Functions
    function viewLeaveRequest(id) {
        if (window.EMS) {
            window.EMS.showMessage(`Viewing leave request ${id}`, 'info');
        } else {
            alert(`Viewing leave request ${id}`);
        }
    }

    function editLeaveRequest(id) {
        if (window.EMS) {
            window.EMS.showMessage(`Editing leave request ${id}`, 'info');
        } else {
            alert(`Editing leave request ${id}`);
        }
    }

    function cancelLeaveRequest(id) {
        if (confirm('Are you sure you want to cancel this leave request?')) {
            if (window.EMS) {
                window.EMS.showMessage(`Leave request ${id} cancelled`, 'success');
            } else {
                alert(`Leave request ${id} cancelled`);
            }
        }
    }

    // Form Submission
    document.getElementById('leaveApplicationForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const leaveType = formData.get('leave_type');
        const startDate = formData.get('start_date');
        const endDate = formData.get('end_date');
        const reason = formData.get('reason');
        
        if (!leaveType || !startDate || !endDate || !reason) {
            alert('Please fill in all required fields.');
            return;
        }
        
        // Validate dates
        const start = new Date(startDate);
        const end = new Date(endDate);
        const today = new Date();
        today.setHours(0, 0, 0, 0);
        
        if (start < today) {
            alert('Start date cannot be in the past.');
            return;
        }
        
        if (end < start) {
            alert('End date cannot be before start date.');
            return;
        }
        
        // Show success message
        if (window.EMS) {
            window.EMS.showMessage('Leave request submitted successfully!', 'success');
        } else {
            alert('Leave request submitted successfully!');
        }
        
        closeApplyLeaveModal();
        
        // Reset form
        this.reset();
    });

    // Date validation
    document.getElementById('startDate').addEventListener('change', function() {
        const startDate = this.value;
        const endDateInput = document.getElementById('endDate');
        
        if (startDate) {
            endDateInput.min = startDate;
            if (endDateInput.value && endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        }
    });

    // Close modal when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal')) {
            if (e.target.id === 'applyLeaveModal') {
                closeApplyLeaveModal();
            } else if (e.target.id === 'leaveBalanceModal') {
                closeLeaveBalanceModal();
            }
        }
    });

    // Close modal on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeApplyLeaveModal();
            closeLeaveBalanceModal();
        }
    });
</script>
@endsection
