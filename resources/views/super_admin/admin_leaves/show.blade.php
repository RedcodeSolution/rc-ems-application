<link rel="stylesheet" href="{{ asset('css/SuperAdmin/Admin/LeaveShow.css') }}">

<div class="leave-details">
    <div class="detail-section">
        <h4>Leave Information</h4>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Leave ID:</label>
                <span>{{ $leave->leave_id }}</span>
            </div>
            <div class="detail-item">
                <label>Leave Type:</label>
                <span class="badge badge-{{ $leave->leave_type }}">
                    {{ ucfirst(str_replace('_', ' ', $leave->leave_type)) }}
                </span>
            </div>
            <div class="detail-item">
                <label>Duration:</label>
                <span>{{ $leave->duration }} days</span>
            </div>
            <div class="detail-item">
                <label>Status:</label>
                <span class="badge badge-{{ $leave->status }}">
                    {{ ucfirst($leave->status) }}
                </span>
            </div>
            <div class="detail-item">
                <label>Start Date:</label>
                <span>{{ $leave->start_date->format('M d, Y') }}</span>
            </div>
            <div class="detail-item">
                <label>End Date:</label>
                <span>{{ $leave->end_date->format('M d, Y') }}</span>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h4>Admin Information</h4>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Name:</label>
                <span>{{ $leave->user->admin->admin_name ?? $leave->user->name ?? 'Unknown' }}</span>
            </div>
            <div class="detail-item">
                <label>User ID:</label>
                <span>{{ $leave->user->id ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Admin Role:</label>
                <span>Admin</span>
            </div>
            <div class="detail-item">
                <label>Contact:</label>
                <span>{{ $leave->contact_number ?? $leave->user->admin->contact_no ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h4>Leave Details</h4>
        <div class="detail-item full-width">
            <label>Reason:</label>
            <p class="reason-text">{{ $leave->reason }}</p>
        </div>
        @if ($leave->supporting_doc)
            <div class="detail-item">
                <label>Supporting Document:</label>
                <span><i class="fas fa-paperclip"></i> Document attached</span>
            </div>
        @endif
    </div>

    <div class="detail-section">
        <h4>Timeline</h4>
        <div class="detail-grid">
            <div class="detail-item">
                <label>Applied Date:</label>
                <span>{{ $leave->applied_date->format('M d, Y h:i A') }}</span>
            </div>
            @if ($leave->approved_date)
                <div class="detail-item">
                    <label>Approved Date:</label>
                    <span>{{ $leave->approved_date->format('M d, Y h:i A') }}</span>
                </div>
                <div class="detail-item">
                    <label>Approved By:</label>
                    <span>{{ $leave->approvedBy->admin_name ?? 'System' }}</span>
                </div>
            @endif
            @if ($leave->rejected_date)
                <div class="detail-item">
                    <label>Rejected Date:</label>
                    <span>{{ $leave->rejected_date->format('M d, Y h:i A') }}</span>
                </div>
                <div class="detail-item">
                    <label>Rejected By:</label>
                    <span>{{ $leave->rejectedBy->admin_name ?? 'System' }}</span>
                </div>
            @endif
        </div>
        @if ($leave->rejection_reason)
            <div class="detail-item full-width">
                <label>Rejection Reason:</label>
                <p class="rejection-text">{{ $leave->rejection_reason }}</p>
            </div>
        @endif
        @if ($leave->comments)
            <div class="detail-item full-width">
                <label>Comments:</label>
                <p class="comments-text">{{ $leave->comments }}</p>
            </div>
        @endif
    </div>

    @if ($leave->status === 'pending')
        <div class="action-section">
            <h4>Actions</h4>
            <div class="action-buttons">
                <button class="btn btn-success" onclick="approveLeave('{{ $leave->leave_id }}')">
                    <i class="fas fa-check"></i>
                    Approve Leave
                </button>
                <button class="btn btn-danger" onclick="rejectLeave('{{ $leave->leave_id }}')">
                    <i class="fas fa-times"></i>
                    Reject Leave
                </button>
            </div>
        </div>
    @endif
    
    <div class="details-footer">
        <button class="btn btn-secondary" onclick="closeModal()">
            Close
        </button>
    </div>
</div>

