@extends('layouts.admin')

@section('title', 'Manage Team Members')

@section('content')
<div class="card" style="max-width: 600px; margin: 2rem auto;">
    <div class="card-header">
        <h2><i class="fas fa-users"></i> Manage Members for "{{ $team->team_name }}"</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('teams.assignEmployees', $team->team_id) }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="employee_ids" class="form-label">
                    <i class="fas fa-user-friends"></i> Select Team Members
                </label>
                <select name="employee_ids[]" id="employee_ids" class="form-select" multiple size="10" required>
                    @foreach($employees as $employee)
                        <option value="{{ $employee->employee_id }}"
                            {{ $team->employees->contains('employee_id', $employee->employee_id) ? 'selected' : '' }}>
                            {{ $employee->employee_name }} ({{ $employee->email }})
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="form-actions" style="margin-top: 2rem;">
                <a href="{{ route('admin.teams') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Save Members
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

