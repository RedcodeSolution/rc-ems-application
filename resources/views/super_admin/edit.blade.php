@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Super Admin</h1>
    <form action="{{ route('super_admins.update', $superAdmin->super_admin_id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="super_admin_id" class="form-label">Super Admin ID</label>
            <input type="text" class="form-control" id="super_admin_id" name="super_admin_id" value="{{ $superAdmin->super_admin_id }}" readonly>
        </div>
        <div class="mb-3">
            <label for="super_admin_name" class="form-label">Super Admin Name</label>
            <input type="text" class="form-control" id="super_admin_name" name="super_admin_name" value="{{ $superAdmin->super_admin_name }}" required>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
