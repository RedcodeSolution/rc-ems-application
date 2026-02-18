@extends('layouts.employee')

@section('title', 'Announcements')

@section('content')
    <div class="announcements-container">
        <!-- Header Section -->
        <div class="announcements-header">
            <div class="header-content">
                <div class="header-info">
                    <h1><i class="fas fa-bullhorn"></i> Company Announcements</h1>
                    <p>Stay updated with the latest company news and important updates</p>
                </div>
                <div class="header-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $totalAnnouncements ?? 8 }}</span>
                        <span class="stat-label">Total</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $unreadAnnouncements ?? 3 }}</span>
                        <span class="stat-label">Unread</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $urgentAnnouncements ?? 2 }}</span>
                        <span class="stat-label">Urgent</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-controls">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search announcements..."
                        onkeyup="searchAnnouncements()">
                </div>
                <div class="filter-dropdown">
                    <select id="priorityFilter" onchange="filterAnnouncements()">
                        <option value="">All Priorities</option>
                        <option value="low">Low Priority</option>
                        <option value="medium">Medium Priority</option>
                        <option value="high">High Priority</option>
                        <option value="urgent">Urgent</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select id="categoryFilter" onchange="filterAnnouncements()">
                        <option value="">All Categories</option>
                        <option value="general">General</option>
                        <option value="hr">HR Updates</option>
                        <option value="policy">Policy Changes</option>
                        <option value="events">Events</option>
                        <option value="system">System Updates</option>
                        <option value="finance">Finance</option>
                        <option value="operations">Operations</option>
                    </select>
                </div>
                <div class="filter-dropdown">
                    <select id="statusFilter" onchange="filterAnnouncements()">
                        <option value="">All Status</option>
                        <option value="read">Read</option>
                        <option value="unread">Unread</option>
                    </select>
                </div>
            </div>
        </div>


        <div class="announcements-section">

            <h2 class="section-title">
                <i class="fas fa-bullhorn"></i> Announcements
            </h2>

            <div class="announcements-grid">
                @forelse($announcements as $announcement)
                    <div class="announcement-card {{ strtolower($announcement->priority ?? 'medium') }}"
                        data-id="{{ $announcement->announcement_id }}"
                        data-priority="{{ strtolower($announcement->priority ?? 'medium') }}"
                        data-category="{{ strtolower($announcement->category ?? 'general') }}"
                        data-status="{{ $announcement->is_read ? 'read' : 'unread' }}">

                        <div class="announcement-header">
                            <div class="announcement-meta">
                                <div class="priority-badge {{ strtolower($announcement->priority ?? 'medium') }}">
                                    <i class="fas fa-flag"></i>
                                    <span>{{ ucfirst($announcement->priority ?? 'Medium') }}</span>
                                </div>
                                <div class="category-badge">
                                    <i class="fas fa-tag"></i>
                                    <span>{{ ucfirst($announcement->category ?? 'General') }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="announcement-content">
                            <h3 class="announcement-title">{{ $announcement->title }}</h3>
                            <div class="announcement-excerpt">
                                <p>{{ Str::limit($announcement->content, 150) }}</p>
                            </div>

                            <div class="announcement-details">
                                <div class="detail-item">
                                    <i class="fas fa-calendar"></i>
                                    <span>{{ $announcement->created_at->format('M d, Y') }}</span>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-user"></i>
                                    <span>{{ $announcement->category ?? 'HR Department' }}</span>
                                </div>

                                <div class="detail-item">
                                    <i class="fas fa-users"></i>
                                    <span>
                                        {{ is_array($announcement->target_audience)
                                            ? implode(', ', $announcement->target_audience)
                                            : $announcement->target_audience ?? 'All Employees' }}
                                    </span>
                                </div>

                                @if ($announcement->expires_at)
                                    <div class="detail-item">
                                        <i class="fas fa-clock"></i>
                                        <span>Expires: {{ $announcement->expires_at->format('M d, Y') }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="announcement-footer">
                            <button class="btn btn-primary"
                                onclick="viewFullAnnouncement('{{ $announcement->announcement_id }}')">
                                <i class="fas fa-eye"></i> Read More
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="no-announcements">
                        <i class="fas fa-info-circle"></i>
                        <p>No announcements available for you at this time.</p>
                    </div>
                @endforelse
            </div>

            @if (method_exists($announcements, 'links'))
                <div class="pagination-section">
                    {{ $announcements->links('pagination::bootstrap-5') }}
                </div>
            @endif

        </div>


        <!-- Full Announcement Modal -->
        <div id="announcementModal" class="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="modalTitle">Announcement Details</h3>
                    <button class="close-btn" onclick="closeModal()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="modalContent">
                        <!-- Dynamic content will be loaded here -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" onclick="closeModal()">Close</button>
                </div>
            </div>
        </div>

        <style>
            /* Announcements Styles with Red Theme */
            .announcements-container {
                max-width: 1200px;
                margin: 0 auto;
                padding: 0;
            }

            .announcements-header {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                color: white;
                padding: 2rem;
                border-radius: 1rem;
                margin-bottom: 2rem;
                box-shadow: 0 4px 20px rgba(220, 38, 38, 0.3);
            }

            .header-content {
                display: flex;
                justify-content: space-between;
                align-items: center;
                gap: 2rem;
            }

            .header-info h1 {
                font-size: 2rem;
                font-weight: 700;
                margin: 0 0 0.5rem 0;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .header-info p {
                font-size: 1.1rem;
                opacity: 0.9;
                margin: 0;
            }

            .header-stats {
                display: flex;
                gap: 2rem;
            }

            .stat-item {
                text-align: center;
                display: flex;
                flex-direction: column;
                gap: 0.25rem;
            }

            .stat-number {
                font-size: 2rem;
                font-weight: 700;
                color: #fef2f2;
            }

            .stat-label {
                font-size: 0.9rem;
                opacity: 0.8;
            }

            .filter-section {
                background: white;
                padding: 1.5rem;
                border-radius: 1rem;
                margin-bottom: 2rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .filter-controls {
                display: flex;
                gap: 1rem;
                align-items: center;
                flex-wrap: wrap;
            }

            .search-box {
                position: relative;
                flex: 1;
                min-width: 300px;
            }

            .search-box i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: #6b7280;
                z-index: 1;
            }

            .search-box input {
                width: 100%;
                padding: 0.75rem 1rem 0.75rem 2.5rem;
                border: 2px solid #e5e7eb;
                border-radius: 0.5rem;
                font-size: 1rem;
                transition: all 0.3s ease;
            }

            .search-box input:focus {
                outline: none;
                border-color: #dc2626;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            }

            .filter-dropdown select {
                padding: 0.75rem 1rem;
                border: 2px solid #e5e7eb;
                border-radius: 0.5rem;
                font-size: 1rem;
                min-width: 150px;
                transition: all 0.3s ease;
            }

            .filter-dropdown select:focus {
                outline: none;
                border-color: #dc2626;
                box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
            }

            .announcements-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(400px, 1fr));
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .announcement-card {
                background: white;
                border-radius: 1rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                transition: all 0.3s ease;
                border-left: 4px solid #dc2626;
            }

            .announcement-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            }

            .announcement-card.urgent {
                border-left-color: #dc2626;
                background: linear-gradient(135deg, rgba(220, 38, 38, 0.05) 0%, rgba(239, 68, 68, 0.02) 100%);
            }

            .announcement-card.high {
                border-left-color: #f59e0b;
                background: linear-gradient(135deg, rgba(245, 158, 11, 0.05) 0%, rgba(251, 191, 36, 0.02) 100%);
            }

            .announcement-card.medium {
                border-left-color: #3b82f6;
                background: linear-gradient(135deg, rgba(59, 130, 246, 0.05) 0%, rgba(147, 197, 253, 0.02) 100%);
            }

            .announcement-card.low {
                border-left-color: #10b981;
                background: linear-gradient(135deg, rgba(16, 185, 129, 0.05) 0%, rgba(52, 211, 153, 0.02) 100%);
            }

            .announcement-header {
                padding: 1.5rem;
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                border-bottom: 1px solid #f3f4f6;
            }

            .announcement-meta {
                display: flex;
                gap: 0.5rem;
                flex-wrap: wrap;
            }

            .priority-badge,
            .category-badge,
            .unread-badge {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                padding: 0.25rem 0.75rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 600;
            }

            .priority-badge.urgent {
                background: rgba(220, 38, 38, 0.1);
                color: #dc2626;
            }

            .priority-badge.high {
                background: rgba(245, 158, 11, 0.1);
                color: #f59e0b;
            }

            .priority-badge.medium {
                background: rgba(59, 130, 246, 0.1);
                color: #3b82f6;
            }

            .priority-badge.low {
                background: rgba(16, 185, 129, 0.1);
                color: #10b981;
            }

            .category-badge {
                background: rgba(107, 114, 128, 0.1);
                color: #6b7280;
            }

            .unread-badge {
                background: rgba(220, 38, 38, 0.1);
                color: #dc2626;
            }

            .announcement-actions {
                display: flex;
                gap: 0.5rem;
            }

            .action-btn {
                padding: 0.5rem;
                border: none;
                border-radius: 0.5rem;
                background: #f3f4f6;
                color: #6b7280;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .action-btn:hover {
                background: #dc2626;
                color: white;
            }

            .announcement-content {
                padding: 0 1.5rem 1.5rem;
            }

            .announcement-title {
                font-size: 1.25rem;
                font-weight: 700;
                color: #1f2937;
                margin: 0 0 1rem 0;
            }

            .announcement-excerpt p {
                color: #6b7280;
                line-height: 1.6;
                margin: 0 0 1rem 0;
            }

            .announcement-details {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                margin-bottom: 1rem;
            }

            .detail-item {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .detail-item i {
                color: #dc2626;
            }

            .announcement-footer {
                padding: 1rem 1.5rem;
                background: #f9fafb;
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-top: 1px solid #f3f4f6;
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
            }

            .btn-primary {
                background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
                color: white;
            }

            .btn-primary:hover {
                background: linear-gradient(135deg, #b91c1c 0%, #991b1b 100%);
                transform: translateY(-1px);
            }

            .btn-secondary {
                background: #6b7280;
                color: white;
            }

            .btn-secondary:hover {
                background: #4b5563;
            }

            .announcement-stats {
                display: flex;
                gap: 1rem;
            }

            .announcement-stats .stat-item {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .pagination-section {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 1.5rem;
                background: white;
                border-radius: 1rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .pagination-info {
                color: #6b7280;
                font-size: 0.875rem;
            }

            .pagination-controls {
                display: flex;
                gap: 0.5rem;
            }

            .pagination-btn {
                padding: 0.5rem 1rem;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                background: white;
                color: #6b7280;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .pagination-btn:hover:not(:disabled) {
                background: #dc2626;
                color: white;
                border-color: #dc2626;
            }

            .pagination-btn.current {
                background: #dc2626;
                color: white;
                border-color: #dc2626;
            }

            .pagination-btn:disabled {
                opacity: 0.5;
                cursor: not-allowed;
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
                animation: fadeIn 0.3s ease;
            }

            .modal.show {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .modal-content {
                background: white;
                border-radius: 1rem;
                max-width: 600px;
                width: 90%;
                max-height: 80vh;
                overflow-y: auto;
                animation: slideIn 0.3s ease;
            }

            .modal-header {
                padding: 1.5rem;
                border-bottom: 1px solid #f3f4f6;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .modal-header h3 {
                margin: 0;
                color: #1f2937;
            }

            .close-btn {
                background: none;
                border: none;
                font-size: 1.5rem;
                cursor: pointer;
                color: #6b7280;
                transition: color 0.3s ease;
            }

            .close-btn:hover {
                color: #dc2626;
            }

            .modal-body {
                padding: 1.5rem;
            }

            .modal-footer {
                padding: 1.5rem;
                border-top: 1px solid #f3f4f6;
                display: flex;
                justify-content: flex-end;
                gap: 1rem;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                }

                to {
                    opacity: 1;
                }
            }

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

            /* Responsive Design */
            @media (max-width: 768px) {
                .header-content {
                    flex-direction: column;
                    text-align: center;
                }

                .header-stats {
                    justify-content: center;
                }

                .filter-controls {
                    flex-direction: column;
                    align-items: stretch;
                }

                .search-box {
                    min-width: auto;
                }

                .announcements-grid {
                    grid-template-columns: 1fr;
                }

                .announcement-header {
                    flex-direction: column;
                    align-items: flex-start;
                    gap: 1rem;
                }

                .announcement-footer {
                    flex-direction: column;
                    gap: 1rem;
                    align-items: flex-start;
                }

                .pagination-section {
                    flex-direction: column;
                    gap: 1rem;
                    text-align: center;
                }
            }
        </style>

        <script>
            // Search functionality
            function searchAnnouncements() {
                const searchTerm = document.getElementById('searchInput').value.toLowerCase();
                const cards = document.querySelectorAll('.announcement-card');

                cards.forEach(card => {
                    const title = card.querySelector('.announcement-title').textContent.toLowerCase();
                    const content = card.querySelector('.announcement-excerpt p').textContent.toLowerCase();

                    if (title.includes(searchTerm) || content.includes(searchTerm)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            }

            // Filter functionality
            function filterAnnouncements() {
                const priorityFilter = document.getElementById('priorityFilter')?.value.toLowerCase() || '';
                const categoryFilter = document.getElementById('categoryFilter')?.value.toLowerCase() || '';
                const statusFilter = document.getElementById('statusFilter')?.value.toLowerCase() || '';
                const cards = document.querySelectorAll('.announcement-card');

                cards.forEach(card => {
                    const priority = card.dataset.priority?.toLowerCase() || '';
                    const category = card.dataset.category?.toLowerCase() || '';
                    const status = card.dataset.status?.toLowerCase() || '';

                    let show = true;

                    if (priorityFilter && priority !== priorityFilter) show = false;
                    if (categoryFilter && category !== categoryFilter) show = false;
                    if (statusFilter && status !== statusFilter) show = false;

                    card.style.display = show ? 'block' : 'none';
                });
            }


            // View announcement details
            function viewAnnouncement(announcementId) {
                // This would typically fetch announcement details from server
                console.log('Viewing announcement:', announcementId);
                showNotification('Opening announcement details...', 'info');
            }

            async function viewFullAnnouncement(announcementId) {
                const modal = document.getElementById('announcementModal');
                const modalTitle = document.getElementById('modalTitle');
                const modalContent = document.getElementById('modalContent');

                try {
                    // ✅ Mark as read first
                    await fetch(`/employee/announcements/${announcementId}/read`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                'content')
                        }
                    });

                    // ✅ Fetch full announcement details
                    const response = await fetch(`/employee/announcements/${announcementId}`);
                    const result = await response.json();

                    if (!response.ok || result.status !== 'success') {
                        showNotification(result.message || 'Unable to load announcement.', 'error');
                        return;
                    }

                    const a = result.data;
                    const deptList = a.departments?.length ? a.departments.join(', ') : 'N/A';
                    const teamList = a.teams?.length ? a.teams.join(', ') : 'N/A';

                    modalTitle.textContent = a.title || 'Announcement';

                    modalContent.innerHTML = `
            <div class="announcement-full">
                <div class="announcement-meta" style="margin-bottom: 1rem;">
                    <span class="priority-badge ${a.priority.toLowerCase()}">
                        <i class="fas fa-flag"></i> ${a.priority}
                    </span>
                    <span class="category-badge">
                        <i class="fas fa-tag"></i> ${a.category}
                    </span>
                </div>
                <div class="announcement-details" style="margin-bottom: 1.5rem;">
                    <div class="detail-item"><i class="fas fa-calendar"></i> <span>${a.created_at}</span></div>
                    <div class="detail-item"><i class="fas fa-user"></i> <span>${a.author}</span></div>
                    <div class="detail-item"><i class="fas fa-building"></i> <span>${deptList}</span></div>
                    <div class="detail-item"><i class="fas fa-users"></i> <span>${teamList}</span></div>
                    ${a.expires_at ? `<div class="detail-item"><i class="fas fa-clock"></i> <span>Expires: ${a.expires_at}</span></div>` : ''}
                </div>
                <div class="announcement-content">${a.content}</div>
            </div>
        `;

                    modal.classList.add('show');
                    modal.style.display = 'flex';
                } catch (error) {
                    console.error('Error fetching announcement:', error);
                    showNotification('Something went wrong. Try again later.', 'error');
                }
            }

            // Close modal
            function closeModal() {
                const modal = document.getElementById('announcementModal');
                modal.classList.remove('show');
                modal.style.display = 'none';
            }

            // Mark as read from modal
            function markAsReadFromModal() {
                // Implementation would depend on how we track the current announcement
                showNotification('Announcement marked as read', 'success');
                closeModal();
            }

            // Notification system
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `notification ${type}`;
                notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'times-circle' : 'info-circle'}"></i>
        <span>${message}</span>
    `;

                // Add notification styles
                notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 1rem 1.5rem;
        background: ${type === 'success' ? '#10b981' : type === 'error' ? '#ef4444' : '#3b82f6'};
        color: white;
        border-radius: 0.5rem;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1001;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: 500;
        animation: slideInFromRight 0.3s ease;
    `;

                document.body.appendChild(notification);

                // Remove notification after 3 seconds
                setTimeout(() => {
                    notification.style.animation = 'slideOutToRight 0.3s ease';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            }

            // Add animations
            const style = document.createElement('style');
            style.textContent = `
    @keyframes slideInFromRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOutToRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
            document.head.appendChild(style);

            // Close modal when clicking outside
            document.addEventListener('click', function(e) {
                if (e.target.classList.contains('modal')) {
                    closeModal();
                }
            });

            // Close modal on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeModal();
                }
            });
        </script>
    @endsection
