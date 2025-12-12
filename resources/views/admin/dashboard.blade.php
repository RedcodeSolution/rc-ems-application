@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('content')
    <link rel="stylesheet" href="{{ asset('css/admin/dashboard.css') }}">
    <style>
        .team-distribution-wrapper {
            display: flex;
            flex-wrap: wrap;
            gap: 24px;
            align-items: flex-start;
            padding: 10px;
        }
        .team-chart-container {
            flex: 1;
            min-width: 280px;
            height: 300px;
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .team-list-container {
            flex: 1;
            min-width: 280px;
            max-height: 300px;
            overflow-y: auto;
            padding-right: 8px;
        }
        .team-list-container::-webkit-scrollbar {
            width: 6px;
        }
        .team-list-container::-webkit-scrollbar-track {
            background: #f8fafc;
            border-radius: 3px;
        }
        .team-list-container::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .team-list-container::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        .team-list-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px;
            margin-bottom: 8px;
            background: #fff;
            border: 1px solid #f1f5f9;
            border-radius: 12px;
            transition: all 0.2s ease;
        }
        .team-list-item:hover {
            background-color: #f8fafc;
            border-color: #e2e8f0;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        }
        .team-info {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .team-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
            box-shadow: 0 0 0 2px rgba(255,255,255,0.8);
        }
        .team-details {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .team-name {
            font-weight: 600;
            color: #1e293b;
            font-size: 0.95rem;
        }
        .team-projects {
            font-size: 0.8rem;
            color: #64748b;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .team-count {
            text-align: right;
            padding-left: 16px;
            border-left: 1px solid #f1f5f9;
        }
        .team-count-number {
            font-weight: 700;
            color: #0f172a;
            font-size: 1.1rem;
            line-height: 1;
        }
        .team-count-label {
            font-size: 0.65rem;
            color: #94a3b8;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 4px;
        }

        /* Mobile Responsive Enhancements */
        .btn-icon { display: none; }
        @media (max-width: 480px) {
            .btn-text { display: none; }
            .btn-icon { display: inline-block; }
            .view-all-btn { 
                padding: 0.5rem 0.75rem !important; 
                display: flex;
                align-items: center;
                justify-content: center;
            }
        }
    </style>
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



        <div class="stats-grid">
        <div class="stat-card stat-card-departments animate-fade-in-up" style="animation-delay: 0.6s">
            <div class="stat-card-header">
                <div>
                    <div class="stat-card-title">Departments</div>
                    <div class="stat-card-value counter" id="metric-departments" data-target="{{ $departmentsCount ?? 0 }}">
                        {{ $departmentsCount ?? 0 }}</div>
                    
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
                    <div class="stat-card-value counter" id="metric-admins" data-target="{{ $adminsCount ?? 0 }}">{{ $adminsCount ?? 0 }}</div>
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

                                    @if(in_array($meeting->status, ['completed', 'cancelled']))
                                        <div class="meeting-status-display">
                                            <button class="join-meeting-btn" disabled 
                                                style="opacity: 0.7; pointer-events: none; cursor: not-allowed; background: #94a3b8; width: 100%; justify-content: center; margin-top: 10px;">
                                                <i class="fas fa-ban"></i> 
                                                Meeting {{ ucfirst($meeting->status) }}
                                            </button>
                                        </div>
                                    @elseif ($meeting->status === 'ongoing' || $isAdmin)
                                        <div class="meeting-link-display">
                                            <input type="text" value="{{ $meeting->meeting_link }}"
                                                class="meeting-link-input" readonly>
                                            <button onclick="copyToClipboard('{{ $meeting->meeting_link }}', event)"
                                                class="copy-btn">
                                                <i class="fas fa-copy"></i> Copy
                                            </button>
                                        </div>

                                        <div class="meeting-actions" style="display: flex; gap: 8px; margin-top: 10px;">
                                            <a href="{{ route('meetings.join', $meeting) }}" class="join-meeting-btn"
                                                target="_blank" rel="noopener noreferrer">
                                                <i class="fas fa-external-link-alt"></i>
                                                {{ $isAdmin && $meeting->status === 'scheduled' ? 'Start Meeting' : 'Join Meeting' }}
                                            </a>

                                            @if($isAdmin)
                                                @if($meeting->status === 'ongoing')
                                                    <button onclick="updateMeetingStatus('{{ $meeting->id }}', 'completed')" class="btn btn-sm btn-danger" style="background-color: #ef4444; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                                        <i class="fas fa-stop-circle"></i> End
                                                    </button>
                                                @elseif($meeting->status === 'scheduled')
                                                    <button onclick="updateMeetingStatus('{{ $meeting->id }}', 'cancelled')" class="btn btn-sm btn-secondary" style="background-color: #64748b; color: white; border: none; padding: 0.5rem 1rem; border-radius: 6px; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                                                        <i class="fas fa-times-circle"></i> Cancel
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
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


        <div class="chart-card animate-fade-in-up" style="animation-delay: 0.9s">
            <div class="chart-header">
                <div class="chart-title">Team Distribution</div>
            </div>
            <div class="table-container" style="box-shadow: none; border: none; overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Team Name</th>
                            <th>Active Projects</th>
                            <th>Members</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($teams_details) && count($teams_details) > 0)
                            @foreach($teams_details as $index => $team)
                                @php
                                    $colors = ['#667eea', '#764ba2', '#f59e0b', '#ef4444', '#10b981', '#3b82f6'];
                                    $color = $colors[$index % count($colors)];
                                    $bgSoft = $color . '20'; // 20 is hex opacity ~12%
                                    $pNames = !empty($team['project_names']) ? implode(', ', $team['project_names']) : 'No active projects';
                                    if ($team['projects_count'] > count($team['project_names'])) {
                                        $pNames .= ' (+' . ($team['projects_count'] - count($team['project_names'])) . ')';
                                    }
                                @endphp
                                <tr style="transition: background-color 0.2s;">
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ $bgSoft }}; color: {{ $color }}; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.9rem;">
                                                {{ substr($team['name'], 0, 1) }}
                                            </div>
                                            <div style="font-weight: 600; color: #1e293b; font-size: 0.95rem;">
                                                {{ $team['name'] }}
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div style="display: flex; align-items: center; gap: 8px;">
                                            <span style="display: inline-flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #f1f5f9; border-radius: 50%; color: #64748b;">
                                                <i class="fas fa-project-diagram" style="font-size: 0.75rem;"></i>
                                            </span>
                                            <span style="font-size: 0.875rem; color: #475569;">{{ $pNames }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span style="display: inline-block; padding: 4px 12px; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 20px; font-weight: 600; color: #334155; font-size: 0.85rem;">
                                            {{ $team['employees_count'] }} Members
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center" style="padding: 3rem; color: #9ca3af;">
                                    <div style="width: 48px; height: 48px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                                        <i class="fas fa-users-slash" style="font-size: 1.2rem; color: #cbd5e1;"></i>
                                    </div>
                                    <div style="font-weight: 500;">No team data available</div>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
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

                <a href="{{ route('admin.projects', ['action' => 'create']) }}" class="quick-action">
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

    <div class="activity-section" style="margin-bottom: 2rem;">
        <div class="chart-card animate-fade-in-up" style="animation-delay: 0.95s; width: 100%;">
            <div class="chart-header">
                <div class="chart-title">Top performing Employees</div>
            </div>
            <div class="table-container" style="box-shadow: none; border: none; overflow-x: auto;">
                <table class="table" id="topPerformersTable">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            <th>Role</th>
                            <th>Performance Score</th>
                            <th>Projects Completed</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(isset($topPerformers) && count($topPerformers) > 0)
                            @foreach($topPerformers as $p)
                                <tr>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#64748b;">
                                                {{ substr($p['name'], 0, 1) }}
                                            </div>
                                            <div>
                                                <div style="font-weight:600;color:var(--text-primary);">{{ $p['name'] }}</div>
                                                <div style="font-size:0.75rem;color:var(--text-secondary);">{{ $p['email'] }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $p['role'] }}</td>
                                    <td>
                                        <div style="display:flex;align-items:center;gap:0.5rem;">
                                            <div style="flex:1;height:6px;background:#f1f5f9;border-radius:3px;overflow:hidden;width:80px;">
                                                <div style="width:{{ $p['score'] }}%;height:100%;background:{{ $p['score'] >= 80 ? '#22c55e' : ($p['score'] >= 60 ? '#eab308' : '#ef4444') }};"></div>
                                            </div>
                                            <div style="display:flex; flex-direction:column; align-items:flex-end;">
                                                <span style="font-weight:600;font-size:0.875rem;">{{ $p['score'] }}%</span>
                                                <span style="font-size:0.7rem;color:#64748b;">{{ $p['rating'] }} / 5</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $p['projects_count'] }}</td>
                                    <td>
                                        <span class="status-badge {{ $p['status'] === 'active' ? 'ongoing' : 'scheduled' }}" style="font-size:0.75rem;padding:0.25rem 0.5rem;">
                                            {{ ucfirst($p['status']) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="text-center">No data available</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>




    </div>


    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

            // Performance chart removed

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
                                const elDepartments = document.getElementById('metric-departments');
                                const elAdmins = document.getElementById('metric-admins');
                                const elNotifications = document.getElementById('metric-notifications');
                                const elNotificationsUnread = document.getElementById('metric-notifications-unread');
                                const elJoinings = document.getElementById('metric-joinings');
                                const elPendingLeaves = document.getElementById('metric-pending-leaves');
                                const elEmployees = document.getElementById('metric-employees');
                                const morningSlot = document.getElementById('morning-meeting-card');
                                const eveningSlot = document.getElementById('evening-meeting-card');

                                const topPerformersBody = document.querySelector('#topPerformersTable tbody');

                                function fetchData(range = '1M') {
                                    fetch(`${apiUrl}?range=${range}`, {
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

                                            if (elDepartments && typeof metrics.departments !== 'undefined') {
                                                elDepartments.setAttribute('data-target', metrics.departments);
                                                elDepartments.textContent = metrics.departments;
                                            }
                                            if (elAdmins && typeof metrics.admins !== 'undefined') {
                                                elAdmins.setAttribute('data-target', metrics.admins);
                                                elAdmins.textContent = metrics.admins;
                                            }
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
                                                    const joinBtn = slotEl.querySelector('.join-meeting-btn');
                                                    if (joinBtn) {
                                                        const isEnded = ['completed', 'cancelled'].includes(m.status);
                                                        if (isEnded) {
                                                            joinBtn.setAttribute('href', 'javascript:void(0)');
                                                            joinBtn.style.opacity = '0.6';
                                                            joinBtn.style.pointerEvents = 'none';
                                                            joinBtn.style.cursor = 'not-allowed';
                                                            joinBtn.style.background = '#94a3b8'; // Grey out
                                                            joinBtn.innerHTML = '<i class="fas fa-ban"></i> ' + (m.status === 'completed' ? 'Ended' : 'Cancelled');
                                                        } else {
                                                            joinBtn.setAttribute('href', m.meeting_link || '#');
                                                            joinBtn.style.opacity = '';
                                                            joinBtn.style.pointerEvents = '';
                                                            joinBtn.style.cursor = '';
                                                            joinBtn.style.background = '';
                                                            joinBtn.innerHTML = '<i class="fas fa-video"></i> Join';
                                                        }
                                                    }

                                                    const badge = slotEl.querySelector('.status-badge');
                                                    if (badge) {
                                                        let badgeClass = 'scheduled';
                                                        if (m.status === 'ongoing') badgeClass = 'ongoing';
                                                        
                                                        badge.className = 'status-badge ' + badgeClass;
                                                        
                                                        // Inline styles for completed/cancelled as fallback
                                                        if (m.status === 'completed') {
                                                            badge.style.backgroundColor = '#10b981';
                                                            badge.style.color = '#fff';
                                                        } else if (m.status === 'cancelled') {
                                                            badge.style.backgroundColor = '#64748b';
                                                            badge.style.color = '#fff';
                                                        } else {
                                                            badge.style.backgroundColor = '';
                                                            badge.style.color = '';
                                                        }

                                                        badge.textContent = (m.status || 'scheduled').charAt(0).toUpperCase() + (m.status || 'scheduled').slice(1);
                                                    }
                                                }
                                                // Added for dynamic update
                                                const isAdmin = {{ Auth::check() && in_array(Auth::user()->role, ['admin', 'super_admin']) ? 'true' : 'false' }};
                                                // Simplified rebuilding of buttons for dynamics (omitted for brevity, assuming page reload on status change for now or user will handle basic flow)
                                                // Ideally we should rebuild the innerHTML of meeting-link-section here if we want full real-time updates without reload.
                                                // For now, the static page load handles the initial buttons. JS updates below handle the actions.
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
                                                    teamChart.data.datasets[0].backgroundColor = colors;
                                                    teamChart.update();
                                                } else if (!use) {
                                                    console.info(
                                                        'No team/department distribution data available for chart.');
                                                }
                                            } catch (e) {
                                                console.error('Failed to render team distribution chart', e);
                                            }

                                            // Update Team Details List (Independent of chart)
                                            try {
                                                const teamListEl = document.getElementById('teamDetailsList');
                                                if (teamListEl) {
                                                    teamListEl.innerHTML = '';
                                                    
                                                    const teamsData = payload.teams_details || [];
                                                    // Fallback if teams_details is empty but distribution data exists
                                                    if (teamsData.length === 0 && payload.teams_distribution && payload.teams_distribution.labels) {
                                                        payload.teams_distribution.labels.forEach((l, i) => {
                                                            teamsData.push({
                                                                name: l,
                                                                employees_count: payload.teams_distribution.data[i] || 0,
                                                                projects_count: 0,
                                                                project_names: []
                                                            });
                                                        });
                                                    }

                                                    // Generate colors if needed (reuse generateColors if possible, or simple fallback)
                                                    const colors = window.generateColors ? window.generateColors(teamsData.length) : teamsData.map(() => '#667eea');

                                                    teamsData.forEach((team, index) => {
                                                        const color = colors[index] || '#ccc';
                                                        const projectNames = team.project_names && team.project_names.length > 0 
                                                            ? team.project_names.join(', ') + (team.projects_count > team.project_names.length ? ` (+${team.projects_count - team.project_names.length})` : '')
                                                            : 'No active projects';

                                                        const item = document.createElement('div');
                                                        item.style.cssText = 'display: flex; align-items: flex-start; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid #f3f4f6;';
                                                        
                                                        item.innerHTML = `
                                                            <div style="display: flex; gap: 10px;">
                                                                <span style="width: 10px; height: 10px; border-radius: 50%; background-color: ${color}; display: inline-block; margin-top: 5px;"></span>
                                                                <div>
                                                                    <div style="font-weight: 600; color: #374151; font-size: 0.9rem;">${team.name}</div>
                                                                    <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">
                                                                        <i class="fas fa-project-diagram" style="font-size: 0.7rem; margin-right: 4px;"></i>
                                                                        ${projectNames}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div style="text-align: right; min-width: 60px;">
                                                                <span style="font-weight: 700; color: #111827; font-size: 0.9rem;">${team.employees_count}</span>
                                                                <div style="font-size: 0.7rem; color: #9ca3af;">Employees</div>
                                                            </div>
                                                        `;
                                                        teamListEl.appendChild(item);
                                                    });
                                                    
                                                    if (teamsData.length === 0) {
                                                        teamListEl.innerHTML = '<p style="text-align: center; color: #9ca3af; margin-top: 20px;">No team data available</p>';
                                                    }
                                                }
                                            } catch (e) {
                                                console.error('Failed to render team list', e);
                                            }


                                            // Performance Analytics removed
                                            // console.log('Payload received:', payload);

                                                // handle activities list population


                                            // Top Performers Table
                                            if (topPerformersBody) {
                                                // console.log('Updating top performers table', payload.topPerformers);
                                                topPerformersBody.innerHTML = '';
                                                if (Array.isArray(payload.topPerformers) && payload.topPerformers.length > 0) {
                                                    payload.topPerformers.forEach(p => {
                                                        const tr = document.createElement('tr');
                                                        tr.innerHTML = `
                                                            <td>
                                                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                                                    <div style="width:32px;height:32px;border-radius:50%;background:#e2e8f0;display:flex;align-items:center;justify-content:center;font-weight:bold;color:#64748b;">
                                                                        ${p.name.charAt(0)}
                                                                    </div>
                                                                    <div>
                                                                        <div style="font-weight:600;color:var(--text-primary);">${p.name}</div>
                                                                        <div style="font-size:0.75rem;color:var(--text-secondary);">${p.email}</div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>${p.role}</td>
                                                            <td>
                                                                <div style="display:flex;align-items:center;gap:0.5rem;">
                                                                    <div style="flex:1;height:6px;background:#f1f5f9;border-radius:3px;overflow:hidden;width:80px;">
                                                                        <div style="width:${p.score}%;height:100%;background:${p.score >= 80 ? '#22c55e' : (p.score >= 60 ? '#eab308' : '#ef4444')};"></div>
                                                                    </div>
                                                                    <div style="display:flex; flex-direction:column; align-items:flex-end;">
                                                                        <span style="font-weight:600;font-size:0.875rem;">${p.score}%</span>
                                                                        <span style="font-size:0.7rem;color:#64748b;">${p.rating || 0} / 5</span>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>${p.projects_count}</td>
                                                            <td>
                                                                <span class="status-badge ${p.status === 'active' ? 'ongoing' : 'scheduled'}" style="font-size:0.75rem;padding:0.25rem 0.5rem;">
                                                                    ${p.status.charAt(0).toUpperCase() + p.status.slice(1)}
                                                                </span>
                                                            </td>
                                                        `;
                                                        topPerformersBody.appendChild(tr);
                                                    });
                                                } else {
                                                    topPerformersBody.innerHTML = '<tr><td colspan="5" class="text-center">No data available</td></tr>';
                                                }
                                            }

                                        })
                                        .catch(err => {
                                            console.error('Failed to load admin dashboard data:', err);
                                            if (loadingEl) loadingEl.textContent = 'Failed to load activities.';
                                            if (topPerformersBody) topPerformersBody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Failed to load data</td></tr>';
                                        });
                                }

                                // Initial fetch
                                fetchData();

                                // Chart Controls removed
                            })();
                        });
        </script>
        @if (session('success'))
            <script>
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            </script>
        @endif
        <script>
            function updateMeetingStatus(meetingId, status) {
                const action = status === 'completed' ? 'End' : 'Cancel';
                const confirmBtnColor = status === 'completed' ? '#ef4444' : '#64748b';

                Swal.fire({
                    title: `Are you sure you want to ${action} this meeting?`,
                    text: "Employees will be notified.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: confirmBtnColor,
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: `Yes, ${action} it!`
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/meetings/${meetingId}/status`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({ status: status })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    'Updated!',
                                    `Meeting has been ${status}.`,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message || 'Something went wrong.',
                                    'error'
                                );
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'Failed to update meeting status.',
                                'error'
                            );
                        });
                    }
                });
            }
        </script>
    @endpush
    <style>
        .activity-list {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        .activity-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #e5e7eb;
            border-radius: 0.75rem;
            transition: all 0.2s ease;
            background: #fff;
        }
        .activity-item:hover {
            background: #f8fafc; /* bg-secondary approximation */
            border-color: #667eea; /* var(--primary) approximation */
        }
        .activity-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); /* var(--gradient-hero) */
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            flex-shrink: 0;
        }
        .activity-content {
            flex: 1;
        }
        .activity-content h4 {
            font-size: 0.95rem;
            font-weight: 600;
            margin: 0 0 0.25rem 0;
            color: #1f2937; /* text-primary */
        }
        .activity-content p {
            font-size: 0.85rem;
            color: #6b7280; /* text-secondary */
            margin: 0 0 0.25rem 0;
        }
        .activity-time {
            font-size: 0.75rem;
            color: #9ca3af; /* text-light */
        }
    </style>
@endsection
