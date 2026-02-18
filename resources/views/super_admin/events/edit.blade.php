@extends('layouts.super_admin')

@section('title', 'Edit Event')
<link rel="stylesheet" href="{{ asset('css/SuperAdmin/Event/eventEdit.css') }}">
@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-edit"></i>
            Edit Event
        </h1>
        <p class="page-description">
            Update event information and details
        </p>
    </div>
    <div class="header-actions">
        <a href="{{ route('super_admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i>
            Back to Events
        </a>
    </div>
</div>

<div class="form-container">
    <form action="{{ route('super_admin.events.update', $event['id']) }}" method="POST" class="event-form">
        @csrf
        @method('PUT')

        <div class="form-section">
            <h3>Basic Information</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="title">Event Title *</label>
                    <input type="text" id="title" name="title" required class="form-control" value="{{ $event['title'] }}" placeholder="Enter event title">
                </div>

                <div class="form-group">
                    <label for="type">Event Type *</label>
                    <select id="type" name="type" required class="form-control">
                        <option value="">Select event type</option>
                        <option value="company" {{ $event['type'] === 'company' ? 'selected' : '' }}>Company Meeting</option>
                        <option value="workshop" {{ $event['type'] === 'workshop' ? 'selected' : '' }}>Workshop</option>
                        <option value="product" {{ $event['type'] === 'product' ? 'selected' : '' }}>Product Launch</option>
                        <option value="client" {{ $event['type'] === 'client' ? 'selected' : '' }}>Client Presentation</option>
                        <option value="recognition" {{ $event['type'] === 'recognition' ? 'selected' : '' }}>Recognition Ceremony</option>
                        <option value="conference" {{ $event['type'] === 'conference' ? 'selected' : '' }}>Conference</option>
                        <option value="training" {{ $event['type'] === 'training' ? 'selected' : '' }}>Training Session</option>
                        <option value="social" {{ $event['type'] === 'social' ? 'selected' : '' }}>Social Event</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required class="form-control" rows="4" placeholder="Describe the event details, objectives, and what attendees can expect">{{ $event['description'] }}</textarea>
            </div>
        </div>

        <div class="form-section">
            <h3>Date & Time</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="date">Event Date *</label>
                    <input type="date" id="date" name="date" required class="form-control" value="{{ $event['date']->format('Y-m-d') }}">
                </div>

                <div class="form-group">
                    <label for="time">Event Time *</label>
                    <input type="time" id="time" name="time" required class="form-control" value="{{ $event['time'] }}">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="duration">Duration (hours)</label>
                    <input type="number" id="duration" name="duration" min="0.5" max="24" step="0.5" class="form-control" placeholder="2.5" value="{{ $event['duration'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control">
                        <option value="upcoming" {{ $event['status'] === 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="draft" {{ $event['status'] === 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="cancelled" {{ $event['status'] === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="completed" {{ $event['status'] === 'completed' ? 'selected' : '' }}>Completed</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Location & Capacity</h3>

            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required class="form-control" value="{{ $event['location'] }}" placeholder="Enter venue or room">
                </div>

                <div class="form-group">
                    <label for="attendees">Expected Attendees</label>
                    <input type="number" id="attendees" name="attendees" min="1" class="form-control" placeholder="50" value="{{ $event['attendees'] }}">
                </div>
            </div>

            <div class="form-group">
                <label for="organizer">Organizer *</label>
                <input type="text" id="organizer" name="organizer" required class="form-control" value="{{ $event['organizer'] }}" placeholder="Department or person responsible">
            </div>
        </div>

        <div class="form-section">
            <h3>Additional Details</h3>

            <div class="form-group">
                <label for="agenda">Agenda (Optional)</label>
                <textarea id="agenda" name="agenda" class="form-control" rows="4" placeholder="Enter agenda items, one per line">{{ isset($event['agenda']) ? implode("\n", $event['agenda']) : '' }}</textarea>
                <small class="form-help">Enter each agenda item on a new line</small>
            </div>

            <div class="form-group">
                <label for="requirements">Requirements (Optional)</label>
                <textarea id="requirements" name="requirements" class="form-control" rows="3" placeholder="Enter requirements for attendees">{{ isset($event['requirements']) ? implode("\n", $event['requirements']) : '' }}</textarea>
                <small class="form-help">Enter each requirement on a new line</small>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-control" placeholder="organizer@company.com" value="{{ $event['contact'] ?? '' }}">
                </div>

                <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="tel" id="contact_phone" name="contact_phone" class="form-control" placeholder="+1 (555) 123-4567" value="{{ $event['phone'] ?? '' }}">
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="button" class="btn btn-secondary" onclick="window.location.href='{{ route('super_admin.events.index') }}'">
                <i class="fas fa-times"></i>
                Cancel
            </button>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                Update Event
            </button>
        </div>
    </form>
</div>


<script>

document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });

    textarea.style.height = 'auto';
    textarea.style.height = textarea.scrollHeight + 'px';
});

document.querySelector('.event-form').addEventListener('submit', function(e) {
    const requiredFields = this.querySelectorAll('[required]');
    let isValid = true;

    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.style.borderColor = '#ef4444';
            isValid = false;
        } else {
            field.style.borderColor = '';
        }
    });

    if (!isValid) {
        e.preventDefault();
        alert('Please fill in all required fields.');
    }
});

document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        if (this.style.borderColor === 'rgb(239, 68, 68)') {
            this.style.borderColor = '';
        }
    });
});
</script>
@endsection
