@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin/teams.css') }}">

@section('title', 'Teams Management')

@section('content')
    <!-- Summary Cards -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        <!-- Total Teams -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(37, 99, 235, 0.1); color: var(--redcode-blue); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-users-cog"></i>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Total Teams</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $teams->count() }}</div>
            </div>
        </div>

        <!-- Active Teams -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(5, 150, 105, 0.1); color: var(--redcode-green); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Active Teams</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">{{ $teams->where('team_status', 'Active')->count() }}</div>
            </div>
        </div>

        <!-- Total Members -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(245, 158, 11, 0.1); color: var(--redcode-orange); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-users"></i>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Total Members</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">
                    {{ $teams->sum(function($team) { return $team->employees->count(); }) }}
                </div>
            </div>
        </div>

        <!-- Avg Team Size -->
        <div class="card" style="padding: 1.5rem; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 50px; height: 50px; border-radius: 12px; background: rgba(107, 114, 128, 0.1); color: var(--text-secondary); display: flex; align-items: center; justify-content: center; font-size: 1.5rem;">
                <i class="fas fa-chart-pie"></i>
            </div>
            <div>
                <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Avg Team Size</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-primary);">
                    {{ $teams->count() > 0 ? round($teams->avg(function($team) { return $team->employees->count(); }), 1) : 0 }}
                </div>
            </div>
        </div>
    </div>

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users-cog"></i> Teams</h2>
        <div class="flex gap-2">
            <button onclick="openTeamModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                <span class="btn-text">New Team</span>
            </button>

        </div>
    </div>
    <div class="card-body">
        <!-- Search & Actions Toolbar -->
        <div class="teams-toolbar mb-4">
            <form method="GET" action="{{ route('admin.teams') }}" class="search-form">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search teams..."
                    class="form-input search-input"
                >
                <button class="btn btn-secondary" type="submit">
                    <i class="fas fa-search"></i>
                    Search
                </button>
            </form>
        </div>

        <!-- Teams Grid -->
        <div class="teams-grid">
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
                                    '{{ $team->team_id }}',
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
                            <i class="fas fa-hashtag"></i>Team ID
                        </label>
                        <div style="position: relative;">
                            <i class="input-icon fas fa-hashtag"></i>
                            <div class="view-field" id="view_team_id"></div>
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


function openViewTeamModal(teamName, teamId, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills) {
    // Populate view fields with the team data
    document.getElementById('view_team_name').textContent = teamName || 'N/A';
    document.getElementById('view_team_id').textContent = teamId || 'N/A';
    document.getElementById('view_department').textContent = department || 'N/A';
    document.getElementById('view_team_lead').textContent = teamLead || 'N/A';
    document.getElementById('view_max_size').textContent = maxSize || 'N/A';

    // Format budget with currency symbol
    document.getElementById('view_budget').textContent = budget ? `$${parseFloat(budget).toLocaleString()}` : 'N/A';

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
    document.getElementById('view_work_mode').textContent = workMode || 'N/A';
    document.getElementById('view_description').textContent = description || 'N/A';
    document.getElementById('view_goals').textContent = goals || 'N/A';
    document.getElementById('view_skills_required').textContent = skills || 'N/A';

    // Store data for potential edit modal opening
    window.currentTeamData = {
        teamName, teamId, department, teamLead, maxSize, budget, status, priority, location, workMode, description, goals, skills
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
