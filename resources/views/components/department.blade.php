@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Departments</h1>
    <a href="{{ route('departments.create') }}" class="btn btn-primary">Add Department</a>
    <table class="table mt-3">
        <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($departments as $department)
        <tr>
            <td>{{ $department->department_id }}</td>
            <td>{{ $department->department_name }}</td>
            <td>
                <a href="{{ route('departments.show', $department) }}" class="btn btn-info">View</a>
                <a href="{{ route('departments.edit', $department) }}" class="btn btn-warning">Edit</a>
                <form action="{{ route('departments.destroy', $department) }}" method="POST" style="display:inline;">
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
