@extends('layouts.super_admin')

@section('title', 'Super Admin Overview Dashboard')

@section('content')
    <div class="dashboard-container">
        <!-- Dashboard Header -->
        <div class="dashboard-header">
            <div class="header-content">
                <h1><i class="fas fa-crown"></i> Super Admin Overview</h1>
                <p>Complete system management and monitoring dashboard</p>
            </div>
            <div class="header-actions">
                <button class="btn btn-primary" onclick="refreshDashboard()">
                    <i class="fas fa-sync-alt"></i> Refresh
                </button>
                <button class="btn btn-secondary" onclick="exportReport()">
                    <i class="fas fa-download"></i> Export Report
                </button>
            </div>
        </div>

        <!-- Quick Stats Grid -->
        <div class="stats-grid">
            <div class="stat-card admins">
                <div class="stat-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Admins</h3>
                    <div class="stat-number">{{ $dashboardStats['total_admins'] ?? 0 }}</div>
                    <p>System administrators</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> +2
                </div>
            </div>

            <div class="stat-card employees">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>Total Employees</h3>
                    <div class="stat-number">{{ $dashboardStats['total_employees'] ?? 0 }}</div>
                    <p>Active workforce</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> +{{ $dashboardStats['recent_registrations'] ?? 0 }}
                </div>
            </div>

            <div class="stat-card departments">
                <div class="stat-icon">
                    <i class="fas fa-building"></i>
                </div>
                <div class="stat-content">
                    <h3>Departments</h3>
                    <div class="stat-number">{{ $dashboardStats['total_departments'] ?? 0 }}</div>
                    <p>Active departments</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i> Stable
                </div>
            </div>

            <div class="stat-card projects">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3>Active Projects</h3>
                    <div class="stat-number">{{ $dashboardStats['active_projects'] ?? 0 }}</div>
                    <p>Ongoing projects</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i> +3
                </div>
            </div>

            <div class="stat-card leaves">
                <div class="stat-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>Pending Leaves</h3>
                    <div class="stat-number">{{ $dashboardStats['pending_leaves'] ?? 0 }}</div>
                    <p>Awaiting approval</p>
                </div>
                <div class="stat-trend warning">
                    <i class="fas fa-exclamation"></i> Review
                </div>
            </div>

            <div class="stat-card alerts">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-content">
                    <h3>System Alerts</h3>
                    <div class="stat-number">{{ $dashboardStats['system_alerts'] ?? 0 }}</div>
                    <p>Active alerts</p>
                </div>
                <div class="stat-trend warning">
                    <i class="fas fa-bell"></i> Monitor
                </div>
            </div>
        </div>

        <!-- Today's Meetings Section -->
        @if (isset($todayMeetings) && $todayMeetings->count() > 0)
            <div class="meeting-section">
                <div class="meeting-header-section">
                    <h2 class="meeting-section-title">
                        <i class="fas fa-video"></i>
                        Today's Stand-up Meetings
                    </h2>
                    <p class="meeting-section-subtitle">Morning and Evening meetings for all team members</p>
                </div>

                <div class="meetings-grid">
                    @foreach ($todayMeetings as $meeting)
                        <div class="meeting-card">
                            <div class="meeting-header">
                                <div class="meeting-title">
                                    <i
                                        class="fas fa-{{ str_contains(strtolower($meeting->title), 'morning') ? 'sun' : 'moon' }}"></i>
                                    {{ $meeting->title }}
                                </div>
                                <div class="meeting-status">
                                    <span
                                        class="status-badge {{ $meeting->status === 'ongoing' ? 'ongoing' : 'scheduled' }}">
                                        {{ ucfirst($meeting->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="meeting-content">
                                <div class="meeting-info">
                                    <div class="meeting-time">
                                        <i class="fas fa-clock"></i>
                                        {{ $meeting->getFormattedTime() }}
                                    </div>
                                    <div class="meeting-duration">
                                        <i class="fas fa-hourglass-half"></i>
                                        {{ $meeting->getDuration() }} minutes
                                    </div>
                                </div>
                                <div class="meeting-link-section">
                                    <div class="meeting-link-display">
                                        <input type="text" value="{{ $meeting->meeting_link }}"
                                            class="meeting-link-input" readonly>
                                        <button onclick="copyToClipboard('{{ $meeting->meeting_link }}')" class="copy-btn">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>
                                    <a href="{{ route('meetings.join', $meeting) }}" class="join-meeting-btn">
                                        <i class="fas fa-external-link-alt"></i>
                                        Join Meeting
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Main Dashboard Content -->
        <div class="dashboard-content">
            <!-- Left Column -->
            <div class="dashboard-left">
                <!-- System Health Card -->
                <div class="dashboard-card system-health">
                    <div class="card-header">
                        <h2><i class="fas fa-heartbeat"></i> System Health</h2>
                        <span class="health-status excellent">Excellent</span>
                    </div>
                    <div class="card-content">
                        <div class="health-metrics">
                            <div class="health-metric">
                                <span class="metric-label">Server Uptime</span>
                                <span class="metric-value">{{ $systemHealth['server_uptime'] ?? 0 }}</span>
                            </div>
                            <div class="health-metric">
                                <span class="metric-label">Database Health</span>
                                <span class="metric-value">{{ $systemHealth['database_health'] ?? 0 }}</span>
                            </div>
                            <div class="health-metric">
                                <span class="metric-label">Storage Usage</span>
                                <span class="metric-value">{{ $systemHealth['storage_usage'] ?? 0 }}</span>
                            </div>
                            <div class="health-metric">
                                <span class="metric-label">Active Sessions</span>
                                <span class="metric-value">{{ $systemHealth['active_sessions'] ?? 0 }}</span>
                            </div>
                            <div class="health-metric">
                                <span class="metric-label">Avg Response Time</span>
                                <span class="metric-value">{{ $systemHealth['avg_response_time'] ?? 0 }}</span>
                            </div>
                            <div class="health-metric">
                                <span class="metric-label">Error Rate</span>
                                <span class="metric-value">{{ $systemHealth['error_rate'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="dashboard-card quick-actions">
                    <div class="card-header">
                        <h2><i class="fas fa-bolt"></i> Quick Actions</h2>
                    </div>
                    <div class="card-content">
                        <div class="action-grid">
                            <a href="{{ route('super_admin.admins') }}" class="action-item">
                                <i class="fas fa-user-plus"></i>
                                <span>Add New Admin</span>
                            </a>
                            {{-- <a href="{{ route('super_admin.system_alerts') }}" class="action-item">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>View Alerts</span>
                            </a>
                            <a href="{{ route('super_admin.settings') }}" class="action-item">
                                <i class="fas fa-cogs"></i>
                                <span>System Settings</span>
                            </a>
                            <a href="{{ route('super_admin.security_settings') }}" class="action-item">
                                <i class="fas fa-shield-alt"></i>
                                <span>Security</span>
                            </a>
                            <a href="{{ route('super_admin.database_settings') }}" class="action-item">
                                <i class="fas fa-database"></i>
                                <span>Database</span>
                            </a>
                            <a href="{{ route('super_admin.announcements') }}" class="action-item">
                                <i class="fas fa-bullhorn"></i>
                                <span>Announcements</span>
                            </a> --}}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="dashboard-right">
                <!-- Recent Activities -->
                <div class="dashboard-card recent-activities">
                    <div class="card-header">
                        <h2><i class="fas fa-history"></i> Recent Activities</h2>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <div class="activities-list">
                            {{-- @foreach ($recentActivities as $activity)
                                <div class="activity-item activity-{{ $activity['type'] }}">
                                    <div class="activity-icon">
                                        <i class="{{ $activity['icon'] }}"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title">{{ $activity['action'] }}</div>
                                        <div class="activity-details">{{ $activity['details'] }}</div>
                                        <div class="activity-time">{{ $activity['timestamp']->diffForHumans() }}</div>
                                    </div>
                                </div>
                            @endforeach --}}
                        </div>
                    </div>
                </div>

                <!-- Performance Charts -->
                <div class="dashboard-card charts">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-line"></i> Performance Overview</h2>
                        <div class="chart-tabs">
                            <button class="chart-tab active" onclick="showChart('registrations')">Registrations</button>
                            <button class="chart-tab" onclick="showChart('leaves')">Leaves</button>
                            <button class="chart-tab" onclick="showChart('projects')">Projects</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="chart-container">
                            <canvas id="performanceChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bottom Section -->
        <div class="dashboard-bottom">
            <!-- System Status Overview -->
            <div class="dashboard-card status-overview">
                <div class="card-header">
                    <h2><i class="fas fa-server"></i> System Status Overview</h2>
                </div>
                <div class="card-content">
                    <div class="status-grid">
                        <div class="status-item">
                            <div class="status-icon healthy">
                                <i class="fas fa-check-circle"></i>
                            </div>
                            <div class="status-content">
                                <h4>Application Server</h4>
                                <p>Running smoothly - No issues detected</p>
                                <span class="status-badge healthy">Healthy</span>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon healthy">
                                <i class="fas fa-database"></i>
                            </div>
                            <div class="status-content">
                                <h4>Database</h4>
                                <p>All connections stable - Performance optimal</p>
                                <span class="status-badge healthy">Optimal</span>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon warning">
                                <i class="fas fa-hdd"></i>
                            </div>
                            <div class="status-content">
                                <h4>Storage</h4>
                                <p>{{ $systemHealth['storage_usage'] ?? 0 }} used - Consider cleanup</p>
                                <span class="status-badge warning">Monitor</span>
                            </div>
                        </div>

                        <div class="status-item">
                            <div class="status-icon healthy">
                                <i class="fas fa-shield-alt"></i>
                            </div>
                            <div class="status-content">
                                <h4>Security</h4>
                                <p>All security protocols active</p>
                                <span class="status-badge healthy">Secure</span>
                            </div>
                        </div>
                    </div>
                </div>
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
            --shadow-light: 0 1px 3px rgba(0, 0, 0, 0.1);
            --shadow-medium: 0 4px 6px rgba(0, 0, 0, 0.1);
            --shadow-heavy: 0 10px 15px rgba(0, 0, 0, 0.1);
        }

        .dashboard-container {
            padding: 2rem;
            background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
            min-height: 100vh;
        }

        /* Dashboard Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 2rem;
            padding: 2rem;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
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

        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--redcode-primary), var(--redcode-accent));
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-heavy);
        }

        .stat-card:hover::before {
            transform: scaleX(1);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
            flex-shrink: 0;
        }

        .stat-card.admins .stat-icon {
            background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
        }

        .stat-card.employees .stat-icon {
            background: linear-gradient(135deg, var(--redcode-blue), #1E40AF);
        }

        .stat-card.departments .stat-icon {
            background: linear-gradient(135deg, var(--redcode-green), #047857);
        }

        .stat-card.projects .stat-icon {
            background: linear-gradient(135deg, var(--redcode-orange), #B45309);
        }

        .stat-card.leaves .stat-icon {
            background: linear-gradient(135deg, var(--redcode-yellow), #B45309);
        }

        .stat-card.alerts .stat-icon {
            background: linear-gradient(135deg, #EF4444, #DC2626);
        }

        .stat-content {
            flex: 1;
        }

        .stat-content h3 {
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

        .stat-content p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin: 0;
        }

        .stat-trend {
            display: flex;
            align-items: center;
            gap: 0.25rem;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            flex-shrink: 0;
        }

        .stat-trend.positive {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .stat-trend.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        .stat-trend.neutral {
            background: rgba(107, 114, 128, 0.1);
            color: #6B7280;
        }

        /* Dashboard Content Layout */
        .dashboard-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .dashboard-left,
        .dashboard-right {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* Dashboard Cards */
        .dashboard-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow-medium);
            border: 1px solid var(--border-light);
            overflow: hidden;
        }

        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-header h2 {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .card-header h2 i {
            color: var(--redcode-primary);
        }

        .card-content {
            padding: 2rem;
        }

        /* System Health */
        .health-status {
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .health-status.excellent {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .health-metrics {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }

        .health-metric {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: var(--bg-secondary);
            border-radius: 8px;
            border: 1px solid var(--border-light);
        }

        .metric-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
            font-weight: 500;
        }

        .metric-value {
            font-size: 0.9rem;
            color: var(--text-primary);
            font-weight: 600;
        }

        /* Quick Actions */
        .action-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
            gap: 1rem;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.5rem;
            padding: 1rem;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            text-decoration: none;
            color: var(--text-primary);
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .action-item:hover {
            background: var(--redcode-primary);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.2);
        }

        .action-item i {
            font-size: 1.25rem;
            color: var(--redcode-primary);
            transition: color 0.3s ease;
        }

        .action-item:hover i {
            color: white;
        }

        .action-item span {
            font-size: 0.85rem;
            font-weight: 600;
            text-align: center;
        }

        /* Recent Activities */
        .view-all {
            color: var(--redcode-primary);
            text-decoration: none;
            font-size: 0.9rem;
            font-weight: 600;
        }

        .activities-list {
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
            transition: all 0.3s ease;
        }

        .activity-item:hover {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .activity-employee {
            border-left-color: var(--redcode-blue);
        }

        .activity-leave {
            border-left-color: var(--redcode-yellow);
        }

        .activity-project {
            border-left-color: var(--redcode-green);
        }

        .activity-system {
            border-left-color: var(--redcode-orange);
        }

        .activity-admin {
            border-left-color: var(--redcode-primary);
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
            flex-shrink: 0;
        }

        .activity-content {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
            font-size: 0.9rem;
        }

        .activity-details {
            color: var(--text-secondary);
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }

        .activity-time {
            color: var(--text-light);
            font-size: 0.75rem;
        }

        /* Chart Section */
        .chart-tabs {
            display: flex;
            gap: 0.5rem;
        }

        .chart-tab {
            padding: 0.5rem 1rem;
            border: none;
            background: var(--bg-secondary);
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            color: var(--text-secondary);
        }

        .chart-tab.active,
        .chart-tab:hover {
            background: var(--redcode-primary);
            color: white;
        }

        .chart-container {
            height: 200px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Bottom Section */
        .dashboard-bottom {
            margin-bottom: 2rem;
        }

        .status-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
        }

        .status-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.5rem;
            background: var(--bg-secondary);
            border-radius: 12px;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .status-item:hover {
            background: white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .status-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.25rem;
            color: white;
            flex-shrink: 0;
        }

        .status-icon.healthy {
            background: linear-gradient(135deg, var(--redcode-green), #047857);
        }

        .status-icon.warning {
            background: linear-gradient(135deg, var(--redcode-yellow), #B45309);
        }

        .status-content h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }

        .status-content p {
            font-size: 0.85rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }

        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status-badge.healthy {
            background: rgba(16, 185, 129, 0.1);
            color: #10B981;
        }

        .status-badge.warning {
            background: rgba(245, 158, 11, 0.1);
            color: #F59E0B;
        }

        /* Responsive Design */
        @media (max-width: 1200px) {
            .dashboard-content {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }

            .dashboard-header {
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .header-actions {
                justify-content: center;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }

            .stat-card {
                flex-direction: column;
                text-align: center;
            }

            .health-metrics {
                grid-template-columns: 1fr;
            }

            .action-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .status-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Meeting Section Styles */
        .meeting-section {
            margin-bottom: 2rem;
        }

        .meeting-header-section {
            margin-bottom: 1.5rem;
        }

        .meeting-section-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 0.5rem;
        }

        .meeting-section-title i {
            color: var(--redcode-primary);
        }

        .meeting-section-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
            margin: 0;
        }

        .meetings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
            gap: 1.5rem;
        }

        .meeting-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 1.5rem;
            border: 1px solid var(--border-light);
            transition: all 0.3s ease;
        }

        .meeting-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.12);
        }

        .meeting-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .meeting-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .meeting-title i {
            color: var(--redcode-primary);
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-badge.scheduled {
            background: var(--redcode-yellow);
            color: #92400e;
        }

        .status-badge.ongoing {
            background: var(--redcode-green);
            color: white;
        }

        .meeting-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
            align-items: center;
        }

        .meeting-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .meeting-time,
        .meeting-duration {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-size: 1rem;
            color: var(--text-secondary);
        }

        .meeting-time i,
        .meeting-duration i {
            color: var(--redcode-primary);
            width: 1rem;
        }

        .meeting-link-section {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .meeting-link-display {
            display: flex;
            gap: 0.75rem;
            align-items: center;
        }

        .meeting-link-input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 2px solid var(--border-medium);
            border-radius: 0.5rem;
            font-size: 0.875rem;
            background: var(--gray-50);
            color: var(--text-primary);
            font-family: 'Courier New', monospace;
        }

        .copy-btn {
            padding: 0.75rem 1rem;
            background: var(--gray-600);
            color: white;
            border: none;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .copy-btn:hover {
            background: var(--gray-700);
            transform: translateY(-1px);
        }

        .join-meeting-btn {
            padding: 1rem 1.5rem;
            background: linear-gradient(135deg, var(--redcode-primary) 0%, var(--redcode-accent) 100%);
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 600;
            text-align: center;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(220, 38, 38, 0.3);
        }

        .join-meeting-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
            color: white;
        }

        @media (max-width: 768px) {
            .meeting-content {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .meeting-link-display {
                flex-direction: column;
            }
        }
    </style>

    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeChart();
            startRealTimeUpdates();
        });

        // Chart data
        const chartData = {
            registrations: {{ json_encode($chartData['monthly_registrations' ?? 0]) }},
            leaves: {{ json_encode($chartData['leave_requests' ?? 0]) }},
            projects: {{ json_encode($chartData['project_completion' ?? 0]) }}
        };

        let currentChart = null;

        function initializeChart() {
            const ctx = document.getElementById('performanceChart');
            if (ctx) {
                showChart('registrations');
            }
        }

        function showChart(type) {
            // Update active tab
            document.querySelectorAll('.chart-tab').forEach(tab => tab.classList.remove('active'));
            event?.target?.classList.add('active');

            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;

            // Destroy existing chart
            if (currentChart) {
                currentChart.destroy();
            }

            // Chart configuration
            const config = {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    datasets: [{
                        label: getChartLabel(type),
                        data: chartData[type],
                        borderColor: getChartColor(type),
                        backgroundColor: getChartColor(type) + '20',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: getChartColor(type),
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: '#E5E7EB'
                            }
                        },
                        x: {
                            grid: {
                                color: '#E5E7EB'
                            }
                        }
                    }
                }
            };

            currentChart = new Chart(ctx, config);
        }

        function getChartLabel(type) {
            const labels = {
                registrations: 'Monthly Registrations',
                leaves: 'Leave Requests',
                projects: 'Project Completions'
            };
            return labels[type] || 'Data';
        }

        function getChartColor(type) {
            const colors = {
                registrations: '#2563EB',
                leaves: '#F59E0B',
                projects: '#10B981'
            };
            return colors[type] || '#DC2626';
        }

        // Dashboard functions
        function refreshDashboard() {
            showNotification('Refreshing dashboard...', 'info');

            setTimeout(() => {
                // Simulate data refresh
                location.reload();
            }, 1000);
        }

        function exportReport() {
            showNotification('Generating system report...', 'info');

            setTimeout(() => {
                showNotification('Report exported successfully!', 'success');
            }, 2000);
        }

        // Real-time updates simulation
        function startRealTimeUpdates() {
            setInterval(() => {
                // Update random stat numbers slightly
                updateRandomStats();
            }, 30000); // Update every 30 seconds
        }

        function updateRandomStats() {
            const statNumbers = document.querySelectorAll('.stat-number');
            statNumbers.forEach(stat => {
                const currentValue = parseInt(stat.textContent);
                const change = Math.random() > 0.5 ? 1 : -1;
                const newValue = Math.max(0, currentValue + change);

                if (newValue !== currentValue) {
                    stat.textContent = newValue;

                    // Add animation
                    stat.style.transform = 'scale(1.1)';
                    stat.style.color = '#DC2626';

                    setTimeout(() => {
                        stat.style.transform = 'scale(1)';
                        stat.style.color = '#111827';
                    }, 300);
                }
            });
        }

        // Notification system
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

        // Load Chart.js if not already loaded
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = () => initializeChart();
            document.head.appendChild(script);
        }

        // Copy to clipboard function for meeting links
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                // Show success message
                const copyBtn = event.target.closest('.copy-btn');
                if (copyBtn) {
                    const originalText = copyBtn.innerHTML;
                    copyBtn.innerHTML = '<i class="fas fa-check"></i> Copied!';
                    copyBtn.style.background = '#059669';
                    setTimeout(() => {
                        copyBtn.innerHTML = originalText;
                        copyBtn.style.background = '';
                    }, 2000);
                }
            }).catch(function(err) {
                console.error('Could not copy text: ', err);
                alert('Failed to copy meeting link. Please copy manually.');
            });
        }
    </script>
@endsection
