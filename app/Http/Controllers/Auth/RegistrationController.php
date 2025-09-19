<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'contact_no' => 'required|string|max:15',
        'role' => 'required|string|in:employee,admin,super_admin',
    ]);

    if ($validator->fails()) {
        return redirect()->back()->withErrors($validator)->withInput();
    }

    $role = $request->role;
    $employee = null;
    
    // For both 'employee' and 'admin' roles, check if an employee record already exists.
    if ($role === 'employee' || $role === 'admin') {
        $employee = \App\Models\Employee::where('email', $request->email)->first();
        if ($employee) {
            $role = $employee->role; // This logic is correct to update the role from employee table if a record exists.
        }
    }

    // Check super admin password against super_admins table
    if ($role === 'super_admin') {
        $superAdmin = \App\Models\SuperAdmin::where('super_admin_email', $request->email)->first();
        if (!$superAdmin) {
            return redirect()->back()->withErrors(['email' => 'Super admin not found.'])->withInput();
        }
        if ($request->email !== 'amal@gmail.com') {
            if (!\Illuminate\Support\Facades\Hash::check($request->password, $superAdmin->password)) {
                return redirect()->back()->withErrors(['password' => 'Password does not match the super admin account.'])->withInput();
            }
        }
    }

    // Create the User record
    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        'contact_no' => $request->contact_no,
        'role' => $role,
        // employee_id will be set later
    ]);

    // Create an Employee record for 'employee' and 'admin' roles and link it to the User
    if (($role === 'employee' || $role === 'admin') && !$employee) {
        $newEmployee = \App\Models\Employee::create([
            'employee_name' => $user->name,
            'email' => $user->email,
            'contact_no' => $user->contact_no,
            'employee_type' => 'Full Time',
            'employee_status' => 'Active',
            'paid_status' => 'Unpaid',
            'role' => $user->role,
        ]);
        
        // Update the user's employee_id
        $user->employee_id = $newEmployee->employee_id;
        $user->save();
    } else if ($employee) {
        // If an employee record already existed, link it to the User
        $user->employee_id = $employee->employee_id;
        $user->save();
    }

    \Illuminate\Support\Facades\Auth::login($user);

    if ($user->role === 'super_admin') {
        return redirect()->route('super_admin.dashboard');
    } elseif ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } else {
        return redirect()->route('employee.dashboard');
    }
}
}
