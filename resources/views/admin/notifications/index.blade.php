@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin/notifications.css') }}">
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

    /* Modern Notifications Styles */
    .card {
        border-radius: 1rem;
        box-shadow: 0 2px 16px 0 rgba(0, 0, 0, 0.07);
        border: none;
        background: #fff;
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
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        box-shadow: 0 1px 4px 0 rgba(0, 0, 0, 0.04);
    }

    .btn-primary {
        background: linear-gradient(90deg, var(--primary) 60%, var(--secondary) 100%);
        color: #fff;
        border: none;
    }

    .btn-primary:hover {
        background: linear-gradient(90deg, var(--secondary) 60%, var(--primary) 100%);
    }

    .btn-secondary {
        background: var(--primary-light);
        color: var(--text-secondary);
        border: none;
    }

    .btn-secondary:hover {
        background: var(--divider);
    }

    .btn-warning {
        background: var(--warning);
        color: #fff;
        border: none;
    }

    .btn-warning:hover {
        background: #ffb300;
    }

    .btn-danger {
        background: var(--danger);
        color: #fff;
        border: none;
    }

    .btn-danger:hover {
        background: #d84315;
    }

    .btn-success {
        background: var(--success);
        color: #fff;
        border: none;
    }

    .btn-success:hover {
        background: #388e3c;
    }

    .btn-info {
        background: var(--info);
        color: #fff;
        border: none;
    }

    .btn-info:hover {
        background: #007c91;
    }

    .badge {
        font-weight: 600;
        letter-spacing: 0.02em;
        display: inline-block;
    }

    .form-select {
        border-radius: 0.5rem;
        border: 1px solid var(--divider);
        background: var(--primary-light);
        padding: 0.5rem 1rem;
        font-size: 1rem;
        transition: border 0.2s, box-shadow 0.2s;
    }

    .form-select:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 2px #d32f2f22;
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

    .card-body h4 {
        color: var(--text-primary);
        margin: 0;
        font-size: 1rem;
        font-weight: 600;
    }

    .card-body p {
        color: var(--text-secondary);
        margin-bottom: 1rem;
        line-height: 1.7;
    }

    .card[style*="border-left"] {
        box-shadow: 0 2px 8px 0 rgba(37, 99, 235, 0.04);
        transition: box-shadow 0.2s;
    }

    .card[style*="border-left"]:hover {
        box-shadow: 0 4px 24px 0 rgba(37, 99, 235, 0.08);
    }

    .card-body>div[style*="display: flex; flex-direction: column"]>.card {
        border: 1px solid var(--divider);
    }

    a.btn,
    button.btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
    }

    @media (max-width: 900px) {

        .card-body,
        .card-header {
            padding: 1rem;
        }

        .card-body h4 {
            font-size: 0.95rem;
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
        backdrop-filter: blur(4px);
    }

    .modal.show {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: #fff;
        border-radius: 1rem;
        width: 90%;
        max-width: 600px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
        animation: modalSlideIn 0.3s ease-out;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(-30px) scale(0.95);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--divider);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: linear-gradient(90deg, var(--primary-light) 60%, #fff 100%);
        border-radius: 1rem 1rem 0 0;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .modal-body {
        padding: 2rem;
    }

    .close {
        color: var(--text-secondary);
        font-size: 1.5rem;
        font-weight: bold;
        cursor: pointer;
        border: none;
        background: none;
        padding: 0.5rem;
        border-radius: 0.5rem;
        transition: all 0.2s;
    }

    .close:hover {
        color: var(--primary);
        background: var(--primary-light);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        color: var(--text-primary);
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 0.75rem 1rem;
        border: 1px solid var(--divider);
        border-radius: 0.5rem;
        font-size: 1rem;
        transition: all 0.2s;
        background: var(--primary-light);
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: var(--primary);
        box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.1);
    }

    .form-group textarea {
        resize: vertical;
        min-height: 100px;
    }

    .input-icon {
        position: relative;
    }

    .input-icon i {
        position: absolute;
        left: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: var(--text-secondary);
        font-size: 0.875rem;
    }

    .input-icon input {
        padding-left: 2.5rem;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--divider);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn-cancel {
        background: var(--primary-light);
        color: var(--text-secondary);
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: var(--divider);
    }

    .btn-submit {
        background: var(--primary);
        color: #fff;
        border: none;
        padding: 0.75rem 2rem;
        border-radius: 0.5rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-submit:hover {
        background: var(--secondary);
    }

    .btn-submit:disabled {
        background: var(--text-disabled);
        cursor: not-allowed;
    }

    /* Notification Animation */
    @keyframes notificationSlideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .notification-item {
        animation: notificationSlideIn 0.3s ease-out;
    }

    .notification-item.unread {
        background: rgba(211, 47, 47, 0.02);
        border-left-color: var(--primary);
    }

    .notification-item.read {
        opacity: 0.8;
    }

    /* Search and Filter Styles */
    .search-input {
        border-radius: 0.5rem;
        border: 1px solid var(--divider);
        background: var(--primary-light);
        padding: 0.5rem 1rem;
        font-size: 1rem;
        transition: border 0.2s, box-shadow 0.2s;
        width: 300px;
    }

    .search-input:focus {
        border-color: var(--primary);
        outline: none;
        box-shadow: 0 0 0 2px #d32f2f22;
    }

    /* Notification Badge */
    .notification-badge {
        position: relative;
        display: inline-block;
    }

    .notification-badge::after {
        content: '';
        position: absolute;
        top: -2px;
        right: -2px;
        width: 8px;
        height: 8px;
        background: var(--primary);
        border-radius: 50%;
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% {
            opacity: 1;
        }

        50% {
            opacity: 0.5;
        }

        100% {
            opacity: 1;
        }
    }

    /* Toast Notifications */
    .toast {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--success);
        color: white;
        padding: 1rem 1.5rem;
        border-radius: 0.5rem;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        animation: slideIn 0.3s ease-out;
    }

    .toast.error {
        background: var(--danger);
    }

    .toast.warning {
        background: var(--warning);
    }

    .toast.info {
        background: var(--info);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .filter-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .filter-group {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        flex: 1;
    }

    .filter-actions {
        display: flex;
        gap: 0.75rem;
    }

    .search-wrapper {
        width: 300px;
    }

    .filter-select {
        width: 150px;
    }

    @media (max-width: 900px) {
        .search-wrapper {
            width: 200px;
        }
        .filter-select {
            width: auto;
            flex: 1;
        }
    }

    @media (max-width: 600px) {
        .modal-content {
            width: 95%;
            margin: 1rem;
        }

        .modal-header,
        .modal-body,
        .modal-footer {
            padding: 1rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer button {
            width: 100%;
        }

        /* Responsive Filters */
        .filter-container {
            flex-direction: column;
            align-items: stretch;
            gap: 1rem;
        }

        .filter-group {
            flex-direction: column;
            width: 100%;
        }

        .search-wrapper {
            width: 100%;
        }
        
        .search-input {
            width: 100% !important; /* Force full width on mobile */
        }

        .filter-select {
            width: 100% !important;
        }

        .filter-actions {
            justify-content: flex-end; /* Align clear/refresh to right, or stretch if needed */
            width: 100%;
        }

        .filter-actions .btn {
            flex: 1; /* Make action buttons equal width */
            justify-content: center;
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
        }
    }
</style>

@section('title', 'Notifications')

@section('content')
    <!-- Notification Statistics -->
    <div
        style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <div class="card" onclick="filterByStatus('')" style="cursor: pointer;">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;"
                    id="totalNotifications">{{ $totalCount }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">Total Notifications</div>
            </div>
        </div>
        <div class="card" onclick="filterByStatus('unread')" style="cursor: pointer;">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;"
                    id="unreadCount">{{ $unreadCount }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">Unread</div>
            </div>
        </div>
        <div class="card" onclick="filterByStatus('read')" style="cursor: pointer;">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;"
                    id="readCount">{{ $readCount }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">Read</div>
            </div>
        </div>
        <div class="card" onclick="filterByAction()" style="cursor: pointer;">
            <div class="card-body text-center">
                <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;"
                    id="actionCount">{{ $actionCount }}</div>
                <div style="color: var(--gray-600); font-weight: 500;">Action Required</div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-header">
            <h2>
                <i class="fas fa-bell {{ $unreadCount == 0 ? 'notification' : 'notification-badge' }}"></i>
                Notifications
            </h2>
            <div class="flex gap-2">
                <button class="btn btn-primary" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i>
                    <span class="btn-text">Mark All Read</span>
                </button>
                <button class="btn btn-danger" onclick="deleteAllNotifications()">
                    <i class="fas fa-trash-alt"></i>
                    <span class="btn-text">Delete All</span>
                </button>


            </div>
        </div>
        <div class="card-body">
            <!-- Filter Section -->
            <div class="filter-container mb-4">
                <div class="filter-group">
                    <div class="input-icon search-wrapper">
                        <i class="fas fa-search"></i>
                        <input type="text" class="search-input" placeholder="Search notifications..." id="searchInput"
                            onkeyup="searchNotifications()">
                    </div>
                    <select class="form-select filter-select" id="statusFilter" onchange="filterNotifications()">
                        <option value="">All Notifications</option>
                        <option value="unread">Unread</option>
                        <option value="read">Read</option>
                    </select>
                    <select class="form-select filter-select" id="typeFilter" onchange="filterNotifications()">
                        <option value="">All Types</option>
                        <option value="system">System</option>
                        <option value="employee">Employee</option>
                        <option value="leave">Leave</option>
                        <option value="project">Project</option>
                    </select>
                </div>
                <div class="filter-actions">
                    <button class="btn btn-secondary" onclick="clearFilters()">
                        <i class="fas fa-times"></i>
                        <span class="btn-text">Clear</span>
                    </button>
                    <button class="btn btn-info" onclick="refreshNotifications()">
                        <i class="fas fa-sync"></i>
                        <span class="btn-text">Refresh</span>
                    </button>
                </div>
            </div>

            <!-- Notifications List -->
            <div id="notificationsList" style="display: flex; flex-direction: column; gap: 1rem;">
                {{-- <div class="card notification-item unread" data-type="employee" data-id="1"
                    style="border-left: 4px solid var(--primary); background: rgba(59, 130, 246, 0.02);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; gap: 1rem; flex: 1;">
                                <div
                                    style="width: 3rem; height: 3rem; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                                    <i class="fas fa-user-plus"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4
                                        style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                        New Employee Registration</h4>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">John Doe has
                                        registered and is pending approval. Please review the application and take necessary
                                        action.</p>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <span><i class="fas fa-clock"></i> 5 minutes ago</span>
                                        <span><i class="fas fa-tag"></i> Employee</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(1)"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary" style="padding: 0.5rem;" onclick="markAsRead(1)"
                                    title="Mark as Read">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(1)"
                                    title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="card notification-item unread" data-type="leave" data-id="2"
                    style="border-left: 4px solid var(--warning); background: rgba(245, 158, 11, 0.02);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; gap: 1rem; flex: 1;">
                                <div
                                    style="width: 3rem; height: 3rem; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--warning); flex-shrink: 0;">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4
                                        style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                        Leave Request Pending</h4>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Sarah Miller
                                        submitted a leave request for December 20-27, 2024. Awaiting your approval.</p>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <span><i class="fas fa-clock"></i> 1 hour ago</span>
                                        <span><i class="fas fa-tag"></i> Leave</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(2)"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-success" style="padding: 0.5rem;" onclick="approveLeave(2)"
                                    title="Approve">
                                    <i class="fas fa-check"></i>
                                </button>
                                <button class="btn btn-danger" style="padding: 0.5rem;" onclick="rejectLeave(2)"
                                    title="Reject">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="card notification-item read" data-type="project" data-id="3"
                    style="border-left: 4px solid var(--success);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; gap: 1rem; flex: 1;">
                                <div
                                    style="width: 3rem; height: 3rem; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); flex-shrink: 0;">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4
                                        style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                        Project Completed</h4>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Development
                                        team completed Project Alpha successfully. All deliverables have been submitted.</p>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <span><i class="fas fa-clock"></i> 3 hours ago</span>
                                        <span><i class="fas fa-tag"></i> Project</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(3)"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;"
                                    onclick="deleteNotification(3)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="card notification-item read" data-type="system" data-id="4"
                    style="border-left: 4px solid var(--info);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; gap: 1rem; flex: 1;">
                                <div
                                    style="width: 3rem; height: 3rem; background: rgba(6, 182, 212, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--info); flex-shrink: 0;">
                                    <i class="fas fa-chart-bar"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4
                                        style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                        Monthly Report Generated</h4>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Employee
                                        performance report for October 2024 is ready for review. Download from the reports
                                        section.</p>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <span><i class="fas fa-clock"></i> 1 day ago</span>
                                        <span><i class="fas fa-tag"></i> System</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(4)"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;"
                                    onclick="deleteNotification(4)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}

                {{-- <div class="card notification-item read" data-type="system" data-id="5"
                    style="border-left: 4px solid var(--secondary);">
                    <div class="card-body">
                        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                            <div style="display: flex; gap: 1rem; flex: 1;">
                                <div
                                    style="width: 3rem; height: 3rem; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--secondary); flex-shrink: 0;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div style="flex: 1;">
                                    <h4
                                        style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                        System Maintenance Reminder</h4>
                                    <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Scheduled
                                        maintenance on Sunday, December 1st, from 2:00 AM to 4:00 AM. System will be
                                        temporarily unavailable.</p>
                                    <div
                                        style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                        <span><i class="fas fa-clock"></i> 2 days ago</span>
                                        <span><i class="fas fa-tag"></i> System</span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex gap-1">
                                <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(5)"
                                    title="View Details">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-secondary" style="padding: 0.5rem;"
                                    onclick="deleteNotification(5)" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div> --}}
                @forelse($notifications as $notification)
                    <div class="card notification-item {{ $notification->is_read ? '' : 'unread' }}"
                        data-type="{{ $notification->type }}" data-id="{{ $notification->notifi_id }}"
                        style="border-left: 4px solid 
            {{ $notification->type === 'leave' ? 'var(--warning)' : 'var(--primary)' }};
            background: {{ $notification->is_read ? 'white' : 'rgba(245, 158, 11, 0.02)' }};">

                        <div class="card-body">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">

                                <!-- Left side -->
                                <div style="display: flex; gap: 1rem; flex: 1;">
                                    <div
                                        style="width: 3rem; height: 3rem; 
                        background: rgba(245, 158, 11, 0.1); 
                        border-radius: 50%; display: flex; 
                        align-items: center; justify-content: center; 
                        color: var(--warning); flex-shrink: 0;">
                                        <i class="fas fa-calendar-times"></i>
                                    </div>

                                    <div style="flex: 1;">
                                        <h4
                                            style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">
                                            {{ $notification->title ?? 'Notification' }}
                                        </h4>

                                        <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">
                                            {{ $notification->message }}
                                        </p>

                                        <div
                                            style="display: flex; align-items: center; gap: 1rem; 
                            font-size: 0.875rem; color: var(--gray-500);">
                                            <span><i class="fas fa-clock"></i>
                                                {{ $notification->created_at->diffForHumans() }}
                                            </span>
                                            <span><i class="fas fa-tag"></i>
                                                {{ ucfirst($notification->type ?? 'system') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Right side action buttons -->
                                <div class="flex gap-1">
                                    <!-- Always show View button -->
                                    <button class="btn btn-info" style="padding: 0.5rem;"
                                        onclick="viewNotification('{{ $notification->notifi_id }}')" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if (!$notification->is_read)
                                        <button class="btn btn-primary" style="padding: 0.5rem;"
                                            onclick="markAsRead('{{ $notification->notifi_id }}')" title="Mark as Read">
                                            <i class="fas fa-check"></i>
                                        </button>
                                    @endif
                                    <button class="btn btn-secondary" style="padding: 0.5rem;"
                                        onclick="deleteNotification('{{ $notification->notifi_id }}')" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>

                                    @if ($notification->type === 'leave')
                                        @php
                                            // fetch leave status via eager-loaded relationship (if you set it up)
                                            $leaveStatus = $notification->leave->status ?? null;
                                        @endphp

                                        @if ($leaveStatus === 'pending')
                                            <button class="btn btn-success" style="padding: 0.5rem;"
                                                onclick="approveLeave('{{ $notification->reference_id }}', '{{ $notification->notifi_id }}')"
                                                title="Approve">
                                                <i class="fas fa-check"></i>
                                            </button>
                                            <button class="btn btn-danger" style="padding: 0.5rem;"
                                                onclick="rejectLeave('{{ $notification->reference_id }}', '{{ $notification->notifi_id }}')"
                                                title="Reject">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        @endif
                                    @endif
                                    @if ($notification->type === 'project')
                                        @php

                                            $projectStatus = $notification->project->status ?? null;
                                        @endphp

                                        @if ($projectStatus === 'Completed')
                                            <button class="btn btn-info" style="padding: 0.5rem;"
                                                onclick="viewProject('{{ $notification->reference_id }}')"
                                                title="View Project">
                                                <i class="fas fa-folder-open"></i>
                                            </button>
                                        @endif
                                    @endif

                                    @if ($notification->type === 'employee')
                                        @php
                                            $employee = $notification->employee ?? null;
                                        @endphp

                                        @if ($employee)
                                            <button class="btn btn-info" style="padding: 0.5rem;"
                                                onclick="viewEmployee('{{ $notification->reference_id }}')"
                                                title="View Employee">
                                                <i class="fas fa-user"></i>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p>No notifications available.</p>
                @endforelse

            </div>
            <!-- Pagination -->
            <div class="flex justify-between items-center mt-4">
                <div style="color: var(--gray-600); font-size: 0.875rem;">
                    Showing <span id="showingStart">1</span> to <span id="showingEnd">5</span> of
                    <span id="totalCount">0</span> notifications
                </div>
                <div class="flex gap-1" id="paginationContainer">
                    <!-- JS will insert pagination buttons here -->
                </div>
            </div>

        </div>
    </div>



    <!-- Modals -->
    <!-- View Notification Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-eye"></i> Notification Details</h3>
                <button class="close" onclick="closeModal('viewModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div id="notificationDetails">
                    <!-- Notification details will be populated here -->
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('viewModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Create Notification Modal -->
    <div id="createModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-plus"></i> Create New Notification</h3>
                <button class="close" onclick="closeModal('createModal')">&times;</button>
            </div>
            <div class="modal-body">
                <form id="createNotificationForm">
                    <div class="form-group">
                        <label for="notificationTitle">Title</label>
                        <div class="input-icon">
                            <i class="fas fa-heading"></i>
                            <input type="text" id="notificationTitle" name="title" required
                                placeholder="Enter notification title">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notificationMessage">Message</label>
                        <textarea id="notificationMessage" name="message" required placeholder="Enter notification message"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="notificationType">Type</label>
                        <select id="notificationType" name="type" required>
                            <option value="">Select Type</option>
                            <option value="system">System</option>
                            <option value="employee">Employee</option>
                            <option value="leave">Leave</option>
                            <option value="project">Project</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notificationPriority">Priority</label>
                        <select id="notificationPriority" name="priority">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="notificationRecipients">Recipients</label>
                        <select id="notificationRecipients" name="recipients" multiple>
                            <option value="all">All Users</option>
                            <option value="admins">Admins Only</option>
                            <option value="employees">Employees Only</option>
                            <option value="department">Department Specific</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('createModal')">Cancel</button>
                <button class="btn-submit" onclick="createNotification()">Create Notification</button>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div id="settingsModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-cog"></i> Notification Settings</h3>
                <button class="close" onclick="closeModal('settingsModal')">&times;</button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label>Email Notifications</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="checkbox" id="emailNotifications" checked>
                        <label for="emailNotifications">Send email notifications</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Auto-mark as read</label>
                    <div style="display: flex; gap: 1rem; align-items: center;">
                        <input type="checkbox" id="autoMarkRead">
                        <label for="autoMarkRead">Automatically mark as read after viewing</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Notification Sound</label>
                    <select id="notificationSound">
                        <option value="default">Default</option>
                        <option value="none">None</option>
                        <option value="chime">Chime</option>
                        <option value="bell">Bell</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Auto-refresh interval (seconds)</label>
                    <select id="refreshInterval">
                        <option value="30">30 seconds</option>
                        <option value="60" selected>1 minute</option>
                        <option value="300">5 minutes</option>
                        <option value="0">Disabled</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('settingsModal')">Cancel</button>
                <button class="btn-submit" onclick="saveSettings()">Save Settings</button>
            </div>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Action</h3>
                <button class="close" onclick="closeModal('confirmModal')">&times;</button>
            </div>
            <div class="modal-body">
                <p id="confirmMessage">Are you sure you want to perform this action?</p>
            </div>
            <div class="modal-footer">
                <button class="btn-cancel" onclick="closeModal('confirmModal')">Cancel</button>
                <button class="btn-submit" id="confirmButton" onclick="confirmAction()">Confirm</button>
            </div>
        </div>
    </div>

    <script>
        // Global variables
        let currentPage = 1;
        let notificationsPerPage = 3;
        let totalNotifications = 23;
        let currentFilter = '';
        let currentType = '';
        let currentSearch = '';
        let confirmCallback = null;

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateStats();
            setupAutoRefresh();
        });

        // Modal functions
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('show');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('show');
        }

        function openCreateModal() {
            openModal('createModal');
        }

        function openSettingsModal() {
            openModal('settingsModal');
        }

        function viewNotification(id) {
            const notification = getNotificationById(1);
            if (notification) {
                const details = `
            <div style="padding: 1rem; border: 1px solid var(--divider); border-radius: 0.5rem; margin-bottom: 1rem;">
                <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">${notification.title}</h4>
                <p style="margin: 0 0 1rem 0; color: var(--text-secondary);">${notification.message}</p>
                <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                    <span><i class="fas fa-clock"></i> ${notification.time}</span>
                    <span><i class="fas fa-tag"></i> ${notification.type}</span>
                    <span><i class="fas fa-user"></i> ${notification.sender || 'System'}</span>
                </div>
            </div>
        `;
                document.getElementById('notificationDetails').innerHTML = details;
                openModal('viewModal');

                // Mark as read if it's unread
                if (notification.status === 'unread') {
                    markAsRead(id);
                }
            }
        }


        async function viewNotification(notificationId) {
            console.log("Notification ID:", notificationId);

            try {
                const response = await fetch(`/admin/notifications/${notificationId}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log('API Response Data:', data);

                if (data) {
                    const details = `
                <div style="padding: 1rem; border: 1px solid var(--divider); border-radius: 0.5rem; margin-bottom: 1rem;">
                    <h4 style="margin: 0 0 0.5rem 0; color: var(--text-primary);">${data.title}</h4>
                    <p style="margin: 0 0 1rem 0; color: var(--text-secondary);">${data.message}</p>
                    <div style="display: flex; gap: 1rem; font-size: 0.875rem; color: var(--text-secondary);">
                        <span><i class="fas fa-clock"></i> ${data.created_at}</span>
                        <span><i class="fas fa-tag"></i> ${data.type}</span>
                        <span><i class="fas fa-user"></i> ${data.user_id || 'System'}</span>
                    </div>
                </div>
            `;
                    document.getElementById('notificationDetails').innerHTML = details;
                    openModal('viewModal');

                    // Mark as read if unread
                    if (data.is_read === 0) {
                        markAsRead(notificationId);
                    }
                }

            } catch (error) {
                console.error('There was a problem with the fetch operation:', error);
            }
        }

        async function markAsRead(id) {
            try {
                const response = await fetch(`/admin/notifications/${id}/mark-as-read`, {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                        "Content-Type": "application/json"
                    }
                });

                const result = await response.json();
                if (result.success) {
                    const notification = document.querySelector(`[data-id="${id}"]`);
                    if (notification) {
                        notification.classList.remove('unread');
                        notification.classList.add('read');
                    }
                    showToast(result.message, 'success');
                    updateStats();
                }
            } catch (error) {
                console.error("Error marking notification as read:", error);
            }
        }


        async function markAllAsRead() {
            showConfirm('Are you sure you want to mark all notifications as read?', async () => {
                try {
                    const response = await fetch(`/admin/notifications/mark-all-as-read`, {
                        method: "POST",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .content,
                            "Content-Type": "application/json"
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
                        unreadNotifications.forEach(notification => {
                            notification.classList.remove('unread');
                            notification.classList.add('read');
                        });
                        showToast(result.message, 'success');
                        updateStats();
                    }
                } catch (error) {
                    console.error("Error marking all notifications as read:", error);
                }
            });
        }

        async function deleteAllNotifications() {
            showConfirm('Are you sure you want to delete ALL notifications? This action cannot be undone.', async () => {
                try {
                    const response = await fetch(`/admin/notifications/delete-all`, {
                        method: "DELETE",
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                            "Content-Type": "application/json"
                        }
                    });

                    const result = await response.json();
                    if (result.success) {
                        document.getElementById('notificationsList').innerHTML = '<p>No notifications available.</p>';
                        showToast(result.message, 'success');
                        updateStats();
                    } else {
                        showToast(result.message || 'Failed to delete notifications', 'error');
                    }
                } catch (error) {
                    console.error("Error deleting all notifications:", error);
                    showToast('An error occurred while deleting notifications', 'error');
                }
            });
        }


        function deleteNotification(id) {
            showConfirm('Are you sure you want to delete this notification?', async () => {
                try {
                    const response = await fetch(`/admin/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error(`HTTP error! Status: ${response.status}`);

                    const result = await response.json();

                    if (result.success) {
                        const notification = document.querySelector(`[data-id="${id}"]`);
                        if (notification) {
                            notification.style.animation = 'slideOut 0.3s ease-out';
                            setTimeout(() => {
                                notification.remove();
                                showToast('Notification deleted', 'success');
                                updateStats();
                            }, 300);
                        }
                    } else {
                        showToast(result.message || 'Failed to delete notification', 'error');
                    }
                } catch (error) {
                    console.error('Error deleting notification:', error);
                    showToast('An error occurred while deleting the notification', 'error');
                }
            });
        }

        async function approveLeave(leaveId, notificationId) {
            showConfirm('Are you sure you want to approve this leave request?', async () => {
                try {
                    const response = await fetch(`/admin/leaves/${leaveId}/status`, {
                        method: 'PUT',
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .content,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            status: 'approved'
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        if (notificationId) markAsRead(notificationId);
                        showToast('Leave request approved', 'success');
                        updateStats();
                    } else {
                        showToast('Failed to approve leave', 'error');
                    }
                } catch (error) {
                    console.error('Error approving leave:', error);
                    showToast('Error approving leave', 'error');
                }
            });
        }

        async function rejectLeave(leaveId, notificationId) {
            showConfirm('Are you sure you want to reject this leave request?', async () => {
                try {
                    const response = await fetch(`/admin/leaves/${leaveId}/status`, {
                        method: 'PUT',
                        headers: {
                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                                .content,
                            "Content-Type": "application/json"
                        },
                        body: JSON.stringify({
                            status: 'rejected',
                            rejection_reason: 'rejected'
                        })
                    });

                    const result = await response.json();
                    if (result.success) {
                        if (notificationId) markAsRead(notificationId);
                        showToast('Leave request rejected', 'error');
                        updateStats();
                    } else {
                        showToast('Failed to reject leave', 'error');
                    }
                } catch (error) {
                    console.error('Error rejecting leave:', error);
                    showToast('Error rejecting leave', 'error');
                }
            });
        }


        function createNotification() {
            const form = document.getElementById('createNotificationForm');
            const formData = new FormData(form);

            if (formData.get('title') && formData.get('message') && formData.get('type')) {
                // Here you would typically send the data to your backend
                console.log('Creating notification:', Object.fromEntries(formData));

                closeModal('createModal');
                form.reset();
                showToast('Notification created successfully', 'success');

                // Add new notification to the list (mock implementation)
                addNewNotification(formData);
            } else {
                showToast('Please fill in all required fields', 'error');
            }
        }

        function addNewNotification(formData) {
            const notificationsList = document.getElementById('notificationsList');
            const newNotification = document.createElement('div');
            newNotification.className = 'card notification-item unread';
            newNotification.setAttribute('data-type', formData.get('type'));
            newNotification.setAttribute('data-id', Date.now());

            const typeColors = {
                'system': 'var(--info)',
                'employee': 'var(--primary)',
                'leave': 'var(--warning)',
                'project': 'var(--success)'
            };

            const typeIcons = {
                'system': 'fas fa-cogs',
                'employee': 'fas fa-user',
                'leave': 'fas fa-calendar',
                'project': 'fas fa-project-diagram'
            };

            newNotification.innerHTML = `
        <div class="card-body">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div style="display: flex; gap: 1rem; flex: 1;">
                    <div style="width: 3rem; height: 3rem; background: ${typeColors[formData.get('type')]}22; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: ${typeColors[formData.get('type')]}; flex-shrink: 0;">
                        <i class="${typeIcons[formData.get('type')]}"></i>
                    </div>
                    <div style="flex: 1;">
                        <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">${formData.get('title')}</h4>
                        <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">${formData.get('message')}</p>
                        <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                            <span><i class="fas fa-clock"></i> Just now</span>
                            <span><i class="fas fa-tag"></i> ${formData.get('type')}</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-1">
                    <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(${Date.now()})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn btn-primary" style="padding: 0.5rem;" onclick="markAsRead(${Date.now()})" title="Mark as Read">
                        <i class="fas fa-check"></i>
                    </button>
                    <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(${Date.now()})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        </div>
    `;

            notificationsList.insertBefore(newNotification, notificationsList.firstChild);
            updateStats();
        }

        // Search and filter functions
        function searchNotifications() {
            const searchTerm = document.getElementById('searchInput').value.toLowerCase();
            currentSearch = searchTerm;
            filterNotifications();
        }

        function filterNotifications() {
            const notifications = document.querySelectorAll('.notification-item');
            const statusFilter = document.getElementById('statusFilter').value;
            const typeFilter = document.getElementById('typeFilter').value;

            notifications.forEach(notification => {
                const title = notification.querySelector('h4').textContent.toLowerCase();
                const message = notification.querySelector('p').textContent.toLowerCase();
                const type = notification.getAttribute('data-type');
                const status = notification.classList.contains('unread') ? 'unread' : 'read';

                const matchesSearch = currentSearch === '' || title.includes(currentSearch) || message.includes(
                    currentSearch);
                const matchesStatus = statusFilter === '' || status === statusFilter;
                const matchesType = typeFilter === '' || type === typeFilter;

                if (matchesSearch && matchesStatus && matchesType) {
                    notification.style.display = 'block';
                } else {
                    notification.style.display = 'none';
                }
            });

            updateStats();
        }

        function clearFilters() {
            document.getElementById('searchInput').value = '';
            document.getElementById('statusFilter').value = '';
            document.getElementById('typeFilter').value = '';
            currentSearch = '';
            filterNotifications();
            showToast('Filters cleared', 'info');

            updateStats();
        }

        function filterByStatus(status) {
            document.getElementById('statusFilter').value = status;
            filterNotifications();
            updateStats();
        }


        function filterByAction() {
            const notifications = document.querySelectorAll('.notification-item');
            notifications.forEach(notification => {
                const type = notification.getAttribute('data-type');
                const status = notification.classList.contains('unread') ? 'unread' : 'read';

                if (status === 'unread' && (type === 'employee' || type === 'leave')) {
                    notification.style.display = 'block';
                } else {
                    notification.style.display = 'none';
                }
            });
            updateStats();
        }

        document.addEventListener('DOMContentLoaded', () => {
            updatePagination();
        });

        function refreshNotifications() {
            showToast('Refreshing notifications...', 'info');
            fetch('/admin/notifications')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('notificationsContainer').innerHTML = html;
                    filterNotifications();
                    updateStats();
                    updatePagination();
                    showToast('Notifications refreshed', 'success');
                });
        }

        function updatePagination() {
            const notifications = document.querySelectorAll('#notificationsList .notification-item');
            totalNotifications = notifications.length;

            const totalPages = Math.ceil(totalNotifications / notificationsPerPage);
            const start = (currentPage - 1) * notificationsPerPage;
            const end = Math.min(start + notificationsPerPage, totalNotifications);

            // Show only current page's notifications
            notifications.forEach((n, i) => {
                n.style.display = (i >= start && i < end) ? 'block' : 'none';
            });

            // Update counters
            document.getElementById('showingStart').textContent = totalNotifications === 0 ? 0 : start + 1;
            document.getElementById('showingEnd').textContent = end;
            document.getElementById('totalCount').textContent = totalNotifications;

            // Rebuild pagination buttons dynamically
            const paginationContainer = document.getElementById('paginationContainer');
            paginationContainer.innerHTML = '';

            // Prev button
            const prevBtn = document.createElement('button');
            prevBtn.className = "btn btn-secondary";
            prevBtn.style.padding = "0.5rem 0.75rem";
            prevBtn.innerHTML = '<i class="fas fa-chevron-left"></i>';
            prevBtn.disabled = currentPage === 1;
            prevBtn.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    updatePagination();
                }
            };
            paginationContainer.appendChild(prevBtn);

            // Page number buttons
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = "btn " + (i === currentPage ? "btn-primary" : "btn-secondary");
                pageBtn.style.padding = "0.5rem 0.75rem";
                pageBtn.textContent = i;
                pageBtn.onclick = () => {
                    currentPage = i;
                    updatePagination();
                };
                paginationContainer.appendChild(pageBtn);
            }

            // Next button
            const nextBtn = document.createElement('button');
            nextBtn.className = "btn btn-secondary";
            nextBtn.style.padding = "0.5rem 0.75rem";
            nextBtn.innerHTML = '<i class="fas fa-chevron-right"></i>';
            nextBtn.disabled = currentPage === totalPages;
            nextBtn.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    updatePagination();
                }
            };
            paginationContainer.appendChild(nextBtn);
        }


        // Statistics functions
        function updateStats() {
            const notifications = document.getElementById('totalCount').length;
            console.log(notifications);

            const unreadCount = document.querySelectorAll('.notification-item.unread').length;
            const readCount = notifications.length - unreadCount;
            const actionCount = document.querySelectorAll(
                    '.notification-item.unread[data-type="employee"], .notification-item.unread[data-type="leave"]')
                .length;

            document.getElementById('totalNotifications').textContent = notifications.length;
            document.getElementById('unreadCount').textContent = unreadCount;
            document.getElementById('readCount').textContent = readCount;
            document.getElementById('actionCount').textContent = actionCount;

            totalNotifications = notifications.length;
            updatePagination();
        }

        // Settings functions
        function saveSettings() {
            const emailNotifications = document.getElementById('emailNotifications').checked;
            const autoMarkRead = document.getElementById('autoMarkRead').checked;
            const notificationSound = document.getElementById('notificationSound').value;
            const refreshInterval = document.getElementById('refreshInterval').value;

            // Save settings to localStorage or backend
            const settings = {
                emailNotifications,
                autoMarkRead,
                notificationSound,
                refreshInterval
            };

            localStorage.setItem('notificationSettings', JSON.stringify(settings));
            closeModal('settingsModal');
            showToast('Settings saved successfully', 'success');

            // Update auto-refresh if needed
            setupAutoRefresh();
        }

        function setupAutoRefresh() {
            const settings = JSON.parse(localStorage.getItem('notificationSettings')) || {};
            const interval = settings.refreshInterval || 60;

            if (interval > 0) {
                setInterval(() => {
                    refreshNotifications();
                }, interval * 1000);
            }
        }

        // Utility functions
        function showToast(message, type = 'info') {
            const toast = document.createElement('div');
            toast.className = `toast ${type}`;
            toast.textContent = message;

            document.body.appendChild(toast);

            setTimeout(() => {
                toast.remove();
            }, 3000);
        }

        function showConfirm(message, callback) {
            document.getElementById('confirmMessage').textContent = message;
            confirmCallback = callback;
            openModal('confirmModal');
        }

        function confirmAction() {
            if (confirmCallback) {
                confirmCallback();
                confirmCallback = null;
            }
            closeModal('confirmModal');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    closeModal(modal.id);
                }
            });
        }

        // Add slideOut animation
        const style = document.createElement('style');
        style.textContent = `
    @keyframes slideOut {
        from {
            opacity: 1;
            transform: translateX(0);
        }
        to {
            opacity: 0;
            transform: translateX(100%);
        }
    }
`;
        document.head.appendChild(style);
    </script>
@endsection
