@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Documents</h1>
    <a href="{{ route('documents.create') }}" class="btn btn-primary mb-3">Create Document</a>
    <table class="table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Employee</th>

            <th>Version</th>
            <th>Review Date</th>
            <th>Permission</th>
            <th>Project</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @foreach($documents as $document)
        <tr>
            <td>{{ $document->document_id }}</td>
            <td>{{ $document->employee->employee_name ?? 'N/A' }}</td>
            <td>{{ $document->version }}</td>
            <td>{{ $document->review_date }}</td>
            <td>{{ $document->access_permission }}</td>
            <td>{{ $document->project->project_name ?? 'N/A' }}</td>
            <td>
                <a href="{{ route('documents.edit', $document->document_id) }}" class="btn btn-warning btn-sm">Edit</a>
                <form action="{{ route('documents.destroy', $document->document_id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
                <a href="{{ route('documents.show', $document->document_id) }}" class="btn btn-info btn-sm">View</a>
                {{-- Optional: Add a View button if you have a show route --}}

            </td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
