@extends('layouts.super_admin')

@section('title', 'System Statistics - Super Admin Dashboard')

@section('content')
<div class="system-stats-container">
    <!-- System Stats Header -->
    <div class="system-stats-header">
        <div class="stats-header-content">
            <h1><i class="fas fa-chart-line"></i> System Statistics Dashboard</h1>
            <p>Comprehensive system performance metrics and analytics</p>
        </div>
        <div class="stats-header-actions">
            <button class="btn btn-primary" onclick="refreshSystemStats()">
                <i class="fas fa-sync-alt"></i> Refresh Data
            </button>
            <button class="btn btn-secondary" onclick="exportSystemReport()">
                <i class="fas fa-download"></i> Export Report
            </button>
            <button class="btn btn-info" onclick="scheduleReport()">
                <i class="fas fa-clock"></i> Schedule
            </button>
        </div>
    </div>

    <!-- System Overview Cards -->
    <div class="system-overview-grid">
        <div class="overview-card server-performance">
            <div class="overview-header">
                <div class="overview-icon">
                    <i class="fas fa-server"></i>
                </div>
                <div class="overview-info">
                    <h3>Server Performance</h3>
                    <span class="status-healthy">Excellent</span>
                </div>
            </div>
            <div class="overview-metrics">
                <div class="metric-item">
                    <span class="metric-label">CPU Usage</span>
                    <div class="metric-value">
                        <span class="metric-number">24%</span>
                        <div class="progress-bar">
                            <div class="progress-fill" style="width: 24%; background: linear-gradient(45deg, #10B981, #059669);"></div>
                        </div>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Memory Usage</span>
                    <div class="metric-value">
                        <span class="metric-number">67%</span>
                        <div class="progress-bar">
                            <div class="progress-fill memory" style="width: 67%; background: linear-gradient(45deg, #F59E0B, #D97706);"></div>
                        </div>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Disk Usage</span>
                    <div class="metric-value">
                        <span class="metric-number">45%</span>
                        <div class="progress-bar">
                            <div class="progress-fill disk" style="width: 45%; background: linear-gradient(45deg, #3B82F6, #1D4ED8);"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-card database-status">
            <div class="overview-header">
                <div class="overview-icon">
                    <i class="fas fa-database"></i>
                </div>
                <div class="overview-info">
                    <h3>Database Status</h3>
                    <span class="status-healthy">Optimized</span>
                </div>
            </div>
            <div class="overview-metrics">
                <div class="metric-item">
                    <span class="metric-label">Queries/sec</span>
                    <div class="metric-value">
                        <span class="metric-number">1,247</span>
                        <span class="metric-trend positive">
                            <i class="fas fa-arrow-up"></i> +12%
                        </span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Avg Response</span>
                    <div class="metric-value">
                        <span class="metric-number">23ms</span>
                        <span class="metric-trend positive">
                            <i class="fas fa-arrow-down"></i> -8%
                        </span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Active Connections</span>
                    <div class="metric-value">
                        <span class="metric-number">156</span>
                        <span class="metric-trend neutral">
                            <i class="fas fa-minus"></i> Stable
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-card user-activity">
            <div class="overview-header">
                <div class="overview-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="overview-info">
                    <h3>User Activity</h3>
                    <span class="status-normal">Normal</span>
                </div>
            </div>
            <div class="overview-metrics">
                <div class="metric-item">
                    <span class="metric-label">Active Users</span>
                    <div class="metric-value">
                        <span class="metric-number">1,024</span>
                        <span class="metric-trend positive">
                            <i class="fas fa-arrow-up"></i> +5%
                        </span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Peak Today</span>
                    <div class="metric-value">
                        <span class="metric-number">1,456</span>
                        <span class="metric-time">at 14:30</span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">New Registrations</span>
                    <div class="metric-value">
                        <span class="metric-number">23</span>
                        <span class="metric-trend positive">
                            <i class="fas fa-arrow-up"></i> +15%
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="overview-card system-health">
            <div class="overview-header">
                <div class="overview-icon">
                    <i class="fas fa-heartbeat"></i>
                </div>
                <div class="overview-info">
                    <h3>System Health</h3>
                    <span class="status-excellent">Excellent</span>
                </div>
            </div>
            <div class="overview-metrics">
                <div class="metric-item">
                    <span class="metric-label">Uptime</span>
                    <div class="metric-value">
                        <span class="metric-number">99.8%</span>
                        <span class="metric-time">45d 12h</span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Error Rate</span>
                    <div class="metric-value">
                        <span class="metric-number">0.02%</span>
                        <span class="metric-trend positive">
                            <i class="fas fa-arrow-down"></i> -50%
                        </span>
                    </div>
                </div>
                <div class="metric-item">
                    <span class="metric-label">Last Incident</span>
                    <div class="metric-value">
                        <span class="metric-number">12</span>
                        <span class="metric-time">days ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Real-time Charts Section -->
    <div class="charts-section">
        <div class="charts-header">
            <h2><i class="fas fa-chart-area"></i> Real-time Analytics</h2>
            <div class="time-range-selector">
                <button class="time-btn active" data-range="1h">1H</button>
                <button class="time-btn" data-range="6h">6H</button>
                <button class="time-btn" data-range="24h">24H</button>
                <button class="time-btn" data-range="7d">7D</button>
                <button class="time-btn" data-range="30d">30D</button>
            </div>
        </div>

        <div class="charts-grid">
            <div class="chart-container">
                <div class="chart-header">
                    <h3>System Performance Trends</h3>
                    <div class="chart-legend">
                        <span class="legend-item cpu">
                            <span class="legend-color"></span> CPU
                        </span>
                        <span class="legend-item memory">
                            <span class="legend-color"></span> Memory
                        </span>
                        <span class="legend-item disk">
                            <span class="legend-color"></span> Disk I/O
                        </span>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="performanceTrendChart" width="600" height="300"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3>User Activity Over Time</h3>
                    <div class="chart-info">
                        <span class="info-item">Peak: <strong>1,456</strong></span>
                        <span class="info-item">Average: <strong>892</strong></span>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="userActivityChart" width="600" height="300"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3>Database Performance</h3>
                    <div class="chart-controls">
                        <button class="chart-control-btn active">Queries</button>
                        <button class="chart-control-btn">Response Time</button>
                        <button class="chart-control-btn">Connections</button>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="databaseChart" width="600" height="300"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-header">
                    <h3>Error & Alert Distribution</h3>
                    <div class="chart-summary">
                        <span class="summary-item critical">
                            <span class="summary-count">2</span>
                            <span class="summary-label">Critical</span>
                        </span>
                        <span class="summary-item warning">
                            <span class="summary-count">7</span>
                            <span class="summary-label">Warnings</span>
                        </span>
                        <span class="summary-item info">
                            <span class="summary-count">23</span>
                            <span class="summary-label">Info</span>
                        </span>
                    </div>
                </div>
                <div class="chart-body">
                    <canvas id="alertsChart" width="600" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- System Logs Section -->
    <div class="logs-section">
        <div class="logs-header">
            <h2><i class="fas fa-file-alt"></i> Recent System Logs</h2>
            <div class="logs-controls">
                <select class="log-filter" id="logTypeFilter">
                    <option value="all">All Types</option>
                    <option value="error">Errors</option>
                    <option value="warning">Warnings</option>
                    <option value="info">Information</option>
                    <option value="debug">Debug</option>
                </select>
                <input type="text" class="log-search" placeholder="Search logs..." id="logSearch">
                <button class="btn btn-primary" onclick="exportLogs()">
                    <i class="fas fa-download"></i> Export
                </button>
            </div>
        </div>

        <div class="logs-container">
            <div class="log-entry error">
                <div class="log-timestamp">2025-07-22 14:32:15</div>
                <div class="log-level error">ERROR</div>
                <div class="log-source">Database</div>
                <div class="log-message">Connection timeout to secondary database server</div>
                <div class="log-actions">
                    <button class="log-action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="log-action-btn" title="Mark as Resolved">
                        <i class="fas fa-check"></i>
                    </button>
                </div>
            </div>

            <div class="log-entry warning">
                <div class="log-timestamp">2025-07-22 14:28:42</div>
                <div class="log-level warning">WARN</div>
                <div class="log-source">Auth</div>
                <div class="log-message">Multiple failed login attempts from IP: 192.168.1.25</div>
                <div class="log-actions">
                    <button class="log-action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button class="log-action-btn" title="Block IP">
                        <i class="fas fa-ban"></i>
                    </button>
                </div>
            </div>

            <div class="log-entry info">
                <div class="log-timestamp">2025-07-22 14:25:17</div>
                <div class="log-level info">INFO</div>
                <div class="log-source">System</div>
                <div class="log-message">System backup completed successfully (Size: 2.3GB)</div>
                <div class="log-actions">
                    <button class="log-action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="log-entry info">
                <div class="log-timestamp">2025-07-22 14:20:33</div>
                <div class="log-level info">INFO</div>
                <div class="log-source">Users</div>
                <div class="log-message">New user registration: john.doe@company.com</div>
                <div class="log-actions">
                    <button class="log-action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>

            <div class="log-entry debug">
                <div class="log-timestamp">2025-07-22 14:15:28</div>
                <div class="log-level debug">DEBUG</div>
                <div class="log-source">Cache</div>
                <div class="log-message">Cache cleared for user sessions (Affected: 234 entries)</div>
                <div class="log-actions">
                    <button class="log-action-btn" title="View Details">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="logs-pagination">
            <div class="pagination-info">
                Showing 1-5 of 1,247 log entries
            </div>
            <div class="pagination-controls">
                <button class="pagination-btn" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="pagination-btn active">1</button>
                <button class="pagination-btn">2</button>
                <button class="pagination-btn">3</button>
                <button class="pagination-btn">...</button>
                <button class="pagination-btn">249</button>
                <button class="pagination-btn">
                    <i class="fas fa-chevron-right"></i>
                </button>
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
        --gray-50: #F9FAFB;
        --gray-100: #F3F4F6;
        --gray-200: #E5E7EB;
        --gray-300: #D1D5DB;
        --gray-400: #9CA3AF;
        --gray-500: #6B7280;
        --gray-600: #4B5563;
        --gray-700: #374151;
        --gray-800: #1F2937;
        --gray-900: #111827;
    }

    .system-stats-container {
        padding: 2rem;
        background: linear-gradient(135deg, var(--bg-secondary) 0%, var(--bg-primary) 100%);
        min-height: 100vh;
    }

    /* System Stats Header */
    .system-stats-header {
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

    .stats-header-content h1 {
        font-size: 2rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.5rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .stats-header-content h1 i {
        color: var(--redcode-primary);
        font-size: 1.75rem;
    }

    .stats-header-content p {
        color: var(--text-secondary);
        font-size: 1rem;
        margin: 0;
    }

    .stats-header-actions {
        display: flex;
        gap: 1rem;
        flex-wrap: wrap;
    }

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

    .btn-secondary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(107, 114, 128, 0.3);
    }

    .btn-info {
        background: linear-gradient(135deg, var(--redcode-blue), #1E40AF);
        color: white;
        box-shadow: 0 4px 15px rgba(37, 99, 235, 0.2);
    }

    .btn-info:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
    }

    /* System Overview Grid */
    .system-overview-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .overview-card {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .overview-card::before {
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

    .overview-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
    }

    .overview-card:hover::before {
        transform: scaleX(1);
    }

    .overview-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .overview-icon {
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

    .server-performance .overview-icon {
        background: linear-gradient(135deg, #10B981, #059669);
    }

    .database-status .overview-icon {
        background: linear-gradient(135deg, #3B82F6, #1D4ED8);
    }

    .user-activity .overview-icon {
        background: linear-gradient(135deg, #F59E0B, #D97706);
    }

    .system-health .overview-icon {
        background: linear-gradient(135deg, var(--redcode-primary), var(--redcode-accent));
    }

    .overview-info h3 {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--text-primary);
        margin-bottom: 0.25rem;
    }

    .status-healthy {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-normal {
        background: rgba(37, 99, 235, 0.1);
        color: #2563EB;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .status-excellent {
        background: rgba(220, 38, 38, 0.1);
        color: var(--redcode-primary);
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 600;
    }

    .overview-metrics {
        display: flex;
        flex-direction: column;
        gap: 1rem;
    }

    .metric-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .metric-label {
        font-size: 0.9rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .metric-value {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .metric-number {
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--text-primary);
    }

    .progress-bar {
        width: 120px;
        height: 8px;
        background: var(--gray-200);
        border-radius: 4px;
        overflow: hidden;
    }

    .progress-fill {
        height: 100%;
        border-radius: 4px;
        transition: width 0.3s ease;
    }

    .metric-trend {
        display: flex;
        align-items: center;
        gap: 0.25rem;
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
    }

    .metric-trend.positive {
        background: rgba(16, 185, 129, 0.1);
        color: #10B981;
    }

    .metric-trend.neutral {
        background: rgba(107, 114, 128, 0.1);
        color: #6B7280;
    }

    .metric-time {
        font-size: 0.8rem;
        color: var(--text-light);
        font-weight: 500;
    }

    /* Charts Section */
    .charts-section {
        margin-bottom: 2rem;
    }

    .charts-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding: 1.5rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .charts-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .charts-header h2 i {
        color: var(--redcode-primary);
    }

    .time-range-selector {
        display: flex;
        gap: 0.5rem;
        background: var(--bg-secondary);
        padding: 0.25rem;
        border-radius: 8px;
    }

    .time-btn {
        padding: 0.5rem 1rem;
        border: none;
        background: transparent;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .time-btn.active,
    .time-btn:hover {
        background: white;
        color: var(--redcode-primary);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .charts-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
        gap: 1.5rem;
    }

    .chart-container {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .chart-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .chart-header h3 {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--text-primary);
        margin: 0;
    }

    .chart-legend {
        display: flex;
        gap: 1rem;
    }

    .legend-item {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .legend-color {
        width: 12px;
        height: 12px;
        border-radius: 2px;
    }

    .legend-item.cpu .legend-color {
        background: #10B981;
    }

    .legend-item.memory .legend-color {
        background: #F59E0B;
    }

    .legend-item.disk .legend-color {
        background: #3B82F6;
    }

    .chart-info {
        display: flex;
        gap: 1rem;
    }

    .info-item {
        font-size: 0.8rem;
        color: var(--text-secondary);
    }

    .chart-controls {
        display: flex;
        gap: 0.5rem;
    }

    .chart-control-btn {
        padding: 0.4rem 0.8rem;
        border: 1px solid var(--border-light);
        background: white;
        border-radius: 6px;
        font-size: 0.8rem;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
    }

    .chart-control-btn.active,
    .chart-control-btn:hover {
        background: var(--redcode-primary);
        color: white;
        border-color: var(--redcode-primary);
    }

    .chart-summary {
        display: flex;
        gap: 1rem;
    }

    .summary-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.25rem;
    }

    .summary-count {
        font-size: 1.2rem;
        font-weight: 700;
    }

    .summary-label {
        font-size: 0.7rem;
        text-transform: uppercase;
        font-weight: 600;
    }

    .summary-item.critical {
        color: var(--redcode-primary);
    }

    .summary-item.warning {
        color: var(--redcode-orange);
    }

    .summary-item.info {
        color: var(--redcode-blue);
    }

    .chart-body {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 300px;
        background: var(--bg-secondary);
        border-radius: 8px;
    }

    /* System Logs Section */
    .logs-section {
        background: white;
        border-radius: 16px;
        padding: 2rem;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid var(--border-light);
    }

    .logs-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 1px solid var(--border-light);
    }

    .logs-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-primary);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .logs-header h2 i {
        color: var(--redcode-primary);
    }

    .logs-controls {
        display: flex;
        gap: 1rem;
        align-items: center;
    }

    .log-filter,
    .log-search {
        padding: 0.5rem 1rem;
        border: 1px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.9rem;
        background: white;
        color: var(--text-primary);
    }

    .log-filter:focus,
    .log-search:focus {
        outline: none;
        border-color: var(--redcode-primary);
        box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
    }

    .logs-container {
        display: flex;
        flex-direction: column;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
    }

    .log-entry {
        display: grid;
        grid-template-columns: 150px 80px 120px 1fr auto;
        gap: 1rem;
        align-items: center;
        padding: 1rem;
        background: var(--bg-secondary);
        border-radius: 8px;
        border-left: 4px solid transparent;
        transition: all 0.3s ease;
    }

    .log-entry:hover {
        background: white;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .log-entry.error {
        border-left-color: var(--redcode-primary);
    }

    .log-entry.warning {
        border-left-color: var(--redcode-orange);
    }

    .log-entry.info {
        border-left-color: var(--redcode-blue);
    }

    .log-entry.debug {
        border-left-color: var(--gray-500);
    }

    .log-timestamp {
        font-size: 0.8rem;
        color: var(--text-secondary);
        font-family: monospace;
    }

    .log-level {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 0.25rem 0.5rem;
        border-radius: 12px;
        text-align: center;
    }

    .log-level.error {
        background: rgba(220, 38, 38, 0.1);
        color: var(--redcode-primary);
    }

    .log-level.warning {
        background: rgba(217, 119, 6, 0.1);
        color: var(--redcode-orange);
    }

    .log-level.info {
        background: rgba(37, 99, 235, 0.1);
        color: var(--redcode-blue);
    }

    .log-level.debug {
        background: rgba(107, 114, 128, 0.1);
        color: var(--gray-500);
    }

    .log-source {
        font-size: 0.85rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .log-message {
        font-size: 0.9rem;
        color: var(--text-primary);
        line-height: 1.4;
    }

    .log-actions {
        display: flex;
        gap: 0.5rem;
    }

    .log-action-btn {
        width: 32px;
        height: 32px;
        border: none;
        background: var(--bg-primary);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
        border: 1px solid var(--border-light);
    }

    .log-action-btn:hover {
        background: var(--redcode-primary);
        color: white;
        border-color: var(--redcode-primary);
    }

    .logs-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 1rem;
        border-top: 1px solid var(--border-light);
    }

    .pagination-info {
        font-size: 0.9rem;
        color: var(--text-secondary);
    }

    .pagination-controls {
        display: flex;
        gap: 0.5rem;
    }

    .pagination-btn {
        width: 36px;
        height: 36px;
        border: 1px solid var(--border-light);
        background: white;
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
        color: var(--text-secondary);
        font-size: 0.9rem;
        font-weight: 500;
    }

    .pagination-btn:hover:not(:disabled) {
        background: var(--redcode-primary);
        color: white;
        border-color: var(--redcode-primary);
    }

    .pagination-btn.active {
        background: var(--redcode-primary);
        color: white;
        border-color: var(--redcode-primary);
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
</style>

<script>
    // Initialize charts when page loads
    document.addEventListener('DOMContentLoaded', function() {
        initializeCharts();
        initializeLogFilters();
        startRealTimeUpdates();
    });

    function initializeCharts() {
        // Initialize performance trend chart
        const performanceCtx = document.getElementById('performanceTrendChart');
        if (performanceCtx) {
            const ctx = performanceCtx.getContext('2d');
            ctx.fillStyle = '#f3f4f6';
            ctx.fillRect(0, 0, 600, 300);
            ctx.fillStyle = '#6b7280';
            ctx.font = '16px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Performance Trend Chart', 300, 150);
        }

        // Initialize user activity chart
        const userActivityCtx = document.getElementById('userActivityChart');
        if (userActivityCtx) {
            const ctx = userActivityCtx.getContext('2d');
            ctx.fillStyle = '#f3f4f6';
            ctx.fillRect(0, 0, 600, 300);
            ctx.fillStyle = '#6b7280';
            ctx.font = '16px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('User Activity Chart', 300, 150);
        }

        // Initialize database chart
        const databaseCtx = document.getElementById('databaseChart');
        if (databaseCtx) {
            const ctx = databaseCtx.getContext('2d');
            ctx.fillStyle = '#f3f4f6';
            ctx.fillRect(0, 0, 600, 300);
            ctx.fillStyle = '#6b7280';
            ctx.font = '16px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Database Performance Chart', 300, 150);
        }

        // Initialize alerts chart
        const alertsCtx = document.getElementById('alertsChart');
        if (alertsCtx) {
            const ctx = alertsCtx.getContext('2d');
            ctx.fillStyle = '#f3f4f6';
            ctx.fillRect(0, 0, 600, 300);
            ctx.fillStyle = '#6b7280';
            ctx.font = '16px Inter, sans-serif';
            ctx.textAlign = 'center';
            ctx.fillText('Alerts Distribution Chart', 300, 150);
        }
    }

    function initializeLogFilters() {
        const logTypeFilter = document.getElementById('logTypeFilter');
        const logSearch = document.getElementById('logSearch');

        if (logTypeFilter) {
            logTypeFilter.addEventListener('change', filterLogs);
        }

        if (logSearch) {
            logSearch.addEventListener('input', filterLogs);
        }
    }

    function filterLogs() {
        const logType = document.getElementById('logTypeFilter').value;
        const searchTerm = document.getElementById('logSearch').value.toLowerCase();
        const logEntries = document.querySelectorAll('.log-entry');

        logEntries.forEach(entry => {
            const entryType = entry.classList[1]; // error, warning, info, debug
            const entryText = entry.querySelector('.log-message').textContent.toLowerCase();
            
            const typeMatch = logType === 'all' || entryType === logType;
            const textMatch = searchTerm === '' || entryText.includes(searchTerm);
            
            entry.style.display = typeMatch && textMatch ? 'grid' : 'none';
        });
    }

    function startRealTimeUpdates() {
        // Update metrics every 30 seconds
        setInterval(updateSystemMetrics, 30000);
        
        // Update charts every 2 minutes
        setInterval(updateCharts, 120000);
    }

    function updateSystemMetrics() {
        // Simulate real-time metric updates
        const cpuUsage = Math.floor(Math.random() * 40) + 15; // 15-55%
        const memoryUsage = Math.floor(Math.random() * 30) + 50; // 50-80%
        const diskUsage = Math.floor(Math.random() * 20) + 35; // 35-55%
        
        // Update progress bars
        const cpuBar = document.querySelector('.server-performance .progress-fill');
        const memoryBar = document.querySelector('.server-performance .progress-fill.memory');
        const diskBar = document.querySelector('.server-performance .progress-fill.disk');
        
        if (cpuBar) {
            cpuBar.style.width = cpuUsage + '%';
            cpuBar.parentElement.previousElementSibling.querySelector('.metric-number').textContent = cpuUsage + '%';
        }
        
        if (memoryBar) {
            memoryBar.style.width = memoryUsage + '%';
            memoryBar.parentElement.previousElementSibling.querySelector('.metric-number').textContent = memoryUsage + '%';
        }
        
        if (diskBar) {
            diskBar.style.width = diskUsage + '%';
            diskBar.parentElement.previousElementSibling.querySelector('.metric-number').textContent = diskUsage + '%';
        }
    }

    function updateCharts() {
        // Placeholder for chart updates - would integrate with real charting library
        console.log('Updating charts with new data...');
    }

    function refreshSystemStats() {
        // Show loading state
        document.body.style.cursor = 'wait';
        
        // Simulate refresh delay
        setTimeout(() => {
            updateSystemMetrics();
            updateCharts();
            document.body.style.cursor = 'default';
            
            // Show success message
            showNotification('System statistics refreshed successfully', 'success');
        }, 2000);
    }

    function exportSystemReport() {
        // Simulate export functionality
        showNotification('System report export initiated', 'info');
    }

    function scheduleReport() {
        // Simulate schedule functionality
        showNotification('Report scheduling dialog would open here', 'info');
    }

    function exportLogs() {
        // Simulate log export
        showNotification('System logs export initiated', 'info');
    }

    function showNotification(message, type = 'info') {
        // Create notification element
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
            border-left: 4px solid ${type === 'success' ? '#10B981' : type === 'error' ? '#DC2626' : '#2563EB'};
        `;
        
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

    // Time range selector functionality
    document.querySelectorAll('.time-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            document.querySelectorAll('.time-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const range = this.dataset.range;
            // Update charts based on selected time range
            console.log('Selected time range:', range);
        });
    });

    // Chart control buttons
    document.querySelectorAll('.chart-control-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const container = this.closest('.chart-container');
            container.querySelectorAll('.chart-control-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Update chart based on selected control
            console.log('Selected chart control:', this.textContent);
        });
    });
</script>
@endsection
