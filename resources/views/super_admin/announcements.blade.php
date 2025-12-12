@extends('layouts.super_admin')

@section('title', 'Announcements Management - Super Admin Dashboard')

@section('content')
<div class="announcements-container">
    <!-- Announcements Header -->
    <div class="announcements-header">
        <div class="header-content">
            <h1><i class="fas fa-bullhorn"></i> Announcements Management</h1>
            <p>Create, manage, and distribute company-wide announcements and notifications</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" onclick="openCreateModal()">
                <i class="fas fa-plus"></i> Create Announcement
            </button>

            <button class="btn btn-outline" onclick="refreshAnnouncements()">
                <i class="fas fa-sync"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Announcements Overview Dashboard -->
    <div class="announcements-overview">
        <div class="overview-cards">
            <div class="announcement-card total-announcements">
                <div class="card-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <div class="card-content">
                    <h3>Total Announcements</h3>
                    <div class="stat-number">{{ $announcementStats['total_announcements'] }}</div>
                    <p>All time announcements</p>
                </div>
            </div>

            <div class="announcement-card active-announcements">
                <div class="card-icon">
                    <i class="fas fa-play-circle"></i>
                </div>
                <div class="card-content">
                    <h3>Active</h3>
                    <div class="stat-number">{{ $announcementStats['active_announcements'] }}</div>
                    <p>Currently published</p>
                </div>
            </div>

            <div class="announcement-card pending-announcements">
                <div class="card-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="card-content">
                    <h3>Pending</h3>
                    <div class="stat-number">{{ $announcementStats['pending_announcements'] }}</div>
                    <p>Awaiting approval</p>
                </div>
            </div>

            <div class="announcement-card today-announcements">
                <div class="card-icon">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <div class="card-content">
                    <h3>Today</h3>
                    <div class="stat-number">{{ $announcementStats['today_announcements'] }}</div>
                    <p>Created today</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Announcements Management Tabs -->
    <div class="announcements-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showAnnouncementTab('list')" data-tab="list">
                <i class="fas fa-list"></i> All Announcements
            </button>
            <button class="nav-tab" onclick="showAnnouncementTab('analytics')" data-tab="analytics">
                <i class="fas fa-chart-bar"></i> Analytics
            </button>
            <button class="nav-tab" onclick="showAnnouncementTab('settings')" data-tab="settings">
                <i class="fas fa-cog"></i> Settings
            </button>
            <button class="nav-tab" onclick="showAnnouncementTab('activity')" data-tab="activity">
                <i class="fas fa-history"></i> Activity Log
            </button>
        </div>
    </div>

    <!-- Announcements Content -->
    <div class="announcements-content">
        
        <!-- Announcements List -->
        <div id="list-announcements" class="announcement-panel active">
            <div class="panel-header">
                <h2><i class="fas fa-list"></i> All Announcements</h2>
                <p>Manage all company announcements and notifications</p>
            </div>
            
            <div class="announcements-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="status-filter">Status</label>
                        <select id="status-filter" class="filter-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="pending">Pending</option>
                            <option value="draft">Draft</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="priority-filter">Priority</label>
                        <select id="priority-filter" class="filter-select">
                            <option value="">All Priorities</option>
                            <option value="high">High Priority</option>
                            <option value="medium">Medium Priority</option>
                            <option value="low">Low Priority</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="department-filter">Department</label>
                        <select id="department-filter" class="filter-select">
                            <option value="">All Departments</option>
                            @foreach($departments as $department)
                            <option value="{{ $department->id }}">{{ $department->department_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <input type="text" id="search-input" placeholder="Search announcements..." class="filter-input">
                    </div>
                    
                    <div class="filter-group">
                        <button class="btn btn-primary" onclick="applyFilters()">
                            <i class="fas fa-filter"></i> Filter
                        </button>
                        <button class="btn btn-secondary" onclick="clearFilters()">
                            <i class="fas fa-times"></i> Clear
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="announcements-table-container">
                <table class="announcements-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                            </th>
                            <th>Announcement ID / Content</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Departments</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                        <tr class="announcement-row" data-id="{{ $announcement->id }}">
                            <td>
                                <input type="checkbox" class="announcement-checkbox" value="{{ $announcement->id }}">
                            </td>
                            <td>
                                <div class="announcement-title-cell">
                                    <h4>{{ $announcement->announcement_title ?? $announcement->announcement_id }}</h4>
                                    <p>{{ Str::limit($announcement->announcement_text ?? $announcement->content, 80) }}</p>
                                </div>
                            </td>
                            <td>
                                <span class="priority-badge priority-{{ strtolower($announcement->priority ?? 'medium') }}">
                                    {{ ucfirst($announcement->priority ?? 'Medium') }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ strtolower($announcement->status ?? 'active') }}">
                                    {{ ucfirst($announcement->status ?? 'Active') }}
                                </span>
                            </td>
                            <td>
                                <div class="departments-list">
                                    @if($announcement->departments && $announcement->departments->count() > 0)
                                        @foreach($announcement->departments->take(2) as $department)
                                            <span class="department-tag">{{ $department->department_name ?? 'Unknown' }}</span>
                                        @endforeach
                                        @if($announcement->departments->count() > 2)
                                            <span class="department-tag more">+{{ $announcement->departments->count() - 2 }} more</span>
                                        @endif
                                    @else
                                        <span class="department-tag">All Departments</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="date">{{ \Carbon\Carbon::parse($announcement->date)->format('M d, Y') }}</span>
                                    <span class="time">{{ $announcement->created_at ? $announcement->created_at->format('h:i A') : 'N/A' }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewAnnouncement({{ $announcement->id }})" title="View">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn-action btn-edit" onclick="editAnnouncement({{ $announcement->id }})" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn-action btn-duplicate" onclick="duplicateAnnouncement({{ $announcement->id }})" title="Duplicate">
                                        <i class="fas fa-copy"></i>
                                    </button>
                                    <button class="btn-action btn-delete" onclick="deleteAnnouncement({{ $announcement->id }})" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="no-data">
                                <div class="no-data-message">
                                    <i class="fas fa-bullhorn"></i>
                                    <h3>No announcements found</h3>
                                    <p>Create your first announcement to get started</p>
                                    <button class="btn btn-primary" onclick="openCreateModal()">
                                        <i class="fas fa-plus"></i> Create Announcement
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="bulk-actions">
                    <button class="btn btn-warning" onclick="bulkPublish()" disabled id="bulk-publish">
                        <i class="fas fa-play"></i> Publish Selected
                    </button>
                    <button class="btn btn-secondary" onclick="bulkArchive()" disabled id="bulk-archive">
                        <i class="fas fa-archive"></i> Archive Selected
                    </button>
                    <button class="btn btn-danger" onclick="bulkDelete()" disabled id="bulk-delete">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
                
                <div class="pagination-info">
                    <span>Showing {{ $announcements->count() }} of {{ $announcements->count() }} announcements</span>
                </div>
            </div>
        </div>

        <!-- Analytics Panel -->
        <div id="analytics-announcements" class="announcement-panel">
            <div class="panel-header">
                <h2><i class="fas fa-chart-bar"></i> Announcements Analytics</h2>
                <p>Analyze announcement performance and engagement metrics</p>
            </div>
            
            <div class="analytics-grid">
                <div class="analytics-card">
                    <h3>Priority Distribution</h3>
                    <div class="priority-stats">
                        <div class="priority-item">
                            <span class="priority-color high"></span>
                            <span>High Priority: {{ $announcementStats['priority_high'] }}</span>
                        </div>
                        <div class="priority-item">
                            <span class="priority-color medium"></span>
                            <span>Medium Priority: {{ $announcementStats['priority_medium'] }}</span>
                        </div>
                        <div class="priority-item">
                            <span class="priority-color low"></span>
                            <span>Low Priority: {{ $announcementStats['priority_low'] }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3>Weekly Activity</h3>
                    <div class="weekly-stats">
                        <div class="stat-item">
                            <span class="stat-label">This Week</span>
                            <span class="stat-value">{{ $announcementStats['this_week_announcements'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Active</span>
                            <span class="stat-value">{{ $announcementStats['active_announcements'] }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Pending</span>
                            <span class="stat-value">{{ $announcementStats['pending_announcements'] }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3>Performance Metrics</h3>
                    <div class="metrics-list">
                        <div class="metric-row">
                            <span>Engagement Rate</span>
                            <span class="metric-value">85.2%</span>
                        </div>
                        <div class="metric-row">
                            <span>Read Rate</span>
                            <span class="metric-value">92.7%</span>
                        </div>
                        <div class="metric-row">
                            <span>Response Rate</span>
                            <span class="metric-value">34.5%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Panel -->
        <div id="settings-announcements" class="announcement-panel">
            <div class="panel-header">
                <h2><i class="fas fa-cog"></i> Announcement Settings</h2>
                <p>Configure announcement system settings and preferences</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Default Settings</h3>
                    <div class="setting-item">
                        <label for="default-priority">Default Priority</label>
                        <select id="default-priority" class="setting-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            Auto-publish approved announcements
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Require approval for all announcements
                        </label>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Notification Settings</h3>
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            Email notifications for new announcements
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            Push notifications for urgent announcements
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            SMS notifications for critical announcements
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Panel -->
        <div id="activity-announcements" class="announcement-panel">
            <div class="panel-header">
                <h2><i class="fas fa-history"></i> Recent Activity</h2>
                <p>Track recent announcement activities and changes</p>
            </div>
            
            <div class="activity-log">
                @foreach($recentActivity as $activity)
                <div class="activity-item activity-{{ $activity['type'] }}">
                    <div class="activity-icon">
                        <i class="fas fa-{{ $activity['type'] === 'create' ? 'plus' : ($activity['type'] === 'publish' ? 'play' : ($activity['type'] === 'update' ? 'edit' : 'archive')) }}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">
                            {{ ucfirst($activity['action']) }} "{{ $activity['announcement_title'] }}"
                        </div>
                        <div class="activity-meta">
                            <span class="activity-user">by {{ $activity['user'] }}</span>
                            <span class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- View Announcement Modal -->
<div id="viewAnnouncementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-eye"></i> View Announcement</h2>
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="viewAnnouncementContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            <button class="btn btn-primary" onclick="editFromView()">
                <i class="fas fa-edit"></i> Edit This Announcement
            </button>
        </div>
    </div>
</div>

<!-- Create/Edit Announcement Modal -->
<div id="announcementModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Create New Announcement</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body">
            <form id="announcementForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="announcement_id">Announcement ID *</label>
                        <input type="text" id="announcement_id" name="announcement_id" required class="form-input" placeholder="Enter unique announcement ID">
                    </div>
                    
                    <div class="form-group">
                        <label for="announcement_title">Title</label>
                        <input type="text" id="announcement_title" name="announcement_title" class="form-input" placeholder="Enter announcement title">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="content">Content *</label>
                    <textarea id="content" name="content" required class="form-textarea" rows="4" placeholder="Enter main announcement content"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="announcement_text">Additional Text</label>
                    <textarea id="announcement_text" name="announcement_text" class="form-textarea" rows="3" placeholder="Enter additional announcement details"></textarea>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" id="date" name="date" class="form-input" value="{{ date('Y-m-d') }}">
                    </div>
                    
                    <div class="form-group">
                        <label for="priority">Priority</label>
                        <select id="priority" name="priority" class="form-select">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-select">
                            <option value="draft">Draft</option>
                            <option value="pending">Pending Approval</option>
                            <option value="active" selected>Active</option>
                            <option value="archived">Archived</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="target_team_id">Target Team (Optional)</label>
                        <input type="text" id="target_team_id" name="target_team_id" class="form-input" placeholder="Enter team ID if applicable">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="departments">Target Departments</label>
                    <select id="departments" name="departments[]" multiple class="form-select">
                        @foreach($departments as $department)
                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                        @endforeach
                    </select>
                    <small>Hold Ctrl/Cmd to select multiple departments</small>
                </div>
                
                <div class="form-group">
                    <label class="setting-checkbox">
                        <input type="checkbox" name="send_notifications">
                        <span class="checkmark"></span>
                        Send notifications to affected departments
                    </label>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeModal()">Cancel</button>
            <button class="btn btn-primary" onclick="saveAnnouncement()">Save Announcement</button>
        </div>
    </div>
</div>

<style>
    .announcements-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .announcements-header {
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

    /* Overview Cards */
    .announcements-overview {
        margin-bottom: 2rem;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .announcement-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        display: flex;
        align-items: center;
        gap: 1rem;
        transition: transform 0.3s ease;
    }

    .announcement-card:hover {
        transform: translateY(-2px);
    }

    .card-icon {
        width: 60px;
        height: 60px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .total-announcements .card-icon {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
    }

    .active-announcements .card-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .pending-announcements .card-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .today-announcements .card-icon {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
    }

    .card-content h3 {
        font-size: 0.9rem;
        font-weight: 600;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .card-content p {
        font-size: 0.85rem;
        color: var(--text-secondary);
        margin: 0;
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
        background: linear-gradient(135deg, var(--gray-600), var(--gray-700));
        color: white;
        box-shadow: 0 4px 15px rgba(107, 114, 128, 0.2);
    }

    .btn-warning {
        background: linear-gradient(135deg, #F59E0B, #D97706);
        color: white;
        box-shadow: 0 4px 15px rgba(245, 158, 11, 0.2);
    }

    .btn-danger {
        background: linear-gradient(135deg, #EF4444, #DC2626);
        color: white;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.2);
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

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    /* Navigation Tabs */
    .announcements-navigation {
        margin-bottom: 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .nav-tabs {
        display: flex;
        flex-wrap: wrap;
    }

    .nav-tab {
        flex: 1;
        padding: 1rem 1.5rem;
        background: none;
        border: none;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: var(--text-secondary);
        border-bottom: 3px solid transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        min-width: 140px;
    }

    .nav-tab:hover {
        background: var(--bg-secondary);
        color: var(--text-primary);
    }

    .nav-tab.active {
        background: var(--bg-secondary);
        color: var(--redcode-primary);
        border-bottom-color: var(--redcode-primary);
    }

    /* Content Panels */
    .announcements-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .announcement-panel {
        display: none;
        padding: 2rem;
    }

    .announcement-panel.active {
        display: block;
    }

    .panel-header {
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .panel-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .panel-header h2 i {
        color: var(--redcode-primary);
    }

    /* Filters */
    .announcements-filters {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background: var(--bg-secondary);
        border-radius: 12px;
        border: 1px solid var(--border-light);
    }

    .filter-row {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
        min-width: 150px;
    }

    .filter-group label {
        font-size: 0.85rem;
        font-weight: 600;
        color: var(--text-secondary);
    }

    .filter-select,
    .filter-input {
        padding: 0.5rem;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        background: white;
        font-size: 0.9rem;
    }

    /* Table Styles */
    .announcements-table-container {
        overflow-x: auto;
        margin-bottom: 1rem;
    }

    .announcements-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .announcements-table th,
    .announcements-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    .announcements-table th {
        background: var(--bg-secondary);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .announcement-title-cell h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .announcement-title-cell p {
        margin: 0;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .priority-badge,
    .status-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .priority-high {
        background: #FEE2E2;
        color: #991B1B;
    }

    .priority-medium {
        background: #FEF3C7;
        color: #92400E;
    }

    .priority-low {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .status-active {
        background: #ECFDF5;
        color: #065F46;
    }

    .status-pending {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-draft {
        background: #F3F4F6;
        color: #374151;
    }

    .departments-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }

    .department-tag {
        padding: 0.125rem 0.5rem;
        background: var(--bg-secondary);
        border-radius: 12px;
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .department-tag.more {
        background: var(--redcode-primary);
        color: white;
    }

    .date-cell {
        display: flex;
        flex-direction: column;
    }

    .date {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.85rem;
    }

    .time {
        font-size: 0.75rem;
        color: var(--text-secondary);
    }

    .action-buttons {
        display: flex;
        gap: 0.5rem;
    }

    .btn-action {
        width: 32px;
        height: 32px;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.8rem;
    }

    .btn-view {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .btn-edit {
        background: #FEF3C7;
        color: #92400E;
    }

    .btn-duplicate {
        background: #E0E7FF;
        color: #5B21B6;
    }

    .btn-delete {
        background: #FEE2E2;
        color: #991B1B;
    }

    .btn-action:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .no-data {
        text-align: center;
        padding: 3rem;
    }

    .no-data-message i {
        font-size: 3rem;
        color: var(--text-light);
        margin-bottom: 1rem;
    }

    .no-data-message h3 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
    }

    .no-data-message p {
        color: var(--text-secondary);
        margin-bottom: 1.5rem;
    }

    /* Table Footer */
    .table-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1rem;
        background: var(--bg-secondary);
        border-top: 1px solid var(--border-light);
    }

    .bulk-actions {
        display: flex;
        gap: 0.5rem;
    }

    .bulk-actions .btn {
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
    }

    .pagination-info {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    /* Analytics Styles */
    .analytics-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 2rem;
    }

    .analytics-card {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-light);
    }

    .analytics-card h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
    }

    .priority-stats,
    .weekly-stats,
    .metrics-list {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .priority-item,
    .stat-item,
    .metric-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .priority-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .priority-color.high {
        background: #DC2626;
    }

    .priority-color.medium {
        background: #F59E0B;
    }

    .priority-color.low {
        background: #2563EB;
    }

    .stat-value,
    .metric-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    /* Settings Styles */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
    }

    .setting-group {
        background: var(--bg-secondary);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-light);
    }

    .setting-group h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid var(--border-light);
    }

    .setting-item {
        margin-bottom: 1.5rem;
    }

    .setting-item:last-child {
        margin-bottom: 0;
    }

    .setting-item label {
        display: block;
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .setting-select {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        background: white;
        font-size: 0.9rem;
    }

    /* Checkbox Styles */
    .setting-checkbox {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        cursor: pointer;
        font-weight: 600;
        color: var(--text-primary);
    }

    .setting-checkbox input[type="checkbox"] {
        display: none;
    }

    .checkmark {
        width: 20px;
        height: 20px;
        border: 2px solid var(--border-medium);
        border-radius: 4px;
        position: relative;
        transition: all 0.3s ease;
        background: white;
    }

    .setting-checkbox input[type="checkbox"]:checked + .checkmark {
        background: var(--redcode-primary);
        border-color: var(--redcode-primary);
    }

    .setting-checkbox input[type="checkbox"]:checked + .checkmark::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
        font-weight: bold;
    }

    /* Activity Log */
    .activity-log {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .activity-item {
        display: flex;
        align-items: center;
        gap: 1rem;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: 8px;
        border-left: 4px solid var(--border-light);
    }

    .activity-create {
        border-left-color: #10B981;
    }

    .activity-publish {
        border-left-color: #2563EB;
    }

    .activity-update {
        border-left-color: #F59E0B;
    }

    .activity-archive {
        border-left-color: #6B7280;
    }

    .activity-icon {
        width: 40px;
        height: 40px;
        border-radius: 8px;
        background: var(--redcode-primary);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-title {
        font-weight: 600;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .activity-meta {
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .activity-user {
        margin-right: 0.5rem;
    }

    /* View Modal Specific Styles */
    #viewAnnouncementContent {
        max-height: 400px;
        overflow-y: auto;
        padding: 1rem 0;
    }

    .view-section {
        margin-bottom: 1.5rem;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: 8px;
        border-left: 4px solid var(--redcode-primary);
    }

    .view-section h4 {
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        font-weight: 600;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .view-section .content {
        color: var(--text-secondary);
        line-height: 1.6;
    }

    .view-meta {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .meta-value {
        font-size: 0.9rem;
        color: var(--text-primary);
        font-weight: 500;
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
    }

    .modal-content {
        background-color: white;
        margin: 5% auto;
        padding: 0;
        border-radius: 12px;
        width: 90%;
        max-width: 800px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid var(--border-light);
    }

    .modal-header h2 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: var(--text-secondary);
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    /* Form Styles */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .form-group label {
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .form-input,
    .form-select,
    .form-textarea {
        padding: 0.75rem;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.3s ease;
    }

    .form-input:focus,
    .form-select:focus,
    .form-textarea:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .form-group small {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .announcements-container {
            padding: 1rem;
        }

        .announcements-header {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .header-actions {
            justify-content: center;
        }

        .overview-cards {
            grid-template-columns: 1fr;
        }

        .nav-tabs {
            flex-direction: column;
        }

        .nav-tab {
            min-width: auto;
        }

        .filter-row {
            flex-direction: column;
        }

        .filter-group {
            min-width: auto;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .table-footer {
            flex-direction: column;
            gap: 1rem;
            align-items: stretch;
        }

        .bulk-actions {
            justify-content: center;
        }

        .analytics-grid,
        .settings-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<script>
    // Dummy data for demonstration
    const dummyAnnouncements = {
        1: {
            id: 1,
            announcement_id: 'ANN-2025-001',
            announcement_title: 'New Security Policy Update',
            content: 'Important updates to our company security policies effective immediately.',
            announcement_text: 'All employees must read and acknowledge the new security guidelines including password requirements, two-factor authentication setup, and data handling procedures. Training sessions will be conducted next week.',
            status: 'active',
            priority: 'high',
            date: '2025-07-23',
            departments: ['IT Department', 'HR Department', 'Finance'],
            target_team_id: 'TEAM-SEC-001',
            created_at: '2025-07-23 09:30:00',
            created_by: 'Super Admin'
        },
        2: {
            id: 2,
            announcement_id: 'ANN-2025-002',
            announcement_title: 'Holiday Schedule 2025',
            content: 'Updated holiday schedule for the remainder of 2025.',
            announcement_text: 'Please note the following holiday dates: Independence Day (July 4), Labor Day (September 2), Thanksgiving (November 28-29), Christmas (December 25). Office will be closed on these dates.',
            status: 'active',
            priority: 'medium',
            date: '2025-07-22',
            departments: ['All Departments'],
            target_team_id: null,
            created_at: '2025-07-22 14:15:00',
            created_by: 'HR Manager'
        },
        3: {
            id: 3,
            announcement_id: 'ANN-2025-003',
            announcement_title: 'Office Renovation Notice',
            content: 'Upcoming office renovation project starting next month.',
            announcement_text: 'The main office will undergo renovation from August 1-15. During this period, please use the temporary workspace on the 3rd floor. IT support will be available to help with equipment relocation.',
            status: 'pending',
            priority: 'medium',
            date: '2025-07-21',
            departments: ['Operations', 'IT Department'],
            target_team_id: 'TEAM-OPS-001',
            created_at: '2025-07-21 11:20:00',
            created_by: 'Operations Manager'
        }
    };

    // Initialize announcements page
    document.addEventListener('DOMContentLoaded', function() {
        setupAnnouncementTabs();
        setupTableFeatures();
        setupFilters();
        addDummyDataToTable();
    });

    // Add dummy data to table for demonstration
    function addDummyDataToTable() {
        const tableBody = document.querySelector('.announcements-table tbody');
        if (!tableBody) return;
        
        // Clear existing rows except the "no data" row
        const existingRows = tableBody.querySelectorAll('.announcement-row');
        existingRows.forEach(row => row.remove());
        
        // Add dummy announcement rows
        Object.values(dummyAnnouncements).forEach(announcement => {
            const row = createAnnouncementRow(announcement);
            tableBody.insertBefore(row, tableBody.firstChild);
        });
        
        // Remove "no data" row if it exists
        const noDataRow = tableBody.querySelector('tr:not(.announcement-row)');
        if (noDataRow) noDataRow.remove();
    }

    // Create announcement row HTML
    function createAnnouncementRow(announcement) {
        const row = document.createElement('tr');
        row.className = 'announcement-row';
        row.setAttribute('data-id', announcement.id);
        
        const priorityClass = announcement.priority ? announcement.priority.toLowerCase() : 'medium';
        const statusClass = announcement.status ? announcement.status.toLowerCase() : 'active';
        const departmentTags = announcement.departments.slice(0, 2).map(dept => 
            `<span class="department-tag">${dept}</span>`
        ).join('');
        const moreDepts = announcement.departments.length > 2 ? 
            `<span class="department-tag more">+${announcement.departments.length - 2} more</span>` : '';
        
        row.innerHTML = `
            <td>
                <input type="checkbox" class="announcement-checkbox" value="${announcement.id}">
            </td>
            <td>
                <div class="announcement-title-cell">
                    <h4>${announcement.announcement_title || announcement.announcement_id}</h4>
                    <p>${announcement.announcement_text ? announcement.announcement_text.substring(0, 80) + '...' : announcement.content.substring(0, 80) + '...'}</p>
                </div>
            </td>
            <td>
                <span class="priority-badge priority-${priorityClass}">
                    ${announcement.priority ? announcement.priority.charAt(0).toUpperCase() + announcement.priority.slice(1) : 'Medium'}
                </span>
            </td>
            <td>
                <span class="status-badge status-${statusClass}">
                    ${announcement.status ? announcement.status.charAt(0).toUpperCase() + announcement.status.slice(1) : 'Active'}
                </span>
            </td>
            <td>
                <div class="departments-list">
                    ${departmentTags}
                    ${moreDepts}
                </div>
            </td>
            <td>
                <div class="date-cell">
                    <span class="date">${new Date(announcement.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}</span>
                    <span class="time">${new Date(announcement.created_at).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit', hour12: true })}</span>
                </div>
            </td>
            <td>
                <div class="action-buttons">
                    <button class="btn-action btn-view" onclick="viewAnnouncement(${announcement.id})" title="View">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="btn-action btn-edit" onclick="editAnnouncement(${announcement.id})" title="Edit">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn-action btn-duplicate" onclick="duplicateAnnouncement(${announcement.id})" title="Duplicate">
                        <i class="fas fa-copy"></i>
                    </button>
                    <button class="btn-action btn-delete" onclick="deleteAnnouncement(${announcement.id})" title="Delete">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </td>
        `;
        
        return row;
    }

    // Tab switching functionality
    function setupAnnouncementTabs() {
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                showAnnouncementTab(targetTab);
            });
        });
    }

    function showAnnouncementTab(tabName) {
        // Remove active class from all tabs and panels
        document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.announcement-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to selected tab and panel
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(`${tabName}-announcements`).classList.add('active');
    }

    // Table functionality
    function setupTableFeatures() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', toggleSelectAll);
        }

        // Individual checkboxes
        const checkboxes = document.querySelectorAll('.announcement-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
    }

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.announcement-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        updateBulkActions();
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        const bulkButtons = ['bulk-publish', 'bulk-archive', 'bulk-delete'];
        
        bulkButtons.forEach(buttonId => {
            const button = document.getElementById(buttonId);
            if (button) {
                button.disabled = checkedBoxes.length === 0;
            }
        });
    }

    // Filter functionality
    function setupFilters() {
        const searchInput = document.getElementById('search-input');
        if (searchInput) {
            searchInput.addEventListener('input', debounce(applyFilters, 300));
        }
    }

    function applyFilters() {
        const status = document.getElementById('status-filter')?.value || '';
        const priority = document.getElementById('priority-filter')?.value || '';
        const department = document.getElementById('department-filter')?.value || '';
        const search = document.getElementById('search-input')?.value.toLowerCase() || '';
        
        const rows = document.querySelectorAll('.announcement-row');
        
        rows.forEach(row => {
            const statusMatch = !status || row.querySelector('.status-badge').textContent.toLowerCase().includes(status);
            const priorityMatch = !priority || row.querySelector('.priority-badge').textContent.toLowerCase().includes(priority);
            const searchMatch = !search || row.textContent.toLowerCase().includes(search);
            
            row.style.display = statusMatch && priorityMatch && searchMatch ? '' : 'none';
        });
        
        showNotification('Filters applied successfully!', 'success');
    }

    function clearFilters() {
        document.getElementById('status-filter').value = '';
        document.getElementById('priority-filter').value = '';
        document.getElementById('department-filter').value = '';
        document.getElementById('search-input').value = '';
        
        document.querySelectorAll('.announcement-row').forEach(row => {
            row.style.display = '';
        });
        
        showNotification('Filters cleared!', 'info');
    }

    // Modal functionality
    function openCreateModal() {
        document.getElementById('modalTitle').innerHTML = '<i class="fas fa-plus"></i> Create New Announcement';
        document.getElementById('announcementForm').reset();
        // Set current date
        document.getElementById('date').value = new Date().toISOString().split('T')[0];
        document.getElementById('announcementModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('announcementModal').style.display = 'none';
    }

    function closeViewModal() {
        document.getElementById('viewAnnouncementModal').style.display = 'none';
    }

    function editFromView() {
        closeViewModal();
        const currentId = document.getElementById('viewAnnouncementModal').dataset.currentId;
        if (currentId) {
            editAnnouncement(parseInt(currentId));
        }
    }

    // Enhanced announcement actions with dummy data
    function viewAnnouncement(id) {
        const announcement = dummyAnnouncements[id];
        if (!announcement) {
            showNotification('Announcement not found!', 'error');
            return;
        }

        showNotification(`Loading announcement details...`, 'info');
        
        setTimeout(() => {
            const modal = document.getElementById('viewAnnouncementModal');
            modal.dataset.currentId = id;
            
            const content = document.getElementById('viewAnnouncementContent');
            content.innerHTML = `
                <div class="view-meta">
                    <div class="meta-item">
                        <span class="meta-label">Announcement ID</span>
                        <span class="meta-value">${announcement.announcement_id}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Status</span>
                        <span class="meta-value">
                            <span class="status-badge status-${announcement.status.toLowerCase()}">
                                ${announcement.status.charAt(0).toUpperCase() + announcement.status.slice(1)}
                            </span>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Priority</span>
                        <span class="meta-value">
                            <span class="priority-badge priority-${announcement.priority.toLowerCase()}">
                                ${announcement.priority.charAt(0).toUpperCase() + announcement.priority.slice(1)}
                            </span>
                        </span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Created</span>
                        <span class="meta-value">${new Date(announcement.created_at).toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'long', 
                            day: 'numeric',
                            hour: 'numeric',
                            minute: '2-digit'
                        })}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Created By</span>
                        <span class="meta-value">${announcement.created_by}</span>
                    </div>
                    <div class="meta-item">
                        <span class="meta-label">Target Team</span>
                        <span class="meta-value">${announcement.target_team_id || 'All Teams'}</span>
                    </div>
                </div>
                
                <div class="view-section">
                    <h4>Title</h4>
                    <div class="content">${announcement.announcement_title}</div>
                </div>
                
                <div class="view-section">
                    <h4>Main Content</h4>
                    <div class="content">${announcement.content}</div>
                </div>
                
                ${announcement.announcement_text ? `
                <div class="view-section">
                    <h4>Additional Details</h4>
                    <div class="content">${announcement.announcement_text}</div>
                </div>
                ` : ''}
                
                <div class="view-section">
                    <h4>Target Departments</h4>
                    <div class="content">
                        <div class="departments-list">
                            ${announcement.departments.map(dept => `<span class="department-tag">${dept}</span>`).join('')}
                        </div>
                    </div>
                </div>
            `;
            
            modal.style.display = 'block';
            showNotification('Announcement loaded successfully!', 'success');
        }, 800);
    }

    function editAnnouncement(id) {
        const announcement = dummyAnnouncements[id];
        if (!announcement) {
            showNotification('Announcement not found!', 'error');
            return;
        }

        showNotification(`Loading announcement for editing...`, 'info');
        
        setTimeout(() => {
            document.getElementById('modalTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Announcement';
            
            // Populate form with existing data
            document.getElementById('announcement_id').value = announcement.announcement_id;
            document.getElementById('announcement_title').value = announcement.announcement_title || '';
            document.getElementById('content').value = announcement.content;
            document.getElementById('announcement_text').value = announcement.announcement_text || '';
            document.getElementById('date').value = announcement.date;
            document.getElementById('priority').value = announcement.priority;
            document.getElementById('status').value = announcement.status;
            document.getElementById('target_team_id').value = announcement.target_team_id || '';
            
            // Mark the form as editing
            const form = document.getElementById('announcementForm');
            form.dataset.editingId = id;
            form.dataset.mode = 'edit';
            
            document.getElementById('announcementModal').style.display = 'block';
            showNotification('Ready to edit announcement!', 'success');
        }, 1000);
    }

    function duplicateAnnouncement(id) {
        const announcement = dummyAnnouncements[id];
        if (!announcement) {
            showNotification('Announcement not found!', 'error');
            return;
        }

        showNotification(`Duplicating announcement...`, 'info');
        
        setTimeout(() => {
            // Create new announcement with incremented ID
            const newId = Math.max(...Object.keys(dummyAnnouncements).map(Number)) + 1;
            const duplicatedAnnouncement = {
                ...announcement,
                id: newId,
                announcement_id: `ANN-2025-${String(newId).padStart(3, '0')}`,
                announcement_title: `Copy of ${announcement.announcement_title}`,
                status: 'draft',
                created_at: new Date().toISOString(),
                created_by: 'Super Admin'
            };
            
            // Add to dummy data
            dummyAnnouncements[newId] = duplicatedAnnouncement;
            
            // Add row to table
            const tableBody = document.querySelector('.announcements-table tbody');
            const newRow = createAnnouncementRow(duplicatedAnnouncement);
            tableBody.insertBefore(newRow, tableBody.firstChild);
            
            // Add highlight effect
            newRow.style.backgroundColor = '#E0F2FE';
            setTimeout(() => {
                newRow.style.backgroundColor = '';
            }, 3000);
            
            showNotification('Announcement duplicated successfully!', 'success');
        }, 1500);
    }

    function deleteAnnouncement(id) {
        const announcement = dummyAnnouncements[id];
        if (!announcement) {
            showNotification('Announcement not found!', 'error');
            return;
        }

        // Enhanced confirmation dialog
        const confirmDelete = confirm(`Are you sure you want to delete this announcement?\n\nTitle: ${announcement.announcement_title}\nID: ${announcement.announcement_id}\n\nThis action cannot be undone.`);
        
        if (confirmDelete) {
            showNotification(`Deleting announcement...`, 'warning');
            
            setTimeout(() => {
                // Remove from dummy data
                delete dummyAnnouncements[id];
                
                // Add fade out animation to row
                const row = document.querySelector(`[data-id="${id}"]`);
                if (row) {
                    row.style.transition = 'all 0.3s ease';
                    row.style.opacity = '0';
                    row.style.transform = 'translateX(-20px)';
                    
                    setTimeout(() => {
                        row.remove();
                        showNotification('Announcement deleted successfully!', 'success');
                        updateBulkActions();
                    }, 300);
                }
            }, 1000);
        }
    }

    function saveAnnouncement() {
        const form = document.getElementById('announcementForm');
        const formData = new FormData(form);
        const isEditing = form.dataset.mode === 'edit';
        const editingId = form.dataset.editingId;
        
        // Basic validation
        const announcementId = formData.get('announcement_id');
        const content = formData.get('content');
        
        if (!announcementId || !content) {
            showNotification('Please fill in all required fields!', 'error');
            return;
        }

        // Check for duplicate ID when creating new announcement
        if (!isEditing && Object.values(dummyAnnouncements).some(ann => ann.announcement_id === announcementId)) {
            showNotification('Announcement ID already exists! Please use a unique ID.', 'error');
            return;
        }
        
        showNotification(isEditing ? 'Updating announcement...' : 'Creating announcement...', 'info');
        
        setTimeout(() => {
            const announcementData = {
                id: isEditing ? parseInt(editingId) : Math.max(...Object.keys(dummyAnnouncements).map(Number)) + 1,
                announcement_id: announcementId,
                announcement_title: formData.get('announcement_title') || '',
                content: content,
                announcement_text: formData.get('announcement_text') || '',
                date: formData.get('date'),
                priority: formData.get('priority'),
                status: formData.get('status'),
                target_team_id: formData.get('target_team_id') || null,
                departments: Array.from(formData.getAll('departments[]')).map(id => {
                    // Convert department IDs to names (dummy mapping)
                    const deptNames = {
                        'DEPT-001': 'IT Department',
                        'DEPT-002': 'HR Department', 
                        'DEPT-003': 'Finance Department',
                        'DEPT-004': 'Operations Department'
                    };
                    return deptNames[id] || 'Unknown Department';
                }) || ['All Departments'],
                created_at: isEditing ? dummyAnnouncements[editingId].created_at : new Date().toISOString(),
                created_by: isEditing ? dummyAnnouncements[editingId].created_by : 'Super Admin'
            };
            
            if (isEditing) {
                // Update existing announcement
                dummyAnnouncements[editingId] = announcementData;
                
                // Update table row
                const row = document.querySelector(`[data-id="${editingId}"]`);
                if (row) {
                    const newRow = createAnnouncementRow(announcementData);
                    row.parentNode.replaceChild(newRow, row);
                    
                    // Add highlight effect
                    newRow.style.backgroundColor = '#FEF3C7';
                    setTimeout(() => {
                        newRow.style.backgroundColor = '';
                    }, 3000);
                }
            } else {
                // Add new announcement
                dummyAnnouncements[announcementData.id] = announcementData;
                
                // Add row to table
                const tableBody = document.querySelector('.announcements-table tbody');
                const newRow = createAnnouncementRow(announcementData);
                tableBody.insertBefore(newRow, tableBody.firstChild);
                
                // Add highlight effect
                newRow.style.backgroundColor = '#ECFDF5';
                setTimeout(() => {
                    newRow.style.backgroundColor = '';
                }, 3000);
            }
            
            closeModal();
            form.removeAttribute('data-editing-id');
            form.removeAttribute('data-mode');
            showNotification(isEditing ? 'Announcement updated successfully!' : 'Announcement created successfully!', 'success');
        }, 2000);
    }

    // Bulk actions
    function bulkPublish() {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        showNotification(`Publishing ${checkedBoxes.length} announcements...`, 'info');
        
        setTimeout(() => {
            showNotification(`${checkedBoxes.length} announcements published successfully!`, 'success');
        }, 2000);
    }

    function bulkArchive() {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        if (confirm(`Are you sure you want to archive ${checkedBoxes.length} announcements?`)) {
            showNotification(`Archiving ${checkedBoxes.length} announcements...`, 'warning');
            
            setTimeout(() => {
                showNotification(`${checkedBoxes.length} announcements archived successfully!`, 'success');
            }, 1500);
        }
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.announcement-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        if (confirm(`Are you sure you want to delete ${checkedBoxes.length} announcements? This action cannot be undone.`)) {
            showNotification(`Deleting ${checkedBoxes.length} announcements...`, 'warning');
            
            setTimeout(() => {
                checkedBoxes.forEach(checkbox => {
                    const row = checkbox.closest('.announcement-row');
                    if (row) row.remove();
                });
                showNotification(`${checkedBoxes.length} announcements deleted successfully!`, 'success');
                updateBulkActions();
            }, 1500);
        }
    }

    // Other actions


    function refreshAnnouncements() {
        showNotification('Refreshing announcements...', 'info');
        
        setTimeout(() => {
            showNotification('Announcements refreshed!', 'success');
            // Here you would reload the announcements data
        }, 1000);
    }

    // Utility functions
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    function showNotification(message, type = 'info') {
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.15);
            z-index: 10000;
            border-left: 4px solid ${type === 'success' ? '#10B981' : type === 'error' ? '#DC2626' : type === 'warning' ? '#F59E0B' : '#2563EB'};
            font-weight: 500;
            max-width: 350px;
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 4000);
    }

    // Close modal when clicking outside
    window.onclick = function(event) {
        const modal = document.getElementById('announcementModal');
        const viewModal = document.getElementById('viewAnnouncementModal');
        
        if (event.target === modal) {
            closeModal();
        }
        if (event.target === viewModal) {
            closeViewModal();
        }
    }
</script>
@endsection
