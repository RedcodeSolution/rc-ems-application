@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admins</h1>
    <a href="{{ route('admins.create') }}" class="btn btn-primary">Add Admin</a>
    <table class="table mt-3">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($admins as $admin)
        <tr>
            <td>{{ $admin->admin_id }}</td>
            <td>{{ $admin->admin_name }}</td>
            <td>
                <a href="{{ route('admins.show', $admin) }}" class="btn btn-info">View</a>
                <a href="{{ route('admins.edit', $admin) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('admins.destroy', $admin) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
