@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Super Admins</h1>
    <a href="{{ route('super_admins.create') }}" class="btn btn-primary mb-3">Create Super Admin</a>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($superAdmins as $superAdmin)
        <tr>
            <td>{{ $superAdmin->super_admin_id }}</td>
            <td>{{ $superAdmin->super_admin_name }}</td>
            <td>
                <a href="{{ route('super_admins.edit', $superAdmin->super_admin_id) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('super_admins.destroy', $superAdmin->super_admin_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <a href="{{ route('super_admins.show', $superAdmin->super_admin_id) }}" class="btn btn-info">View</a>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
