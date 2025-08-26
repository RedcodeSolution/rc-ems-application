@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Edit Employee</h1>
    <form action="{{ route('employees.update', $employee->employee_id) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="text" name="employee_name" value="{{ $employee->employee_name }}" required>
        <input type="text" name="employee_type" value="{{ $employee->employee_type }}" required>
        <input type="text" name="employee_status" value="{{ $employee->employee_status }}" required>
        <input type="text" name="contact_no" value="{{ $employee->contact_no }}" required>
        <select name="department_id">
            <option value="">Select Department</option>
            @foreach($departments as $department)
            <option value="{{ $department->department_id }}" {{ $employee->department_id == $department->department_id ? 'selected' : '' }}>
                {{ $department->department_id }}
            </option>
            @endforeach
        </select>

        <select name="admin_id">
            <option value="">Select Admin</option>
            @foreach($admins as $admin)
            <option value="{{ $admin->admin_id }}" {{ $employee->admin_id == $admin->admin_id ? 'selected' : '' }}>
                {{ $admin->admin_id }}
            </option>
            @endforeach
        </select>
        <input type="text" name="paid_status" value="{{ $employee->paid_status }}" required>
        <input type="text" name="role" value="{{ $employee->role }}" required>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
