<div class="event-details-view">
    <div class="event-header-section">
        <div class="event-title-section">
            <h2 class="event-title">{{ $event['title'] }}</h2>
            <div class="event-badges">
                <span class="badge badge-{{ $event['type'] }}">
                    {{ ucfirst($event['type']) }}
                </span>
                <span class="badge badge-{{ $event['status'] }}">
                    {{ ucfirst($event['status']) }}
                </span>
            </div>
        </div>
        <div class="event-meta">
            <div class="meta-item">
                <i class="fas fa-calendar"></i>
                <span>{{ $event['date'] ? $event['date']->format('l, F d, Y') : '-' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-clock"></i>
                <span>{{ $event['time'] ?? '-' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ $event['location'] ?? '-' }}</span>
            </div>
            <div class="meta-item">
                <i class="fas fa-users"></i>
                <span>{{ $event['attendees'] ?? 0 }} attendees</span>
            </div>
        </div>
    </div>

    <div class="event-description-section">
        <h3>Description</h3>
        <p>{{ $event['description'] }}</p>
    </div>

    @if(!empty($event['agenda']))
    <div class="event-agenda-section">
        <h3>Agenda</h3>
        <ul class="agenda-list">
            @foreach($event['agenda'] as $item)
            <li class="agenda-item">
                <i class="fas fa-check-circle"></i>
                <span>{{ $item }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(!empty($event['requirements']))
    <div class="event-requirements-section">
        <h3>Requirements</h3>
        <ul class="requirements-list">
            @foreach($event['requirements'] as $requirement)
            <li class="requirement-item">
                <i class="fas fa-info-circle"></i>
                <span>{{ $requirement }}</span>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="event-contact-section">
        <h3>Contact Information</h3>
        <div class="contact-info">
            <div class="contact-item">
                <i class="fas fa-user-tie"></i>
                <span><strong>Organizer:</strong> {{ $event['organizer'] }}</span>
            </div>
            @if(!empty($event['contact']))
            <div class="contact-item">
                <i class="fas fa-envelope"></i>
                <span><strong>Email:</strong> {{ $event['contact'] }}</span>
            </div>
            @endif
            @if(!empty($event['phone']))
            <div class="contact-item">
                <i class="fas fa-phone"></i>
                <span><strong>Phone:</strong> {{ $event['phone'] }}</span>
            </div>
            @endif
        </div>
    </div>

    <div class="event-actions-section">
        <button class="btn btn-primary" onclick="editEvent({{ $event['id'] }})">
            <i class="fas fa-edit"></i>
            Edit Event
        </button>
        <button class="btn btn-secondary" onclick="closeModal()">
            <i class="fas fa-times"></i>
            Close
        </button>
    </div>
</div>

<style>
.event-details-view {
    padding: 0;
}

.event-header-section {
    margin-bottom: 2rem;
}

.event-title-section {
    margin-bottom: 1rem;
}

.event-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
}

.event-badges {
    display: flex;
    gap: 0.5rem;
}

.badge {
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

.event-meta {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-secondary);
    font-size: 0.875rem;
}

.meta-item i {
    color: var(--primary);
    width: 1rem;
}

.event-description-section,
.event-agenda-section,
.event-requirements-section,
.event-contact-section {
    margin-bottom: 2rem;
}

.event-description-section h3,
.event-agenda-section h3,
.event-requirements-section h3,
.event-contact-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 1rem 0;
}

.event-description-section p {
    color: var(--text-secondary);
    line-height: 1.6;
    margin: 0;
}

.agenda-list,
.requirements-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.agenda-item,
.requirement-item {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.5rem 0;
    color: var(--text-secondary);
    line-height: 1.5;
}

.agenda-item i {
    color: var(--success);
    margin-top: 0.125rem;
}

.requirement-item i {
    color: var(--info);
    margin-top: 0.125rem;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: var(--text-secondary);
}

.contact-item i {
    color: var(--primary);
    width: 1rem;
}

.event-actions-section {
    display: flex;
    gap: 0.75rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-weight: 600;
    text-decoration: none;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-primary {
    background: var(--primary);
    color: #fff;
}

.btn-primary:hover {
    background: var(--primary-dark);
    transform: translateY(-1px);
}

.btn-secondary {
    background: var(--bg-secondary);
    color: var(--text-primary);
    border: 1px solid var(--border-light);
}

.btn-secondary:hover {
    background: var(--border-light);
}

.btn-success {
    background: var(--success);
    color: #fff;
}

.btn-success:hover {
    background: #047857;
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .event-meta {
        grid-template-columns: 1fr;
    }

    .event-actions-section {
        flex-direction: column;
    }

    .event-badges {
        flex-wrap: wrap;
    }
}
</style>

<script>
function editEvent(eventId) {
    window.location.href = `/super_admin/events/${eventId}/edit`;
}


</script>
