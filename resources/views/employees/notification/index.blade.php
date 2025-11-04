@extends('layouts.employee')

@section('title', 'All Notifications - Employee Dashboard')
<link rel="stylesheet" href="{{ asset('css/Employee/employeeNotification.css') }}">
@section('content')
    <div class="notifications-container">

        <div class="notifications-header">
            <div class="header-content">
                <h1><i class="fas fa-bell"></i> All Notifications</h1>
                <p>Manage and monitor your notifications</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="markAllAsRead()">
                    <i class="fas fa-check-double"></i> Mark All Read
                </button>
                <button class="btn btn-secondary" onclick="refreshNotifications()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
            </div>
        </div>

        <div class="notification-stats-grid">
            <div class="notification-stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Notifications</h3>
                    <div class="stat-number">{{ $notificationStats['total'] ?? $notifications->count() }}</div>
                    <p>All notifications</p>
                </div>
            </div>

            <div class="notification-stat-card unread">
                <div class="stat-icon">
                    <i class="fas fa-envelope"></i>
                </div>
                <div class="stat-content">
                    <h3>Unread</h3>
                    <div class="stat-number">
                        {{ $notificationStats['unread'] ?? $notifications->where('is_read', false)->count() }}</div>
                    <p>Require attention</p>
                </div>
                <div class="stat-trend urgent">
                    <i class="fas fa-exclamation"></i> Action Required
                </div>
            </div>

            <div class="notification-stat-card">
                <div class="stat-icon">
                    <i class="fas fa-envelope-open"></i>
                </div>
                <div class="stat-content">
                    <h3>Read</h3>
                    <div class="stat-number">
                        {{ $notificationStats['read'] ?? $notifications->where('is_read', true)->count() }}</div>
                </div>
            </div>
        </div>

        <div class="notification-filters">
            <div class="filter-row">
                <div class="filter-group">
                    <label for="type-filter">Type</label>
                    <select id="type-filter" class="filter-select" onchange="filterNotifications()">
                        <option value="">All Types</option>
                        <option value="admin">Employee</option>
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
                    <input type="text" id="search-input" placeholder="Search notifications..." class="filter-input"
                        oninput="filterNotifications()">
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
        <div class="notifications-list">
            @foreach ($notifications as $notification)
                <div class="notification-item {{ $notification['is_read'] ? 'read' : 'unread' }}"
                    data-id="{{ $notification['notifi_id'] }}" data-type="{{ $notification['type'] }}"
                    data-priority="{{ $notification['priority'] }}"
                    data-status="{{ $notification['is_read'] ? 'read' : 'unread' }}">

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
                                    {{ \Carbon\Carbon::parse($notification['timestamp'])->diffForHumans() }}
                                </span>
                            </div>
                        </div>

                        <p class="notification-message">{{ $notification['message'] }}</p>

                        <div class="notification-footer">
                            <span class="notification-from">
                                <i class="fas fa-user"></i> {{ $notification->user->name ?? 'Unknown User' }}
                            </span>
                            <span class="notification-type">
                                <i class="fas fa-tag"></i> {{ ucfirst($notification['type']) }}
                            </span>
                        </div>
                    </div>

                    <div class="notification-actions">
                        @if (!$notification['is_read'])
                            <button class="btn-action btn-mark-read"
                                onclick="markAsRead('{{ $notification['notifi_id'] }}', {{ $notification['is_read'] ? 'true' : 'false' }})"
                                title="Mark as Read">
                                <i class="fas fa-check"></i>
                            </button>
                        @endif
                        <button class="btn-action btn-view" onclick="viewNotification('{{ $notification->notifi_id }}')"
                            title="View Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button class="btn-action btn-delete"
                            onclick="deleteNotification('{{ $notification['notifi_id'] }}')" title="Delete">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <div id="empty-state" class="empty-state" style="display: none;">
            <i class="fas fa-bell-slash"></i>
            <h3>No notifications found</h3>
            <p>No notifications match your current filters.</p>
        </div>
    </div>

    <div class="notifications-pagination">
        <div class="pagination-info">
            Showing {{ $notifications->count() }} of {{ $notificationStats['total'] ?? $notifications->count() }}
            notifications
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

    <!-- Single Shared Notification Details Modal -->
    {{-- <div id="notificationModal" class="modal">
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
                <button id="modalMarkReadBtn" class="btn btn-primary" style="display: none;"
                    onclick="markAsReadFromModal()">
                    <i class="fas fa-check"></i> Mark as Read
                </button>
            </div>
        </div>
    </div> --}}

    <div id="notificationModal">
        <div class="notification-modal-content">
            <button class="close-modal-btn" onclick="closeNotificationModal()">
                <i class="fas fa-times"></i>
            </button>
            <div id="notificationDetails">
                <!-- Dynamic content will be injected here by JS -->
            </div>
        </div>
    </div>


    {{-- JavaScript copied/adapted from super_admin notifications view so interactions match --}}
    <script>
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

            const emptyState = document.getElementById('empty-state');
            if (emptyState) {
                if (visibleCount === 0) {
                    emptyState.style.display = 'block';
                } else {
                    emptyState.style.display = 'none';
                }
            }
        }


        function clearFilters() {
            const tf = document.getElementById('type-filter');
            const pf = document.getElementById('priority-filter');
            const sf = document.getElementById('status-filter');
            const si = document.getElementById('search-input');
            if (tf) tf.value = '';
            if (pf) pf.value = '';
            if (sf) sf.value = '';
            if (si) si.value = '';
            filterNotifications();
        }

        function markAllAsRead() {
            if (!confirm('Are you sure you want to mark all notifications as read?')) return;

            const unreadNotifications = document.querySelectorAll('.notification-item.unread');
            unreadNotifications.forEach(notification => {
                notification.classList.remove('unread');
                notification.classList.add('read');
                notification.dataset.status = 'read';

                const markReadBtn = notification.querySelector('.btn-mark-read');
                if (markReadBtn) markReadBtn.remove();
            });

            updateNotificationStats();
            showNotification('All notifications marked as read!', 'success');

            fetch(`{{ route('employee.notifications.markAsRead', ['id' => '__dummy__']) }}`.replace('/__dummy__', '/'), {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .catch(() => {
                    fetch('{{ route('employee.notifications.markAsRead', ['id' => '__dummy__']) }}'.replace(
                        'notifications/__dummy__/mark-as-read', 'notifications/mark-all-as-read'), {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content'),
                            'Content-Type': 'application/json',
                        }
                    }).catch(() => {});
                });
        }

        function markAsRead(notificationId, isRead) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (!notification) {
                showNotification('Notification not found!', 'error');
                return;
            }

            if (isRead === true || notification.dataset.status === 'read') {
                showNotification('This notification is already marked as read.', 'info');
                return;
            }

            notification.classList.remove('unread');
            notification.classList.add('read');
            notification.dataset.status = 'read';

            const markReadBtn = notification.querySelector('.btn-mark-read');
            if (markReadBtn) markReadBtn.remove();

            updateNotificationStats();
            showNotification('Notification marked as read!', 'success');

            fetch(`/employee/notifications/${notificationId}/mark-as-read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (!data.success) {
                        showNotification('Failed to update server!', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification('Error updating server!', 'error');
                });
        }

        async function viewNotification(notificationId) {
            console.log("Viewing notification:", notificationId);

            try {
                const response = await fetch(`/employee/notifications/${notificationId}`);
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);

                const data = await response.json();
                console.log("Notification data:", data);

                // fallback defaults if fields are missing
                const title = data.title || 'No Title';
                const message = data.message || 'No message available.';
                const type = data.type || 'general';
                const priority = data.priority || 'medium';
                const status = data.is_read ? 'read' : 'unread';
                const from = data.sender_name || 'System';
                const time = new Date(data.created_at).toLocaleString();
                const icon = 'fas fa-bell'; // or use from backend if available
                const color = priority === 'high' ? 'red' : priority === 'low' ? 'green' : 'blue';

                const modalContent = `
        <div class="notification-detail-header">
            <div class="detail-icon ${color}">
                <i class="${icon}"></i>
            </div>
            <div class="detail-info">
                <h3>${title}</h3>
                <div class="detail-meta">
                    <span class="detail-priority priority-${priority}">
                        <i class="fas fa-flag"></i> ${priority.toUpperCase()} PRIORITY
                    </span>
                    <span class="detail-status ${status}">
                        <i class="fas fa-${status === 'read' ? 'check-circle' : 'envelope'}"></i>
                        ${status.toUpperCase()}
                    </span>
                </div>
            </div>
        </div>

        <div class="notification-detail-body">
            <div class="detail-section">
                <label><i class="fas fa-comment-alt"></i> Message</label>
                <div class="detail-message">${message}</div>
            </div>

            <div class="detail-row">
                <div class="detail-section">
                    <label><i class="fas fa-user"></i> From</label>
                    <div class="detail-value">${from}</div>
                </div>
                <div class="detail-section">
                    <label><i class="fas fa-tag"></i> Type</label>
                    <div class="detail-value">${type}</div>
                </div>
            </div>

            <div class="detail-row">
                <div class="detail-section">
                    <label><i class="fas fa-clock"></i> Received</label>
                    <div class="detail-value">${time}</div>
                </div>
                <div class="detail-section">
                    <label><i class="fas fa-hashtag"></i> ID</label>
                    <div class="detail-value">#${notificationId}</div>
                </div>
            </div>

            <div class="detail-section">
                <label><i class="fas fa-cogs"></i> Actions Available</label>
                <div class="detail-actions">
                    ${status === 'unread'
                        ? `<button class="btn btn-success btn-sm" onclick="markAsReadFromModal('${notificationId}')">
                                                                        <i class="fas fa-check"></i> Mark as Read
                                                                       </button>`
                        : `<span class="text-muted"><i class="fas fa-check-circle"></i> Already Read</span>`
                    }
                    <button class="btn btn-danger btn-sm" onclick="deleteFromModal('${notificationId}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                    <button class="btn btn-primary btn-sm" onclick="shareNotification('${notificationId}')">
                        <i class="fas fa-share"></i> Share
                    </button>
                </div>
            </div>
        </div>`;

                // Inject HTML into modal container
                document.getElementById('notificationDetails').innerHTML = modalContent;

                // Show modal
                document.getElementById('notificationModal').classList.add('show');

                // Mark as read if unread
                if (!data.is_read) {
                    await markAsRead(notificationId);
                }

            } catch (error) {
                console.error("Error viewing notification:", error);
                showNotification("Failed to load notification details.", "error");
            }
        }

        function deleteNotification(notificationId) {
            if (!confirm('Are you sure you want to delete this notification?')) return;

            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (!notification) {
                showNotification('Notification not found!', 'error');
                return;
            }

            fetch(`/employee/notifications/${notificationId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        notification.remove();
                        updateNotificationStats();
                        showNotification('Notification deleted!', 'success');
                        closeNotificationModal();
                    } else {
                        showNotification('Failed to delete notification!', 'error');
                    }
                })
                .catch(err => {
                    console.error(err);
                    showNotification('Error deleting notification!', 'error');
                });
        }

        function deleteFromModal(notificationId) {
            deleteNotification(notificationId);
        }

        function refreshNotifications() {
            showNotification('Refreshing notifications...', 'info');
            setTimeout(() => {
                location.reload();
            }, 800);
        }

        function updateNotificationStats() {
            const allNotifications = document.querySelectorAll('.notification-item');
            const unreadNotifications = document.querySelectorAll('.notification-item.unread');

            const unreadStatNumber = document.querySelector('.unread .stat-number');
            if (unreadStatNumber) unreadStatNumber.textContent = unreadNotifications.length;

            const badge = document.querySelector('.sidebar .badge');
            if (badge) badge.textContent = unreadNotifications.length;
        }

        function showNotification(message, type = 'info') {
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

            document.body.appendChild(notification);

            setTimeout(() => notification.style.transform = 'translateX(0)', 100);

            setTimeout(() => {
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => {
                    notification.remove();
                }, 300);
            }, 4000);
        }


        function closeNotificationModal() {
            const modal = document.getElementById('notificationModal');
            if (modal) modal.classList.remove('show');
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

        function shareNotification(notificationId) {
            const notification = document.querySelector(`[data-id="${notificationId}"]`);
            if (!notification) return showNotification('Notification not found!', 'error');

            const title = notification.querySelector('.notification-title')?.textContent || '';
            const message = notification.querySelector('.notification-message')?.textContent || '';
            const shareText = `Notification: ${title}\n\n${message}`;

            if (navigator.share) {
                navigator.share({
                        title: 'Notification',
                        text: shareText
                    })
                    .then(() => showNotification('Notification shared successfully!', 'success'))
                    .catch(() => fallbackShare(shareText));
            } else {
                fallbackShare(shareText);
            }
        }

        function fallbackShare(text) {
            navigator.clipboard.writeText(text)
                .then(() => showNotification('Notification content copied to clipboard!', 'success'))
                .catch(() => showNotification('Unable to share notification', 'error'));
        }

        document.addEventListener('DOMContentLoaded', function() {
            if (typeof filterNotifications === 'function') filterNotifications();
            if (typeof updateNotificationStats === 'function') updateNotificationStats();

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    const modal = document.getElementById('notificationModal');
                    if (modal && modal.classList.contains('show')) closeNotificationModal();
                }
            });

            const modalEl = document.getElementById('notificationModal');
            if (modalEl) {
                modalEl.addEventListener('click', function(e) {
                    if (e.target === this) closeNotificationModal();
                });
            }
        });
    </script>
@endsection
