@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Project</h1>
    <form action="{{ route('projects.update', $project->project_id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="project_id" value="{{ $project->project_id }}" class="form-control mb-2" disabled>
        <input type="text" name="project_name" value="{{ old('project_name', $project->project_name) }}" required class="form-control mb-2">
        <select name="team_id" class="form-control mb-2" required>
            <option value="">Select Team</option>
            @foreach($teams as $team)
            <option value="{{ $team->team_id }}" {{ $project->team_id == $team->team_id ? 'selected' : '' }}>
                {{ $team->team_name }}
            </option>
            @endforeach
        </select>
        <textarea name="description" class="form-control mb-2">{{ old('description', $project->description) }}</textarea>
        <input type="text" name="client" value="{{ old('client', $project->client) }}" class="form-control mb-2">
        <input type="text" name="status" value="{{ old('status', $project->status) }}" class="form-control mb-2">
        <input type="date" name="start_date" value="{{ old('start_date', $project->start_date) }}" class="form-control mb-2">
        <input type="date" name="end_date" value="{{ old('end_date', $project->end_date) }}" class="form-control mb-2">
        <textarea name="milestone_info" class="form-control mb-2">{{ old('milestone_info', $project->milestone_info) }}</textarea>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
