@extends('layouts.super_admin')

@section('title', 'System Alerts Management - Super Admin Dashboard')

@section('content')
<div class="system-alerts-container">
    <!-- System Alerts Header -->
    <div class="system-alerts-header">
        <div class="header-content">
            <h1><i class="fas fa-exclamation-triangle"></i> System Alerts Management</h1>
            <p>Monitor, manage, and respond to critical system alerts and notifications</p>
        </div>
        <div class="header-actions">
            <button class="btn btn-primary" id="openCreateAlertModal">
                <i class="fas fa-plus"></i> Create Alert
            </button>

            <button class="btn btn-outline" onclick="refreshAlerts()">
                <i class="fas fa-sync"></i> Refresh
            </button>
        </div>
    </div>

    <!-- Alerts Overview Dashboard -->
    <div class="alerts-overview">
        <div class="overview-cards">
            <div class="alert-card total-alerts">
                <div class="card-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="card-content">
                    <h3>Total Alerts</h3>
                    <div class="stat-number">{{ $alertStats['total_alerts'] }}</div>
                    <p>All system alerts</p>
                </div>
            </div>

            <div class="alert-card critical-alerts">
                <div class="card-icon">
                    <i class="fas fa-exclamation-circle"></i>
                </div>
                <div class="card-content">
                    <h3>Critical</h3>
                    <div class="stat-number">{{ $alertStats['critical_alerts'] }}</div>
                    <p>Requires immediate attention</p>
                </div>
            </div>

            <div class="alert-card warning-alerts">
                <div class="card-icon">
                    <i class="fas fa-exclamation"></i>
                </div>
                <div class="card-content">
                    <h3>Warnings</h3>
                    <div class="stat-number">{{ $alertStats['warning_alerts'] }}</div>
                    <p>Monitor closely</p>
                </div>
            </div>

            <div class="alert-card active-alerts">
                <div class="card-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="card-content">
                    <h3>Active</h3>
                    <div class="stat-number">{{ $alertStats['active_alerts'] }}</div>
                    <p>Currently unresolved</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Alerts Management Tabs -->
    <div class="alerts-navigation">
        <div class="nav-tabs">
            <button class="nav-tab active" onclick="showAlertTab('list')" data-tab="list">
                <i class="fas fa-list"></i> All Alerts
            </button>
            <button class="nav-tab" onclick="showAlertTab('analytics')" data-tab="analytics">
                <i class="fas fa-chart-bar"></i> Analytics
            </button>
            <button class="nav-tab" onclick="showAlertTab('settings')" data-tab="settings">
                <i class="fas fa-cog"></i> Settings
            </button>
            <button class="nav-tab" onclick="showAlertTab('activity')" data-tab="activity">
                <i class="fas fa-history"></i> Activity Log
            </button>
        </div>
    </div>

    <!-- Alerts Content -->
    <div class="alerts-content">
        
        <!-- Alerts List -->
        <div id="list-alerts" class="alert-panel active">
            <div class="panel-header">
                <h2><i class="fas fa-list"></i> All System Alerts</h2>
                <p>Monitor and manage all system alerts and notifications</p>
            </div>
            
            <div class="alerts-filters">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="severity-filter">Severity</label>
                        <select id="severity-filter" class="filter-select">
                            <option value="">All Severities</option>
                            <option value="critical">Critical</option>
                            <option value="warning">Warning</option>
                            <option value="info">Information</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="status-filter">Status</label>
                        <select id="status-filter" class="filter-select">
                            <option value="">All Status</option>
                            <option value="active">Active</option>
                            <option value="acknowledged">Acknowledged</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="category-filter">Category</label>
                        <select id="category-filter" class="filter-select">
                            <option value="">All Categories</option>
                            <option value="security">Security</option>
                            <option value="database">Database</option>
                            <option value="infrastructure">Infrastructure</option>
                            <option value="performance">Performance</option>
                            <option value="backup">Backup</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <input type="text" id="search-input" placeholder="Search alerts..." class="filter-input">
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
            
            <div class="alerts-table-container">
                <table class="alerts-table">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" onchange="toggleSelectAll()">
                            </th>
                            <th>Alert Details</th>
                            <th>Severity</th>
                            <th>Status</th>
                            <th>Category</th>
                            <th>Created</th>
                            <th>Assigned To</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($allAlerts as $alert)
                        <tr class="alert-row" data-id="{{ $alert['id'] }}">
                            <td>
                                <input type="checkbox" class="alert-checkbox" value="{{ $alert['id'] }}">
                            </td>
                            <td>
                                <div class="alert-title-cell">
                                    <h4>{{ $alert['title'] }}</h4>
                                    <p>{{ Str::limit($alert['message'], 80) }}</p>
                                    <small class="alert-id">{{ $alert['alert_id'] }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="severity-badge severity-{{ $alert['severity'] }}">
                                    {{ ucfirst($alert['severity']) }}
                                </span>
                            </td>
                            <td>
                                <span class="status-badge status-{{ $alert['status'] }}">
                                    {{ ucfirst($alert['status']) }}
                                </span>
                            </td>
                            <td>
                                <span class="category-tag category-{{ $alert['category'] }}">
                                    {{ ucfirst($alert['category']) }}
                                </span>
                            </td>
                            <td>
                                <div class="date-cell">
                                    <span class="date">{{ $alert['created_at']->format('M d, Y') }}</span>
                                    <span class="time">{{ $alert['created_at']->format('h:i A') }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="assigned-to">
                                    <span class="assignee">{{ $alert['assigned_to'] }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-action btn-view" onclick="viewAlert({{ $alert['id'] }})" title="View Details">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    @if($alert['status'] === 'active')
                                    <button class="btn-action btn-acknowledge" onclick="acknowledgeAlert({{ $alert['id'] }})" title="Acknowledge">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @endif
                                    @if($alert['status'] !== 'resolved')
                                    <button class="btn-action btn-resolve" onclick="resolveAlert({{ $alert['id'] }})" title="Resolve">
                                        <i class="fas fa-check-circle"></i>
                                    </button>
                                    @endif
                                    <button class="btn-action btn-assign" onclick="assignAlert({{ $alert['id'] }})" title="Assign">
                                        <i class="fas fa-user-plus"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="no-data">
                                <div class="no-data-message">
                                    <i class="fas fa-shield-alt"></i>
                                    <h3>No system alerts found</h3>
                                    <p>Your system is running smoothly with no alerts</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="table-footer">
                <div class="bulk-actions">
                    <button class="btn btn-warning" onclick="bulkAcknowledge()" disabled id="bulk-acknowledge">
                        <i class="fas fa-check"></i> Acknowledge Selected
                    </button>
                    <button class="btn btn-success" onclick="bulkResolve()" disabled id="bulk-resolve">
                        <i class="fas fa-check-circle"></i> Resolve Selected
                    </button>
                    <button class="btn btn-danger" onclick="bulkDelete()" disabled id="bulk-delete">
                        <i class="fas fa-trash"></i> Delete Selected
                    </button>
                </div>
                
                <div class="pagination-info">
                    <span>Showing {{ $allAlerts->count() }} alerts</span>
                </div>
            </div>
        </div>

        <!-- Analytics Panel -->
        <div id="analytics-alerts" class="alert-panel">
            <div class="panel-header">
                <h2><i class="fas fa-chart-bar"></i> Alert Analytics</h2>
                <p>Analyze alert patterns and system health trends</p>
            </div>
            
            <div class="analytics-grid">
                <div class="analytics-card">
                    <h3>Alert Categories</h3>
                    <div class="category-stats">
                        @foreach($alertCategories as $category => $count)
                        <div class="category-item">
                            <span class="category-color {{ $category }}"></span>
                            <span>{{ ucfirst($category) }}: {{ $count }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3>Response Metrics</h3>
                    <div class="response-stats">
                        <div class="stat-item">
                            <span class="stat-label">Average Response Time</span>
                            <span class="stat-value">23 minutes</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Resolution Rate</span>
                            <span class="stat-value">89.2%</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Critical Alerts Today</span>
                            <span class="stat-value">{{ $alertStats['critical_alerts'] }}</span>
                        </div>
                    </div>
                </div>
                
                <div class="analytics-card">
                    <h3>System Health Score</h3>
                    <div class="health-score">
                        <div class="score-circle">
                            <div class="score-number">87</div>
                            <div class="score-label">Health Score</div>
                        </div>
                        <div class="health-details">
                            <div class="health-item positive">
                                <i class="fas fa-check-circle"></i>
                                <span>Security: Excellent</span>
                            </div>
                            <div class="health-item warning">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>Performance: Good</span>
                            </div>
                            <div class="health-item positive">
                                <i class="fas fa-shield-alt"></i>
                                <span>Infrastructure: Stable</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Settings Panel -->
        <div id="settings-alerts" class="alert-panel">
            <div class="panel-header">
                <h2><i class="fas fa-cog"></i> Alert Settings</h2>
                <p>Configure alert thresholds and notification preferences</p>
            </div>
            
            <div class="settings-grid">
                <div class="setting-group">
                    <h3>Alert Thresholds</h3>
                    <div class="setting-item">
                        <label for="cpu-threshold">CPU Usage Threshold (%)</label>
                        <input type="number" id="cpu-threshold" class="setting-input" value="80" min="0" max="100">
                    </div>
                    
                    <div class="setting-item">
                        <label for="memory-threshold">Memory Usage Threshold (%)</label>
                        <input type="number" id="memory-threshold" class="setting-input" value="85" min="0" max="100">
                    </div>
                    
                    <div class="setting-item">
                        <label for="disk-threshold">Disk Usage Threshold (%)</label>
                        <input type="number" id="disk-threshold" class="setting-input" value="90" min="0" max="100">
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Notification Settings</h3>
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            Email notifications for critical alerts
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            SMS notifications for security alerts
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Slack integration for team alerts
                        </label>
                    </div>
                </div>
                
                <div class="setting-group">
                    <h3>Auto-Resolution</h3>
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox">
                            <span class="checkmark"></span>
                            Auto-resolve info level alerts after 24 hours
                        </label>
                    </div>
                    
                    <div class="setting-item">
                        <label class="setting-checkbox">
                            <input type="checkbox" checked>
                            <span class="checkmark"></span>
                            Auto-acknowledge duplicate alerts
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Activity Log Panel -->
        <div id="activity-alerts" class="alert-panel">
            <div class="panel-header">
                <h2><i class="fas fa-history"></i> Recent Activity</h2>
                <p>Track recent alert activities and response actions</p>
            </div>
            
            <div class="activity-log">
                @foreach($recentActivity as $activity)
                <div class="activity-item activity-{{ $activity['type'] }}">
                    <div class="activity-icon">
                        <i class="fas fa-{{ $activity['type'] === 'create' ? 'plus' : ($activity['type'] === 'acknowledge' ? 'check' : ($activity['type'] === 'resolve' ? 'check-circle' : 'user-plus')) }}"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">
                            {{ ucfirst($activity['action']) }} "{{ $activity['alert_title'] }}"
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

<!-- View Alert Modal -->
<div id="viewAlertModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-eye"></i> Alert Details</h2>
            <button class="modal-close" onclick="closeViewModal()">&times;</button>
        </div>
        <div class="modal-body">
            <div id="viewAlertContent">
                <!-- Content will be populated by JavaScript -->
            </div>
        </div>
        <div class="modal-footer">
            <button class="btn btn-secondary" onclick="closeViewModal()">Close</button>
            <button class="btn btn-warning" onclick="acknowledgeFromView()">
                <i class="fas fa-check"></i> Acknowledge
            </button>
            <button class="btn btn-success" onclick="resolveFromView()">
                <i class="fas fa-check-circle"></i> Resolve
            </button>
        </div>
    </div>
</div>

<!-- Create Alert Modal -->
<div id="createAlertModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2><i class="fas fa-plus"></i> Create System Alert</h2>
            <button class="modal-close" onclick="closeCreateAlertModal()">&times;</button>
        </div>
        <form id="createAlertForm" method="POST" action="{{ route('super_admin.system_alerts.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="alert_title" style="font-weight: 600;">Title</label>
                    <input type="text" name="title" id="alert_title" class="form-control" required maxlength="255" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                    <div class="text-danger" id="error_title"></div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="alert_message" style="font-weight: 600;">Message</label>
                    <textarea name="message" id="alert_message" class="form-control" required rows="4" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;"></textarea>
                    <div class="text-danger" id="error_message"></div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="alert_severity" style="font-weight: 600;">Severity</label>
                    <select name="severity" id="alert_severity" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                        <option value="">Select Severity</option>
                        <option value="critical">Critical</option>
                        <option value="warning">Warning</option>
                        <option value="info">Information</option>
                    </select>
                    <div class="text-danger" id="error_severity"></div>
                </div>
                <div class="form-group" style="margin-bottom: 1.5rem;">
                    <label for="alert_category" style="font-weight: 600;">Category</label>
                    <select name="category" id="alert_category" class="form-control" required style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                        <option value="">Select Category</option>
                        <option value="security">Security</option>
                        <option value="database">Database</option>
                        <option value="infrastructure">Infrastructure</option>
                        <option value="performance">Performance</option>
                        <option value="backup">Backup</option>
                        <option value="application">Application</option>
                    </select>
                    <div class="text-danger" id="error_category"></div>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="alert_assigned_to" style="font-weight: 600;">Assigned To (optional)</label>
                    <input type="text" name="assigned_to" id="alert_assigned_to" class="form-control" maxlength="255" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #E5E7EB;">
                    <div class="text-danger" id="error_assigned_to"></div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeCreateAlertModal()">Cancel</button>
                <button type="submit" class="btn btn-primary"><i class="fas fa-plus"></i> Create Alert</button>
            </div>
        </form>
    </div>
</div>

<style>
    .system-alerts-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* Header Section */
    .system-alerts-header {
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
    .alerts-overview {
        margin-bottom: 2rem;
    }

    .overview-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
    }

    .alert-card {
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

    .alert-card:hover {
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

    .total-alerts .card-icon {
        background: linear-gradient(135deg, #2563EB, #1D4ED8);
    }

    .critical-alerts .card-icon {
        background: linear-gradient(135deg, #DC2626, #B91C1C);
    }

    .warning-alerts .card-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .active-alerts .card-icon {
        background: linear-gradient(135deg, #10B981, #059669);
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

    .btn-success {
        background: linear-gradient(135deg, #10B981, #059669);
        color: white;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.2);
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
    .alerts-navigation {
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
    .alerts-content {
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        overflow: hidden;
    }

    .alert-panel {
        display: none;
        padding: 2rem;
    }

    .alert-panel.active {
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
    .alerts-filters {
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
    .alerts-table-container {
        overflow-x: auto;
        margin-bottom: 1rem;
    }

    .alerts-table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    .alerts-table th,
    .alerts-table td {
        padding: 1rem;
        text-align: left;
        border-bottom: 1px solid var(--border-light);
    }

    .alerts-table th {
        background: var(--bg-secondary);
        font-weight: 600;
        color: var(--text-primary);
        font-size: 0.9rem;
    }

    .alert-title-cell h4 {
        margin: 0 0 0.25rem 0;
        font-size: 1rem;
        font-weight: 600;
        color: var(--text-primary);
    }

    .alert-title-cell p {
        margin: 0 0 0.25rem 0;
        font-size: 0.85rem;
        color: var(--text-secondary);
    }

    .alert-id {
        font-size: 0.75rem;
        color: var(--text-light);
        background: var(--bg-secondary);
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
    }

    .severity-badge,
    .status-badge,
    .category-tag {
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }

    .severity-critical {
        background: #FEE2E2;
        color: #991B1B;
    }

    .severity-warning {
        background: #FEF3C7;
        color: #92400E;
    }

    .severity-info {
        background: #DBEAFE;
        color: #1E40AF;
    }

    .status-active {
        background: #FEE2E2;
        color: #991B1B;
    }

    .status-acknowledged {
        background: #FEF3C7;
        color: #92400E;
    }

    .status-resolved {
        background: #ECFDF5;
        color: #065F46;
    }

    .category-tag {
        background: var(--bg-secondary);
        color: var(--text-secondary);
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

    .assigned-to {
        font-size: 0.9rem;
        color: var(--text-primary);
        font-weight: 500;
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

    .btn-acknowledge {
        background: #FEF3C7;
        color: #92400E;
    }

    .btn-resolve {
        background: #ECFDF5;
        color: #065F46;
    }

    .btn-assign {
        background: #E0E7FF;
        color: #5B21B6;
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

    .category-stats,
    .response-stats {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
    }

    .category-item,
    .stat-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .category-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        margin-right: 0.5rem;
    }

    .category-color.security {
        background: #DC2626;
    }

    .category-color.database {
        background: #2563EB;
    }

    .category-color.infrastructure {
        background: #059669;
    }

    .category-color.performance {
        background: #F59E0B;
    }

    .category-color.backup {
        background: #7C3AED;
    }

    .stat-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .health-score {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .score-circle {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: white;
        flex-shrink: 0;
    }

    .score-number {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .score-label {
        font-size: 0.7rem;
        text-transform: uppercase;
    }

    .health-details {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    .health-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
    }

    .health-item.positive {
        color: #059669;
    }

    .health-item.warning {
        color: #F59E0B;
    }

    /* Settings Styles */
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
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

    .setting-input {
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
        border-left-color: #2563EB;
    }

    .activity-acknowledge {
        border-left-color: #F59E0B;
    }

    .activity-resolve {
        border-left-color: #10B981;
    }

    .activity-assign {
        border-left-color: #7C3AED;
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
        max-height: 400px;
        overflow-y: auto;
    }

    .modal-footer {
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-light);
        background: var(--bg-secondary);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .system-alerts-container {
            padding: 1rem;
        }

        .system-alerts-header {
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
    // Initialize alerts page
    document.addEventListener('DOMContentLoaded', function() {
        setupAlertTabs();
        setupTableFeatures();
        setupFilters();
    });

    // Tab switching functionality
    function setupAlertTabs() {
        const tabs = document.querySelectorAll('.nav-tab');
        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const targetTab = this.dataset.tab;
                showAlertTab(targetTab);
            });
        });
    }

    function showAlertTab(tabName) {
        // Remove active class from all tabs and panels
        document.querySelectorAll('.nav-tab').forEach(tab => tab.classList.remove('active'));
        document.querySelectorAll('.alert-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to selected tab and panel
        document.querySelector(`[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(`${tabName}-alerts`).classList.add('active');
    }

    // Table functionality
    function setupTableFeatures() {
        // Select all checkbox
        const selectAllCheckbox = document.getElementById('select-all');
        if (selectAllCheckbox) {
            selectAllCheckbox.addEventListener('change', toggleSelectAll);
        }

        // Individual checkboxes
        const checkboxes = document.querySelectorAll('.alert-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', updateBulkActions);
        });
    }

    function toggleSelectAll() {
        const selectAllCheckbox = document.getElementById('select-all');
        const checkboxes = document.querySelectorAll('.alert-checkbox');
        
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectAllCheckbox.checked;
        });
        
        updateBulkActions();
    }

    function updateBulkActions() {
        const checkedBoxes = document.querySelectorAll('.alert-checkbox:checked');
        const bulkButtons = ['bulk-acknowledge', 'bulk-resolve', 'bulk-delete'];
        
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
        const severity = document.getElementById('severity-filter')?.value || '';
        const status = document.getElementById('status-filter')?.value || '';
        const category = document.getElementById('category-filter')?.value || '';
        const search = document.getElementById('search-input')?.value.toLowerCase() || '';
        
        const rows = document.querySelectorAll('.alert-row');
        
        rows.forEach(row => {
            const severityMatch = !severity || row.querySelector('.severity-badge').textContent.toLowerCase().includes(severity);
            const statusMatch = !status || row.querySelector('.status-badge').textContent.toLowerCase().includes(status);
            const categoryMatch = !category || row.querySelector('.category-tag').textContent.toLowerCase().includes(category);
            const searchMatch = !search || row.textContent.toLowerCase().includes(search);
            
            row.style.display = severityMatch && statusMatch && categoryMatch && searchMatch ? '' : 'none';
        });
        
        showNotification('Filters applied successfully!', 'success');
    }

    function clearFilters() {
        document.getElementById('severity-filter').value = '';
        document.getElementById('status-filter').value = '';
        document.getElementById('category-filter').value = '';
        document.getElementById('search-input').value = '';
        
        document.querySelectorAll('.alert-row').forEach(row => {
            row.style.display = '';
        });
        
        showNotification('Filters cleared!', 'info');
    }

    // Alert actions
    function viewAlert(id) {
        showNotification(`Loading alert details...`, 'info');
        
        setTimeout(() => {
            const modal = document.getElementById('viewAlertModal');
            modal.dataset.currentId = id;
            
            const content = document.getElementById('viewAlertContent');
            content.innerHTML = `
                <div class="alert-details">
                    <h3>Alert #${id} Details</h3>
                    <p>Full alert information would be loaded here...</p>
                </div>
            `;
            
            modal.style.display = 'block';
            showNotification('Alert details loaded!', 'success');
        }, 800);
    }

    function acknowledgeAlert(id) {
        showNotification(`Acknowledging alert ${id}...`, 'info');
        
        setTimeout(() => {
            const row = document.querySelector(`[data-id="${id}"]`);
            if (row) {
                const statusBadge = row.querySelector('.status-badge');
                statusBadge.textContent = 'Acknowledged';
                statusBadge.className = 'status-badge status-acknowledged';
                
                // Remove acknowledge button, keep resolve button
                const acknowledgeBtn = row.querySelector('.btn-acknowledge');
                if (acknowledgeBtn) {
                    acknowledgeBtn.remove();
                }
            }
            showNotification('Alert acknowledged successfully!', 'success');
        }, 1000);
    }

    function resolveAlert(id) {
        showNotification(`Resolving alert ${id}...`, 'info');
        
        setTimeout(() => {
            const row = document.querySelector(`[data-id="${id}"]`);
            if (row) {
                const statusBadge = row.querySelector('.status-badge');
                statusBadge.textContent = 'Resolved';
                statusBadge.className = 'status-badge status-resolved';
                
                // Remove action buttons except view
                const actionButtons = row.querySelectorAll('.btn-action:not(.btn-view)');
                actionButtons.forEach(btn => btn.remove());
            }
            showNotification('Alert resolved successfully!', 'success');
        }, 1000);
    }

    function assignAlert(id) {
        const assignee = prompt('Enter assignee name:');
        if (assignee) {
            showNotification(`Assigning alert ${id} to ${assignee}...`, 'info');
            
            setTimeout(() => {
                const row = document.querySelector(`[data-id="${id}"]`);
                if (row) {
                    const assignedTo = row.querySelector('.assignee');
                    if (assignedTo) {
                        assignedTo.textContent = assignee;
                    }
                }
                showNotification(`Alert assigned to ${assignee} successfully!`, 'success');
            }, 1000);
        }
    }

    // Modal functions
    function closeViewModal() {
        document.getElementById('viewAlertModal').style.display = 'none';
    }

    function acknowledgeFromView() {
        const currentId = document.getElementById('viewAlertModal').dataset.currentId;
        if (currentId) {
            acknowledgeAlert(parseInt(currentId));
            closeViewModal();
        }
    }

    function resolveFromView() {
        const currentId = document.getElementById('viewAlertModal').dataset.currentId;
        if (currentId) {
            resolveAlert(parseInt(currentId));
            closeViewModal();
        }
    }

    // Bulk actions
    function bulkAcknowledge() {
        const checkedBoxes = document.querySelectorAll('.alert-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        showNotification(`Acknowledging ${checkedBoxes.length} alerts...`, 'info');
        
        setTimeout(() => {
            checkedBoxes.forEach(checkbox => {
                const row = checkbox.closest('.alert-row');
                const statusBadge = row.querySelector('.status-badge');
                statusBadge.textContent = 'Acknowledged';
                statusBadge.className = 'status-badge status-acknowledged';
            });
            showNotification(`${checkedBoxes.length} alerts acknowledged successfully!`, 'success');
        }, 2000);
    }

    function bulkResolve() {
        const checkedBoxes = document.querySelectorAll('.alert-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        if (confirm(`Are you sure you want to resolve ${checkedBoxes.length} alerts?`)) {
            showNotification(`Resolving ${checkedBoxes.length} alerts...`, 'info');
            
            setTimeout(() => {
                checkedBoxes.forEach(checkbox => {
                    const row = checkbox.closest('.alert-row');
                    const statusBadge = row.querySelector('.status-badge');
                    statusBadge.textContent = 'Resolved';
                    statusBadge.className = 'status-badge status-resolved';
                });
                showNotification(`${checkedBoxes.length} alerts resolved successfully!`, 'success');
            }, 2000);
        }
    }

    function bulkDelete() {
        const checkedBoxes = document.querySelectorAll('.alert-checkbox:checked');
        if (checkedBoxes.length === 0) return;
        
        if (confirm(`Are you sure you want to delete ${checkedBoxes.length} alerts? This action cannot be undone.`)) {
            showNotification(`Deleting ${checkedBoxes.length} alerts...`, 'warning');
            
            setTimeout(() => {
                checkedBoxes.forEach(checkbox => {
                    const row = checkbox.closest('.alert-row');
                    if (row) row.remove();
                });
                showNotification(`${checkedBoxes.length} alerts deleted successfully!`, 'success');
                updateBulkActions();
            }, 1500);
        }
    }

    // Other actions
    function createAlert() {
        showNotification('Opening alert creation form...', 'info');
        // Implement alert creation modal
    }

    function refreshAlerts() {
        showNotification('Refreshing alerts...', 'info');
        
        setTimeout(() => {
            showNotification('Alerts refreshed!', 'success');
            // Here you would reload the alerts data
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

    // Create Alert Modal logic
    const createAlertModal = document.getElementById('createAlertModal');
    const openCreateAlertBtn = document.getElementById('openCreateAlertModal');
    if (openCreateAlertBtn) {
        openCreateAlertBtn.onclick = function() {
            createAlertModal.style.display = 'block';
        };
    }
    function closeCreateAlertModal() {
        createAlertModal.style.display = 'none';
        clearCreateAlertForm();
    }
    function clearCreateAlertForm() {
        document.getElementById('createAlertForm').reset();
        document.getElementById('error_title').textContent = '';
        document.getElementById('error_message').textContent = '';
        document.getElementById('error_severity').textContent = '';
        document.getElementById('error_category').textContent = '';
        document.getElementById('error_assigned_to').textContent = '';
    }
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === createAlertModal) {
            closeCreateAlertModal();
        }
    });
    // AJAX form submission
    document.getElementById('createAlertForm').onsubmit = function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        // Clear errors
        clearCreateAlertForm();
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                // Show validation errors
                for (const key in data.errors) {
                    if (data.errors.hasOwnProperty(key)) {
                        document.getElementById('error_' + key).textContent = data.errors[key][0];
                    }
                }
            } else if (data.success) {
                showNotification('System alert created successfully!', 'success');
                closeCreateAlertModal();
                setTimeout(() => { window.location.reload(); }, 1000);
            } else {
                showNotification('Failed to create alert.', 'error');
            }
        })
        .catch(() => {
            showNotification('Failed to create alert.', 'error');
        });
    };
</script>
@endsection
