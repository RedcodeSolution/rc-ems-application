@extends('layouts.super_admin')

@section('title', 'Create Event')

@section('content')
<div class="page-header">
    <div class="header-content">
        <h1 class="page-title">
            <i class="fas fa-plus-circle"></i>
            Create New Event
        </h1>
        <p class="page-description">
            Add a new event to the company calendar
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
    @if ($errors->any())
        <div class="alert alert-danger" style="margin: 2rem;">
            <ul style="margin: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('super_admin.events.store') }}" method="POST" class="event-form">
        @csrf

        <div class="form-section">
            <div class="form-group">
                <label for="title">Event Title *</label>
                <input type="text" id="title" name="title" required class="form-control @error('title') is-invalid @enderror" placeholder="Enter event title" value="{{ old('title') }}">
                @error('title')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="type">Event Type *</label>
                <select id="type" name="type" required class="form-control @error('type') is-invalid @enderror">
                    <option value="">Select event type</option>
                    <option value="company" {{ old('type') == 'company' ? 'selected' : '' }}>Company Meeting</option>
                    <option value="workshop" {{ old('type') == 'workshop' ? 'selected' : '' }}>Workshop</option>
                    <option value="product" {{ old('type') == 'product' ? 'selected' : '' }}>Product Launch</option>
                    <option value="client" {{ old('type') == 'client' ? 'selected' : '' }}>Client Presentation</option>
                    <option value="recognition" {{ old('type') == 'recognition' ? 'selected' : '' }}>Recognition Ceremony</option>
                    <option value="conference" {{ old('type') == 'conference' ? 'selected' : '' }}>Conference</option>
                    <option value="training" {{ old('type') == 'training' ? 'selected' : '' }}>Training Session</option>
                    <option value="social" {{ old('type') == 'social' ? 'selected' : '' }}>Social Event</option>
                </select>
                @error('type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="description">Description *</label>
                <textarea id="description" name="description" required class="form-control @error('description') is-invalid @enderror" rows="4" placeholder="Describe the event details, objectives, and what attendees can expect">{{ old('description') }}</textarea>
                @error('description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h3>Date & Time</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="date">Event Date *</label>
                    <input type="date" id="date" name="date" required class="form-control @error('date') is-invalid @enderror" value="{{ old('date') }}">
                    @error('date')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="time">Event Time *</label>
                    <input type="time" id="time" name="time" required class="form-control @error('time') is-invalid @enderror" value="{{ old('time') }}">
                    @error('time')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="duration">Duration (hours)</label>
                    <input type="number" id="duration" name="duration" min="0.5" max="24" step="0.5" class="form-control @error('duration') is-invalid @enderror" placeholder="2.5" value="{{ old('duration') }}">
                    @error('duration')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="status">Status</label>
                    <select id="status" name="status" class="form-control @error('status') is-invalid @enderror">
                        <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>Upcoming</option>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
        </div>

        <div class="form-section">
            <h3>Location & Capacity</h3>
            <div class="form-row">
                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required class="form-control @error('location') is-invalid @enderror" placeholder="Enter venue or room" value="{{ old('location') }}">
                    @error('location')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="attendees">Expected Attendees</label>
                    <input type="number" id="attendees" name="attendees" min="1" class="form-control @error('attendees') is-invalid @enderror" placeholder="50" value="{{ old('attendees') }}">
                    @error('attendees')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="form-group">
                <label for="organizer">Organizer *</label>
                <input type="text" id="organizer" name="organizer" required class="form-control @error('organizer') is-invalid @enderror" placeholder="Department or person responsible" value="{{ old('organizer') }}">
                @error('organizer')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="form-section">
            <h3>Additional Details</h3>
            <div class="form-group">
                <label for="agenda">Agenda (Optional)</label>
                <textarea id="agenda" name="agenda" class="form-control @error('agenda') is-invalid @enderror" rows="4" placeholder="Enter agenda items, one per line">{{ old('agenda') }}</textarea>
                <small class="form-help">Enter each agenda item on a new line</small>
                @error('agenda')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-group">
                <label for="requirements">Requirements (Optional)</label>
                <textarea id="requirements" name="requirements" class="form-control @error('requirements') is-invalid @enderror" rows="3" placeholder="Enter requirements for attendees">{{ old('requirements') }}</textarea>
                <small class="form-help">Enter each requirement on a new line</small>
                @error('requirements')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" class="form-control @error('contact_email') is-invalid @enderror" placeholder="organizer@company.com" value="{{ old('contact_email') }}">
                    @error('contact_email')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="tel" id="contact_phone" name="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" placeholder="+1 (555) 123-4567" value="{{ old('contact_phone') }}">
                    @error('contact_phone')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
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
                Create Event
            </button>
        </div>
    </form>
</div>

<style>
.form-container {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    overflow: hidden;
}

.event-form {
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid var(--border-light);
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.form-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0 0 1.5rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.form-section h3::before {
    content: '';
    width: 4px;
    height: 1.125rem;
    background: var(--primary);
    border-radius: 2px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.form-row:last-child {
    margin-bottom: 0;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.form-group label {
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
}

.form-control {
    padding: 0.75rem 1rem;
    border: 1px solid var(--border-light);
    border-radius: 0.5rem;
    font-size: 0.875rem;
    transition: all 0.2s ease;
    background: #fff;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1);
}

.form-control::placeholder {
    color: var(--text-light);
}

.form-help {
    font-size: 0.75rem;
    color: var(--text-secondary);
    margin-top: 0.25rem;
}

.form-actions {
    display: flex;
    justify-content: flex-end;
    gap: 1rem;
    padding-top: 2rem;
    border-top: 1px solid var(--border-light);
    margin-top: 2rem;
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

/* Responsive */
@media (max-width: 768px) {
    .event-form {
        padding: 1rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .form-actions {
        flex-direction: column-reverse;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<script>
// Set default date to today
document.getElementById('date').value = new Date().toISOString().split('T')[0];

// Auto-resize textareas
document.querySelectorAll('textarea').forEach(textarea => {
    textarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = this.scrollHeight + 'px';
    });
});

// Form validation
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

// Remove error styling on input
document.querySelectorAll('.form-control').forEach(input => {
    input.addEventListener('input', function() {
        if (this.style.borderColor === 'rgb(239, 68, 68)') {
            this.style.borderColor = '';
        }
    });
});
</script>
@endsection
