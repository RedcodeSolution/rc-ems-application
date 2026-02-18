@extends('layouts.super_admin')

@section('title', 'Events Management')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/Event/eventManagement.css') }}">
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
            </div>
        </div>
    </div>
</div>

<div class="modal" id="eventDetailsModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Event Details</h3>
            <button class="modal-close" onclick="closeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" id="eventDetailsContent">
        </div>
    </div>
</div>

<script>
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', () => {
        const tabName = button.getAttribute('data-tab');

        document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
        document.querySelectorAll('.tab-panel').forEach(panel => panel.classList.remove('active'));

        button.classList.add('active');
        document.getElementById(tabName + '-panel').classList.add('active');

        if (tabName === 'calendar') {
            initializeCalendar();
        }
    });
});

function viewEvent(eventId) {
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

document.addEventListener('DOMContentLoaded', function () {
    const activeTab = document.querySelector('.tab-navigation .tab-button.active');
    if (activeTab) {
        activeTab.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
    }

    document.querySelectorAll('.tab-navigation .tab-button').forEach(btn => {
        btn.addEventListener('click', () => {
            setTimeout(() => {
                btn.scrollIntoView({ inline: 'center', behavior: 'smooth', block: 'nearest' });
            }, 120);
        });
    });
});

window.onclick = function(event) {
    const modal = document.getElementById('eventDetailsModal');
    if (event.target === modal) {
        closeModal();
    }
}
</script>
@endsection
