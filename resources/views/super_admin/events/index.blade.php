@extends('layouts.super_admin')

@section('title', 'Events Management')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-calendar-alt"></i>
            Events Management
        </h1>
        <p class="page-description">
            Manage and organize company events, meetings, and activities
        </p>
    </div>
    <div class="header-actions">
        <button class="btn btn-secondary" onclick="exportEvents()">
            <i class="fas fa-download"></i>
            Export Events
        </button>
        <a href="{{ route('super_admin.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            Create Event
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-icon total">
            <i class="fas fa-calendar"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $totalEvents }}</h3>
            <p class="stat-label">Total Events</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon upcoming">
            <i class="fas fa-clock"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $upcomingEvents }}</h3>
            <p class="stat-label">Upcoming Events</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon completed">
            <i class="fas fa-check-circle"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $completedEvents }}</h3>
            <p class="stat-label">Completed Events</p>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-icon month">
            <i class="fas fa-calendar-week"></i>
        </div>
        <div class="stat-content">
            <h3 class="stat-number">{{ $thisMonthEvents }}</h3>
            <p class="stat-label">This Month</p>
        </div>
    </div>
</div>

<!-- Content Tabs -->
<div class="content-tabs">
    <div class="tab-navigation">
        <button class="tab-button active" data-tab="all">
            <i class="fas fa-list-alt"></i>
            All Events
            <span class="tab-badge">{{ $totalEvents }}</span>
        </button>
        <button class="tab-button" data-tab="upcoming">
            <i class="fas fa-clock"></i>
            Upcoming
            <span class="tab-badge">{{ $upcomingEvents }}</span>
        </button>
        <button class="tab-button" data-tab="completed">
            <i class="fas fa-check-circle"></i>
            Completed
            <span class="tab-badge">{{ $completedEvents }}</span>
        </button>
        <button class="tab-button" data-tab="calendar">
            <i class="fas fa-calendar-alt"></i>
            Calendar View
        </button>
    </div>

    <!-- All Events Tab -->
    <div class="tab-panel active" id="all-panel">
        <div class="panel-header">
            <h3>All Events</h3>
            <div class="bulk-actions">
                <button class="btn btn-success" onclick="bulkPublish()">
                    <i class="fas fa-globe"></i>
                    Publish Selected
                </button>
                <button class="btn btn-danger" onclick="bulkDelete()">
                    <i class="fas fa-trash"></i>
                    Delete Selected
                </button>
            </div>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
            <div class="event-card">
                <div class="event-header">
                    <div class="event-type-badge badge-{{ $event->type }}">
                        {{ ucfirst($event->type) }}
                    </div>
                    <div class="event-status-badge badge-{{ $event->status }}">
                        {{ ucfirst($event->status) }}
                    </div>
                </div>

                <div class="event-content">
                    <h4 class="event-title">{{ $event->title }}</h4>
                    <p class="event-description">{{ Str::limit($event->description, 100) }}</p>

                    <div class="event-details">
                        <div class="event-detail">
                            <i class="fas fa-calendar"></i>
                            <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-clock"></i>
                            <span>{{ $event->time }}</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>{{ $event->location }}</span>
                        </div>
                        <div class="event-detail">
                            <i class="fas fa-users"></i>
                            <span>{{ $event->attendees }} attendees</span>
                        </div>
                    </div>

                    <div class="event-organizer">
                        <i class="fas fa-user-tie"></i>
                        <span>{{ $event->organizer }}</span>
                    </div>
                </div>

                <div class="event-actions">
                    <button class="btn btn-sm btn-info" onclick="viewEvent({{ $event->id }})">
                        <i class="fas fa-eye"></i>
                        View
                    </button>
                    <button class="btn btn-sm btn-primary" onclick="editEvent({{ $event->id }})">
                        <i class="fas fa-edit"></i>
                        Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="deleteEvent({{ $event->id }})">
                        <i class="fas fa-trash"></i>
                        Delete
                    </button>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <div class="empty-content">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Events Found</h4>
                    <p>Create your first event to get started.</p>
                    <a href="{{ route('super_admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Create Event
                    </a>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Upcoming Events Tab -->
    <div class="tab-panel" id="upcoming-panel">
        <div class="panel-header">
            <h3>Upcoming Events</h3>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
                @if($event->status === 'upcoming')
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-type-badge badge-{{ $event->type }}">
                            {{ ucfirst($event->type) }}
                        </div>
                        <div class="event-status-badge badge-{{ $event->status }}">
                            {{ ucfirst($event->status) }}
                        </div>
                    </div>

                    <div class="event-content">
                        <h4 class="event-title">{{ $event->title }}</h4>
                        <p class="event-description">{{ Str::limit($event->description, 100) }}</p>

                        <div class="event-details">
                            <div class="event-detail">
                                <i class="fas fa-calendar"></i>
                                <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $event->time }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-users"></i>
                                <span>{{ $event->attendees }} attendees</span>
                            </div>
                        </div>

                        <div class="event-organizer">
                            <i class="fas fa-user-tie"></i>
                            <span>{{ $event->organizer }}</span>
                        </div>
                    </div>

                    <div class="event-actions">
                        <button class="btn btn-sm btn-info" onclick="viewEvent({{ $event->id }})">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-primary" onclick="editEvent({{ $event->id }})">
                            <i class="fas fa-edit"></i>
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger" onclick="deleteEvent({{ $event->id }})">
                            <i class="fas fa-trash"></i>
                            Delete
                        </button>
                    </div>
                </div>
                @endif
            @empty
            <div class="empty-state">
                <div class="empty-content">
                    <i class="fas fa-calendar-times"></i>
                    <h4>No Upcoming Events</h4>
                    <p>No upcoming events scheduled.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Completed Events Tab -->
    <div class="tab-panel" id="completed-panel">
        <div class="panel-header">
            <h3>Completed Events</h3>
        </div>

        <div class="events-grid">
            @forelse($events as $event)
                @if($event->status === 'completed')
                <div class="event-card">
                    <div class="event-header">
                        <div class="event-type-badge badge-{{ $event->type }}">
                            {{ ucfirst($event->type) }}
                        </div>
                        <div class="event-status-badge badge-{{ $event->status }}">
                            {{ ucfirst($event->status) }}
                        </div>
                    </div>

                    <div class="event-content">
                        <h4 class="event-title">{{ $event->title }}</h4>
                        <p class="event-description">{{ Str::limit($event->description, 100) }}</p>

                        <div class="event-details">
                            <div class="event-detail">
                                <i class="fas fa-calendar"></i>
                                <span>{{ \Carbon\Carbon::parse($event->date)->format('M d, Y') }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-clock"></i>
                                <span>{{ $event->time }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $event->location }}</span>
                            </div>
                            <div class="event-detail">
                                <i class="fas fa-users"></i>
                                <span>{{ $event->attendees }} attendees</span>
                            </div>
                        </div>

                        <div class="event-organizer">
                            <i class="fas fa-user-tie"></i>
                            <span>{{ $event->organizer }}</span>
                        </div>
                    </div>

                    <div class="event-actions">
                        <button class="btn btn-sm btn-info" onclick="viewEvent({{ $event->id }})">
                            <i class="fas fa-eye"></i>
                            View
                        </button>
                        <button class="btn btn-sm btn-secondary" onclick="generateReport({{ $event->id }})">
                            <i class="fas fa-chart-bar"></i>
                            Report
                        </button>
                    </div>
                </div>
                @endif
            @empty
            <div class="empty-state">
                <div class="empty-content">
                    <i class="fas fa-calendar-check"></i>
                    <h4>No Completed Events</h4>
                    <p>No completed events found.</p>
                </div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Calendar View Tab -->
    <div class="tab-panel" id="calendar-panel">
        <div class="panel-header">
            <h3>Calendar View</h3>
            <div class="calendar-controls">
                <button class="btn btn-sm btn-secondary" onclick="previousMonth()">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <span class="current-month" id="currentMonth">July 2025</span>
                <button class="btn btn-sm btn-secondary" onclick="nextMonth()">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <div class="calendar-container">
            <div class="calendar-header">
                <div class="calendar-day-header">Sun</div>
                <div class="calendar-day-header">Mon</div>
                <div class="calendar-day-header">Tue</div>
                <div class="calendar-day-header">Wed</div>
                <div class="calendar-day-header">Thu</div>
                <div class="calendar-day-header">Fri</div>
                <div class="calendar-day-header">Sat</div>
            </div>
            <div class="calendar-body" id="calendarBody">
                <!-- Calendar will be populated by JavaScript -->
            </div>
        </div>
    </div>
</div>

<!-- Event Details Modal -->
<div class="modal" id="eventDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Event Details</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="eventDetailsContent">
            <!-- Content will be loaded here -->
        </div>
    </div>
</div>

<style>
/* Page Header */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding: 1.5rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.header-content h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.header-content h1 i {
    color: var(--primary);
}

.page-description {
    color: var(--text-secondary);
    margin: 0.5rem 0 0 0;
    font-size: 0.875rem;
}

.header-actions {
    display: flex;
    gap: 0.75rem;
}

/* Statistics Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.stat-card {
    background: #fff;
    padding: 1.5rem;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}

.stat-icon {
    width: 3rem;
    height: 3rem;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #fff;
}

.stat-icon.total { background: var(--info); }
.stat-icon.upcoming { background: var(--warning); }
.stat-icon.completed { background: var(--success); }
.stat-icon.month { background: var(--primary); }

.stat-number {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0;
}

.stat-label {
    color: var(--text-secondary);
    margin: 0;
    font-size: 0.875rem;
}

/* Content Tabs */
.content-tabs {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.tab-navigation {
    display: flex;
    border-bottom: 1px solid var(--border-light);
    background: var(--bg-secondary);
}

.tab-button {
    flex: 1;
    padding: 1rem 1.5rem;
    background: none;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-weight: 500;
    color: var(--text-secondary);
    transition: all 0.2s ease;
    position: relative;
}

.tab-button:hover {
    background: rgba(220, 38, 38, 0.05);
    color: var(--primary);
}

.tab-button.active {
    background: #fff;
    color: var(--primary);
    border-bottom: 2px solid var(--primary);
}

.tab-badge {
    background: var(--primary);
    color: #fff;
    padding: 0.25rem 0.5rem;
    border-radius: 1rem;
    font-size: 0.75rem;
    font-weight: 600;
}

.tab-panel {
    display: none;
    padding: 1.5rem;
}

.tab-panel.active {
    display: block;
}

/* Panel Header */
.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.panel-header h3 {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.bulk-actions {
    display: flex;
    gap: 0.75rem;
}

/* Events Grid */
.events-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
}

.event-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.event-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
}

.event-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-light);
}

.event-type-badge, .event-status-badge {
    padding: 0.375rem 0.75rem;
    border-radius: 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-company { background: #DBEAFE; color: #1E40AF; }
.badge-workshop { background: #F3E8FF; color: #7C3AED; }
.badge-product { background: #FEF3C7; color: #92400E; }
.badge-client { background: #D1FAE5; color: #065F46; }
.badge-recognition { background: #FEE2E2; color: #991B1B; }
.badge-conference { background: #E0E7FF; color: #3730A3; }

.badge-upcoming { background: #FEF3C7; color: #92400E; }
.badge-completed { background: #D1FAE5; color: #065F46; }

.event-content {
    padding: 1.5rem;
}

.event-title {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 0.75rem 0;
}

.event-description {
    color: var(--text-secondary);
    margin: 0 0 1rem 0;
    line-height: 1.5;
}

.event-details {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.event-detail {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
}

.event-detail i {
    color: var(--primary);
    width: 1rem;
}

.event-organizer {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: var(--text-secondary);
    font-weight: 500;
}

.event-organizer i {
    color: var(--primary);
}

.event-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: var(--bg-secondary);
    border-top: 1px solid var(--border-light);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.75rem;
    border-radius: 0.375rem;
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 1rem;
}

.empty-content {
    color: var(--text-secondary);
}

.empty-content i {
    font-size: 3rem;
    margin-bottom: 1rem;
    opacity: 0.5;
}

.empty-content h4 {
    margin: 0 0 0.5rem 0;
    color: var(--text-primary);
}

.empty-content p {
    margin: 0 0 1.5rem 0;
    font-size: 0.875rem;
}

/* Calendar */
.calendar-controls {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.current-month {
    font-weight: 600;
    color: var(--text-primary);
    min-width: 120px;
    text-align: center;
}

.calendar-container {
    background: #fff;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.calendar-header {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    background: var(--bg-secondary);
    border-bottom: 1px solid var(--border-light);
}

.calendar-day-header {
    padding: 1rem;
    text-align: center;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.calendar-body {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    min-height: 400px;
}

.calendar-day {
    padding: 0.5rem;
    border-right: 1px solid var(--border-light);
    border-bottom: 1px solid var(--border-light);
    min-height: 80px;
    position: relative;
}

.calendar-day.other-month {
    background: var(--bg-secondary);
    color: var(--text-light);
}

.calendar-day.today {
    background: var(--primary-light);
}

.calendar-day-number {
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.calendar-event {
    background: var(--primary);
    color: #fff;
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    margin-bottom: 0.25rem;
    cursor: pointer;
    transition: background 0.2s ease;
}

.calendar-event:hover {
    background: var(--primary-dark);
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}

.modal-content {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: #fff;
    border-radius: 12px;
    width: 90%;
    max-width: 600px;
    max-height: 80vh;
    overflow-y: auto;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid var(--border-light);
}

.modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    cursor: pointer;
    color: var(--text-secondary);
}

.modal-body {
    padding: 1.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .events-grid {
        grid-template-columns: 1fr;
    }

    .tab-navigation {
        flex-wrap: wrap;
    }

    .tab-button {
        flex: 1 1 50%;
    }

    .panel-header {
        flex-direction: column;
        gap: 1rem;
        align-items: flex-start;
    }

    .bulk-actions {
        width: 100%;
        justify-content: center;
    }

    .event-details {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
// Tab switching functionality
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');

        // Remove active class from all tabs and panels
        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

        // Add active class to clicked tab and corresponding panel
        button.classList.add('active');
        document.getElementById(tabName + '-panel').classList.add('active');

        // Initialize calendar if calendar tab is selected
        if (tabName === 'calendar') {
            initializeCalendar();
        }
    });
});

// Event management functions
function viewEvent(eventId) {
    // Load event details via AJAX
    fetch(`/super_admin/events/${eventId}`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('eventDetailsContent').innerHTML = html;
            document.getElementById('eventDetailsModal').style.display = 'block';
        })
        .catch(error => {
            console.error('Error loading event details:', error);
            alert('Error loading event details');
        });
}

function editEvent(eventId) {
    window.location.href = `/super_admin/events/${eventId}/edit`;
}

function deleteEvent(eventId) {
    if (confirm('Are you sure you want to delete this event?')) {
        fetch(`/super_admin/events/${eventId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Event deleted successfully');
                location.reload();
            } else {
                alert('Error: ' + data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error deleting event');
        });
    }
}

function bulkPublish() {
    alert('Bulk publish functionality will be implemented here');
}

function bulkDelete() {
    if (confirm('Are you sure you want to delete the selected events?')) {
        alert('Bulk delete functionality will be implemented here');
    }
}

function exportEvents() {
    alert('Export functionality will be implemented here');
}

function generateReport(eventId) {
    alert(`Report generation for event ${eventId} will be implemented here`);
}

function closeModal() {
    document.getElementById('eventDetailsModal').style.display = 'none';
}

let currentDate = new Date();

function initializeCalendar() {
    renderCalendar();
}

function renderCalendar() {
    const calendarBody = document.getElementById('calendarBody');
    const currentMonth = document.getElementById('currentMonth');

    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();

    currentMonth.textContent = new Date(year, month).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'long'
    });

    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const startDate = new Date(firstDay);
    startDate.setDate(startDate.getDate() - firstDay.getDay());

    let calendarHTML = '';

    for (let i = 0; i < 42; i++) {
        const date = new Date(startDate);
        date.setDate(startDate.getDate() + i);

        const isCurrentMonth = date.getMonth() === month;
        const isToday = date.toDateString() === new Date().toDateString();

        let dayClass = 'calendar-day';
        if (!isCurrentMonth) dayClass += ' other-month';
        if (isToday) dayClass += ' today';

        calendarHTML += `
            <div class="${dayClass}">
                <div class="calendar-day-number">${date.getDate()}</div>
                ${getEventsForDate(date)}
            </div>
        `;
    }

    calendarBody.innerHTML = calendarHTML;
}

function getEventsForDate(date) {
    const events = @json($events);
    const dateString = date.toISOString().split('T')[0];

    let eventsHTML = '';
    events.forEach(event => {
        const eventDate = new Date(event.date).toISOString().split('T')[0];
        if (eventDate === dateString) {
            eventsHTML += `
                <div class="calendar-event" onclick="viewEvent(${event.id})">
                    ${event.title}
                </div>
            `;
        }
    });

    return eventsHTML;
}

function previousMonth() {
    currentDate.setMonth(currentDate.getMonth() - 1);
    renderCalendar();
}

function nextMonth() {
    currentDate.setMonth(currentDate.getMonth() + 1);
    renderCalendar();
}

// Close modal when clicking outside
window.onclick = function(event) {
    const modal = document.getElementById('eventDetailsModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endsection
