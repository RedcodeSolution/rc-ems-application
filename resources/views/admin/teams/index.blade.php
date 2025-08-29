@extends('layouts.admin')

<style>
/* Modern Teams Management Styles */
.card {
    border-radius: 1rem;
    box-shadow: 0 2px 16px 0 rgba(0,0,0,0.07);
    border: none;
    background: #fff;
    transition: box-shadow 0.2s;
}
.card:hover {
    box-shadow: 0 6px 32px 0 rgba(37,99,235,0.10);
}
.card-header {
    border-bottom: 1px solid #f1f1f1;
    background: linear-gradient(90deg, #f8fafc 60%, #e0e7ef 100%);
    border-radius: 1rem 1rem 0 0;
    padding: 1.5rem 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.card-body {
    padding: 2rem;
}
.btn {
    border-radius: 0.75rem;
    font-weight: 500;
    transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.04);
    border: none;
    outline: none;
}
.btn:active {
    transform: scale(0.97);
}
.btn-primary {
    background: linear-gradient(90deg, #2563eb 60%, #1d4ed8 100%);
    color: #fff;
}
.btn-primary:hover {
    background: linear-gradient(90deg, #1d4ed8 60%, #2563eb 100%);
}
.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}
.btn-secondary:hover {
    background: #e5e7eb;
}
.btn-warning {
    background: #fbbf24;
    color: #fff;
}
.btn-warning:hover {
    background: #f59e42;
}
.btn-info {
    background: #0ea5e9;
    color: #fff;
}
.btn-info:hover {
    background: #0369a1;
}
.badge {
    font-weight: 600;
    letter-spacing: 0.02em;
    display: inline-block;
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
    box-shadow: 0 1px 4px 0 rgba(0,0,0,0.03);
}
.form-input, .form-select {
    border-radius: 0.5rem;
    border: 1px solid #e5e7eb;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    background: #f9fafb;
    transition: border 0.2s, box-shadow 0.2s;
}
.form-input:focus, .form-select:focus {
    border-color: #2563eb;
    outline: none;
    box-shadow: 0 0 0 2px #2563eb22;
}
.flex {
    display: flex;
}
.gap-1 { gap: 0.25rem; }
.gap-2 { gap: 0.75rem; }
.gap-3 { gap: 1.25rem; }
.justify-between { justify-content: space-between; }
.items-center { align-items: center; }
.text-center { text-align: center; }
.mt-4 { margin-top: 1.5rem; }
.mb-4 { margin-bottom: 1.5rem; }
.card-header h3, .card-header h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.card-body h3, .card-body h2 {
    margin: 0;
    font-weight: 700;
    color: #1e293b;
}
.card-body p {
    color: #64748b;
    margin-bottom: 1rem;
    line-height: 1.7;
}
.card .text-center > div:first-child {
    letter-spacing: 0.01em;
}
.card .text-center > div:last-child {
    font-size: 0.95rem;
}
input[type="text"].form-input::placeholder {
    color: #a0aec0;
    opacity: 1;
}
.card-body .badge {
    font-size: 0.75rem;
    padding: 0.25rem 0.75rem;
    border-radius: 1rem;
}
.card-body .flex[style*="display: flex; -webkit-box-orient: horizontal"] > div {
    box-shadow: 0 1px 4px 0 rgba(37,99,235,0.04);
}
.card-body .flex[style*="display: flex; -webkit-box-orient: horizontal"] > div:hover {
    box-shadow: 0 2px 8px 0 rgba(37,99,235,0.08);
}
@media (max-width: 900px) {
    .card-body, .card-header { padding: 1rem; }
    .card-header h3, .card-header h2 { font-size: 1rem; }
}
::-webkit-scrollbar {
    height: 8px;
    background: #f3f4f6;
    border-radius: 4px;
}
::-webkit-scrollbar-thumb {
    background: #e5e7eb;
    border-radius: 4px;
}

/* RedCode Solutions Color Palette */
:root {
    --redcode-primary: #DC2626; /* RedCode Brand Red */
    --redcode-primary-dark: #991B1B; /* Deep Red */
    --redcode-primary-light: #FEE2E2; /* Light Red Background */
    --redcode-accent: #B91C1C; /* Accent Red */
    --redcode-dark: #1F2937; /* Charcoal for headers/nav */
    --redcode-gray: #6B7280; /* Medium Gray for text */
    --redcode-light: #F9FAFB; /* Light Background */
    --redcode-white: #FFFFFF; /* Pure White */
    --redcode-blue: #2563EB; /* Links, buttons */
    --redcode-green: #059669; /* Success states */
    --redcode-orange: #D97706; /* Warnings */
    --redcode-yellow: #F59E0B; /* Alerts */
    --text-primary: #111827; /* Almost Black */
    --text-secondary: #6B7280; /* Medium Gray */
    --text-light: #9CA3AF; /* Light Gray */
    --text-white: #FFFFFF; /* White Text */
    --border-light: #E5E7EB;
    --border-medium: #D1D5DB;
    --border-dark: #6B7280;
    --gradient-primary: linear-gradient(135deg, #DC2626 0%, #991B1B 100%);
    --gradient-hero: linear-gradient(135deg, #DC2626 0%, #1F2937 50%, #991B1B 100%);
    --gradient-glass: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0.05) 100%);
}

/* Modal Styles */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    backdrop-filter: blur(8px);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active {
    opacity: 1;
    visibility: visible;
}

.modal-container {
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(20px);
    border-radius: 2rem;
    width: 90%;
    max-width: 800px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow:
        0 32px 64px rgba(220,38,38,0.15),
        0 0 0 1px rgba(255,255,255,0.05),
        inset 0 1px 0 rgba(255,255,255,0.1);
    border: 1px solid var(--border-light);
    position: relative;
    transform: scale(0.9) translateY(20px);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.modal-overlay.active .modal-container {
    transform: scale(1) translateY(0);
}

.modal-header {
    padding: 2rem 2rem 0 2rem;
    border-bottom: 1px solid var(--border-light);
    margin-bottom: 2rem;
    position: relative;
}

.modal-title {
    font-size: 2rem;
    font-weight: 800;
    background: var(--gradient-primary);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.modal-subtitle {
    color: var(--text-secondary);
    font-size: 1rem;
    font-weight: 500;
    margin-bottom: 1.5rem;
}

.modal-close {
    position: absolute;
    top: 1rem;
    right: 1.5rem;
    background: rgba(220, 38, 38, 0.1);
    border: none;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.3s ease;
    color: var(--redcode-primary);
}

.modal-close:hover {
    background: rgba(220, 38, 38, 0.2);
    transform: scale(1.1);
}

.modal-body {
    padding: 0 2rem 2rem 2rem;
}

/* Enhanced Form Styles */
.form-container {
    display: grid;
    gap: 1.5rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1.5rem;
}

.form-group {
    position: relative;
}

.form-label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 0.875rem;
    letter-spacing: 0.025em;
}

.form-label i {
    margin-right: 0.5rem;
    color: var(--redcode-primary);
}

.form-input, .form-select, .form-textarea {
    width: 100%;
    padding: 12px 16px 12px 48px;
    border: 2px solid var(--border-light);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    background: rgba(248, 250, 252, 0.5);
    backdrop-filter: blur(10px);
    color: var(--text-primary);
    font-weight: 500;
    box-sizing: border-box;
    min-height: 48px;
    display: flex;
    align-items: center;
}

.form-textarea {
    min-height: 100px;
    resize: vertical;
    font-family: inherit;
    padding: 16px 16px 16px 48px;
    align-items: flex-start;
    line-height: 1.5;
}

.form-input:focus, .form-select:focus, .form-textarea:focus {
    outline: none;
    border-color: var(--redcode-primary);
    background: rgba(255, 255, 255, 0.9);
    box-shadow:
        0 0 0 4px rgba(220,38,38,0.08),
        0 8px 25px rgba(220,38,38,0.12);
    transform: translateY(-2px);
}

.input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-light);
    transition: all 0.3s;
    z-index: 3;
    pointer-events: none;
    font-size: 1rem;
    width: 16px;
    text-align: center;
    display: flex;
    align-items: center;
    justify-content: center;
}

.form-group:has(.form-textarea) .input-icon {
    top: 24px;
    transform: translateY(0);
}

.form-group:has(.form-input:focus) .input-icon,
.form-group:has(.form-select:focus) .input-icon,
.form-group:has(.form-textarea:focus) .input-icon {
    color: var(--redcode-primary);
    transform: translateY(-50%) scale(1.1);
}

.form-select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 12px center;
    background-repeat: no-repeat;
    background-size: 16px;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
    padding-top: 1.5rem;
    border-top: 1px solid var(--border-light);
}

.btn {
    padding: 12px 24px;
    border-radius: 0.75rem;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: none;
    position: relative;
    overflow: hidden;
    letter-spacing: 0.025em;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: var(--gradient-primary);
    color: white;
    box-shadow:
        0 8px 25px rgba(220,38,38,0.18),
        0 3px 10px rgba(153,27,27,0.12);
}

.btn-primary::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
    transition: left 0.5s;
}

.btn-primary:hover::before {
    left: 100%;
}

.btn-primary:hover {
    transform: translateY(-2px) scale(1.02);
    box-shadow:
        0 15px 35px rgba(220,38,38,0.22),
        0 5px 15px rgba(153,27,27,0.18);
}

.btn-secondary {
    background: var(--border-light);
    color: var(--text-secondary);
    border: 1px solid var(--border-medium);
}

.btn-secondary:hover {
    background: var(--border-medium);
    transform: translateY(-2px);
}

/* Custom Scrollbar for Modal */
.modal-container::-webkit-scrollbar {
    width: 8px;
}

.modal-container::-webkit-scrollbar-track {
    background: rgba(0, 0, 0, 0.05);
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb {
    background: rgba(220,38,38,0.2);
    border-radius: 4px;
}

.modal-container::-webkit-scrollbar-thumb:hover {
    background: rgba(220,38,38,0.4);
}

/* Error Messages */
.error-message {
    background: rgba(217, 119, 6, 0.1);
    border: 1px solid rgba(217, 119, 6, 0.2);
    color: var(--redcode-orange);
    padding: 12px 16px;
    border-radius: 0.75rem;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

/* View Modal Styles */
.view-field {
    width: 100%;
    padding: 12px 16px 12px 48px; /* Text Start: 48px from left (16px icon + 16px width + 16px buffer = 48px) */
    border: 2px solid var(--border-light);
    border-radius: 0.75rem;
    font-size: 0.9rem;
    background: rgba(248, 250, 252, 0.8);
    color: var(--text-primary);
    font-weight: 500;
    box-sizing: border-box;
    min-height: 48px;
    display: flex;
    align-items: center;
    position: relative;
    cursor: default;
    transition: all 0.3s ease;
}

.view-field:hover {
    background: rgba(220, 38, 38, 0.02);
    border-color: rgba(220, 38, 38, 0.1);
}

.view-textarea {
    min-height: 100px;
    align-items: flex-start;
    padding-top: 16px;
    padding-bottom: 16px;
    white-space: pre-wrap;
    word-wrap: break-word;
    line-height: 1.5;
}

/* Special positioning for view modal textarea icons */
.form-group:has(.view-textarea) .input-icon {
    top: 24px; /* Position icon in the top area of textarea instead of center */
    transform: translateY(0); /* Remove center transform for textarea */
}

.view-field:empty::before {
    content: 'No data available';
    color: var(--text-light);
    font-style: italic;
}

/* Status badge styling in view modal */
.view-field.status-badge {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--redcode-green);
    font-weight: 600;
}

.view-field.status-badge.active {
    background: rgba(16, 185, 129, 0.1);
    border-color: rgba(16, 185, 129, 0.3);
    color: var(--redcode-green);
}

.view-field.status-badge.inactive {
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
    color: var(--redcode-primary);
}

.view-field.status-badge.on-hold {
    background: rgba(245, 158, 11, 0.1);
    border-color: rgba(245, 158, 11, 0.3);
    color: var(--redcode-orange);
}

/* Responsive */
@media (max-width: 768px) {
    .modal-container {
        width: 95%;
        margin: 1rem;
        border-radius: 1.5rem;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .modal-header, .modal-body {
        padding: 1.5rem;
    }

    .modal-title {
        font-size: 1.5rem;
    }

    .form-actions {
        flex-direction: column;
    }
}
</style>

@section('title', 'Teams Management')

@section('content')
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users-cog"></i> Teams</h2>
        <div class="flex gap-2">
            <button onclick="openTeamModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                New Team
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Search Section -->
        <div class="flex justify-between items-center mb-4">
            <form method="GET" action="{{ route('admin.teams') }}" class="flex justify-between items-center mb-4" style="gap: 1rem;">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search teams..."
                    class="form-input"
                    style="width: 300px;"
                >
                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </form>
        </div>

        <!-- Teams Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(350px, 1fr)); gap: 1.5rem;">
            @forelse($teams as $team)
                <div class="card" style="padding: 1.5rem;">
                    <div class="card-header">
                        <h3 style="font-size: 1.125rem; font-weight: 600; color: var(--gray-800);">
                            <i class="fas fa-code" style="color: var(--primary);"></i>
                            {{ $team->team_name }}
                        </h3>
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;"
                                onclick="openViewTeamModal(
                                    '{{ $team->team_name }}',
                                    '',
                                    '{{ optional($departments->firstWhere('department_id', $team->department_id))->department_name ?? '' }}',
                                    '{{ $team->team_lead ?? '' }}',
                                    '{{ $team->max_team_size }}',
                                    '{{ $team->monthly_budget }}',
                                    '{{ $team->team_status }}',
                                    '{{ $team->team_priority }}',
                                    '',
                                    '{{ $team->work_mode }}',
                                    `{{ $team->team_description ?? '' }}`,
                                    `{{ $team->team_goals ?? '' }}`,
                                    `{{ $team->skills_required ?? '' }}`
                                )">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;"
                                onclick="openEditTeamModal(
                                    '{{ $team->team_id }}',
                                    '{{ $team->team_name }}',
                                    '{{ $team->department_id }}',
                                    '{{ $team->team_lead ?? '' }}',
                                    '{{ $team->max_team_size }}',
                                    '{{ $team->monthly_budget }}',
                                    '{{ $team->team_status }}',
                                    '{{ $team->team_priority }}',
                                    '{{ $team->work_mode }}',
                                    `{{ $team->team_description ?? '' }}`,
                                    `{{ $team->team_goals ?? '' }}`,
                                    `{{ $team->skills_required ?? '' }}`
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form action="{{ route('teams.destroy', $team->team_id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" style="padding: 0.5rem; background: #dc3545; color: white;" onclick="return confirm('Are you sure you want to delete this team?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Team Lead -->
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Lead</div>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                @php
                                    $leadName = $team->team_lead ?? 'N/A';
                                    $leadInitials = $leadName !== 'N/A'
                                        ? collect(explode(' ', $leadName))->map(fn($w) => strtoupper(substr($w,0,1)))->implode('')
                                        : 'NA';
                                @endphp
                                <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--primary), var(--secondary)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700;">
                                    {{ $leadInitials }}
                                </div>
                                <div style="font-weight: 600;">{{ $leadName }}</div>
                            </div>
                        </div>
                        <!-- Team Members -->
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Team Members</div>
                            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                <div style="display: flex; -webkit-box-orient: horizontal; -webkit-box-direction: reverse; flex-direction: row-reverse; justify-content: flex-end;">
                                    @php
                                        $members = $team->employees->take(3);
                                    @endphp
                                    @foreach($members as $member)
                                        @php
                                            $initials = collect(explode(' ', $member->employee_name))->map(fn($w) => strtoupper(substr($w,0,1)))->implode('');
                                        @endphp
                                        <div style="width: 2rem; height: 2rem; background: linear-gradient(135deg, var(--success), var(--info)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">
                                            {{ $initials }}
                                        </div>
                                    @endforeach
                                    @if($team->employees->count() > 3)
                                        <div style="width: 2rem; height: 2rem; background: var(--gray-400); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 0.75rem; font-weight: 700; margin-left: -0.5rem; border: 2px solid white;">
                                            +{{ $team->employees->count() - 3 }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">
                                {{ $team->employees->count() }} Members
                            </div>
                        </div>
                        <!-- Active Projects -->
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Active Projects</div>
                            <div style="font-size: 1.25rem; font-weight: 700; color: var(--success);">
                                {{ $team->projects->count() }} Projects
                            </div>
                        </div>
                        <!-- Department -->
                        <div style="margin-bottom: 1rem;">
                            <div style="font-size: 0.875rem; color: var(--gray-600); margin-bottom: 0.5rem;">Department</div>
                            <div style="font-weight: 600;">
                                {{ optional($departments->firstWhere('department_id', $team->department_id))->department_name ?? 'N/A' }}
                            </div>
                        </div>
                        <!-- Status and Manage Members -->
                        <div class="flex justify-between items-center">
                            <span class="badge" style="background: rgba(16, 185, 129, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">
                                {{ $team->team_status }}
                            </span>
                            <button
                                class="btn btn-secondary"
                                style="padding: 0.5rem 1rem; font-size: 0.75rem;"
                                onclick="openManageMembersModal({{ $team->team_id }}, @json($team->employees->pluck('employee_id')), '{{ $team->team_name }}')"
                                type="button"
                            >
                                <i class="fas fa-users"></i>
                                Manage Members
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1/-1; text-align: center; color: #a0aec0;">
                    No teams found.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Team Creation Modal -->
<div id="teamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-users-cog"></i>
                Create New Team
            </div>
            <div class="modal-subtitle">Build your team with the right people and structure</div>
            <button class="modal-close" onclick="closeTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <form action="{{ route('teams.store') }}" method="POST" id="teamForm">
                @csrf
                <div class="form-container">
                    <!-- Team Name -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_name" class="form-label">
                                <i class="fas fa-users"></i>Team Name
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="team_name" name="team_name" class="form-input" placeholder="Enter team name" required>
                            </div>
                        </div>
                    </div>
                    <!-- Department and Team Lead Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="department_id" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                <select id="department_id" name="department_id" class="form-select" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="team_lead" class="form-label">
                                <i class="fas fa-user-tie"></i>Team Lead
                            </label>
                            <div style="position: relative;">
                                <select id="team_lead" name="team_lead" class="form-select" required>
                                    <option value="">Select Team Lead</option>
                                    @foreach($employees as $employee)
                                        <option value="{{ $employee->employee_id }}">{{ $employee->employee_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Team Size and Budget Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="max_team_size" class="form-label">
                                <i class="fas fa-users-cog"></i>Maximum Team Size
                            </label>
                            <div style="position: relative;">
                                <input type="number" id="max_team_size" name="max_team_size" class="form-input" placeholder="Enter maximum team size" min="1" max="50" value="10" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="monthly_budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Monthly Budget
                            </label>
                            <div style="position: relative;">
                                <input type="number" id="monthly_budget" name="monthly_budget" class="form-input" placeholder="Enter monthly budget" min="0" step="0.01">
                            </div>
                        </div>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="team_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Team Status
                            </label>
                            <div style="position: relative;">
                                <select id="team_status" name="team_status" class="form-select" required>
                                    <option value="Active" selected>Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Hold">On Hold</option>
                                    <option value="Disbanded">Disbanded</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="team_priority" class="form-label">
                                <i class="fas fa-star"></i>Team Priority
                            </label>
                            <div style="position: relative;">
                                <select id="team_priority" name="team_priority" class="form-select" required>
                                    <option value="Normal" selected>Normal</option>
                                    <option value="High">High</option>
                                    <option value="Critical">Critical</option>
                                    <option value="Low">Low</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Work Mode Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="work_mode" class="form-label">
                                <i class="fas fa-laptop-house"></i>Work Mode
                            </label>
                            <div style="position: relative;">
                                <select id="work_mode" name="work_mode" class="form-select" required>
                                    <option value="On-site" selected>On-site</option>
                                    <option value="Remote">Remote</option>
                                    <option value="Hybrid">Hybrid</option>
                                    <option value="Flexible">Flexible</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Team Description -->
                    <div class="form-group">
                        <label for="team_description" class="form-label">
                            <i class="fas fa-file-alt"></i>Team Description
                        </label>
                        <div style="position: relative;">
                            <textarea id="team_description" name="team_description" class="form-textarea" placeholder="Describe the team's purpose, goals, and responsibilities" rows="4"></textarea>
                        </div>
                    </div>

                    <!-- Team Goals and Objectives -->
                    <div class="form-group">
                        <label for="team_goals" class="form-label">
                            <i class="fas fa-target"></i>Team Goals & Objectives
                        </label>
                        <div style="position: relative;">
                            <textarea id="team_goals" name="team_goals" class="form-textarea" placeholder="Define the team's key goals and objectives" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Skills Required -->
                    <div class="form-group">
                        <label for="skills_required" class="form-label">
                            <i class="fas fa-cogs"></i>Skills Required
                        </label>
                        <div style="position: relative;">
                            <textarea id="skills_required" name="skills_required" class="form-textarea" placeholder="List the key skills and technologies required for this team" rows="3"></textarea>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeTeamModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Team
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Team Edit Modal -->
<div id="editTeamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-edit"></i>
                Edit Team
            </div>
            <div class="modal-subtitle">Update team information and settings</div>
            <button class="modal-close" onclick="closeEditTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <form action="" method="POST" id="editTeamForm">
                @csrf
                @method('PUT')
                <input type="hidden" id="edit_team_id" name="team_id">

                <div class="form-container">
                    <!-- Team Name -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_team_name" class="form-label">
                                <i class="fas fa-users"></i>Team Name
                            </label>
                            <input type="text" id="edit_team_name" name="team_name" class="form-input" placeholder="Enter team name" required>
                        </div>
                    </div>
                    <!-- Department and Team Lead Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department_id" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <select id="edit_department_id" name="department_id" class="form-select" required>
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_team_lead" class="form-label">
                                <i class="fas fa-user-tie"></i>Team Lead
                            </label>
                            <select id="edit_team_lead" name="team_lead" class="form-select" required>
                                <option value="">Select Team Lead</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->employee_id }}">{{ $employee->employee_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Team Size and Budget Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_max_team_size" class="form-label">
                                <i class="fas fa-users-cog"></i>Maximum Team Size
                            </label>
                            <input type="number" id="edit_max_team_size" name="max_team_size" class="form-input" placeholder="Enter maximum team size" min="1" max="50" required>
                        </div>
                        <div class="form-group">
                            <label for="edit_monthly_budget" class="form-label">
                                <i class="fas fa-dollar-sign"></i>Monthly Budget
                            </label>
                            <input type="number" id="edit_monthly_budget" name="monthly_budget" class="form-input" placeholder="Enter monthly budget" min="0" step="0.01">
                        </div>
                    </div>

                    <!-- Status and Priority Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_team_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Team Status
                            </label>
                            <select id="edit_team_status" name="team_status" class="form-select" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                                <option value="On Hold">On Hold</option>
                                <option value="Disbanded">Disbanded</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="edit_team_priority" class="form-label">
                                <i class="fas fa-star"></i>Team Priority
                            </label>
                            <select id="edit_team_priority" name="team_priority" class="form-select" required>
                                <option value="Low">Low</option>
                                <option value="Normal">Normal</option>
                                <option value="High">High</option>
                                <option value="Critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <!-- Work Mode Row -->
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_work_mode" class="form-label">
                                <i class="fas fa-laptop-house"></i>Work Mode
                            </label>
                            <select id="edit_work_mode" name="work_mode" class="form-select" required>
                                <option value="On-site">On-site</option>
                                <option value="Remote">Remote</option>
                                <option value="Hybrid">Hybrid</option>
                                <option value="Flexible">Flexible</option>
                            </select>
                        </div>
                    </div>

                    <!-- Team Description -->
                    <div class="form-group">
                        <label for="edit_team_description" class="form-label">
                            <i class="fas fa-file-alt"></i>Team Description
                        </label>
                        <textarea id="edit_team_description" name="team_description" class="form-textarea" placeholder="Describe the team's purpose, goals, and responsibilities" rows="4"></textarea>
                    </div>

                    <!-- Team Goals and Objectives -->
                    <div class="form-group">
                        <label for="edit_team_goals" class="form-label">
                            <i class="fas fa-target"></i>Team Goals & Objectives
                        </label>
                        <textarea id="edit_team_goals" name="team_goals" class="form-textarea" placeholder="Define the team's key goals and objectives" rows="3"></textarea>
                    </div>

                    <!-- Skills Required -->
                    <div class="form-group">
                        <label for="edit_skills_required" class="form-label">
                            <i class="fas fa-cogs"></i>Skills Required
                        </label>
                        <textarea id="edit_skills_required" name="skills_required" class="form-textarea" placeholder="List the key skills and technologies required for this team" rows="3"></textarea>
                    </div>

                    <!-- Form Actions -->
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditTeamModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Team
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Team View Modal -->
<div id="viewTeamModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-eye"></i>
                Team Details
            </div>
            <div class="modal-subtitle">View complete team information</div>
            <button class="modal-close" onclick="closeViewTeamModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="form-container">
                <!-- Team Basic Information Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users"></i>Team Name
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users"></i>
                            <div class="view-field" id="view_team_name"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-hashtag"></i>Team Code
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-hashtag"></i>
                            <div class="view-field" id="view_team_code"></div>
                        </div>
                    </div>
                </div>

                <!-- Department and Team Lead Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-building"></i>Department
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-building"></i>
                            <div class="view-field" id="view_department"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-user-tie"></i>Team Lead
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-user-tie"></i>
                            <div class="view-field" id="view_team_lead"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Size and Budget Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-users-cog"></i>Maximum Team Size
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-users-cog"></i>
                            <div class="view-field" id="view_max_size"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-dollar-sign"></i>Monthly Budget
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-dollar-sign"></i>
                            <div class="view-field" id="view_budget"></div>
                        </div>
                    </div>
                </div>

                <!-- Status and Priority Row -->
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-toggle-on"></i>Team Status
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-toggle-on"></i>
                            <div class="view-field" id="view_status"></div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-star"></i>Team Priority
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-star"></i>
                            <div class="view-field" id="view_priority"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Location and Work Mode Row -->
                <div class="form-row">


                    <div class="form-group">
                        <label class="form-label">
                            <i class="fas fa-laptop-house"></i>Work Mode
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-laptop-house"></i>
                            <div class="view-field" id="view_work_mode"></div>
                        </div>
                    </div>
                </div>

                <!-- Team Description -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-file-alt"></i>Team Description
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-file-alt"></i>
                        <div class="view-field view-textarea" id="view_description"></div>
                    </div>
                </div>

                <!-- Team Goals and Objectives -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-target"></i>Team Goals & Objectives
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-target"></i>
                        <div class="view-field view-textarea" id="view_goals"></div>
                    </div>
                </div>

                <!-- Skills Required -->
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-cogs"></i>Skills Required
                    </label>
                    <div style="position: relative;">
                        <i class="input-icon fas fa-cogs"></i>
                        <div class="view-field view-textarea" id="view_skills_required"></div>
                    </div>
                </div>

                <!-- Modal Actions -->
                <div class="form-actions">
                    <button type="button" class="btn btn-secondary" onclick="closeViewTeamModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="openEditTeamModalFromView()">
                        <i class="fas fa-edit"></i> Edit Team
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Manage Members Modal -->
<div id="manageMembersModal" class="modal-overlay">
    <div class="modal-container" style="max-width: 480px; padding-bottom: 0;">
        <div class="modal-header" style="border-bottom: none;">
            <div class="modal-title" style="font-size: 1.5rem;">
                <i class="fas fa-users"></i>
                Manage Team Members
            </div>
            <button class="modal-close" onclick="closeManageMembersModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body" style="padding-top: 0;">
            <form id="manageMembersForm" method="POST" style="margin-bottom: 0;">
                @csrf
                <input type="hidden" name="team_id" id="manageMembersTeamId">
                <div style="margin-bottom: 1.5rem;">
                    <div style="font-weight: 600; color: var(--redcode-primary); margin-bottom: 0.25rem;">
                        <i class="fas fa-users"></i>
                        <span id="manageMembersTeamName" style="font-size: 1.1rem;"></span>
                    </div>
                    <div style="color: var(--text-secondary); font-size: 0.95rem;">
                        Assign or remove members for this team. Hold <kbd>Ctrl</kbd> (or <kbd>Cmd</kbd>) to select multiple.
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 2rem;">
                    <label for="manage_employee_ids" class="form-label" style="font-weight: 500;">
                        <i class="fas fa-user-friends"></i> Team Members
                    </label>
                    <select name="employee_ids[]" id="manage_employee_ids" class="form-select" multiple size="8" required style="min-height: 180px;">
                        @foreach(\App\Models\Employee::all() as $employee)
                            <option value="{{ $employee->employee_id }}">
                                {{ $employee->employee_name }} ({{ $employee->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-actions" style="margin-top: 1.5rem; border-top: none; justify-content: flex-end;">
                    <button type="button" class="btn btn-secondary" onclick="closeManageMembersModal()">
                        <i class="fas fa-times"></i> Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Members
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Team Modal Functions
function openTeamModal() {
    document.getElementById('teamModal').classList.add('active');
    document.body.style.overflow = 'hidden';

    // Enhanced input interactions
    setupInputEnhancements();
}

function closeTeamModal() {
    document.getElementById('teamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('teamForm').reset();

    // Clear any validation styles
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
}

// Edit Team Modal Functions
function openEditTeamModal(teamId, teamName, departmentId, teamLeadName, maxTeamSize, monthlyBudget, teamStatus, teamPriority, workMode, teamDescription, teamGoals, skillsRequired) {
    document.getElementById('editTeamModal').classList.add('active');
    document.body.style.overflow = 'hidden';

    // Set form action dynamically
    document.getElementById('editTeamForm').action = '/admin/teams/' + teamId;
    document.getElementById('edit_team_id').value = teamId;
    document.getElementById('edit_team_name').value = teamName;
    document.getElementById('edit_department_id').value = departmentId;

    // Set the team lead by name (since you store name, not id)
    let teamLeadSelect = document.getElementById('edit_team_lead');
    let foundLead = false;
    for (let i = 0; i < teamLeadSelect.options.length; i++) {
        if (teamLeadSelect.options[i].text.trim() === teamLeadName.trim()) {
            teamLeadSelect.selectedIndex = i;
            foundLead = true;
            break;
        }
    }
    if (!foundLead) {
        teamLeadSelect.selectedIndex = 0;
    }

    document.getElementById('edit_max_team_size').value = maxTeamSize;
    document.getElementById('edit_monthly_budget').value = monthlyBudget;
    document.getElementById('edit_team_status').value = teamStatus;
    document.getElementById('edit_team_priority').value = teamPriority;

    // Fix for work mode select
    document.getElementById('edit_work_mode').value = workMode;

    document.getElementById('edit_team_description').value = teamDescription;
    document.getElementById('edit_team_goals').value = teamGoals;
    document.getElementById('edit_skills_required').value = skillsRequired;

    setupInputEnhancements();
}

function closeEditTeamModal() {
    document.getElementById('editTeamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('editTeamForm').reset();

    // Clear any validation styles
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(field => {
        field.style.borderColor = '';
        field.style.background = '';
    });
}


function openViewTeamModal(teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills) {

    console.log('Opening view team modal with data:', {
        teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills
    });

    // Populate view fields with the team data
    document.getElementById('view_team_name').textContent = teamName || 'N/A';
    document.getElementById('view_team_code').textContent = teamCode || 'N/A';
    document.getElementById('view_department').textContent = department || 'N/A';
    document.getElementById('view_team_lead').textContent = teamLead || 'N/A';
    document.getElementById('view_max_size').textContent = maxSize || 'N/A';

    // Format budget with currency symbol
    document.getElementById('view_budget').textContent = budget ? `$${parseInt(budget).toLocaleString()}` : 'N/A';

    // Handle status with special styling
    const statusField = document.getElementById('view_status');
    statusField.textContent = status || 'N/A';
    statusField.className = 'view-field status-badge';
    if (status) {
        if (status.toLowerCase() === 'active') {
            statusField.classList.add('active');
        } else if (status.toLowerCase() === 'inactive') {
            statusField.classList.add('inactive');
        } else if (status.toLowerCase().includes('hold')) {
            statusField.classList.add('on-hold');
        }
    }

    document.getElementById('view_priority').textContent = priority || 'N/A';
    document.getElementById('view_location').textContent = location || 'N/A';
    document.getElementById('view_work_mode').textContent = workMode || 'N/A';
    document.getElementById('view_description').textContent = description || 'N/A';
    document.getElementById('view_goals').textContent = goals || 'N/A';
    document.getElementById('view_skills_required').textContent = skills || 'N/A';

    // Store data for potential edit modal opening
    window.currentTeamData = {
        teamName, teamCode, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills
    };

    // Show the view modal
    document.getElementById('viewTeamModal').classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeViewTeamModal() {
    document.getElementById('viewTeamModal').classList.remove('active');
    document.body.style.overflow = 'auto';
}

function openEditTeamModalFromView() {
    closeViewTeamModal();

    const data = window.currentTeamData;
    if (data) {
        setTimeout(() => {
            openEditTeamModal(data.teamName, data.teamCode, data.department, data.teamLead,
                         data.maxSize, data.budget, data.status, data.priority, data.location, data.workMode, data.description, data.goals, data.skills);
        }, 300);
    }
}

function openManageMembersModal(teamId, selectedEmployeeIds, teamName) {
    document.getElementById('manageMembersModal').classList.add('active');
    document.body.style.overflow = 'hidden';
    document.getElementById('manageMembersTeamId').value = teamId;
    document.getElementById('manageMembersTeamName').textContent = teamName;

    // Set form action dynamically
    document.getElementById('manageMembersForm').action =
        '/admin/teams/' + teamId + '/manage-members';

    const select = document.getElementById('manage_employee_ids');
    for (let i = 0; i < select.options.length; i++) {
        select.options[i].selected = selectedEmployeeIds.includes(parseInt(select.options[i].value));
    }
}

function closeManageMembersModal() {
    document.getElementById('manageMembersModal').classList.remove('active');
    document.body.style.overflow = 'auto';
    document.getElementById('manageMembersForm').reset();
}

// Enhanced input interactions
function setupInputEnhancements() {
    document.querySelectorAll('.form-input, .form-select, .form-textarea').forEach(input => {
        input.addEventListener('focus', function() {
            const icon = this.previousElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--redcode-primary)';
                icon.style.transform = 'translateY(-50%) scale(1.1)';
            }
        });

        input.addEventListener('blur', function() {
            const icon = this.previousElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--text-light)';
                icon.style.transform = 'translateY(-50%) scale(1)';
            }
        });
    });
}

// Form validation for team creation
document.getElementById('teamForm').addEventListener('submit', function(e) {
    // Basic validation
    const requiredFields = ['team_name', 'department_id', 'team_status', 'team_priority', 'work_mode', 'max_team_size'];
    let isValid = true;
    let firstInvalid = null;

    requiredFields.forEach(fieldName => {
        const field = document.getElementById(fieldName);
        if (field && !field.value.trim()) {
            field.style.borderColor = 'var(--redcode-primary)';
            field.style.background = 'rgba(220, 38, 38, 0.05)';
            if (!firstInvalid) firstInvalid = field;
            isValid = false;
        } else if (field) {
            field.style.borderColor = 'var(--redcode-green)';
            field.style.background = 'rgba(5, 150, 105, 0.05)';
        }
    });

    if (!isValid) {
        alert('Please fill in all required fields');
        if (firstInvalid) firstInvalid.focus();
        e.preventDefault();
        return;
    }

    // Max size validation
    const maxSize = document.getElementById('max_team_size').value;
    if (maxSize && (maxSize < 1 || maxSize > 50)) {
        alert('Maximum team size must be between 1 and 50');
        e.preventDefault();
        return;
    }
    // If validation passes, allow the form to submit
});


// Close modal when clicking outside
document.getElementById('teamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeTeamModal();
    }
});

document.getElementById('editTeamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditTeamModal();
    }
});

document.getElementById('viewTeamModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeViewTeamModal();
    }
});

document.getElementById('manageMembersModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeManageMembersModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        const teamModal = document.getElementById('teamModal');
        const editTeamModal = document.getElementById('editTeamModal');
        const viewTeamModal = document.getElementById('viewTeamModal');

        if (teamModal.classList.contains('active')) {
            closeTeamModal();
        }
        if (editTeamModal.classList.contains('active')) {
            closeEditTeamModal();
        }
        if (viewTeamModal.classList.contains('active')) {
            closeViewTeamModal();
        }
    }
});
</script>
@endsection
