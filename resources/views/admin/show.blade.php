@extends('layouts.admin')

@section('title', 'Admin Details')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Admin Details</h2>
    </div>
    <div class="card-body">
        <h5 class="card-title">Admin ID: {{ $admin->admin_id }}</h5>
        <p class="card-text">Admin Name: {{ $admin->admin_name }}</p>
    </div>
    <div class="card-footer">
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Back to Admin Dashboard</a>
    </div>
</div>
@endsection
