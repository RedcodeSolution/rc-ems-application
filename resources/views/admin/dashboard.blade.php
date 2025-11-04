@extends('layouts.admin')

@section('title', 'Admin Dashboard')
<link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
@section('content')
    <div class="dashboard-container">
        <div class="dashboard-content">
            <div class="dashboard-header animate-fade-in-up">
                <div class="dashboard-title">
                    <i class="fas fa-tachometer-alt"></i>
                    Admin Dashboard
                </div>
                <div class="dashboard-subtitle">
                    Welcome back, {{ auth()->user()?->name ?? 'Admin' }}
                    <i class="fas fa-tasks"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 60%"></div>
            </div>
        </div>

        {{-- Revenue metric (populated from controller / dashboardData) --}}
        <div class="stat-card stat-card-revenue animate-fade-in-up" style="animation-delay: 0.4s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Revenue</div>
                    <div id="metric-revenue" class="stat-card-value">{{ $revenue ?? '$847K' }}</div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>Performance vs last period</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-dollar-sign"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 72%"></div>
            </div>
        </div>

        {{-- Efficiency metric (populated from controller / dashboardData) --}}
        <div class="stat-card stat-card-efficiency animate-fade-in-up" style="animation-delay: 0.5s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Overall Efficiency</div>
                    <div id="metric-efficiency" class="stat-card-value">{{ $efficiency ?? '94.2%' }}</div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+2.4% improvement</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 80%"></div>
            </div>
        </div>

        <div class="stat-card stat-card-departments animate-fade-in-up" style="animation-delay: 0.6s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Departments</div>
                    <div class="stat-card-value counter" id="metric-departments"
                        data-target="{{ $departmentsCount ?? 12 }}">0</div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>+2 new departments</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-building"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 88%"></div>
            </div>
        </div>

        {{-- Total Employees metric (server fallback and client-updated) --}}
        <div class="stat-card stat-card-employees animate-fade-in-up" style="animation-delay: 0.65s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Total Employees</div>
                    <div id="metric-employees" class="stat-card-value counter" data-target="{{ $totalEmployees ?? 0 }}">
                        {{ $totalEmployees ?? 0 }}</div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-users"></i>
                        <span>All staff</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-users"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 70%"></div>
            </div>
        </div>

        <div class="stat-card stat-card-admins animate-fade-in-up" style="animation-delay: 0.7s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Admin Staff</div>
                    <div class="stat-card-value counter" id="metric-admins" data-target="{{ $adminsCount ?? 8 }}">0</div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-arrow-up"></i>
                        <span>Active admins</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 75%"></div>
            </div>
        </div>

        <div class="stat-card stat-card-notifications animate-fade-in-up" style="animation-delay: 0.8s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Notifications</div>
                    {{-- show server fallback value immediately; JS will update data-target and visible text later --}}
                    <div class="stat-card-value counter" id="metric-notifications"
                        data-target="{{ $notificationsCount ?? 0 }}">{{ $notificationsCount ?? 0 }}</div>
                    <div class="stat-card-change warning">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span id="metric-notifications-unread">{{ $notificationsCount ?? 0 }} unread</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-bell"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 65%"></div>
            </div>
        </div>

        <div class="stat-card stat-card-joinings animate-fade-in-up" style="animation-delay: 0.6s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">New Joinings</div>
                    {{-- display daily joinings (server fallback to newJoinings) --}}
                    <div id="metric-joinings" class="stat-card-value counter"
                        data-target="{{ $dailyJoinings ?? ($newJoinings ?? 0) }}">
                        {{ $dailyJoinings ?? ($newJoinings ?? 0) }}
                    </div>
                    <div class="stat-card-change positive">
                        <i class="fas fa-user-plus"></i>
                        <span>Today</span>
                    </div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-user-plus"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 67%"></div>
            </div>
        </div>

        <div class="stat-card stat-card-leaves animate-fade-in-up" style="animation-delay: 0.7s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Pending Leaves</div>
                    {{-- display only the numeric count --}}
                    <div id="metric-pending-leaves" class="stat-card-value">{{ $pendingLeaves ?? 0 }}</div>
                </div>
                <div class="stat-card-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
            </div>
            <div class="stat-card-progress">
                <div class="stat-card-progress-bar" style="width: 45%"></div>
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
                                <span class="status-badge {{ $meeting->status === 'ongoing' ? 'ongoing' : 'scheduled' }}">
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
                                @php
                                    $isAdmin = Auth::user() && in_array(Auth::user()->role, ['admin', 'super_admin']);
                                @endphp

                                @if ($meeting->status === 'ongoing' || $isAdmin)
                                    <div class="meeting-link-display">
                                        <input type="text" value="{{ $meeting->meeting_link }}"
                                            class="meeting-link-input" readonly>
                                        <button onclick="copyToClipboard('{{ $meeting->meeting_link }}', event)"
                                            class="copy-btn">
                                            <i class="fas fa-copy"></i> Copy
                                        </button>
                                    </div>

                                    <a href="{{ route('meetings.join', $meeting) }}" class="join-meeting-btn"
                                        target="_blank" rel="noopener noreferrer">
                                        <i class="fas fa-external-link-alt"></i>
                                        {{ $isAdmin && $meeting->status === 'scheduled' ? 'Start Meeting' : 'Join Meeting' }}
                                    </a>
                                @else
                                    <p class="meeting-upcoming-text">
                                        <i class="fas fa-clock"></i> Meeting not started yet
                                    </p>
                                @endif


                            </div>

                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- removed legacy copyLink helper; use copyToClipboard(text, event) for UI feedback --}}

    <div class="charts-grid">
        <div class="chart-card animate-fade-in-up" style="animation-delay: 0.8s">
            <div class="chart-header">
                <div class="chart-title">Performance Analytics</div>
                <div class="chart-controls">
                    <button class="chart-control-btn active">6M</button>
                    <button class="chart-control-btn">1Y</button>
                    <button class="chart-control-btn">All</button>
                </div>
            </div>
            <div style="height: 400px;">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <div class="chart-card animate-fade-in-up" style="animation-delay: 0.9s">
            <div class="chart-header">
                <div class="chart-title">Team Distribution</div>
            </div>
            <div style="height: 400px;">
                <canvas id="teamChart"></canvas>
            </div>
        </div>
    </div>

    <div class="activity-section">
        <div class="activity-card animate-fade-in-up" style="animation-delay: 1.0s">
            <div class="chart-header">
                <div class="chart-title">Recent Activities</div>
                <a href="{{ route('admin.notifications') }}" class="btn btn-primary">View All</a>
            </div>

            {{-- Activity list container: server-render when $activities or $notifications provided, else JS will populate --}}
            <div id="admin-activity-list">
                @if (
                    !empty($activities) &&
                        (is_array($activities) || (method_exists($activities, 'isNotEmpty') && $activities->isNotEmpty())))
                    @foreach ($activities as $activity)
                        <div class="activity-item">
                            <div class="activity-icon"
                                style="background: {{ $activity->bg ?? ($activity['bg'] ?? 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)') }}">
                                <i class="fas fa-{{ $activity->icon ?? ($activity['icon'] ?? 'bell') }}"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">{{ $activity->title ?? ($activity['title'] ?? '-') }}</div>
                                <div class="activity-description">
                                    {{ $activity->description ?? ($activity['description'] ?? ($activity['desc'] ?? '-')) }}
                                </div>
                                <div class="activity-time">
                                    {{ isset($activity->created_at) ? (is_object($activity->created_at) ? $activity->created_at->diffForHumans() : \Carbon\Carbon::parse($activity->created_at)->diffForHumans()) : $activity['created_at'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @elseif (
                    !empty($notifications) &&
                        (is_array($notifications) || (method_exists($notifications, 'isNotEmpty') && $notifications->isNotEmpty())))
                    @foreach ($notifications as $note)
                        <div class="activity-item">
                            <div class="activity-icon"
                                style="background: linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)">
                                <i class="fas fa-bell"></i>
                            </div>
                            <div class="activity-content">
                                <div class="activity-title">
                                    {{ $note->title ?? ($note['title'] ?? ($note->message ?? ($note['message'] ?? '-'))) }}
                                </div>
                                <div class="activity-description">{{ $note->message ?? ($note['message'] ?? '') }}</div>
                                <div class="activity-time">
                                    {{ isset($note->created_at) ? (is_object($note->created_at) ? $note->created_at->diffForHumans() : \Carbon\Carbon::parse($note->created_at)->diffForHumans()) : $note['created_at'] ?? '-' }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div id="admin-activity-loading" style="padding:1rem;color:var(--text-secondary);">
                        Loading recent activities...
                    </div>
                @endif
            </div>
        </div>

        <div class="activity-card animate-fade-in-up" style="animation-delay: 1.1s">
            <div class="chart-header">
                <div class="chart-title">Quick Actions</div>
            </div>
            <div class="quick-actions">
                <a href="{{ route('employees.create') }}" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%)">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="quick-action-title">Add Employee</div>
                    <div class="quick-action-description">Create new employee profile</div>
                </a>

                <a href="{{ route('admin.projects.create') }}" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%)">
                        <i class="fas fa-project-diagram"></i>
                    </div>
                    <div class="quick-action-title">New Project</div>
                    <div class="quick-action-description">Start a new project</div>
                </a>

                <a href="{{ route('admin.leaves.index') }}" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%)">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="quick-action-title">Review Leaves</div>
                    <div class="quick-action-description">Approve/Reject requests</div>
                </a>

                <a href="{{ route('admin.reports.index') }}" class="quick-action">
                    <div class="quick-action-icon" style="background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%)">
                        <i class="fas fa-chart-bar"></i>
                    </div>
                    <div class="quick-action-title">View Reports</div>
                    <div class="quick-action-description">Analytics & insights</div>
                </a>
            </div>
        </div>
    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            function updateTime() {
                const now = new Date();
                const timeElement = document.getElementById('currentTime');
                if (timeElement) {
                    timeElement.textContent = now.toLocaleTimeString('en-US', {
                        hour12: false,
                        hour: '2-digit',
                        minute: '2-digit',
                        second: '2-digit'
                    });
                }
            }
            setInterval(updateTime, 1000);

            function animateCounters() {
                const counters = document.querySelectorAll('.counter');
                counters.forEach(counter => {
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = 2000;
                    const step = target / (duration / 16);
                    let current = 0;

                    const timer = setInterval(() => {
                        current += step;
                        if (current >= target) {
                            counter.textContent = target;
                            clearInterval(timer);
                        } else {
                            counter.textContent = Math.floor(current);
                        }
                    }, 16);
                });
            }

            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(animateCounters, 500);
            });

            // --- Performance chart: initialize and keep reference to update from backend ---
            const performanceCtx = document.getElementById('performanceChart').getContext('2d');
            const performanceGradient = performanceCtx.createLinearGradient(0, 0, 0, 400);
            performanceGradient.addColorStop(0, 'rgba(220, 38, 38, 0.3)');
            performanceGradient.addColorStop(1, 'rgba(220, 38, 38, 0.05)');

            const performanceGradient2 = performanceCtx.createLinearGradient(0, 0, 0, 400);
            performanceGradient2.addColorStop(0, 'rgba(37, 99, 235, 0.3)');
            performanceGradient2.addColorStop(1, 'rgba(37, 99, 235, 0.05)');

            // create chart instance that we will update when backend data arrives
            const performanceChart = new Chart(performanceCtx, {
                type: 'line',
                data: {
                    labels: [], // will be filled from backend
                    datasets: []
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: {
                                    size: 14,
                                    weight: '600'
                                }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(255, 255, 255, 0.95)',
                            titleColor: '#1e293b',
                            bodyColor: '#64748b',
                            borderColor: '#e2e8f0',
                            borderWidth: 1,
                            cornerRadius: 12,
                            displayColors: true,
                            titleFont: {
                                size: 14,
                                weight: 'bold'
                            },
                            bodyFont: {
                                size: 13
                            },
                            padding: 12
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            suggestedMax: 100,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)',
                                drawBorder: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    size: 12
                                },
                                callback: function(value) {
                                    return value + '%';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false,
                                drawBorder: false
                            },
                            ticks: {
                                padding: 10,
                                font: {
                                    size: 12
                                }
                            }
                        }
                    }
                }
            });

            const teamCtx = document.getElementById('teamChart').getContext('2d');

            function generateColors(n) {
                const colors = [];
                for (let i = 0; i < n; i++) {
                    const hue = Math.round((i * 360 / Math.max(1, n)) % 360);
                    colors.push(`hsl(${hue}deg 70% 45% / 1)`);
                }
                return colors;
            }

            document.addEventListener('DOMContentLoaded', function() {
                        const teamCanvas = document.getElementById('teamChart');
                        let teamChart = null;

                        if (teamCanvas && teamCanvas.getContext) {
                            const teamCtx = teamCanvas.getContext('2d');
                            teamChart = new Chart(teamCtx, {
                                    type: 'doughnut',
                                    data: {
                                        labels: [],
                                        datasets: [{
                                            data: [],
                                            backgroundColor: [],
                                            borderWidth: 0,
                                            hoverOffset: 15
                                        }]
                                    },
                                    options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        cutout: '60%',
                                        plugins: {
                                            legend: {
                                                position: 'bottom',
                                                labels: {
                                                    usePointStyle: true,
                                                    padding: 20,
                                                    font: {
                                                        size: 12,
                                                        weight: '600'
                                                    }
                                                }
                                            },
                                            tooltip: {
                                                backgroundColor: 'rgba(255,255,255,0.95)',
                                                titleColor: '#1e293b',
                                                bodyColor: '#64748b',
                                                borderColor: '#e2e8f0',
                                                borderWidth: 1,
                                                cornerRadius: 12,
                                                displayColors: true,
                                                padding: 12,
                                                callbacks: {
                                                    label: function(context) {
                                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                                        const percentage = total ? ((context.parsed / total) * 100)
                                                            .toFixed(1) : 0;
                                                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                                                    }
                                                }
                                            }
                                        }
                                    });
                            }
                            else {
                                console.warn('teamChart canvas not found; team distribution chart will not be rendered.');
                            }

                            (function() {
                                const apiUrl = "{{ route('admin.dashboard.data') }}";
                                const elRevenue = document.getElementById('metric-revenue');
                                const elEfficiency = document.getElementById('metric-efficiency');
                                const elDepartments = document.getElementById('metric-departments');
                                const elAdmins = document.getElementById('metric-admins');
                                const elNotifications = document.getElementById('metric-notifications');
                                const elNotificationsUnread = document.getElementById('metric-notifications-unread');
                                const elJoinings = document.getElementById('metric-joinings');
                                const elPendingLeaves = document.getElementById('metric-pending-leaves');
                                const elEmployees = document.getElementById('metric-employees');
                                const morningSlot = document.getElementById('morning-meeting-card');
                                const eveningSlot = document.getElementById('evening-meeting-card');
                                const listEl = document.getElementById('admin-activity-list');
                                const loadingEl = document.getElementById('admin-activity-loading');

                                fetch(apiUrl, {
                                        headers: {
                                            'Accept': 'application/json'
                                        },
                                        credentials: 'same-origin'
                                    })
                                    .then(resp => {
                                        if (!resp.ok) throw new Error('Network response was not ok');
                                        return resp.json();
                                    })
                                    .then(payload => {
                                        const metrics = payload.metrics || {};
                                        const meetings = payload.meetings || [];

                                        if (elRevenue && metrics.revenue) elRevenue.textContent = metrics.revenue;
                                        if (elEfficiency && metrics.efficiency) elEfficiency.textContent = metrics
                                            .efficiency;
                                        if (elDepartments && typeof metrics.departments !== 'undefined') elDepartments
                                            .setAttribute('data-target', metrics.departments);
                                        if (elAdmins && typeof metrics.admins !== 'undefined') elAdmins.setAttribute(
                                            'data-target', metrics.admins);
                                        if (elNotifications && typeof metrics.notifications !== 'undefined') {
                                            elNotifications.setAttribute('data-target', metrics.notifications);
                                            elNotifications.textContent = metrics.notifications;
                                            if (elNotificationsUnread) {
                                                const unread = typeof metrics.notifications_unread !== 'undefined' ?
                                                    metrics.notifications_unread : metrics.notifications;
                                                elNotificationsUnread.textContent = `${unread} unread`;
                                            }
                                        }
                                        if (elJoinings) {
                                            const daily = typeof metrics.dailyJoinings !== 'undefined' ? metrics
                                                .dailyJoinings : metrics.newJoinings;
                                            elJoinings.setAttribute('data-target', daily ?? 0);
                                            elJoinings.textContent = daily ?? 0;
                                        }
                                        if (elEmployees && typeof metrics.employees !== 'undefined') {
                                            elEmployees.setAttribute('data-target', metrics.employees);
                                            elEmployees.textContent = metrics.employees;
                                        }

                                        if ((morningSlot || eveningSlot) && Array.isArray(meetings)) {
                                            const lowerTitle = s => (s || '').toString().toLowerCase();
                                            let morning = meetings.find(m => lowerTitle(m.title).includes('morning')) ||
                                                null;
                                            let evening = meetings.find(m => lowerTitle(m.title).includes('evening')) ||
                                                null;
                                            if (!morning && meetings.length > 0) morning = meetings[0];
                                            if (!evening && meetings.length > 1) evening = meetings[1];

                                            function populateSlot(slotEl, m, isMorning) {
                                                if (!slotEl) return;
                                                if (!m) {
                                                    slotEl.querySelector('.meeting-title').innerHTML =
                                                        `<i class="fas fa-${isMorning ? 'sun' : 'moon'}"></i> ${isMorning ? 'Morning Stand-up Meeting' : 'Evening Stand-up Meeting'}`;
                                                    slotEl.querySelector('.meeting-time').innerHTML =
                                                        `<i class="fas fa-clock"></i> -`;
                                                    slotEl.querySelector('.meeting-duration').innerHTML =
                                                        `<i class="fas fa-hourglass-half"></i> -`;
                                                    slotEl.querySelector('.meeting-link-input').value = '';
                                                    slotEl.querySelector('.copy-btn').onclick = (e) => copyToClipboard(
                                                        '', e);
                                                    slotEl.querySelector('.join-meeting-btn').setAttribute('href', '#');
                                                    slotEl.querySelector('.status-badge').className =
                                                        'status-badge scheduled';
                                                    slotEl.querySelector('.status-badge').textContent = 'Scheduled';
                                                    return;
                                                }
                                                const start = m.start_time ? new Date(m.start_time) : null;
                                                const end = m.end_time ? new Date(m.end_time) : null;
                                                const timeText = (start && end) ?
                                                    `${start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})} - ${end.toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'})}` :
                                                    (m.duration ? `${m.duration} minutes` : '-');
                                                slotEl.querySelector('.meeting-title').innerHTML =
                                                    `<i class="fas fa-${lowerTitle(m.title).includes('morning') ? 'sun' : 'moon'}"></i> ${m.title || (isMorning ? 'Morning' : 'Evening')}`;
                                                slotEl.querySelector('.meeting-time').innerHTML =
                                                    `<i class="fas fa-clock"></i> ${timeText}`;
                                                slotEl.querySelector('.meeting-duration').innerHTML =
                                                    `<i class="fas fa-hourglass-half"></i> ${m.duration ? m.duration + ' minutes' : '-'}`;
                                                slotEl.querySelector('.meeting-link-input').value = m.meeting_link ||
                                                    '';
                                                slotEl.querySelector('.copy-btn').onclick = (e) => copyToClipboard(m
                                                    .meeting_link || '', e);
                                                slotEl.querySelector('.join-meeting-btn').setAttribute('href', m
                                                    .meeting_link || '#');
                                                const badge = slotEl.querySelector('.status-badge');
                                                if (badge) {
                                                    badge.className = 'status-badge ' + ((m.status === 'ongoing') ?
                                                        'ongoing' : 'scheduled');
                                                    badge.textContent = (m.status || 'scheduled').charAt(0)
                                                        .toUpperCase() + (m.status || 'scheduled').slice(1);
                                                }
                                            }

                                            populateSlot(morningSlot, morning, true);
                                            populateSlot(eveningSlot, evening, false);
                                        }

                                        // Team Distribution -> update teamChart if available
                                        try {
                                            const teams = payload.teams_distribution && Array.isArray(payload
                                                    .teams_distribution.labels) && payload.teams_distribution.labels
                                                .length ? payload.teams_distribution : null;
                                            const depts = payload.departments_distribution && Array.isArray(payload
                                                    .departments_distribution.labels) && payload
                                                .departments_distribution.labels.length ? payload
                                                .departments_distribution : null;
                                            const use = teams || depts;
                                            if (use && teamChart) {
                                                const labels = use.labels.map(l => String(l));
                                                const data = use.data.map(n => Number(n) || 0);
                                                const colors = generateColors(labels.length);
                                                teamChart.data.labels = labels;
                                                teamChart.data.datasets[0].data = data;
                                                teamChart.data.datasets[0].backgroundColor = colors;
                                                teamChart.update();
                                            } else if (!use) {
                                                console.info(
                                                    'No team/department distribution data available for chart.');
                                            }
                                        } catch (e) {
                                            console.error('Failed to render team distribution chart', e);
                                        }

                                        // Performance Analytics -> update performanceChart from backend payload
                                        try {
                                            // payload.performance expected shape:
                                            // { labels: [...], datasets: [ { label, data: [...], borderColor?, backgroundColor?, tension?, fill? }, ... ] }
                                            const perfPayload = payload.performance || payload.metrics?.performance ||
                                                null;
                                            if (perfPayload && Array.isArray(perfPayload.labels) && Array.isArray(
                                                    perfPayload.datasets)) {
                                                // normalize dataset colors if missing
                                                const datasets = perfPayload.datasets.map((ds, idx) => {
                                                    const base = {
                                                        label: ds.label || `Series ${idx+1}`,
                                                        data: (ds.data || []).map(n => Number(n) || 0),
                                                        borderWidth: ds.borderWidth ?? 3,
                                                        tension: typeof ds.tension !== 'undefined' ? ds
                                                            .tension : 0.4,
                                                        fill: typeof ds.fill !== 'undefined' ? ds.fill :
                                                            true,
                                                        pointRadius: ds.pointRadius ?? 6,
                                                        pointHoverRadius: ds.pointHoverRadius ?? 8
                                                    };
                                                    // choose sensible colors if not provided
                                                    if (ds.borderColor) base.borderColor = ds.borderColor;
                                                    else base.borderColor = idx === 0 ? '#DC2626' : '#2563EB';
                                                    if (ds.backgroundColor) base.backgroundColor = ds
                                                        .backgroundColor;
                                                    else base.backgroundColor = idx === 0 ?
                                                        performanceGradient : performanceGradient2;
                                                    return base;
                                                });

                                                performanceChart.data.labels = perfPayload.labels.map(l => String(l));
                                                performanceChart.data.datasets = datasets;
                                                performanceChart.update();
                                            } else {
                                                // fallback: if metrics contains simple series arrays, map them
                                                if (metrics && Array.isArray(metrics.performance_labels) && Array
                                                    .isArray(metrics.performance_series)) {
                                                    const labels = metrics.performance_labels;
                                                    const datasets = metrics.performance_series.map((s, idx) => ({
                                                        label: s.label || `Series ${idx+1}`,
                                                        data: (s.data || []).map(n => Number(n) || 0),
                                                        borderColor: idx === 0 ? '#DC2626' : '#2563EB',
                                                        backgroundColor: idx === 0 ? performanceGradient :
                                                            performanceGradient2,
                                                        borderWidth: 3,
                                                        tension: 0.4,
                                                        fill: true,
                                                        pointRadius: 6,
                                                        pointHoverRadius: 8
                                                    }));
                                                    performanceChart.data.labels = labels;
                                                    performanceChart.data.datasets = datasets;
                                                    performanceChart.update();
                                                }
                                            }
                                        } catch (e) {
                                            console.error('Failed to render performance chart:', e);
                                        }

                                        // existing: handle activities list population
                                        if (listEl) {
                                            listEl.innerHTML = '';

                                            // prefer activities if present
                                            if (Array.isArray(payload.activities) && payload.activities.length > 0) {
                                                payload.activities.forEach(act => {
                                                    const item = document.createElement('div');
                                                    item.className = 'activity-item';
                                                    const icon = document.createElement('div');
                                                    icon.className = 'activity-icon';
                                                    icon.style.background = act.bg ||
                                                        'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                                                    icon.innerHTML =
                                                        `<i class="fas fa-${act.icon || 'bell'}"></i>`;
                                                    const content = document.createElement('div');
                                                    content.className = 'activity-content';
                                                    const title = document.createElement('div');
                                                    title.className = 'activity-title';
                                                    title.textContent = act.title || '-';
                                                    const desc = document.createElement('div');
                                                    desc.className = 'activity-description';
                                                    desc.textContent = act.description || act.message || '';
                                                    const time = document.createElement('div');
                                                    time.className = 'activity-time';
                                                    time.textContent = act.created_at ? new Date(act.created_at)
                                                        .toLocaleString() : '';
                                                    content.appendChild(title);
                                                    content.appendChild(desc);
                                                    content.appendChild(time);
                                                    item.appendChild(icon);
                                                    item.appendChild(content);
                                                    listEl.appendChild(item);
                                                });

                                                // also append recent notifications after activities (optional)
                                                if (Array.isArray(payload.notifications) && payload.notifications
                                                    .length > 0) {
                                                    payload.notifications.forEach(n => {
                                                        const item = document.createElement('div');
                                                        item.className = 'activity-item';
                                                        const icon = document.createElement('div');
                                                        icon.className = 'activity-icon';
                                                        icon.style.background = n.bg ||
                                                            'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)';
                                                        icon.innerHTML =
                                                            `<i class="fas fa-${n.icon || 'bell'}"></i>`;
                                                        const content = document.createElement('div');
                                                        content.className = 'activity-content';
                                                        const title = document.createElement('div');
                                                        title.className = 'activity-title';
                                                        title.textContent = n.title || n.message || '-';
                                                        const desc = document.createElement('div');
                                                        desc.className = 'activity-description';
                                                        desc.textContent = n.message || '';
                                                        const time = document.createElement('div');
                                                        time.className = 'activity-time';
                                                        time.textContent = n.created_at ? new Date(n.created_at)
                                                            .toLocaleString() : '';
                                                        content.appendChild(title);
                                                        content.appendChild(desc);
                                                        content.appendChild(time);
                                                        item.appendChild(icon);
                                                        item.appendChild(content);
                                                        listEl.appendChild(item);
                                                    });
                                                }
                                            }
                                            // if no activities, show notifications (useful when activities table is empty)
                                            else if (Array.isArray(payload.notifications) && payload.notifications
                                                .length > 0) {
                                                payload.notifications.forEach(n => {
                                                    const item = document.createElement('div');
                                                    item.className = 'activity-item';
                                                    const icon = document.createElement('div');
                                                    icon.className = 'activity-icon';
                                                    icon.style.background = n.bg ||
                                                        'linear-gradient(135deg, #f59e0b 0%, #ef4444 100%)';
                                                    icon.innerHTML =
                                                        `<i class="fas fa-${n.icon || 'bell'}"></i>`;
                                                    const content = document.createElement('div');
                                                    content.className = 'activity-content';
                                                    const title = document.createElement('div');
                                                    title.className = 'activity-title';
                                                    title.textContent = n.title || n.message || '-';
                                                    const desc = document.createElement('div');
                                                    desc.className = 'activity-description';
                                                    desc.textContent = n.message || '';
                                                    const time = document.createElement('div');
                                                    time.className = 'activity-time';
                                                    time.textContent = n.created_at ? new Date(n.created_at)
                                                        .toLocaleString() : '';
                                                    content.appendChild(title);
                                                    content.appendChild(desc);
                                                    content.appendChild(time);
                                                    item.appendChild(icon);
                                                    item.appendChild(content);
                                                    listEl.appendChild(item);
                                                });
                                            } else {
                                                // keep existing loading / fallback behavior
                                                if (loadingEl) loadingEl.textContent =
                                                    'No recent activities or notifications.';
                                            }
                                        }
                                    })
                                    .catch(err => {
                                        console.error('Failed to load admin dashboard data:', err);
                                        if (loadingEl) loadingEl.textContent = 'Failed to load activities.';
                                    });
                            })();
                        });
        </script>
    @endpush
@endsection
