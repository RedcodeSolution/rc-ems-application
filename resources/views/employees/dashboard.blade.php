@extends('layouts.employee')

@section('title', 'Employee Dashboard - Overview')

@section('content')
    <div class="dashboard-container">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <div class="welcome-content">
                <h1>Welcome back, {{ auth()->user()?->name ?? 'Employee' }}!</h1>
                <p>Here's what's happening in your workplace today</p>
            </div>
            <div class="welcome-actions">
                <div class="current-time">
                    <i class="fas fa-clock"></i>
                    <span id="current-time">{{ now()->format('H:i') }}</span>
                </div>
                <div class="current-date">
                    <i class="fas fa-calendar"></i>
                    <span>{{ now()->format('F j, Y') }}</span>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $today_hours_worked ?? '0h 00m' }}</h3>
                    <p>Hours Today</p>
                </div>

                @php
                    $isPositive = $hour_change_percent >= 0;
                @endphp

                <div class="stat-trend {{ $isPositive ? 'positive' : 'negative' }}">
                    <i class="fas {{ $isPositive ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                    <span>{{ $isPositive ? '+' : '' }}{{ $hour_change_percent }}%</span>
                </div>
            </div>


            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-project-diagram"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $assigned_project_count }}</h3>
                    <p>My Projects</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $teamCount }}</h3>
                    <p>My Teams</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>Active</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-calendar-times"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ 21 - $approved_leave_count }}</h3>
                    <p>Leave Balance</p>
                </div>
                <div class="stat-trend neutral">
                    <i class="fas fa-minus"></i>
                    <span>Days Left</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-bell"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $unread_notifications }}</h3>
                    <p>Notifications</p>
                </div>
                <div class="stat-trend warning">
                    <i class="fas fa-exclamation"></i>
                    <span>Unread</span>
                </div>
            </div>

            <div class="stat-card">
                <div class="stat-icon">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="stat-content">
                    <h3>{{ $document_count }}</h3>
                    <p>My Documents</p>
                </div>
                <div class="stat-trend positive">
                    <i class="fas fa-arrow-up"></i>
                    <span>+2 New</span>
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

        <!-- Main Content Grid -->
        <div class="main-grid">
            <!-- Left Column -->
            <div class="left-column">
                <!-- Quick Actions -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-bolt"></i> Quick Actions</h3>
                    </div>
                    <div class="card-content">
                        <div class="action-grid">
                            <a href="{{ route('employee.attendance') }}"class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Clock In</h4>
                                    <p>Start your day</p>
                                </div>
                            </a>

                            <a href="{{ route('employee.leaves.index') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Request Leave</h4>
                                    <p>Apply for time off</p>
                                </div>
                            </a>

                            <a href="{{ route('employee.documents') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-file-upload"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Upload Document</h4>
                                    <p>Submit files</p>
                                </div>
                            </a>

                            <a href="{{ route('employee.profile') }}" class="action-card">
                                <div class="action-icon">
                                    <i class="fas fa-user-edit"></i>
                                </div>
                                <div class="action-content">
                                    <h4>Update Profile</h4>
                                    <p>Edit information</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-history"></i> Recent Activity</h3>
                        <a href="#" class="view-all">View All</a>
                    </div>
                    <div class="card-content">
                        <div class="activity-list">
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-sign-in-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Clocked In</h4>
                                    <p>Started work at 9:00 AM</p>
                                    <span class="activity-time">2 hours ago</span>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-check-circle"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Task Completed</h4>
                                    <p>Frontend UI Update - Dashboard</p>
                                    <span class="activity-time">4 hours ago</span>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-file-alt"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Document Uploaded</h4>
                                    <p>Monthly Report - October 2024</p>
                                    <span class="activity-time">1 day ago</span>
                                </div>
                            </div>

                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="activity-content">
                                    <h4>Leave Approved</h4>
                                    <p>Vacation leave for Dec 25-27</p>
                                    <span class="activity-time">2 days ago</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="right-column">
                <div class="dashboard-card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3><i class="fas fa-tasks"></i> My Tasks</h3>
                        <a href="{{ route('employee.tasks') }}" class="view-all">View All</a>
                    </div>

                    <div class="card-content">
                        @if ($tasks->isEmpty())
                            <p class="text-muted text-center mb-0">No tasks assigned yet.</p>
                        @else
                            <div class="task-list">
                                @foreach ($tasks as $task)
                                    <div class="task-item">
                                        {{-- Priority color indicator --}}
                                        <div class="task-priority {{ strtolower($task->priority) }}"></div>

                                        <div class="task-content">
                                            <h4>{{ $task->title }}</h4>
                                            <p>{{ $task->description ?? 'No description available' }}</p>
                                            <div class="task-meta">
                                                <span class="task-due">
                                                    Due:
                                                    {{ $task->due_date }}
                                                </span>
                                                <span class="task-status {{ strtolower($task->priority) }}">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>


                <!-- Announcements -->
                <div class="dashboard-card">
                    <div class="card-header">
                        <h3><i class="fas fa-bullhorn"></i> Announcements</h3>
                        <a href="{{ route('employee.announcements') }}" class="view-all">View All</a>
                    </div>

                    <div class="card-content">
                        <div class="announcement-list">
                            @forelse ($announcements as $announcement)
                                <div class="announcement-item priority-{{ strtolower($announcement->priority) }}">
                                    <div class="announcement-icon">
                                        @switch($announcement->priority)
                                            @case('urgent')
                                                <i class="fas fa-exclamation-circle text-danger"></i>
                                            @break

                                            @case('high')
                                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                            @break

                                            @case('medium')
                                                <i class="fas fa-info-circle text-primary"></i>
                                            @break

                                            @default
                                                <i class="fas fa-bullhorn text-muted"></i>
                                        @endswitch
                                    </div>

                                    <div class="announcement-content">
                                        <h4>{{ $announcement->title }}</h4>
                                        <p>{{ Str::limit($announcement->content, 100) }}</p>
                                        <div class="announcement-meta">
                                            <span class="announcement-time">
                                                {{ $announcement->created_at->diffForHumans() }}
                                            </span>
                                            @if ($announcement->expires_at)
                                                <span class="announcement-expiry text-muted">
                                                    Expires: {{ $announcement->expires_at->format('M d, Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @empty
                                    <p class="text-muted text-center my-3">
                                        <i class="fas fa-info-circle"></i> No current announcements.
                                    </p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Attendance Chart -->
            <div class="dashboard-card">
                <div class="card-header">
                    <h3><i class="fas fa-chart-line"></i> Attendance Overview</h3>
                    <div class="card-actions">
                        <select class="period-selector">
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                            <option value="year">This Year</option>
                        </select>
                    </div>
                </div>
                <div class="card-content">
                    <div class="attendance-chart">
                        <div class="chart-container">
                            <canvas id="attendanceChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .dashboard-container {
                max-width: 1400px;
                margin: 0 auto;
                padding: 0;
            }

            .welcome-section {
                background: var(--gradient-primary);
                color: white;
                padding: 2rem;
                border-radius: 1rem;
                margin-bottom: 2rem;
                display: flex;
                justify-content: space-between;
                align-items: center;
                box-shadow: var(--shadow-lg);
            }

            .welcome-content h1 {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .welcome-content p {
                font-size: 1.1rem;
                opacity: 0.9;
            }

            .welcome-actions {
                display: flex;
                gap: 1.5rem;
                align-items: center;
            }

            .current-time,
            .current-date {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                font-size: 1.1rem;
                font-weight: 500;
            }

            .stats-grid {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 1.5rem;
                margin-bottom: 2rem;
            }

            .stat-card {
                background: white;
                padding: 1.5rem;
                border-radius: 1rem;
                box-shadow: var(--shadow);
                display: flex;
                align-items: center;
                gap: 1rem;
                transition: transform 0.2s ease, box-shadow 0.2s ease;
            }

            .stat-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
            }

            .stat-icon {
                width: 60px;
                height: 60px;
                border-radius: 50%;
                background: var(--gradient-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.5rem;
            }

            .stat-content {
                flex: 1;
            }

            .stat-content h3 {
                font-size: 2rem;
                font-weight: 700;
                color: var(--text-primary);
                margin-bottom: 0.25rem;
            }

            .stat-content p {
                color: var(--text-secondary);
                font-size: 0.9rem;
            }

            .stat-trend {
                display: flex;
                align-items: center;
                gap: 0.25rem;
                font-size: 0.875rem;
                font-weight: 600;
                padding: 0.25rem 0.75rem;
                border-radius: 1rem;
            }

            .stat-trend.positive {
                background: rgba(67, 160, 71, 0.1);
                color: var(--success);
            }

            .stat-trend.neutral {
                background: rgba(255, 160, 0, 0.1);
                color: var(--warning);
            }

            .stat-trend.warning {
                background: rgba(255, 160, 0, 0.1);
                color: var(--warning);
            }

            .main-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 2rem;
                margin-bottom: 2rem;
            }

            .dashboard-card {
                background: white;
                border-radius: 1rem;
                box-shadow: var(--shadow);
                margin-bottom: 2rem;
                overflow: hidden;
            }

            .card-header {
                padding: 1.5rem;
                border-bottom: 1px solid var(--divider);
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .card-header h3 {
                color: var(--text-primary);
                font-size: 1.25rem;
                font-weight: 600;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .card-header h3 i {
                color: var(--primary);
            }

            .view-all {
                color: var(--primary);
                font-size: 0.875rem;
                font-weight: 500;
                text-decoration: none;
                transition: color 0.2s ease;
            }

            .view-all:hover {
                color: var(--primary-dark);
            }

            .card-content {
                padding: 1.5rem;
            }

            .action-grid {
                display: grid;
                grid-template-columns: repeat(2, 1fr);
                gap: 1rem;
            }

            .action-card {
                display: flex;
                align-items: center;
                gap: 1rem;
                padding: 1rem;
                border: 1px solid var(--divider);
                border-radius: 0.75rem;
                text-decoration: none;
                transition: all 0.2s ease;
            }

            .action-card:hover {
                background: var(--primary-light);
                border-color: var(--primary);
                transform: translateY(-1px);
            }

            .action-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--gradient-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1.125rem;
            }

            .action-content h4 {
                color: var(--text-primary);
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 0.125rem;
            }

            .action-content p {
                color: var(--text-secondary);
                font-size: 0.75rem;
            }

            .activity-list,
            .task-list,
            .announcement-list {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .activity-item,
            .task-item,
            .announcement-item {
                display: flex;
                align-items: flex-start;
                gap: 1rem;
                padding: 1rem;
                border: 1px solid var(--divider);
                border-radius: 0.75rem;
                transition: all 0.2s ease;
            }

            .activity-item:hover,
            .task-item:hover,
            .announcement-item:hover {
                background: var(--bg-secondary);
                border-color: var(--primary);
            }

            .activity-icon,
            .announcement-icon {
                width: 40px;
                height: 40px;
                border-radius: 50%;
                background: var(--gradient-primary);
                display: flex;
                align-items: center;
                justify-content: center;
                color: white;
                font-size: 1rem;
                flex-shrink: 0;
            }

            .activity-content,
            .task-content,
            .announcement-content {
                flex: 1;
            }

            .activity-content h4,
            .task-content h4,
            .announcement-content h4 {
                color: var(--text-primary);
                font-size: 0.9rem;
                font-weight: 600;
                margin-bottom: 0.25rem;
            }

            .activity-content p,
            .task-content p,
            .announcement-content p {
                color: var(--text-secondary);
                font-size: 0.8rem;
                margin-bottom: 0.5rem;
            }

            .activity-time,
            .announcement-time {
                color: var(--text-light);
                font-size: 0.75rem;
            }

            .task-priority {
                width: 4px;
                height: 100%;
                border-radius: 2px;
                flex-shrink: 0;
            }

            .task-priority.high {
                background: var(--danger);
            }

            .task-priority.medium {
                background: var(--warning);
            }

            .task-priority.low {
                background: var(--success);
            }

            .task-meta {
                display: flex;
                justify-content: space-between;
                align-items: center;
                margin-top: 0.5rem;
            }

            .task-due {
                color: var(--text-light);
                font-size: 0.75rem;
            }

            .task-status {
                font-size: 0.75rem;
                font-weight: 500;
                padding: 0.25rem 0.5rem;
                border-radius: 0.5rem;
            }

            .task-status.high {
                background: rgba(230, 74, 25, 0.1);
                color: var(--danger);
            }

            .task-status.medium {
                background: rgba(255, 160, 0, 0.1);
                color: var(--warning);
            }

            .task-status.low {
                background: rgba(67, 160, 71, 0.1);
                color: var(--success);
            }

            .card-actions {
                display: flex;
                gap: 1rem;
                align-items: center;
            }

            .period-selector {
                background: var(--primary-light);
                border: 1px solid var(--divider);
                border-radius: 0.5rem;
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
                color: var(--text-primary);
                cursor: pointer;
                transition: all 0.2s ease;
            }

            .period-selector:hover {
                background: white;
                border-color: var(--primary);
            }

            .attendance-chart {
                height: 300px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--bg-secondary);
                border-radius: 0.5rem;
                color: var(--text-secondary);
                font-size: 0.9rem;
            }

            .chart-container {
                width: 100%;
                height: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            /* Responsive Design */
            @media (max-width: 1024px) {
                .main-grid {
                    grid-template-columns: 1fr;
                }

                .stats-grid {
                    grid-template-columns: repeat(2, 1fr);
                }
            }

            @media (max-width: 768px) {
                .welcome-section {
                    flex-direction: column;
                    text-align: center;
                    gap: 1rem;
                }

                .stats-grid {
                    grid-template-columns: 1fr;
                }

                .action-grid {
                    grid-template-columns: 1fr;
                }
            }

            @media (max-width: 395px) {
                .meetings-grid {
                    grid-template-columns: 1fr !important;
                    gap: 1rem !important;
                    width: 100vw !important;
                    max-width: 100vw !important;
                    box-sizing: border-box !important;
                }

                .meeting-card {
                    padding: 0.75rem !important;
                    font-size: 0.85rem !important;
                    width: 100vw !important;
                    max-width: 100vw !important;
                    box-sizing: border-box !important;
                }

                .meeting-header {
                    flex-direction: column !important;
                    gap: 0.5rem !important;
                }

                .meeting-title {
                    font-size: 1rem !important;
                }

                .meeting-section-title {
                    font-size: 1.1rem !important;
                }

                .meeting-section-subtitle {
                    font-size: 0.85rem !important;
                }

                .meeting-content {
                    grid-template-columns: 1fr !important;
                    gap: 1rem !important;
                }

                .meeting-link-input,
                .copy-btn,
                .join-meeting-btn {
                    font-size: 0.85rem !important;
                    padding: 0.5rem 0.7rem !important;
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
                color: var(--primary);
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
                box-shadow: var(--shadow);
                padding: 1.5rem;
                border: 1px solid var(--divider);
                transition: all 0.3s ease;
            }

            .meeting-card:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
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
                color: var(--primary);
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
                background: var(--warning);
                color: #92400e;
            }

            .status-badge.ongoing {
                background: var(--success);
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
                color: var(--primary);
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
                border: 2px solid var(--divider);
                border-radius: 0.5rem;
                font-size: 0.875rem;
                background: var(--bg-secondary);
                color: var(--text-primary);
                font-family: 'Courier New', monospace;
            }

            .copy-btn {
                padding: 0.75rem 1rem;
                background: var(--text-secondary);
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
                background: var(--text-primary);
                transform: translateY(-1px);
            }

            .join-meeting-btn {
                padding: 1rem 1.5rem;
                background: var(--gradient-primary);
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
                box-shadow: var(--shadow);
            }

            .join-meeting-btn:hover {
                transform: translateY(-2px);
                box-shadow: var(--shadow-lg);
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
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Update current time every second
            function updateTime() {
                const now = new Date();
                const timeElement = document.getElementById('current-time');
                if (timeElement) {
                    timeElement.textContent = now.toLocaleTimeString([], {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }
            }

            // Update time immediately and then every second
            updateTime();
            setInterval(updateTime, 1000);

            // Mock chart data (you can replace this with actual chart library)
            document.addEventListener('DOMContentLoaded', function() {
                const canvas = document.getElementById('attendanceChart');
                if (canvas) {
                    const ctx = canvas.getContext('2d');
                    ctx.fillStyle = 'var(--text-secondary)';
                    ctx.font = '14px Inter';
                    ctx.textAlign = 'center';
                    ctx.fillText('Attendance chart will be rendered here', canvas.width / 2, canvas.height / 2);
                }
            });

            // Copy to clipboard function for meeting links
            function copyToClipboard(text, event) {
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


            const ctx = document.getElementById('attendanceChart').getContext('2d');
            const attendanceChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($attendance_labels),
                    datasets: [{
                            label: 'Present',
                            backgroundColor: '#4caf50',
                            data: @json($present_data),
                        },
                        {
                            label: 'Absent',
                            backgroundColor: '#f44336',
                            data: @json($absent_data),
                        },
                        {
                            label: 'Late',
                            backgroundColor: '#ff9800',
                            data: @json($late_data),
                        },
                        {
                            label: 'Half Day',
                            backgroundColor: '#2196f3',
                            data: @json($halfday_data),
                        }
                    ]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        </script>
    @endsection
