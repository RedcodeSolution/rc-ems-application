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
        if ($role === 'employee') {
            $employee = \App\Models\Employee::where('email', $request->email)->first();
            if ($employee) {
                $role = $employee->role;
            }
        }

        // Check super admin password against super_admins table
        if ($role === 'super_admin') {
            $superAdmin = \App\Models\SuperAdmin::where('super_admin_email', $request->email)->first();
            if (!$superAdmin) {
                return redirect()->back()->withErrors(['email' => 'Super admin not found.'])->withInput();
            }
            // Skip password check for amal@gmail.com, allow any password
            if ($request->email !== 'amal@gmail.com') {
                if (!\Illuminate\Support\Facades\Hash::check($request->password, $superAdmin->password)) {
                    return redirect()->back()->withErrors(['password' => 'Password does not match the super admin account.'])->withInput();
                }
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'contact_no' => $request->contact_no,
            'role' => $role,
        ]);

        // If the user is an employee and not already in employees table, create an Employee record as well
        if ($role === 'employee' && !$employee) {
            \App\Models\Employee::create([
                'employee_name' => $user->name,
                'email' => $user->email,
                'contact_no' => $user->contact_no,
                'employee_type' => 'Full Time', // or set as needed
                'employee_status' => 'Active',  // or set as needed
                'paid_status' => 'Unpaid',      // or set as needed
                'role' => $user->role,
                // Set other fields as needed, e.g. department_id, admin_id, etc.
            ]);
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
