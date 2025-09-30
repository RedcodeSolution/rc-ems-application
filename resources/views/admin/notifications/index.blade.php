@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin/notifications.css') }}">
@section('title', 'Notifications')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-bell notification-badge"></i> Notifications</h2>
        <div class="flex gap-2">
            <button class="btn btn-primary" onclick="markAllAsRead()">
                <i class="fas fa-check-double"></i>
                Mark All Read
            </button>
            <button class="btn btn-success" onclick="openCreateModal()">
                <i class="fas fa-plus"></i>
                Create
            </button>
            <button class="btn btn-secondary" onclick="openSettingsModal()">
                <i class="fas fa-cog"></i>
                Settings
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="noti-toolbar mb-4">
            <div class="noti-filters">
                <div class="input-icon">
                    <i class="fas fa-search"></i>
                    <input type="text" class="search-input" placeholder="Search notifications..." id="searchInput" onkeyup="searchNotifications()">
                </div>
                <select class="form-select noti-select" id="statusFilter" onchange="filterNotifications()">
                    <option value="">All Notifications</option>
                    <option value="unread">Unread</option>
                    <option value="read">Read</option>
                </select>
                <select class="form-select noti-select" id="typeFilter" onchange="filterNotifications()">
                    <option value="">All Types</option>
                    <option value="system">System</option>
                    <option value="employee">Employee</option>
                    <option value="leave">Leave</option>
                    <option value="project">Project</option>
                </select>
            </div>
            <div class="flex gap-2 noti-actions">
                <button class="btn btn-secondary" onclick="clearFilters()">
                    <i class="fas fa-times"></i>
                    Clear
                </button>
                <button class="btn btn-info" onclick="refreshNotifications()">
                    <i class="fas fa-sync"></i>
                    Refresh
                </button>
            </div>
        </div>

        <!-- Notifications List -->
        <div id="notificationsList" class="noti-list">
            <div class="card notification-item unread" data-type="employee" data-id="1" style="border-left: 4px solid var(--primary); background: rgba(59, 130, 246, 0.02);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div style="width: 3rem; height: 3rem; background: rgba(59, 130, 246, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); flex-shrink: 0;">
                                <i class="fas fa-user-plus"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">New Employee Registration</h4>
                                <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">John Doe has registered and is pending approval. Please review the application and take necessary action.</p>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                    <span><i class="fas fa-clock"></i> 5 minutes ago</span>
                                    <span><i class="fas fa-tag"></i> Employee</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(1)" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-primary" style="padding: 0.5rem;" onclick="markAsRead(1)" title="Mark as Read">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(1)" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card notification-item unread" data-type="leave" data-id="2" style="border-left: 4px solid var(--warning); background: rgba(245, 158, 11, 0.02);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div style="width: 3rem; height: 3rem; background: rgba(245, 158, 11, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--warning); flex-shrink: 0;">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Leave Request Pending</h4>
                                <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Sarah Miller submitted a leave request for December 20-27, 2024. Awaiting your approval.</p>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                    <span><i class="fas fa-clock"></i> 1 hour ago</span>
                                    <span><i class="fas fa-tag"></i> Leave</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(2)" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-success" style="padding: 0.5rem;" onclick="approveLeave(2)" title="Approve">
                                <i class="fas fa-check"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" onclick="rejectLeave(2)" title="Reject">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card notification-item read" data-type="project" data-id="3" style="border-left: 4px solid var(--success);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div style="width: 3rem; height: 3rem; background: rgba(16, 185, 129, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--success); flex-shrink: 0;">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Project Completed</h4>
                                <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Development team completed Project Alpha successfully. All deliverables have been submitted.</p>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                    <span><i class="fas fa-clock"></i> 3 hours ago</span>
                                    <span><i class="fas fa-tag"></i> Project</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(3)" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(3)" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card notification-item read" data-type="system" data-id="4" style="border-left: 4px solid var(--info);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div style="width: 3rem; height: 3rem; background: rgba(6, 182, 212, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--info); flex-shrink: 0;">
                                <i class="fas fa-chart-bar"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">Monthly Report Generated</h4>
                                <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Employee performance report for October 2024 is ready for review. Download from the reports section.</p>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                    <span><i class="fas fa-clock"></i> 1 day ago</span>
                                    <span><i class="fas fa-tag"></i> System</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(4)" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(4)" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card notification-item read" data-type="system" data-id="5" style="border-left: 4px solid var(--secondary);">
                <div class="card-body">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <div style="display: flex; gap: 1rem; flex: 1;">
                            <div style="width: 3rem; height: 3rem; background: rgba(99, 102, 241, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--secondary); flex-shrink: 0;">
                                <i class="fas fa-exclamation-triangle"></i>
                            </div>
                            <div style="flex: 1;">
                                <h4 style="font-size: 1rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.5rem;">System Maintenance Reminder</h4>
                                <p style="color: var(--gray-600); margin-bottom: 0.5rem; line-height: 1.5;">Scheduled maintenance on Sunday, December 1st, from 2:00 AM to 4:00 AM. System will be temporarily unavailable.</p>
                                <div style="display: flex; align-items: center; gap: 1rem; font-size: 0.875rem; color: var(--gray-500);">
                                    <span><i class="fas fa-clock"></i> 2 days ago</span>
                                    <span><i class="fas fa-tag"></i> System</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex gap-1">
                            <button class="btn btn-info" style="padding: 0.5rem;" onclick="viewNotification(5)" title="View Details">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-secondary" style="padding: 0.5rem;" onclick="deleteNotification(5)" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--gray-600); font-size: 0.875rem;">
                Showing <span id="showingStart">1</span> to <span id="showingEnd">5</span> of <span id="totalCount">23</span> notifications
            </div>
            <div class="flex gap-1">
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" onclick="previousPage()" id="prevBtn">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;" onclick="goToPage(1)">1</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" onclick="goToPage(2)">2</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" onclick="goToPage(3)">3</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" onclick="nextPage()" id="nextBtn">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Notification Statistics -->
<div class="noti-stats-grid">
    <div class="card" onclick="filterByStatus('')" style="cursor: pointer;">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--primary); margin-bottom: 0.5rem;" id="totalNotifications">23</div>
            <div style="color: var(--gray-600); font-weight: 500;">Total Notifications</div>
        </div>
    </div>
    <div class="card" onclick="filterByStatus('unread')" style="cursor: pointer;">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;" id="unreadCount">5</div>
            <div style="color: var(--gray-600); font-weight: 500;">Unread</div>
        </div>
    </div>
    <div class="card" onclick="filterByStatus('read')" style="cursor: pointer;">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;" id="readCount">18</div>
            <div style="color: var(--gray-600); font-weight: 500;">Read</div>
        </div>
    </div>
    <div class="card" onclick="filterByAction()" style="cursor: pointer;">
        <div class="card-body text-center">
            <div style="font-size: 2rem; font-weight: 700; color: var(--info); margin-bottom: 0.5rem;" id="actionCount">3</div>
            <div style="color: var(--gray-600); font-weight: 500;">Action Required</div>
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
                        <input type="text" id="notificationTitle" name="title" required placeholder="Enter notification title">
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
let notificationsPerPage = 5;
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

// Notification functions
function viewNotification(id) {
    const notification = getNotificationById(id);
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

function getNotificationById(id) {
    // Mock data - replace with actual API call
    const notifications = {
        1: { title: 'New Employee Registration', message: 'John Doe has registered and is pending approval. Please review the application and take necessary action.', time: '5 minutes ago', type: 'Employee', status: 'unread' },
        2: { title: 'Leave Request Pending', message: 'Sarah Miller submitted a leave request for December 20-27, 2024. Awaiting your approval.', time: '1 hour ago', type: 'Leave', status: 'unread' },
        3: { title: 'Project Completed', message: 'Development team completed Project Alpha successfully. All deliverables have been submitted.', time: '3 hours ago', type: 'Project', status: 'read' },
        4: { title: 'Monthly Report Generated', message: 'Employee performance report for October 2024 is ready for review. Download from the reports section.', time: '1 day ago', type: 'System', status: 'read' },
        5: { title: 'System Maintenance Reminder', message: 'Scheduled maintenance on Sunday, December 1st, from 2:00 AM to 4:00 AM. System will be temporarily unavailable.', time: '2 days ago', type: 'System', status: 'read' }
    };
    return notifications[id];
}

function markAsRead(id) {
    const notification = document.querySelector(`[data-id="${id}"]`);
    if (notification) {
        notification.classList.remove('unread');
        notification.classList.add('read');
        showToast('Notification marked as read', 'success');
        updateStats();
    }
}

function markAllAsRead() {
    showConfirm('Are you sure you want to mark all notifications as read?', () => {
        const unreadNotifications = document.querySelectorAll('.notification-item.unread');
        unreadNotifications.forEach(notification => {
            notification.classList.remove('unread');
            notification.classList.add('read');
        });
        showToast('All notifications marked as read', 'success');
        updateStats();
    });
}

function deleteNotification(id) {
    showConfirm('Are you sure you want to delete this notification?', () => {
        const notification = document.querySelector(`[data-id="${id}"]`);
        if (notification) {
            notification.style.animation = 'slideOut 0.3s ease-out';
            setTimeout(() => {
                notification.remove();
                showToast('Notification deleted', 'success');
                updateStats();
            }, 300);
        }
    });
}

function approveLeave(id) {
    showConfirm('Are you sure you want to approve this leave request?', () => {
        markAsRead(id);
        showToast('Leave request approved', 'success');
    });
}

function rejectLeave(id) {
    showConfirm('Are you sure you want to reject this leave request?', () => {
        markAsRead(id);
        showToast('Leave request rejected', 'error');
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

        const matchesSearch = currentSearch === '' || title.includes(currentSearch) || message.includes(currentSearch);
        const matchesStatus = statusFilter === '' || status === statusFilter;
        const matchesType = typeFilter === '' || type === typeFilter;

        if (matchesSearch && matchesStatus && matchesType) {
            notification.style.display = 'block';
        } else {
            notification.style.display = 'none';
        }
    });
}

function clearFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    document.getElementById('typeFilter').value = '';
    currentSearch = '';
    filterNotifications();
    showToast('Filters cleared', 'info');
}

function filterByStatus(status) {
    document.getElementById('statusFilter').value = status;
    filterNotifications();
}

function filterByAction() {
    // Show only notifications that require action (unread employee/leave notifications)
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
}

function refreshNotifications() {
    showToast('Refreshing notifications...', 'info');
    // Here you would typically fetch new notifications from the backend
    setTimeout(() => {
        showToast('Notifications refreshed', 'success');
        updateStats();
    }, 1000);
}

// Pagination functions
function previousPage() {
    if (currentPage > 1) {
        currentPage--;
        updatePagination();
    }
}

function nextPage() {
    const totalPages = Math.ceil(totalNotifications / notificationsPerPage);
    if (currentPage < totalPages) {
        currentPage++;
        updatePagination();
    }
}

function goToPage(page) {
    currentPage = page;
    updatePagination();
}

function updatePagination() {
    const start = (currentPage - 1) * notificationsPerPage + 1;
    const end = Math.min(currentPage * notificationsPerPage, totalNotifications);

    document.getElementById('showingStart').textContent = start;
    document.getElementById('showingEnd').textContent = end;
    document.getElementById('totalCount').textContent = totalNotifications;

    // Update button states
    document.getElementById('prevBtn').disabled = currentPage === 1;
    document.getElementById('nextBtn').disabled = currentPage === Math.ceil(totalNotifications / notificationsPerPage);
}

// Statistics functions
function updateStats() {
    const notifications = document.querySelectorAll('.notification-item');
    const unreadCount = document.querySelectorAll('.notification-item.unread').length;
    const readCount = notifications.length - unreadCount;
    const actionCount = document.querySelectorAll('.notification-item.unread[data-type="employee"], .notification-item.unread[data-type="leave"]').length;

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
