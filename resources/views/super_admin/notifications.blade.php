@extends('layouts.super_admin')

@section('title', 'All Notifications - Super Admin Dashboard')

@section('content')
<div class="notifications-container">
    <!-- Notifications Header -->
    <div class="notifications-header">
        <div class="header-content">
            <h1><i class="fas fa-bell"></i> All Notifications</h1>
            <p>Manage and monitor all system notifications and alerts</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i> Mark All Read
            </button>
            <button class="btn btn-secondary" onclick="refreshNotifications()">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
            <!-- <button class="btn btn-outline" onclick="exportNotifications()">
                <i class="fas fa-download"></i> Export
            </button> -->
        </div>
    </div>

    <!-- Notification Statistics Cards -->
    <div class="notification-stats-grid">
        <div class="notification-stat-card total">
            <div class="stat-icon">
                <i class="fas fa-bell"></i>
            </div>
            <div class="stat-content">
                <h3>Total Notifications</h3>
                <div class="stat-number">{{ $notificationStats['total'] }}</div>
                <p>All notifications</p>
            </div>
        </div>

        <div class="notification-stat-card unread">
            <div class="stat-icon">
                <i class="fas fa-envelope"></i>
            </div>
            <div class="stat-content">
                <h3>Unread</h3>
                <div class="stat-number">{{ $notificationStats['unread'] }}</div>
                <p>Require attention</p>
            </div>
            <div class="stat-trend urgent">
                <i class="fas fa-exclamation"></i> Action Required
            </div>
        </div>

        <div class="notification-stat-card critical">
            <div class="stat-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <div class="stat-content">
                <h3>Critical</h3>
                <div class="stat-number">{{ $notificationStats['critical'] }}</div>
                <p>High priority alerts</p>
            </div>
            <div class="stat-trend critical">
                <i class="fas fa-fire"></i> Immediate Action
            </div>
        </div>

        <div class="notification-stat-card priority">
            <div class="stat-icon">
                <i class="fas fa-flag"></i>
            </div>
            <div class="stat-content">
                <h3>High Priority</h3>
                <div class="stat-number">{{ $notificationStats['high'] }}</div>
                <p>Important notifications</p>
            </div>
        </div>
    </div>

    <!-- Notification Filters -->
    <div class="notification-filters">
        <div class="filter-row">
            <div class="filter-group">
                <label for="type-filter">Type</label>
                <select id="type-filter" class="filter-select" onchange="filterNotifications()">
                    <option value="">All Types</option>
                    <option value="employee">Employee</option>
                    <option value="leave">Leave</option>
                    <option value="security">Security</option>
                    <option value="project">Project</option>
                    <option value="system">System</option>
                    <option value="announcement">Announcement</option>
                    <option value="department">Department</option>
                    <option value="hr">HR</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="priority-filter">Priority</label>
                <select id="priority-filter" class="filter-select" onchange="filterNotifications()">
                    <option value="">All Priorities</option>
                    <option value="critical">Critical</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label for="status-filter">Status</label>
                <select id="status-filter" class="filter-select" onchange="filterNotifications()">
                    <option value="">All Status</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                </select>
            </div>
            
            <div class="filter-group">
                <input type="text" id="search-input" placeholder="Search notifications..." class="filter-input" oninput="filterNotifications()">
            </div>
            
            <div class="filter-group">
                <button class="btn btn-primary" onclick="filterNotifications()">
                    <i class="fas fa-filter"></i> Filter
                </button>
                <button class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i> Clear
                </button>
            </div>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="notifications-content">
        <div class="notifications-list">
            @foreach($notifications as $notification)
            <div class="notification-item {{ $notification['read'] ? 'read' : 'unread' }}" 
                 data-id="{{ $notification['id'] }}" 
                 data-type="{{ $notification['type'] }}" 
                 data-priority="{{ $notification['priority'] }}" 
                 data-status="{{ $notification['read'] ? 'read' : 'unread' }}">
                
                <div class="notification-indicator {{ $notification['color'] }}"></div>
                
                <div class="notification-icon {{ $notification['color'] }}">
                    <i class="{{ $notification['icon'] }}"></i>
                </div>
                
                <div class="notification-content">
                    <div class="notification-header">
                        <h4 class="notification-title">{{ $notification['title'] }}</h4>
                        <div class="notification-meta">
                            <span class="notification-priority priority-{{ $notification['priority'] }}">
                                {{ ucfirst($notification['priority']) }}
                            </span>
                            <span class="notification-time">
                                {{ $notification['timestamp']->diffForHumans() }}
                            </span>
                        </div>
                    </div>
                    
                    <p class="notification-message">{{ $notification['message'] }}</p>
                    
                    <div class="notification-footer">
                        <span class="notification-from">
                            <i class="fas fa-user"></i> {{ $notification['from'] }}
                        </span>
                        <span class="notification-type">
                            <i class="fas fa-tag"></i> {{ ucfirst($notification['type']) }}
                        </span>
                    </div>
                </div>
                
                <div class="notification-actions">
                    @if(!$notification['read'])
                    <button class="btn-action btn-mark-read" onclick="markAsRead({{ $notification['id'] }})" title="Mark as Read">
                        <i class="fas fa-check"></i>
                    </button>
                    @endif
                    <button class="btn-action btn-view" onclick="viewNotification({{ $notification['id'] }})" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteNotification({{ $notification['id'] }})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Empty State -->
        <div id="empty-state" class="empty-state" style="display: none;">
            <i class="fas fa-bell-slash"></i>
            <h3>No notifications found</h3>
            <p>No notifications match your current filters.</p>
        </div>
    </div>

    <!-- Pagination -->
    <div class="notifications-pagination">
        <div class="pagination-info">
            Showing {{ $notifications->count() }} of {{ $notificationStats['total'] }} notifications
        </div>
        <div class="pagination-controls">
            <button class="btn btn-secondary" disabled>
                <i class="fas fa-chevron-left"></i> Previous
            </button>
            <span class="pagination-current">Page 1 of 1</span>
            <button class="btn btn-secondary" disabled>
                Next <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
</div>

<!-- Notification Details Modal -->
<div id="notificationModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-bell"></i> Notification Details</h2>
            <button class="modal-close" onclick="closeNotificationModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="notificationDetails">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeNotificationModal()">Close</button>
            <button class="btn btn-primary" onclick="markAsReadFromModal()">
                <i class="fas fa-check"></i> Mark as Read
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

    .notifications-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header */
    .notifications-header {
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

    .btn-outline {
        background: white;
        color: var(--redcode-primary);
        border: 2px solid var(--redcode-primary);
    }

    .btn-outline:hover {
        background: var(--redcode-primary);
        color: white;
    }

    /* Notification Stats Grid */
    .notification-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .notification-stat-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .notification-stat-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
    }

    .notification-stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: var(--redcode-primary);
    }

    .notification-stat-card.unread::before {
        background: var(--redcode-orange);
    }

    .notification-stat-card.critical::before {
        background: #EF4444;
    }

    .notification-stat-card.priority::before {
        background: var(--redcode-yellow);
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

    .unread .stat-icon {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .critical .stat-icon {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .priority .stat-icon {
        background: rgba(245, 158, 11, 0.1);
        color: var(--redcode-yellow);
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
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .stat-trend.critical {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    /* Notification Filters */
    .notification-filters {
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

    /* Notifications List */
    .notifications-content {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        margin-bottom: 2rem;
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .notification-item {
        display: flex;
        align-items: flex-start;
        padding: 1.5rem;
        background: rgba(248, 250, 252, 0.3);
        border-radius: 12px;
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        position: relative;
    }

    .notification-item:hover {
        background: rgba(220, 38, 38, 0.02);
        border-color: rgba(220, 38, 38, 0.1);
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .notification-item.unread {
        background: rgba(220, 38, 38, 0.05);
        border-left: 4px solid var(--redcode-primary);
    }

    .notification-indicator {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 4px;
        border-radius: 0 2px 2px 0;
    }

    .notification-indicator.blue {
        background: var(--redcode-blue);
    }

    .notification-indicator.orange {
        background: var(--redcode-orange);
    }

    .notification-indicator.red {
        background: #EF4444;
    }

    .notification-indicator.green {
        background: var(--redcode-green);
    }

    .notification-indicator.gray {
        background: var(--redcode-gray);
    }

    .notification-indicator.purple {
        background: #8B5CF6;
    }

    .notification-indicator.teal {
        background: #14B8A6;
    }

    .notification-indicator.indigo {
        background: #6366F1;
    }

    .notification-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        margin-right: 1rem;
        flex-shrink: 0;
    }

    .notification-icon.blue {
        background: rgba(37, 99, 235, 0.1);
        color: var(--redcode-blue);
    }

    .notification-icon.orange {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .notification-icon.red {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .notification-icon.green {
        background: rgba(5, 150, 105, 0.1);
        color: var(--redcode-green);
    }

    .notification-icon.gray {
        background: rgba(107, 114, 128, 0.1);
        color: var(--redcode-gray);
    }

    .notification-icon.purple {
        background: rgba(139, 92, 246, 0.1);
        color: #8B5CF6;
    }

    .notification-icon.teal {
        background: rgba(20, 184, 166, 0.1);
        color: #14B8A6;
    }

    .notification-icon.indigo {
        background: rgba(99, 102, 241, 0.1);
        color: #6366F1;
    }

    .notification-content {
        flex: 1;
    }

    .notification-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.5rem;
    }

    .notification-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
        line-height: 1.4;
    }

    .notification-meta {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .notification-priority {
        padding: 0.25rem 0.75rem;
        border-radius: 12px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }

    .priority-critical {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .priority-high {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .priority-medium {
        background: rgba(37, 99, 235, 0.1);
        color: var(--redcode-blue);
    }

    .priority-low {
        background: rgba(107, 114, 128, 0.1);
        color: var(--redcode-gray);
    }

    .notification-time {
        color: var(--text-light);
        font-size: 0.85rem;
    }

    .notification-message {
        color: var(--text-primary);
        line-height: 1.6;
        margin: 0 0 1rem 0;
    }

    .notification-footer {
        display: flex;
        gap: 1.5rem;
        color: var(--text-secondary);
        font-size: 0.85rem;
    }

    .notification-from, .notification-type {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .notification-actions {
        display: flex;
        gap: 0.5rem;
        align-items: flex-start;
        margin-left: 1rem;
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
        background: rgba(248, 250, 252, 0.8);
        color: var(--text-secondary);
    }

    .btn-mark-read:hover {
        background: var(--redcode-green);
        color: white;
        transform: scale(1.1);
    }

    .btn-view:hover {
        background: var(--redcode-blue);
        color: white;
        transform: scale(1.1);
    }

    .btn-delete:hover {
        background: #EF4444;
        color: white;
        transform: scale(1.1);
    }

    /* Empty State */
    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        color: var(--text-secondary);
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        color: var(--text-light);
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--text-primary);
    }

    /* Pagination */
    .notifications-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .pagination-info {
        color: var(--text-secondary);
        font-size: 0.9rem;
    }

    .pagination-controls {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .pagination-current {
        color: var(--text-primary);
        font-weight: 600;
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
        margin: 5% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 600px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
        border: 1px solid var(--border-light);
    }

    .modal-header {
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-light);
        position: relative;
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
    }

    .modal-footer {
        padding: 1rem 2rem;
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
        border-top: 1px solid var(--border-light);
    }

    /* Enhanced Modal Content Styles */
    .notification-detail-header {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .detail-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        flex-shrink: 0;
    }

    .detail-info {
        flex: 1;
    }

    .detail-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    .detail-meta {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

    .detail-priority,
    .detail-status {
        padding: 0.375rem 0.75rem;
        border-radius: 8px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .detail-priority.priority-critical {
        background: rgba(239, 68, 68, 0.1);
        color: #EF4444;
    }

    .detail-priority.priority-high {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .detail-priority.priority-medium {
        background: rgba(37, 99, 235, 0.1);
        color: var(--redcode-blue);
    }

    .detail-priority.priority-low {
        background: rgba(107, 114, 128, 0.1);
        color: var(--redcode-gray);
    }

    .detail-status.read {
        background: rgba(5, 150, 105, 0.1);
        color: var(--redcode-green);
    }

    .detail-status.unread {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .notification-detail-body {
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
    }

    .detail-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .detail-section {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .detail-section label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
        text-transform: uppercase;
        letter-spacing: 0.05em;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .detail-section label i {
        color: var(--redcode-primary);
        font-size: 0.8rem;
    }

    .detail-value {
        font-size: 0.95rem;
        color: var(--text-primary);
        font-weight: 500;
        padding: 0.5rem 0.75rem;
        background: rgba(248, 250, 252, 0.5);
        border-radius: 6px;
        border: 1px solid var(--border-light);
    }

    .detail-message {
        font-size: 1rem;
        color: var(--text-primary);
        line-height: 1.6;
        padding: 1rem;
        background: rgba(248, 250, 252, 0.3);
        border-radius: 8px;
        border: 1px solid var(--border-light);
        border-left: 4px solid var(--redcode-primary);
    }

    .detail-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
        align-items: center;
    }

    .detail-actions .btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .detail-actions .text-muted {
        color: var(--text-secondary);
        font-size: 0.9rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--redcode-green), #047857);
        color: white;
        box-shadow: 0 4px 15px rgba(5, 150, 105, 0.2);
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(5, 150, 105, 0.3);
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
    }

    .btn-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.3);
    }

    /* Enhanced Modal Display */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(10px);
        animation: fadeIn 0.3s ease;
    }

    .modal.show {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    .modal-content {
        background: white;
        margin: 3% auto;
        padding: 0;
        border-radius: 16px;
        width: 90%;
        max-width: 700px;
        max-height: 85vh;
        overflow-y: auto;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.3);
        border: 1px solid var(--border-light);
        animation: slideIn 0.3s ease;
    }

    @keyframes slideIn {
        from { 
            transform: translateY(-50px);
            opacity: 0;
        }
        to { 
            transform: translateY(0);
            opacity: 1;
        }
    }

    /* Toast Notification Styles */
    .toast-notification {
        font-size: 0.9rem;
    }

    .toast-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        flex-shrink: 0;
    }

    .toast-success .toast-icon {
        background: rgba(5, 150, 105, 0.2);
        color: var(--redcode-green);
    }

    .toast-error .toast-icon {
        background: rgba(239, 68, 68, 0.2);
        color: #EF4444;
    }

    .toast-info .toast-icon {
        background: rgba(37, 99, 235, 0.2);
        color: var(--redcode-blue);
    }

    .toast-content {
        flex: 1;
    }

    .toast-message {
        font-weight: 500;
        color: var(--text-primary);
        line-height: 1.4;
    }

    .toast-close {
        background: none;
        border: none;
        color: var(--text-secondary);
        cursor: pointer;
        padding: 0;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
        transition: all 0.2s ease;
    }

    .toast-close:hover {
        background: rgba(0, 0, 0, 0.1);
        color: var(--text-primary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .notifications-container {
            padding: 1rem;
        }

        .notifications-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .notification-stats-grid {
            grid-template-columns: 1fr;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .notification-item {
            flex-direction: column;
            gap: 1rem;
        }

        .notification-actions {
            margin-left: 0;
            justify-content: center;
        }

        .notifications-pagination {
            flex-direction: column;
            gap: 1rem;
        }

        /* Modal responsiveness */
        .modal-content {
            width: 95%;
            margin: 5% auto;
            max-height: 90vh;
        }

        .notification-detail-header {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 1rem;
        }

        .detail-row {
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        .detail-meta {
            justify-content: center;
        }

        .detail-actions {
            justify-content: center;
        }

        .detail-actions .btn {
            flex: 1;
            min-width: 120px;
        }
    }
</style>

<script>
    // Filter notifications
    function filterNotifications() {
        const typeFilter = document.getElementById('type-filter').value;
        const priorityFilter = document.getElementById('priority-filter').value;
        const statusFilter = document.getElementById('status-filter').value;
        const searchInput = document.getElementById('search-input').value.toLowerCase();
        
        const notifications = document.querySelectorAll('.notification-item');
        let visibleCount = 0;
        
        notifications.forEach(notification => {
            const type = notification.dataset.type;
            const priority = notification.dataset.priority;
            const status = notification.dataset.status;
            const text = notification.textContent.toLowerCase();
            
            const typeMatch = !typeFilter || type === typeFilter;
            const priorityMatch = !priorityFilter || priority === priorityFilter;
            const statusMatch = !statusFilter || status === statusFilter;
            const searchMatch = !searchInput || text.includes(searchInput);
            
            if (typeMatch && priorityMatch && statusMatch && searchMatch) {
                notification.style.display = 'flex';
                visibleCount++;
            } else {
                notification.style.display = 'none';
            }
        });
        
        // Show/hide empty state
        const emptyState = document.getElementById('empty-state');
        if (visibleCount === 0) {
            emptyState.style.display = 'block';
        } else {
            emptyState.style.display = 'none';
        }
    }

    // Clear all filters
    function clearFilters() {
        document.getElementById('type-filter').value = '';
        document.getElementById('priority-filter').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('search-input').value = '';
        filterNotifications();
    }

    // Mark all notifications as read
    function markAllAsRead() {
        if (confirm('Are you sure you want to mark all notifications as read?')) {
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('unread');
                notification.classList.add('read');
                notification.dataset.status = 'read';
                
                // Remove mark as read button
                const markReadBtn = notification.querySelector('.btn-mark-read');
                if (markReadBtn) {
                    markReadBtn.remove();
                }
            });
            
            // Update stats
            updateNotificationStats();
            showNotification('All notifications marked as read!', 'success');
        }
    }

    // Mark single notification as read
    function markAsRead(notificationId) {
        const notification = document.querySelector(`[data-id="${notificationId}"]`);
        if (notification) {
            notification.classList.remove('unread');
            notification.classList.add('read');
            notification.dataset.status = 'read';
            
            // Remove mark as read button
            const markReadBtn = notification.querySelector('.btn-mark-read');
            if (markReadBtn) {
                markReadBtn.remove();
            }
            
            updateNotificationStats();
            showNotification('Notification marked as read!', 'success');
        }
    }

    // View notification details
    function viewNotification(notificationId) {
        // Find the notification data
        const notificationElement = document.querySelector(`[data-id="${notificationId}"]`);
        if (!notificationElement) {
            showNotification('Notification not found!', 'error');
            return;
        }
        
        // Extract notification data from the element
        const notificationData = {
            id: notificationId,
            type: notificationElement.dataset.type,
            priority: notificationElement.dataset.priority,
            status: notificationElement.dataset.status,
            title: notificationElement.querySelector('.notification-title').textContent,
            message: notificationElement.querySelector('.notification-message').textContent,
            from: notificationElement.querySelector('.notification-from').textContent.replace('👤 ', '').trim(),
            typeDisplay: notificationElement.querySelector('.notification-type').textContent.replace('🏷️ ', '').trim(),
            time: notificationElement.querySelector('.notification-time').textContent,
            icon: notificationElement.querySelector('.notification-icon i').className,
            color: notificationElement.querySelector('.notification-icon').className.split(' ').find(cls => cls !== 'notification-icon')
        };
        
        // Create detailed modal content
        const modalContent = `
            <div class="notification-detail-header">
                <div class="detail-icon ${notificationData.color}">
                    <i class="${notificationData.icon}"></i>
                </div>
                <div class="detail-info">
                    <h3>${notificationData.title}</h3>
                    <div class="detail-meta">
                        <span class="detail-priority priority-${notificationData.priority}">
                            <i class="fas fa-flag"></i> ${notificationData.priority.toUpperCase()} PRIORITY
                        </span>
                        <span class="detail-status ${notificationData.status}">
                            <i class="fas fa-${notificationData.status === 'read' ? 'check-circle' : 'envelope'}"></i> 
                            ${notificationData.status.toUpperCase()}
                        </span>
                    </div>
                </div>
            </div>
            
            <div class="notification-detail-body">
                <div class="detail-section">
                    <label><i class="fas fa-comment-alt"></i> Message</label>
                    <div class="detail-message">${notificationData.message}</div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-section">
                        <label><i class="fas fa-user"></i> From</label>
                        <div class="detail-value">${notificationData.from}</div>
                    </div>
                    <div class="detail-section">
                        <label><i class="fas fa-tag"></i> Type</label>
                        <div class="detail-value">${notificationData.typeDisplay}</div>
                    </div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-section">
                        <label><i class="fas fa-clock"></i> Received</label>
                        <div class="detail-value">${notificationData.time}</div>
                    </div>
                    <div class="detail-section">
                        <label><i class="fas fa-hashtag"></i> ID</label>
                        <div class="detail-value">#${notificationData.id}</div>
                    </div>
                </div>
                
                <div class="detail-section">
                    <label><i class="fas fa-cogs"></i> Actions Available</label>
                    <div class="detail-actions">
                        ${notificationData.status === 'unread' ? 
                            '<button class="btn btn-success btn-sm" onclick="markAsReadFromModal(' + notificationData.id + ')"><i class="fas fa-check"></i> Mark as Read</button>' : 
                            '<span class="text-muted"><i class="fas fa-check-circle"></i> Already Read</span>'
                        }
                        <button class="btn btn-danger btn-sm" onclick="deleteFromModal(${notificationData.id})">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                        <button class="btn btn-primary btn-sm" onclick="shareNotification(${notificationData.id})">
                            <i class="fas fa-share"></i> Share
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Update modal content and show
        document.getElementById('notificationDetails').innerHTML = modalContent;
        document.getElementById('notificationModal').style.display = 'block';
        
        // Store current notification ID for modal actions
        window.currentNotificationId = notificationId;
        
        showNotification(`Viewing notification: ${notificationData.title}`, 'info');
    }

    // Delete notification
    function deleteNotification(notificationId) {
        if (confirm('Are you sure you want to delete this notification?')) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (notification) {
                notification.remove();
                updateNotificationStats();
                showNotification('Notification deleted!', 'success');
            }
        }
    }

    // Refresh notifications
    function refreshNotifications() {
        showNotification('Refreshing notifications...', 'info');
        // In a real application, you would reload the data from the server
        setTimeout(() => {
            location.reload();
        }, 1000);
    }

    // Export notifications
    function exportNotifications() {
        showNotification('Exporting notifications...', 'info');
        // Implementation for exporting notifications
    }

    // Update notification statistics
    function updateNotificationStats() {
        const allNotifications = document.querySelectorAll('.notification-item');
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        
        // Update unread count in stat card
        const unreadStatNumber = document.querySelector('.unread .stat-number');
        if (unreadStatNumber) {
            unreadStatNumber.textContent = unreadNotifications.length;
        }
        
        // Update badge in sidebar
        const badge = document.querySelector('.sidebar-menu-item .badge');
        if (badge) {
            badge.textContent = unreadNotifications.length;
        }
    }

    // Show notification message
    function showNotification(message, type = 'info') {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `toast-notification toast-${type}`;
        notification.innerHTML = `
            <div class="toast-icon">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
            </div>
            <div class="toast-content">
                <div class="toast-message">${message}</div>
            </div>
            <button class="toast-close" onclick="this.parentElement.remove()">
                <i class="fas fa-times"></i>
            </button>
        `;
        
        // Add styles for toast notifications
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            min-width: 300px;
            max-width: 400px;
            background: white;
            border-radius: 12px;
            padding: 1rem;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            border-left: 4px solid ${
                type === 'success' ? 'var(--redcode-green)' : 
                type === 'error' ? '#EF4444' : 
                'var(--redcode-blue)'
            };
            z-index: 10000;
            display: flex;
            align-items: center;
            gap: 1rem;
            transform: translateX(100%);
            transition: all 0.3s ease;
            font-family: inherit;
        `;
        
        // Add to page
        document.body.appendChild(notification);
        
        // Show notification with animation
        setTimeout(() => {
            notification.style.transform = 'translateX(0)';
        }, 100);
        
        // Hide notification after 4 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            setTimeout(() => {
                if (notification.parentElement) {
                    notification.remove();
                }
            }, 300);
        }, 4000);
    }

    // Modal functions
    function closeNotificationModal() {
        document.getElementById('notificationModal').style.display = 'none';
        window.currentNotificationId = null;
    }

    function markAsReadFromModal(notificationId = null) {
        const id = notificationId || window.currentNotificationId;
        if (id) {
            markAsRead(id);
            closeNotificationModal();
            showNotification('Notification marked as read!', 'success');
        }
    }
    
    function deleteFromModal(notificationId) {
        if (confirm('Are you sure you want to delete this notification?')) {
            deleteNotification(notificationId);
            closeNotificationModal();
        }
    }
    
    function shareNotification(notificationId) {
        const notification = document.querySelector(`[data-id="${notificationId}"]`);
        if (notification) {
            const title = notification.querySelector('.notification-title').textContent;
            const message = notification.querySelector('.notification-message').textContent;
            
            // Create shareable content
            const shareText = `Notification: ${title}\n${message}`;
            
            if (navigator.share) {
                navigator.share({
                    title: 'Notification',
                    text: shareText
                }).then(() => {
                    showNotification('Notification shared successfully!', 'success');
                }).catch(() => {
                    fallbackShare(shareText);
                });
            } else {
                fallbackShare(shareText);
            }
        }
    }
    
    function fallbackShare(text) {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(text).then(() => {
            showNotification('Notification content copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Unable to share notification', 'error');
        });
    }

    // Initialize page
    document.addEventListener('DOMContentLoaded', function() {
        // Initial filter
        filterNotifications();
        
        // Update stats on page load
        updateNotificationStats();
        
        // Add keyboard support for modal
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const modal = document.getElementById('notificationModal');
                if (modal.style.display === 'block') {
                    closeNotificationModal();
                }
            }
        });
        
        // Close modal when clicking outside
        document.getElementById('notificationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeNotificationModal();
            }
        });
    });
</script>
@endsection
