@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="card" style="max-width: 600px; margin: 0 auto;">
    <div class="card-header">
        <h2>Edit Admin</h2>
    </div>
    <div class="card-body">
        <form action="{{ route('admins.update', $admin->admin_id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="admin_id" class="form-label">Admin ID</label>
                <input type="text" class="form-control" id="admin_id" name="admin_id" value="{{ $admin->admin_id }}" readonly>
            </div>
            <div class="mb-3">
                <label for="admin_name" class="form-label">Admin Name</label>
                <input type="text" class="form-control" id="admin_name" name="admin_name" value="{{ $admin->admin_name }}" required>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
</div>
@endsection
