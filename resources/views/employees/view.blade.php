@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Details</h1>
    <p><strong>ID:</strong> {{ $employee->employee_id }}</p>
    <p><strong>Name:</strong> {{ $employee->employee_name }}</p>
    <p><strong>Type:</strong> {{ $employee->employee_type }}</p>
    <p><strong>Status:</strong> {{ $employee->employee_status }}</p>
    <p><strong>Contact No:</strong> {{ $employee->contact_no }}</p>
    <p><strong>Department ID:</strong> {{ $employee->department_id }}</p>
    <p><strong>Admin ID:</strong> {{ $employee->admin_id }}</p>
    <p><strong>Paid Status:</strong> {{ $employee->paid_status }}</p>

    <p><strong>Role:</strong> {{ $employee->role }}</p>
    <a href="{{ route('employees.edit', $employee->employee_id) }}" class="btn btn-primary">Edit</a>
    <a href="{{ route('employees.index') }}" class="btn btn-secondary">Back to List</a>
</div>
@endsection
