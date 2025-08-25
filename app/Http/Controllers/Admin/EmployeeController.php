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
    public function index()
    {
        $employees = Employee::all();
        return view('employees.index', compact('employees'));
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
            'employee_id' => 'required|unique:employees,employee_id',
            'employee_name' => 'required',
            'email' => 'required|email|unique:employees,email',
            'employee_type' => 'required',
            'employee_status' => 'required',
            'contact_no' => 'required',
            'department_id' => 'nullable',
            'admin_id' => 'nullable',
            'paid_status' => 'required',
            'role' => 'required',
            'team_ids' => 'nullable|array', // Accept multiple teams
            'team_ids.*' => 'exists:teams,team_id',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('profile_photo')) {
            $file = $request->file('profile_photo');
            $filename = time() . '_' . $validated['employee_id'] . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee_photos', $filename, 'public');

            $validated['profile_photo'] = $path;
        }

        DB::transaction(function () use ($validated, $request) {
            $employee = Employee::create($validated);
            if ($request->has('team_ids')) {
                $employee->teams()->sync($request->team_ids);
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
            'team_id' => 'nullable',
            'role' => 'required',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($employee->profile_photo && \Storage::disk('public')->exists($employee->profile_photo)) {
                \Storage::disk('public')->delete($employee->profile_photo);
            }

            $file = $request->file('profile_photo');
            $filename = time() . '_' . $employee_id . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('employee_photos', $filename, 'public');
            $validated['profile_photo'] = $path;
        }

        $employee->update($validated);
        return redirect()->route('admin.employees')->with('success', 'Employee updated successfully.');
    }

    public function destroy($employee_id)
    {
        $employee = Employee::findOrFail($employee_id);
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', 'Employee deleted successfully.');
    }
}
