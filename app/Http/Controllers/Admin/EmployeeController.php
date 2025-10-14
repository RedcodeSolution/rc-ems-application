<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $departments = Department::all();
        $admins = Admin::all();
        $teams = Team::all();

        $employeesQuery = Employee::with(['department', 'admin', 'teams', 'leaves']);

        // Reset filters if requested
        if ($request->has('reset') && $request->reset == '1') {
            return redirect()->route('admin.employees');
        }

        // Leave Status Filter
        if ($request->filled('leave_status')) {
            switch ($request->leave_status) {
                case 'with-leaves':
                    $employeesQuery->has('leaves');
                    break;
                case 'no-leaves':
                    $employeesQuery->doesntHave('leaves');
                    break;
                case 'pending-leaves':
                    $employeesQuery->whereHas('leaves', function ($q) {
                        $q->where('status', 'pending');
                    });
                    break;
                case 'approved-leaves':
                    $employeesQuery->whereHas('leaves', function ($q) {
                        $q->where('status', 'approved');
                    });
                    break;
                case 'rejected-leaves':
                    $employeesQuery->whereHas('leaves', function ($q) {
                        $q->where('status', 'rejected');
                    });
                    break;
                case 'on-leave':
                    $employeesQuery->where('employee_status', 'On Leave');
                    break;
            }
        }

        // Leave Type Filter
        if ($request->filled('leave_type')) {
            $employeesQuery->whereHas('leaves', function ($q) use ($request) {
                $q->where('leave_type', $request->leave_type);
            });
        }

        // Leave Count Filter
        if ($request->filled('leave_count')) {
            switch ($request->leave_count) {
                case '0':
                    $employeesQuery->doesntHave('leaves');
                    break;
                case '1-3':
                    $employeesQuery->has('leaves', '>=', 1)->has('leaves', '<=', 3);
                    break;
                case '4-7':
                    $employeesQuery->has('leaves', '>=', 4)->has('leaves', '<=', 7);
                    break;
                case '8+':
                    $employeesQuery->has('leaves', '>=', 8);
                    break;
            }
        }

        // Leave Period Filter
        if ($request->filled('leave_period')) {
            $employeesQuery->whereHas('leaves', function ($q) use ($request) {
                $now = now();
                switch ($request->leave_period) {
                    case 'this-month':
                        $q->whereMonth('start_date', $now->month)->whereYear('start_date', $now->year);
                        break;
                    case 'last-month':
                        $lastMonth = $now->copy()->subMonth();
                        $q->whereMonth('start_date', $lastMonth->month)->whereYear('start_date', $lastMonth->year);
                        break;
                    case 'this-quarter':
                        $quarter = ceil($now->month / 3);
                        $q->whereRaw('QUARTER(start_date) = ?', [$quarter])->whereYear('start_date', $now->year);
                        break;
                    case 'this-year':
                        $q->whereYear('start_date', $now->year);
                        break;
                    case 'last-30-days':
                        $q->where('start_date', '>=', $now->copy()->subDays(30));
                        break;
                    case 'last-90-days':
                        $q->where('start_date', '>=', $now->copy()->subDays(90));
                        break;
                }
            });
        }

        // Sort logic
        if ($request->filled('sort_by')) {
            switch ($request->sort_by) {
                case 'name_asc':
                    $employeesQuery->orderBy('employee_name', 'asc');
                    break;
                case 'name_desc':
                    $employeesQuery->orderBy('employee_name', 'desc');
                    break;
                case 'department_asc':
                    $employeesQuery->orderBy('department_id', 'asc');
                    break;
                case 'department_desc':
                    $employeesQuery->orderBy('department_id', 'desc');
                    break;
                case 'leave_count_asc':
                    $employeesQuery->withCount('leaves')->orderBy('leaves_count', 'asc');
                    break;
                case 'leave_count_desc':
                    $employeesQuery->withCount('leaves')->orderBy('leaves_count', 'desc');
                    break;
            }
        }

        $employees = $employeesQuery->get();

        return view('admin.employees.index', compact('employees', 'departments', 'admins', 'teams'));
    }

    public function create()
    {
        $departments = Department::all();
        $admins = Admin::all();
        $teams = Team::all();
        return view('employees.create', compact('departments', 'admins', 'teams'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'employee_type' => 'required',
            'employee_status' => 'required',
            'contact_no' => 'required',
            'department_id' => 'nullable',
            'admin_id' => 'nullable',
            'paid_status' => 'required',
            'role' => 'required',
            'team_ids' => 'nullable|array',
            'team_ids.*' => 'exists:teams,team_id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee_photos', $filename, 'public');
            $validated['profile_photo'] = $path;
        }

        DB::transaction(function () use ($validated, $request) {
            $employee = Employee::create($validated);
            if ($request->has('team_ids')) {
                $employee->teams()->sync($request->input('team_ids'));
            }
        });

        return redirect()->route('admin.employees')->with('success', 'Employee and teams saved successfully.');
    }

    public function show($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        return view('employees.view', compact('employee'));
    }

    public function edit($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $departments = Department::all();
        $admins = Admin::all();
        return view('employees.edit', compact('employee', 'departments', 'admins'));
    }

    public function update(Request $request, $employee_id)
    {
        $employee = Employee::findOrFail($employee_id);

        $validated = $request->validate([
            'employee_name' => 'required',
            'email' => 'required|email|unique:employees,email,' . $employee_id . ',employee_id',
            'employee_type' => 'required',
            'employee_status' => 'required',
            'contact_no' => 'required',
            'department_id' => 'nullable',
            'admin_id' => 'nullable',
            'paid_status' => 'required',
            'role' => 'required',
            'team_ids' => 'nullable|array',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($employee->profile_photo && \Storage::disk('public')->exists($employee->profile_photo)) {
                \Storage::disk('public')->delete($employee->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $employee_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee_photos', $filename, 'public');
            $validated['profile_photo'] = $path;
        }

        $employee->update($validated);

        if ($request->has('team_ids')) {
            $employee->teams()->sync($request->team_ids);
        }

        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully.');
    }


    public function destroy($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully.');
    }
}
