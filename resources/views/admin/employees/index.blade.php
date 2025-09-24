@extends('layouts.admin')

<link rel="stylesheet" href="{{ asset('css/admin/employee.css') }}">

@section('title', 'Employees Management')

@section('content')

@if(session('success'))
<div style="background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.2); color: var(--redcode-green); padding: 12px 16px; border-radius: 0.75rem; font-size: 0.875rem; margin-bottom: 1.5rem;">
    <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
    {{ session('success') }}
</div>
@endif

<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-users"></i> Employees</h2>
        <div class="flex gap-2">
            <button onclick="openEmployeeModal()" class="btn btn-primary">
                <i class="fas fa-plus"></i>
                Add Employee
            </button>
            <button class="btn btn-secondary">
                <i class="fas fa-download"></i>
                Export
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="flex justify-between items-center mb-4 gap-2" style="flex-wrap: wrap;">
            <div class="flex gap-2" style="flex-wrap: wrap; width: 100%;">
                <input type="text" id="searchInput" placeholder="Search employees..." class="form-input" onkeyup="searchEmployees()">
                <select class="form-select" id="departmentFilter" onchange="filterEmployees()">
                    <option value="">All Departments</option>
                    @foreach($departments as $department)
                    <option value="{{ $department->department_name }}">{{ $department->department_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2" style="flex-wrap: wrap;">
                <button class="btn btn-secondary" onclick="resetFilters()">
                    <i class="fas fa-refresh"></i>
                    Reset
                </button>
                <button class="btn btn-secondary">
                    <i class="fas fa-sort"></i>
                    Sort
                </button>
            </div>
        </div>

        <div class="card" style="margin-bottom: 1.5rem; border: 1px solid var(--border-light); border-radius: 1rem; background: rgba(255, 255, 255, 0.9);">
            <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-calendar-alt" style="color: var(--redcode-primary);"></i>
                    Employee Leave Management Filter
                </h3>
            </div>

            <div class="card-body" style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1.5rem;">
                    <div>
                        <label for="leaveStatusFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-filter" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Leave Status Filter
                        </label>
                        <select id="leaveStatusFilter" class="form-select" onchange="applyLeaveFilters()">
                            <option value="">All Leave Statuses</option>
                            <option value="with-leaves">With Leave Records</option>
                            <option value="no-leaves">No Leave Records</option>
                            <option value="pending-leaves">Pending Leave Requests</option>
                            <option value="approved-leaves">Approved Leaves</option>
                            <option value="rejected-leaves">Rejected Leaves</option>
                            <option value="on-leave">Currently On Leave</option>
                        </select>
                    </div>

                    <div>
                        <label for="leaveTypeFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-calendar-check" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Leave Type Filter
                        </label>
                        <select id="leaveTypeFilter" class="form-select" onchange="applyLeaveFilters()">
                            <option value="">All Leave Types</option>
                            <option value="annual">Annual Leave</option>
                            <option value="sick">Sick Leave</option>
                            <option value="medical">Medical Leave</option>
                            <option value="personal">Personal Leave</option>
                            <option value="maternity">Maternity Leave</option>
                            <option value="paternity">Paternity Leave</option>
                            <option value="unpaid">Unpaid Leave</option>
                        </select>
                    </div>

                    <div>
                        <label for="leaveCountFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-sort-numeric-up" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Leave Count Filter
                        </label>
                        <select id="leaveCountFilter" class="form-select" onchange="applyLeaveFilters()">
                            <option value="">Any Leave Count</option>
                            <option value="0">No Leaves (0)</option>
                            <option value="1-3">Low (1-3 leaves)</option>
                            <option value="4-7">Medium (4-7 leaves)</option>
                            <option value="8+">High (8+ leaves)</option>
                        </select>
                    </div>

                    <div>
                        <label for="leaveDateFilter" style="display: block; font-weight: 600; color: var(--text-primary); margin-bottom: 0.5rem; font-size: 0.875rem;">
                            <i class="fas fa-calendar" style="color: var(--redcode-primary); margin-right: 0.25rem;"></i>
                            Leave Period Filter
                        </label>
                        <select id="leaveDateFilter" class="form-select" onchange="applyLeaveFilters()">
                            <option value="">All Time</option>
                            <option value="this-month">This Month</option>
                            <option value="last-month">Last Month</option>
                            <option value="this-quarter">This Quarter</option>
                            <option value="this-year">This Year</option>
                            <option value="last-30-days">Last 30 Days</option>
                            <option value="last-90-days">Last 90 Days</option>
                        </select>
                    </div>
                </div>



                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                    <div style="background: rgba(5, 150, 105, 0.1); border: 1px solid rgba(5, 150, 105, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);">{{ $employees->where('employee_status', 'On Leave')->count() }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Currently On Leave</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);">{{ \App\Models\Leave::where('status', 'pending')->count() }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Pending Requests</div>
                    </div>
                    <div style="background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--info);">{{ \App\Models\Leave::where('status', 'approved')->count() }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Approved This Month</div>
                    </div>
                    <div style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);">{{ \App\Models\Leave::where('status', 'rejected')->count() }}</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Rejected This Month</div>
                    </div>
                </div>
            </div>
        </div>

        <div id="employeeLeaveInfo" class="card" style="margin-bottom: 1.5rem; display: none; border: 1px solid var(--border-light); border-radius: 1rem; background: rgba(255, 255, 255, 0.9);">
            <div class="card-header" style="padding: 1.5rem; border-bottom: 1px solid var(--border-light);">
                <h3 style="margin: 0; font-size: 1.25rem; font-weight: 700; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-user-clock" style="color: var(--redcode-primary);"></i>
                    <span id="searchedEmployeeName">Employee Leave Information</span>
                </h3>
                <div style="display: flex; gap: 0.5rem; margin-top: 0.5rem;">
                    <button class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="hideEmployeeLeaveInfo()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="btn btn-primary" style="padding: 0.25rem 0.5rem; font-size: 0.75rem;" onclick="refreshEmployeeLeaveInfo()">
                        <i class="fas fa-refresh"></i> Refresh
                    </button>
                </div>
            </div>

            <div class="card-body" style="padding: 1.5rem;">

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
                    <div style="background: rgba(37, 99, 235, 0.1); border: 1px solid rgba(37, 99, 235, 0.2); border-radius: 0.75rem; padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-user" style="color: var(--info); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--text-primary);" id="employeeName">Employee Name</h4>
                        </div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);" id="employeeDepartment">Department</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);" id="employeePosition">Position</div>
                    </div>

                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 0.75rem; padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-calendar-check" style="color: var(--success); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">Leave Summary</h4>
                        </div>
                        <div style="font-size: 2rem; font-weight: 700; color: var(--success); margin-bottom: 0.5rem;" id="totalLeaveCount">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Total Leaves Taken</div>
                    </div>

                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 0.75rem; padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-clock" style="color: var(--warning); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">Current Status</h4>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning); margin-bottom: 0.5rem;" id="pendingLeaveCount">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Pending Requests</div>
                    </div>

                    <div style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.2); border-radius: 0.75rem; padding: 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                            <i class="fas fa-calendar-times" style="color: var(--danger); font-size: 1.25rem;"></i>
                            <h4 style="margin: 0; font-size: 1.125rem; font-weight: 600; color: var(--text-primary);">Leave Balance</h4>
                        </div>
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger); margin-bottom: 0.5rem;" id="remainingLeaveBalance">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary);">Days Remaining</div>
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin-bottom: 2rem;">
                    <div style="background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--success);" id="approvedLeaveCount">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Approved</div>
                    </div>
                    <div style="background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--warning);" id="pendingLeaveCount2">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Pending</div>
                    </div>
                    <div style="background: rgba(220, 38, 38, 0.1); border: 1px solid rgba(220, 38, 38, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--danger);" id="rejectedLeaveCount">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Rejected</div>
                    </div>
                    <div style="background: rgba(107, 114, 128, 0.1); border: 1px solid rgba(107, 114, 128, 0.2); border-radius: 0.75rem; padding: 1rem; text-align: center;">
                        <div style="font-size: 1.5rem; font-weight: 700; color: var(--text-secondary);" id="totalLeaveDays">0</div>
                        <div style="font-size: 0.875rem; color: var(--text-secondary); font-weight: 500;">Total Days</div>
                    </div>
                </div>


                <div style="margin-bottom: 1.5rem;">
                    <h4 style="margin: 0 0 1rem 0; font-size: 1.125rem; font-weight: 600; color: var(--text-primary); display: flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-history" style="color: var(--redcode-primary);"></i>
                        Recent Leave History
                    </h4>
                    <div class="table-container">
                        <table class="table" id="recentLeaveHistoryTable">
                            <thead>
                            <tr>
                                <th>Leave Type</th>
                                <th>Start Date</th>
                                <th>End Date</th>
                                <th>Duration</th>
                                <th>Status</th>
                                <th>Applied Date</th>
                            </tr>
                            </thead>
                            <tbody id="recentLeaveHistoryTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>

                <div id="noLeaveHistory" style="display: none; text-align: center; padding: 2rem; color: var(--text-secondary);">
                    <i class="fas fa-calendar-times" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                    <p style="margin: 0; font-size: 1.1rem;">No leave history found</p>
                    <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">This employee has no leave records</p>
                </div>

                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <button class="btn btn-primary" onclick="viewFullLeaveHistory()">
                        <i class="fas fa-list"></i> View Full History
                    </button>
                    <button class="btn btn-success" onclick="exportEmployeeLeaveReport()">
                        <i class="fas fa-download"></i> Export Report
                    </button>
                    <button class="btn btn-warning" onclick="addLeaveRecord()">
                        <i class="fas fa-plus"></i> Add Leave Record
                    </button>
                    <button class="btn btn-info" onclick="viewLeaveCalendar()">
                        <i class="fas fa-calendar-alt"></i> View Calendar
                    </button>
                </div>
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                <tr>
                    <th>Employee</th>
                    <th>Department</th>
                    <th>Position</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Leave Count</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($employees as $employee)
                <tr>
                    <td data-label="Employee">
                        <div class="flex items-center gap-3">
                            <div class="user-avatar" style="width: 2rem; height: 2rem; font-size: 0.875rem;">
                                {{ strtoupper(substr($employee->employee_name, 0, 1)) }}{{ strtoupper(substr(str_replace(' ', '', $employee->employee_name), 1, 1)) }}
                            </div>
                            <div>
                                <div style="font-weight: 600;">{{ $employee->employee_name }}</div>
                            </div>
                        </div>
                    </td>
                    <td data-label="Department">{{ $employee->department->department_name ?? 'Not Assigned' }}</td>
                    <td data-label="Position">{{ $employee->role ?? 'Not Specified' }}</td>
                    <td data-label="Email">
                        @if($employee->email)
                        {{ $employee->email }}
                        @else
                        <span style="color: var(--text-secondary); font-style: italic;">No email provided</span>
                        @endif
                    </td>
                    <td data-label="Phone">{{ $employee->contact_no ?? 'Not Provided' }}</td>
                    <td data-label="Status">
                        @if($employee->employee_status == 'Active')
                        <span class="badge" style="background: rgba(5, 150, 105, 0.1); color: var(--success); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Active</span>
                        @elseif($employee->employee_status == 'On Leave')
                        <span class="badge" style="background: rgba(245, 158, 11, 0.1); color: var(--warning); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">On Leave</span>
                        @elseif($employee->employee_status == 'Inactive')
                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Inactive</span>
                        @elseif($employee->employee_status == 'Terminated')
                        <span class="badge" style="background: rgba(220, 38, 38, 0.1); color: var(--danger); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">Terminated</span>
                        @else
                        <span class="badge" style="background: rgba(107, 114, 128, 0.1); color: var(--text-secondary); padding: 0.25rem 0.75rem; border-radius: 1rem; font-size: 0.75rem;">{{ $employee->employee_status }}</span>
                        @endif
                    </td>
                    <td data-label="Leave Count">
                        <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <span class="badge" style="background: rgba(37, 99, 235, 0.1); color: var(--info); padding: 0.25rem 0.5rem; border-radius: 0.375rem; font-size: 0.75rem; font-weight: 600;">
                                    {{ $employee->leaves->count() }}
                                </span>
                            <span style="font-size: 0.75rem; color: var(--text-secondary);">leaves</span>
                        </div>
                        <div style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.25rem;">
                            @php
                            $pendingLeaves = $employee->leaves->where('status', 'pending')->count();
                            $approvedLeaves = $employee->leaves->where('status', 'approved')->count();
                            $rejectedLeaves = $employee->leaves->where('status', 'rejected')->count();
                            @endphp
                            @if($pendingLeaves > 0)
                            <span style="color: var(--warning);">{{ $pendingLeaves }} pending</span>
                            @endif
                            @if($approvedLeaves > 0)
                            <span style="color: var(--success);">{{ $approvedLeaves }} approved</span>
                            @endif
                            @if($rejectedLeaves > 0)
                            <span style="color: var(--danger);">{{ $rejectedLeaves }} rejected</span>
                            @endif
                        </div>
                    </td>
                    <td data-label="Actions">
                        <div class="flex gap-1">
                            <button class="btn btn-secondary" style="padding: 0.5rem;" title="View Employee"
                                onclick="openViewModal(
                                    '{{ $employee->employee_id }}',
                                    '{{ $employee->employee_name }}',
                                    '{{ $employee->email }}',
                                    '{{ $employee->contact_no }}',
                                    '{{ $employee->employee_type }}',
                                    '{{ $employee->role }}',
                                    '{{ $employee->department->department_name ?? "Not Assigned" }}',
                                    '{{ $employee->admin->admin_name ?? "Not Assigned" }}',
                                    '{{ $employee->employee_status }}',
                                    '{{ $employee->paid_status }}',
                                    '{{ $employee->created_at ? $employee->created_at->format("M d, Y") : "N/A" }}',
                                    '{{ $employee->teams->pluck("team_name")->join(", ") ?: "No teams assigned" }}',
                                    '{{ $employee->profile_photo ? asset("storage/" . $employee->profile_photo) : "" }}'
                                )">
                                <i class="fas fa-eye"></i>
                            </button>
                            <button class="btn btn-warning" style="padding: 0.5rem;" title="Edit Employee" onclick="openEditModal('{{ $employee->employee_id }}', '{{ $employee->employee_name }}', '{{ $employee->email }}', '{{ $employee->contact_no }}', '{{ $employee->employee_type }}', '{{ $employee->role }}', '{{ $employee->department_id }}', '{{ $employee->admin_id }}', '{{ $employee->employee_status }}', '{{ $employee->paid_status }}')">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-danger" style="padding: 0.5rem;" title="Delete Employee" onclick="confirmDelete('{{ $employee->employee_id }}', '{{ $employee->employee_name }}')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 2rem; color: var(--text-secondary);">
                        <i class="fas fa-users" style="font-size: 2rem; margin-bottom: 1rem; display: block; opacity: 0.3;"></i>
                        <p style="margin: 0; font-size: 1.1rem;">No employees found</p>
                        <p style="margin: 0.5rem 0 0 0; font-size: 0.9rem;">Click "Add Employee" to create your first employee record</p>
                    </td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-4">
            <div style="color: var(--text-secondary); font-size: 0.875rem;">
                Showing {{ $employees->count() }} of {{ $employees->count() }} employees
            </div>
            <div class="flex gap-1">
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" disabled>
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button class="btn btn-primary" style="padding: 0.5rem 0.75rem;">1</button>
                <button class="btn btn-secondary" style="padding: 0.5rem 0.75rem;" disabled>
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<div id="employeeModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-user-plus"></i>
                Add New Employee
            </div>
            <div class="modal-subtitle">Fill in the employee details below</div>
            <button class="modal-close" onclick="closeEmployeeModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form action="{{ route('employees.store') }}" method="POST" id="employeeForm" enctype="multipart/form-data">
                @csrf
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_name" class="form-label">
                                <i class="fas fa-user"></i>Full Name
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="employee_name" name="employee_name" class="form-input" placeholder="Enter full name" value="{{ old('employee_name') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="email" class="form-label">
                                <i class="fas fa-envelope"></i>Email Address
                            </label>
                            <div style="position: relative;">
                                <input type="email" id="email" name="email" class="form-input" placeholder="Enter email address" value="{{ old('email') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact_no" class="form-label">
                                <i class="fas fa-phone"></i>Contact Number
                            </label>
                            <div style="position: relative;">
                                <input type="tel" id="contact_no" name="contact_no" class="form-input" placeholder="Enter contact number" value="{{ old('contact_no') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_type" class="form-label">
                                <i class="fas fa-briefcase"></i>Employee Type
                            </label>
                            <div style="position: relative;">
                                <select id="employee_type" name="employee_type" class="form-select" required>
                                    <option value="">Select employee type</option>
                                    <option value="Full-time" {{ old('employee_type') == 'Full-time' ? 'selected' : '' }}>Full-time</option>
                                    <option value="Part-time" {{ old('employee_type') == 'Part-time' ? 'selected' : '' }}>Part-time</option>
                                    <option value="Contract" {{ old('employee_type') == 'Contract' ? 'selected' : '' }}>Contract</option>
                                    <option value="Intern" {{ old('employee_type') == 'Intern' ? 'selected' : '' }}>Intern</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="role" class="form-label">
                                <i class="fas fa-user-tag"></i>Role/Position
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="role" name="role" class="form-input" placeholder="Enter role or position" value="{{ old('role') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="department_id" class="form-label">
                            <i class="fas fa-building"></i>Department
                        </label>
                        <div style="position: relative;">
                            <select id="department_id" name="department_id" class="form-select">
                                <option value="">Select Department</option>
                                @foreach($departments as $department)
                                <option value="{{ $department->department_id }}" {{ old('department_id') == $department->department_id ? 'selected' : '' }}>{{ $department->department_name ?? $department->department_id }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="team_ids" class="form-label">
                            <i class="fas fa-users"></i>Teams (Hold Ctrl/Cmd to select multiple)
                        </label>
                        <div style="position: relative;">
                            <select id="team_ids" name="team_ids[]" class="form-select" multiple>
                                @foreach($teams as $team)
                                <option value="{{ $team->team_id }}" {{ is_array(old('team_ids')) && in_array($team->team_id, old('team_ids')) ? 'selected' : '' }}>{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="employee_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Employee Status
                            </label>
                            <div style="position: relative;">
                                <select id="employee_status" name="employee_status" class="form-select" required>
                                    <option value="">Select status</option>
                                    <option value="Active" {{ old('employee_status') == 'Active' ? 'selected' : '' }}>Active</option>
                                    <option value="Inactive" {{ old('employee_status') == 'Inactive' ? 'selected' : '' }}>Inactive</option>
                                    <option value="On Leave" {{ old('employee_status') == 'On Leave' ? 'selected' : '' }}>On Leave</option>
                                    <option value="Terminated" {{ old('employee_status') == 'Terminated' ? 'selected' : '' }}>Terminated</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="admin_id" class="form-label">
                                <i class="fas fa-user-shield"></i>Reporting Manager
                            </label>
                            <div style="position: relative;">
                                <select id="admin_id" name="admin_id" class="form-select">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->admin_id }}" {{ old('admin_id') == $admin->admin_id ? 'selected' : '' }}>{{ $admin->admin_name ?? $admin->admin_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="paid_status" class="form-label">
                            <i class="fas fa-credit-card"></i>Payment Status
                        </label>
                        <div style="position: relative;">
                            <select id="paid_status" name="paid_status" class="form-select" required>
                                <option value="">Select payment status</option>
                                <option value="Paid" {{ old('paid_status') == 'Paid' ? 'selected' : '' }}>Paid</option>
                                <option value="Pending" {{ old('paid_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                <option value="Overdue" {{ old('paid_status') == 'Overdue' ? 'selected' : '' }}>Overdue</option>
                                <option value="Not Applicable" {{ old('paid_status') == 'Not Applicable' ? 'selected' : '' }}>Not Applicable</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="profile_photo" class="form-label">
                            <i class="fas fa-camera"></i>Profile Photo
                        </label>
                        <div class="profile-photo-upload">
                            <div class="photo-preview" id="photoPreview">
                                <div class="photo-placeholder">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Choose Photo</span>
                                </div>
                            </div>
                            <input type="file" id="profile_photo" name="profile_photo" class="photo-input" accept="image/*">
                            <div class="photo-upload-info">
                                <small class="form-text text-muted">Supported formats: JPG, PNG, GIF. Max size: 2MB</small>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEmployeeModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editEmployeeModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-user-edit"></i>
                Edit Employee
            </div>
            <div class="modal-subtitle">Update employee information below</div>
            <button class="modal-close" onclick="closeEditModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="editEmployeeForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-container">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_employee_id" class="form-label">
                                <i class="fas fa-id-badge"></i>Employee ID
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_employee_id" name="employee_id" class="form-input" placeholder="Employee ID" readonly style="background: #f3f4f6; cursor: not-allowed;">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_employee_name" class="form-label">
                                <i class="fas fa-user"></i>Full Name
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_employee_name" name="employee_name" class="form-input" placeholder="Enter full name" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_email" class="form-label">
                                <i class="fas fa-envelope"></i>Email Address
                            </label>
                            <div style="position: relative;">
                                <input type="email" id="edit_email" name="email" class="form-input" placeholder="Enter email address" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_contact_no" class="form-label">
                                <i class="fas fa-phone"></i>Contact Number
                            </label>
                            <div style="position: relative;">
                                <input type="tel" id="edit_contact_no" name="contact_no" class="form-input" placeholder="Enter contact number" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_employee_type" class="form-label">
                                <i class="fas fa-briefcase"></i>Employee Type
                            </label>
                            <div style="position: relative;">
                                <select id="edit_employee_type" name="employee_type" class="form-select" required>
                                    <option value="">Select employee type</option>
                                    <option value="Full-time">Full-time</option>
                                    <option value="Part-time">Part-time</option>
                                    <option value="Contract">Contract</option>
                                    <option value="Intern">Intern</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_role" class="form-label">
                                <i class="fas fa-user-tag"></i>Role/Position
                            </label>
                            <div style="position: relative;">
                                <input type="text" id="edit_role" name="role" class="form-input" placeholder="Enter role or position" required>
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_department_id" class="form-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div style="position: relative;">
                                <select id="edit_department_id" name="department_id" class="form-select" required>
                                    <option value="">Select Department</option>
                                    @foreach($departments as $department)
                                    <option value="{{ $department->department_id }}">{{ $department->department_name ?? $department->department_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_admin_id" class="form-label">
                                <i class="fas fa-user-shield"></i>Reporting Manager
                            </label>
                            <div style="position: relative;">
                                <select id="edit_admin_id" name="admin_id" class="form-select">
                                    <option value="">Select Admin</option>
                                    @foreach($admins as $admin)
                                    <option value="{{ $admin->admin_id }}">{{ $admin->admin_name ?? $admin->admin_id }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_team_ids" class="form-label">
                            <i class="fas fa-users"></i> Teams (Hold Ctrl/Cmd to select multiple)
                        </label>
                        <div style="position: relative;">
                            <select id="edit_team_ids" name="team_ids[]" class="form-select" multiple>
                                @foreach($teams as $team)
                                <option value="{{ $team->team_id }}">{{ $team->team_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="edit_employee_status" class="form-label">
                                <i class="fas fa-toggle-on"></i>Employee Status
                            </label>
                            <div style="position: relative;">
                                <select id="edit_employee_status" name="employee_status" class="form-select" required>
                                    <option value="">Select status</option>
                                    <option value="Active">Active</option>
                                    <option value="Inactive">Inactive</option>
                                    <option value="On Leave">On Leave</option>
                                    <option value="Terminated">Terminated</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="edit_paid_status" class="form-label">
                                <i class="fas fa-credit-card"></i>Payment Status
                            </label>
                            <div style="position: relative;">
                                <select id="edit_paid_status" name="paid_status" class="form-select" required>
                                    <option value="">Select payment status</option>
                                    <option value="Paid">Paid</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Overdue">Overdue</option>
                                    <option value="Not Applicable">Not Applicable</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="edit_profile_photo" class="form-label">
                            <i class="fas fa-image"></i> Profile Photo
                        </label>
                        <div class="profile-photo-upload">
                            <div class="photo-preview" id="edit_photoPreviewContainer" onclick="document.getElementById('edit_profile_photo').click();">
                                <img id="edit_photoPreview" src="" alt="Profile Photo" style="display: none;">
                                <div id="edit_photoPlaceholder" class="photo-placeholder">
                                    <i class="fas fa-user-circle"></i>
                                    <span>Choose Photo</span>
                                </div>
                            </div>
                            <input type="file" id="edit_profile_photo" name="profile_photo" class="photo-input" accept="image/*">
                            <div class="photo-upload-info">
                                <small>JPG, PNG, GIF. Max 2MB.</small>
                            </div>
                        </div>
                    </div>

                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeEditModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Employee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div id="viewEmployeeModal" class="modal-overlay">
    <div class="modal-container">
        <div class="modal-header">
            <div class="modal-title">
                <i class="fas fa-user-circle"></i>
                Employee Details
            </div>
            <div class="modal-subtitle">Complete employee information</div>
            <button class="modal-close" onclick="closeViewModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <div class="modal-body">
            <div class="form-container">
                <div style="text-align: center; margin-bottom: 2rem; padding: 1.5rem; background: var(--gradient-glass); border-radius: 1rem; border: 1px solid var(--border-light);">
                    <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 16px rgba(220,38,38,0.2);">
                        <img id="view_profile_photo" src="" alt="Profile Photo" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%; box-shadow: 0 2px 8px rgba(220,38,38,0.08); border: 3px solid var(--redcode-primary); background: #fff; display: none;">
                        <i id="view_profile_photo_placeholder" class="fas fa-user" style="font-size: 2rem; color: white; display: block;"></i>
                    </div>
                    <h3 id="view_employee_name_header" style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--text-primary);"></h3>
                    <p id="view_employee_id_header" style="margin: 0.25rem 0 0; color: var(--text-secondary); font-size: 0.9rem;"></p>
                </div>

                <div class="view-section">
                    <h4 class="view-section-title">
                        <i class="fas fa-user"></i>
                        Personal Information
                    </h4>
                    <div class="view-grid">
                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-id-badge"></i>Employee ID
                            </label>
                            <div id="view_employee_id" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-user"></i>Full Name
                            </label>
                            <div id="view_employee_name" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-envelope"></i>Email Address
                            </label>
                            <div id="view_email" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-phone"></i>Contact Number
                            </label>
                            <div id="view_contact_no" class="view-value"></div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <h4 class="view-section-title">
                        <i class="fas fa-briefcase"></i>
                        Employment Information
                    </h4>
                    <div class="view-grid">
                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-briefcase"></i>Employee Type
                            </label>
                            <div id="view_employee_type" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-user-tag"></i>Role/Position
                            </label>
                            <div id="view_role" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-building"></i>Department
                            </label>
                            <div id="view_department" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-user-shield"></i>Reporting Manager
                            </label>
                            <div id="view_admin" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-users"></i>Teams
                            </label>
                            <div id="view_teams" class="view-value"></div>
                        </div>
                    </div>
                </div>

                <div class="view-section">
                    <h4 class="view-section-title">
                        <i class="fas fa-chart-line"></i>
                        Status Information
                    </h4>
                    <div class="view-grid">
                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-toggle-on"></i>Employee Status
                            </label>
                            <div id="view_employee_status" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-credit-card"></i>Payment Status
                            </label>
                            <div id="view_paid_status" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-calendar-plus"></i>Date Added
                            </label>
                            <div id="view_created_at" class="view-value"></div>
                        </div>

                        <div class="view-field">
                            <label class="view-label">
                                <i class="fas fa-clock"></i>Account Age
                            </label>
                            <div id="view_account_age" class="view-value"></div>
                        </div>
                    </div>
                </div>

                <div class="form-actions" style="justify-content: center; margin-top: 2rem;">
                    <button class="btn btn-secondary" style="padding: 0.5rem 1rem;" onclick="closeViewModal()">
                        <i class="fas fa-times"></i> Close
                    </button>
                    <button class="btn btn-primary" style="padding: 0.5rem 1rem;" onclick="openEditModalFromView()">
                        <i class="fas fa-user-edit"></i> Edit Employee
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openEmployeeModal() {
        document.getElementById('employeeModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEmployeeModal() {
        document.getElementById('employeeModal').classList.remove('active');
        document.body.style.overflow = 'auto';


        document.getElementById('employeeForm').reset();

        const errorMessage = document.querySelector('#employeeModal .error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    document.getElementById('employeeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEmployeeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmployeeModal();
        }
    });


    if (document.getElementById('photoPreview') && document.getElementById('profile_photo')) {
        document.getElementById('photoPreview').addEventListener('click', function() {
            document.getElementById('profile_photo').click();
        });

        document.getElementById('profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('photoPreview');

            if (file) {

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }


                if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                    alert('Please select a valid image file (JPG, PNG, GIF)');
                    this.value = '';
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Profile Photo">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = `
                <div class="photo-placeholder">
                    <i class="fas fa-user-circle"></i>
                    <span>Choose Photo</span>
                </div>
            `;
            }
        });
    }


    let currentEditEmployeeId = null;

    function openEditModal(employeeId, employeeName, email, contactNo, employeeType, role, departmentId, adminId, employeeStatus, paidStatus, profilePhotoUrl) {
        currentEditEmployeeId = employeeId;

        document.getElementById('edit_employee_id').value = employeeId || '';
        document.getElementById('edit_employee_name').value = employeeName || '';
        document.getElementById('edit_email').value = email || '';
        document.getElementById('edit_contact_no').value = contactNo || '';
        document.getElementById('edit_role').value = role || '';
        document.getElementById('edit_employee_type').value = employeeType || '';
        document.getElementById('edit_department_id').value = departmentId || '';
        document.getElementById('edit_admin_id').value = adminId || '';
        document.getElementById('edit_employee_status').value = employeeStatus || '';
        document.getElementById('edit_paid_status').value = paidStatus || '';
        document.getElementById('edit_profile_photo').addEventListener('change', function(e) {
            const file = e.target.files[0];
            const preview = document.getElementById('edit_photoPreview');
            const placeholder = document.getElementById('edit_photoPlaceholder');

            if (file) {

                if (file.size > 2 * 1024 * 1024) {
                    alert('File size must be less than 2MB');
                    this.value = '';
                    return;
                }
                if (!file.type.match(/^image\/(jpeg|jpg|png|gif)$/)) {
                    alert('Please select a valid image file (JPG, PNG, GIF)');
                    this.value = '';
                    return;
                }
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                    placeholder.style.display = 'none';
                };
                reader.readAsDataURL(file);
            } else {
                preview.src = '';
                preview.style.display = 'none';
                placeholder.style.display = 'flex';
            }
        });
        document.getElementById('editEmployeeForm').action = `/admin/employees/${employeeId}`;

        const photoPreview = document.getElementById('edit_photoPreview');
        const photoPlaceholder = document.getElementById('edit_photoPlaceholder');
        if (profilePhotoUrl) {
            photoPreview.src = profilePhotoUrl;
            photoPreview.style.display = 'block';
            photoPlaceholder.style.display = 'none';
        } else {
            photoPreview.src = '';
            photoPreview.style.display = 'none';
            photoPlaceholder.style.display = 'flex';
        }

        document.getElementById('editEmployeeModal').classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeEditModal() {
        document.getElementById('editEmployeeModal').classList.remove('active');
        document.body.style.overflow = 'auto';

        document.getElementById('editEmployeeForm').reset();

        const errorMessage = document.querySelector('#editEmployeeModal .error-message');
        if (errorMessage) {
            errorMessage.remove();
        }
    }

    document.getElementById('editEmployeeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const addModal = document.getElementById('employeeModal');
            const editModal = document.getElementById('editEmployeeModal');

            if (addModal.classList.contains('active')) {
                closeEmployeeModal();
            } else if (editModal.classList.contains('active')) {
                closeEditModal();
            }
        }
    });

    document.getElementById('employeeModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEmployeeModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeEmployeeModal();
        }
    });

    document.getElementById('editEmployeeForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'var(--redcode-primary)';
                field.style.background = 'rgba(220, 38, 38, 0.05)';
            } else {
                field.style.borderColor = 'var(--redcode-green)';
                field.style.background = 'rgba(5, 150, 105, 0.05)';
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    document.getElementById('edit_contact_no').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        this.value = value;
    });

    document.querySelectorAll('#editEmployeeModal .form-input, #editEmployeeModal .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--redcode-primary)';
                icon.style.transform = 'translateY(-50%) scale(1.1)';
            }
        });

        input.addEventListener('blur', function() {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--text-light)';
                icon.style.transform = 'translateY(-50%) scale(1)';
            }
        });
    });

    document.getElementById('employeeForm').addEventListener('submit', function(e) {
        const requiredFields = this.querySelectorAll('[required]');
        let isValid = true;

        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.style.borderColor = 'var(--redcode-primary)';
                field.style.background = 'rgba(220, 38, 38, 0.05)';
            } else {
                field.style.borderColor = 'var(--redcode-green)';
                field.style.background = 'rgba(5, 150, 105, 0.05)';
            }
        });

        if (!isValid) {
            e.preventDefault();
            alert('Please fill in all required fields.');
        }
    });

    document.getElementById('contact_no').addEventListener('input', function() {
        let value = this.value.replace(/\D/g, '');
        if (value.length > 15) {
            value = value.substring(0, 15);
        }
        this.value = value;
    });

    document.querySelectorAll('.form-input, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--redcode-primary)';
                icon.style.transform = 'translateY(-50%) scale(1.1)';
            }
        });

        input.addEventListener('blur', function() {
            const icon = this.nextElementSibling;
            if (icon && icon.classList.contains('input-icon')) {
                icon.style.color = 'var(--text-light)';
                icon.style.transform = 'translateY(-50%) scale(1)';
            }
        });
    });


    @if ($errors->any())
        window.addEventListener('load', function() {
            openEmployeeModal();
        });
    @endif


    function searchEmployees() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (row.cells.length === 1) return;

            const employeeName = row.cells[0].textContent.toLowerCase();
            const employeeId = row.cells[0].textContent.toLowerCase();
            const department = row.cells[1].textContent.toLowerCase();
            const role = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();
            const phone = row.cells[4].textContent.toLowerCase();

            if (employeeName.includes(searchTerm) ||
                employeeId.includes(searchTerm) ||
                department.includes(searchTerm) ||
                role.includes(searchTerm) ||
                email.includes(searchTerm) ||
                phone.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }

    function filterEmployees() {
        const departmentFilter = document.getElementById('departmentFilter').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            if (row.cells.length === 1) return;

            const department = row.cells[1].textContent.toLowerCase();

            if (departmentFilter === '' || department.includes(departmentFilter)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }


    function resetFilters() {
        document.getElementById('searchInput').value = '';
        document.getElementById('departmentFilter').value = '';

        const rows = document.querySelectorAll('tbody tr');
        rows.forEach(row => {
            row.style.display = '';
        });
    }


    function confirmDelete(employeeId, employeeName) {
        if (confirm(`Are you sure you want to delete employee "${employeeName}" (ID: ${employeeId})?\n\nThis action cannot be undone.`)) {

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/employees/${employeeId}`;

            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken) {
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = csrfToken.getAttribute('content');
                form.appendChild(csrfInput);
            }

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }
    }


    let currentViewEmployeeId = null;

    function openViewModal(employeeId, employeeName, email, contactNo, employeeType, role, department, admin, employeeStatus, paidStatus, createdAt, teams, profilePhotoUrl) {
        currentViewEmployeeId = employeeId;

        var img = document.getElementById('view_profile_photo');
        var placeholder = document.getElementById('view_profile_photo_placeholder');
        if (profilePhotoUrl && profilePhotoUrl !== '') {
            img.src = profilePhotoUrl;
            img.style.display = 'block';
            placeholder.style.display = 'none';
        } else {
            img.src = '';
            img.style.display = 'none';
            placeholder.style.display = 'block';
        }


        document.getElementById('view_employee_id').textContent = employeeId || 'Not specified';
        document.getElementById('view_employee_name').textContent = employeeName || 'Not specified';
        document.getElementById('view_employee_name_header').textContent = employeeName || 'Unknown Employee';
        document.getElementById('view_employee_id_header').textContent = `Employee ID: ${employeeId || 'Not specified'}`;
        document.getElementById('view_email').textContent = email || 'Not specified';
        document.getElementById('view_contact_no').textContent = contactNo || 'Not specified';
        document.getElementById('view_employee_type').textContent = employeeType || 'Not specified';
        document.getElementById('view_role').textContent = role || 'Not specified';
        document.getElementById('view_department').textContent = department || 'Not assigned';
        document.getElementById('view_admin').textContent = admin || 'Not assigned';
        document.getElementById('view_teams').textContent = teams || 'No teams assigned';
        document.getElementById('view_created_at').textContent = createdAt || 'Not specified';


        const statusElement = document.getElementById('view_employee_status');
        if (employeeStatus) {
            let statusClass = '';
            switch(employeeStatus.toLowerCase()) {
                case 'active':
                    statusClass = 'active';
                    break;
                case 'inactive':
                    statusClass = 'inactive';
                    break;
                case 'on leave':
                    statusClass = 'on-leave';
                    break;
                case 'terminated':
                    statusClass = 'terminated';
                    break;
                default:
                    statusClass = 'inactive';
            }
            statusElement.innerHTML = `<span class="status-badge ${statusClass}">${employeeStatus}</span>`;
        } else {
            statusElement.textContent = 'Not specified';
        }


        const paidElement = document.getElementById('view_paid_status');
        if (paidStatus) {
            let paidClass = '';
            switch(paidStatus.toLowerCase()) {
                case 'paid':
                    paidClass = 'paid';
                    break;
                case 'pending':
                    paidClass = 'pending';
                    break;
                case 'overdue':
                    paidClass = 'overdue';
                    break;
                case 'not applicable':
                    paidClass = 'not-applicable';
                    break;
                default:
                    paidClass = 'pending';
            }
            paidElement.innerHTML = `<span class="status-badge ${paidClass}">${paidStatus}</span>`;
        } else {
            paidElement.textContent = 'Not specified';
        }


        const accountAgeElement = document.getElementById('view_account_age');
        if (createdAt && createdAt !== 'N/A') {
            try {
                const createdDate = new Date(createdAt);
                const now = new Date();
                const diffTime = Math.abs(now - createdDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if (diffDays < 30) {
                    accountAgeElement.textContent = `${diffDays} days`;
                } else if (diffDays < 365) {
                    const months = Math.floor(diffDays / 30);
                    accountAgeElement.textContent = `${months} month${months > 1 ? 's' : ''}`;
                } else {
                    const years = Math.floor(diffDays / 365);
                    const remainingMonths = Math.floor((diffDays % 365) / 30);
                    let ageText = `${years} year${years > 1 ? 's' : ''}`;
                    if (remainingMonths > 0) {
                        ageText += ` ${remainingMonths} month${remainingMonths > 1 ? 's' : ''}`;
                    }
                    accountAgeElement.textContent = ageText;
                }
            } catch (e) {
                accountAgeElement.textContent = 'Unable to calculate';
            }
        } else {
            accountAgeElement.textContent = 'Not available';
        }


        const modal = document.getElementById('viewEmployeeModal');
        modal.classList.add('active');
    }

    function closeViewModal() {
        const modal = document.getElementById('viewEmployeeModal');
        modal.classList.remove('active');
        currentViewEmployeeId = null;
    }

    function openEditModalFromView() {
        if (currentViewEmployeeId) {

            const rows = document.querySelectorAll('tbody tr');
            for (let row of rows) {
                if (row.cells.length > 1) {
                    const employeeIdCell = row.cells[0].textContent.trim();
                    if (employeeIdCell === currentViewEmployeeId) {

                        const editButton = row.querySelector('button[title="Edit Employee"]');
                        if (editButton) {
                            editButton.click();
                            return;
                        }
                    }
                }
            }
        }
    }


    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay')) {
            if (e.target.id === 'viewEmployeeModal') {
                closeViewModal();
            }
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            if (document.getElementById('viewEmployeeModal').classList.contains('active')) {
                closeViewModal();
            }
        }
    });


    let currentLeaveFilter = 'all';

    function filterByLeaveStatus(filterType) {
        currentLeaveFilter = filterType;
        applyLeaveFilters();


        document.querySelectorAll('[onclick^="filterByLeaveStatus"]').forEach(btn => {
            btn.classList.remove('btn-primary', 'btn-warning', 'btn-info', 'btn-success', 'btn-danger', 'btn-secondary');
            btn.classList.add('btn-secondary');
        });


        const activeButton = document.querySelector(`[onclick="filterByLeaveStatus('${filterType}')"]`);
        if (activeButton) {
            activeButton.classList.remove('btn-secondary');
            switch(filterType) {
                case 'all':
                    activeButton.classList.add('btn-primary');
                    break;
                case 'with-leaves':
                    activeButton.classList.add('btn-warning');
                    break;
                case 'pending-leaves':
                    activeButton.classList.add('btn-info');
                    break;
                case 'approved-leaves':
                    activeButton.classList.add('btn-success');
                    break;
                case 'rejected-leaves':
                    activeButton.classList.add('btn-danger');
                    break;
                case 'no-leaves':
                    activeButton.classList.add('btn-secondary');
                    break;
            }
        }
    }

    function applyLeaveFilters() {
        const rows = document.querySelectorAll('tbody tr');
        const leaveStatusFilter = document.getElementById('leaveStatusFilter').value;
        const leaveTypeFilter = document.getElementById('leaveTypeFilter').value;
        const leaveCountFilter = document.getElementById('leaveCountFilter').value;
        const leaveDateFilter = document.getElementById('leaveDateFilter').value;

        rows.forEach(row => {
            if (row.cells.length < 8) return;


            const status = row.cells[5].textContent.trim();
            const leaveCountCell = row.cells[6];


            const leaveCountBadge = leaveCountCell.querySelector('.badge');
            const leaveCount = leaveCountBadge ? parseInt(leaveCountBadge.textContent) : 0;


            const leaveBreakdown = leaveCountCell.querySelectorAll('span');
            let pendingCount = 0, approvedCount = 0, rejectedCount = 0;

            leaveBreakdown.forEach(span => {
                const text = span.textContent.toLowerCase();
                if (text.includes('pending')) {
                    pendingCount = parseInt(text.match(/\d+/)[0]);
                } else if (text.includes('approved')) {
                    approvedCount = parseInt(text.match(/\d+/)[0]);
                } else if (text.includes('rejected')) {
                    rejectedCount = parseInt(text.match(/\d+/)[0]);
                }
            });

            let showRow = true;


            if (currentLeaveFilter !== 'all') {
                switch(currentLeaveFilter) {
                    case 'with-leaves':
                        showRow = leaveCount > 0;
                        break;
                    case 'no-leaves':
                        showRow = leaveCount === 0;
                        break;
                    case 'pending-leaves':
                        showRow = pendingCount > 0;
                        break;
                    case 'approved-leaves':
                        showRow = approvedCount > 0;
                        break;
                    case 'rejected-leaves':
                        showRow = rejectedCount > 0;
                        break;
                    case 'on-leave':
                        showRow = status === 'On Leave';
                        break;
                }
            }


            if (showRow && leaveStatusFilter) {
                switch(leaveStatusFilter) {
                    case 'with-leaves':
                        showRow = leaveCount > 0;
                        break;
                    case 'no-leaves':
                        showRow = leaveCount === 0;
                        break;
                    case 'pending-leaves':
                        showRow = pendingCount > 0;
                        break;
                    case 'approved-leaves':
                        showRow = approvedCount > 0;
                        break;
                    case 'rejected-leaves':
                        showRow = rejectedCount > 0;
                        break;
                    case 'on-leave':
                        showRow = status === 'On Leave';
                        break;
                }
            }


            if (showRow && leaveCountFilter) {
                switch(leaveCountFilter) {
                    case '0':
                        showRow = leaveCount === 0;
                        break;
                    case '1-3':
                        showRow = leaveCount >= 1 && leaveCount <= 3;
                        break;
                    case '4-7':
                        showRow = leaveCount >= 4 && leaveCount <= 7;
                        break;
                    case '8+':
                        showRow = leaveCount >= 8;
                        break;
                }
            }


            if (showRow && leaveTypeFilter) {
                showRow = leaveCount > 0;
            }

            if (showRow && leaveDateFilter) {
                            showRow = leaveCount > 0;
            }

            row.style.display = showRow ? '' : 'none';
        });

        updateFilterResults();
    }

    function updateFilterResults() {
        const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])');
        const totalRows = document.querySelectorAll('tbody tr');

        console.log(`Showing ${visibleRows.length} of ${totalRows.length} employees`);
    }


    function exportLeaveReport() {
        alert('Exporting leave report...');
    }

    function bulkApproveLeaves() {
        alert('Opening bulk approve interface...');
    }

    function viewLeaveCalendar() {
        alert('Opening leave calendar...');
    }

    function generateLeaveAnalytics() {
        alert('Generating leave analytics...');

    }



    function searchEmployees() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const rows = document.querySelectorAll('tbody tr');
        let foundEmployee = null;

        rows.forEach(row => {
            if (row.cells.length < 8) return;

            const employeeName = row.cells[0].textContent.toLowerCase();
            const department = row.cells[1].textContent.toLowerCase();
            const position = row.cells[2].textContent.toLowerCase();
            const email = row.cells[3].textContent.toLowerCase();

            const matchesSearch = employeeName.includes(searchTerm) ||
                department.includes(searchTerm) ||
                position.includes(searchTerm) ||
                email.includes(searchTerm);


            const currentDisplay = row.style.display;
            if (currentDisplay === 'none') {

                row.style.display = 'none';
            } else {
                row.style.display = matchesSearch ? '' : 'none';
            }

            if (matchesSearch && employeeName.includes(searchTerm) && searchTerm.length > 2) {
                const employeeId = row.querySelector('button[title="View Employee"]')?.getAttribute('onclick')?.match(/'([^']+)'/)?.[1];
                if (employeeId) {
                    foundEmployee = {
                        id: employeeId,
                        name: row.cells[0].textContent.trim(),
                        department: row.cells[1].textContent.trim(),
                        position: row.cells[2].textContent.trim()
                    };
                }
            }
        });

        // Show leave information if an employee was found
        if (foundEmployee) {
            showEmployeeLeaveInfo(foundEmployee.id, foundEmployee.name, foundEmployee.department, foundEmployee.position);
        } else {
            hideEmployeeLeaveInfo();
        }
    }
</script>

@if ($errors->any())
<script>
    document.addEventListener('DOMContentLoaded', function() {
        openEmployeeModal();

        // Show errors in modal
        const errorContainer = document.createElement('div');
        errorContainer.innerHTML = `
                <div class="error-message" style="margin-bottom: 1rem;">
                    <i class="fas fa-exclamation-triangle" style="margin-right: 0.5rem;"></i>
                    <strong>Please correct the following errors:</strong>
                    <ul style="margin: 0.5rem 0 0 1rem; list-style: disc;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            `;

        const form = document.getElementById('employeeForm');
        form.insertBefore(errorContainer, form.firstChild);
    });
</script>

@endif

@endsection
