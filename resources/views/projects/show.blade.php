@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Project Details</h1>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">ID: {{ $project->project_id }}</h5>
            <p class="card-text">Name: {{ $project->project_name }}</p>
            <p class="card-text">Team: {{ $project->team_id }}</p>
            <p class="card-text">Description: {{ $project->description }}</p>
            <p class="card-text">Client: {{ $project->client }}</p>
            <p class="card-text">Status: {{ $project->status }}</p>
            <p class="card-text">Start Date: {{ $project->start_date }}</p>
            <p class="card-text">End Date: {{ $project->end_date }}</p>
            <p class="card-text">Milestone Info: {{ $project->milestone_info }}</p>
        </div>
    </div>
    <a href="{{ route('projects.index') }}" class="btn btn-secondary mt-3">Back to Projects</a>
</div>
@endsection
