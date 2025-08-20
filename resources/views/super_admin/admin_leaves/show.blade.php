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
                <span>{{ $leave->employee->employee_name ?? 'Unknown' }}</span>
            </div>
            <div class="detail-item">
                <label>Employee ID:</label>
                <span>{{ $leave->employee->employee_id ?? 'N/A' }}</span>
            </div>
            <div class="detail-item">
                <label>Admin Role:</label>
                <span>{{ $leave->employee->admin->admin_name ?? 'Admin' }}</span>
            </div>
            <div class="detail-item">
                <label>Contact:</label>
                <span>{{ $leave->contact_number ?? 'N/A' }}</span>
            </div>
        </div>
    </div>

    <div class="detail-section">
        <h4>Leave Details</h4>
        <div class="detail-item full-width">
            <label>Reason:</label>
            <p class="reason-text">{{ $leave->reason }}</p>
        </div>
        @if($leave->supporting_doc)
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
            @if($leave->approved_date)
            <div class="detail-item">
                <label>Approved Date:</label>
                <span>{{ $leave->approved_date->format('M d, Y h:i A') }}</span>
            </div>
            <div class="detail-item">
                <label>Approved By:</label>
                <span>{{ $leave->approvedBy->admin_name ?? 'System' }}</span>
            </div>
            @endif
            @if($leave->rejected_date)
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
        @if($leave->rejection_reason)
        <div class="detail-item full-width">
            <label>Rejection Reason:</label>
            <p class="rejection-text">{{ $leave->rejection_reason }}</p>
        </div>
        @endif
        @if($leave->comments)
        <div class="detail-item full-width">
            <label>Comments:</label>
            <p class="comments-text">{{ $leave->comments }}</p>
        </div>
        @endif
    </div>

    @if($leave->status === 'pending')
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
</div>

<style>
.leave-details {
    padding: 1rem;
}

.detail-section {
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: 8px;
}

.detail-section h4 {
    margin: 0 0 1rem 0;
    color: var(--text-primary);
    font-size: 1.125rem;
    font-weight: 600;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.detail-item {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item.full-width {
    grid-column: 1 / -1;
}

.detail-item label {
    font-weight: 600;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.detail-item span {
    color: var(--text-primary);
    font-weight: 500;
}

.reason-text, .rejection-text, .comments-text {
    margin: 0;
    padding: 1rem;
    background: #fff;
    border-radius: 6px;
    border: 1px solid var(--border-light);
    color: var(--text-primary);
    line-height: 1.5;
}

.action-section {
    margin-top: 2rem;
    padding: 1.5rem;
    background: var(--bg-secondary);
    border-radius: 8px;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    margin-top: 1rem;
}

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

@media (max-width: 768px) {
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .action-buttons {
        flex-direction: column;
    }
}
</style> 