@extends('layouts.super_admin')

@section('title', 'Super Admin Overview Dashboard')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/dashboard.css') }}">
@section('content')
    <div class="dashboard-container">

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
                                <button onclick="copyToClipboard('{{ $meeting->meeting_link }}', event)"
                                        class="copy-btn">
                                    <i class="fas fa-copy"></i> Copy
                                </button>
                            </div>
                            <a href="{{ route('employee.meetings.join', $meeting) }}" class="join-meeting-btn">
                                <i class="fas fa-external-link-alt"></i> Join Meeting
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif


        <div class="dashboard-content">
            <div class="dashboard-left">

                    <div class="dashboard-card recent-activities">
                        <div class="card-header">
                            <h2><i class="fas fa-history"></i> Recent Activities</h2>
                            <a href="#" class="view-all" onclick="openRecentActivities()">View All</a>

                        </div>
                        <div class="card-content">
                            <div class="activities-list">
                                @forelse ($recentActivities->take(3) as $activity) {{-- Show only latest 3 --}}
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
                                @empty
                                <p class="text-center text-gray-500 mt-4">No recent activities found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Modal (Hidden by Default) -->
                    <div id="activitiesModal" class="modal-overlay" style="display: none;">
                        <div class="modal-page">
                            <div class="modal-header">
                                <h2><i class="fas fa-history"></i> All Recent Activities</h2>
                                <button class="close-modal" onclick="closeRecentActivities()">&times;</button>

                            </div>

                            <div class="modal-body">
                                <div class="activities-list">
                                    @forelse ($recentActivities as $activity)
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
                                    @empty
                                    <p class="text-center text-gray-500 mt-4">No activities found.</p>
                                    @endforelse
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
                        </div>
                    </div>
                </div>
            </div>

            <div class="dashboard-right">
                <!-- Performance Overview Card -->
                <div class="dashboard-card charts">
                    <div class="card-header">
                        <h2><i class="fas fa-chart-line"></i> Performance Overview</h2>
                        <div class="chart-tabs">
                            <button class="chart-tab active" onclick="showChart('registrations', event)">Registrations</button>
                            <button class="chart-tab" onclick="showChart('leaves', event)">Leaves</button>
                            <button class="chart-tab" onclick="showChart('projects', event)">Projects</button>
                        </div>
                    </div>
                    <div class="card-content">
                        <div class="chart-container" style="height: 300px;">
                            <canvas id="performanceChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-bottom">

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


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            initializeChart();
            startRealTimeUpdates();
        });


        const chartData = {
            registrations: @json($chartData['monthly_registrations'] ?? []),
            leaves: @json($chartData['leave_requests'] ?? []),
            projects: @json($chartData['project_completion'] ?? [])
        };

        let currentChart = null;

        function initializeChart() {
            const ctx = document.getElementById('performanceChart');
            if (ctx) {
                showChart('registrations');
            }
        }

        function showChart(type, event = null) {
            // Update active tab
            document.querySelectorAll('.chart-tab').forEach(tab => tab.classList.remove('active'));
            event?.target?.classList.add('active');

            const ctx = document.getElementById('performanceChart');
            if (!ctx) return;

            if (currentChart) {
                currentChart.destroy();
            }

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
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: '#E5E7EB' } },
                        x: { grid: { color: '#E5E7EB' } }
                    }
                }
            };

            currentChart = new Chart(ctx, config);
        }

        function getChartLabel(type) {
            return {
                registrations: 'Monthly Registrations',
                leaves: 'Leave Requests',
                projects: 'Project Completions'
            }[type] || 'Data';
        }

        function getChartColor(type) {
            return {
                registrations: '#2563EB',
                leaves: '#F59E0B',
                projects: '#10B981'
            }[type] || '#DC2626';
        }

        document.addEventListener('DOMContentLoaded', initializeChart);

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

        function openRecentActivities() {
            const modal = document.getElementById('activitiesModal');
            modal.style.display = "flex";
        }

        function closeRecentActivities() {
            const modal = document.getElementById('activitiesModal');
            modal.style.display = "none";
        }

        // close when clicking outside
        document.addEventListener("click", function(e) {
            const modal = document.getElementById('activitiesModal');
            if (e.target === modal) {
                modal.style.display = "none";
            }
        });

    </script>
@endsection
