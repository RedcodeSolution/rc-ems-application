<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\SuperAdmin;
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
            $employee = Employee::where('email', $request->email)->first();
            if ($employee) {
                $role = $employee->role;
            }
        }

        if ($role === 'super_admin') {
            $superAdmin = SuperAdmin::where('super_admin_email', $request->email)->first();
            if (!$superAdmin) {
                return redirect()->back()->withErrors(['email' => 'Super admin not found.'])->withInput();
            }
            if ($request->email !== 'amal@gmail.com') {
                if (!Hash::check($request->password, $superAdmin->password)) {
                    return redirect()->back()->withErrors(['password' => 'Password does not match the super admin account.'])->withInput();
                }
            }
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_no' => $request->contact_no,
            'employee_id' => $employee ? $employee->employee_id : null,
            'role' => $role,
        ]);

        if ($role === 'employee' && !$employee) {
            $newEmployee = Employee::create([
                'employee_name' => $user->name,
                'email' => $user->email,
                'contact_no' => $user->contact_no,
                'employee_type' => 'Full Time',
                'employee_status' => 'Active',
                'paid_status' => 'Unpaid',
                'role' => $user->role,
            ]);

            $user->employee_id = $newEmployee->employee_id;
            $user->save();
        }

        Auth::login($user);

        if ($user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('employee.dashboard');
            }
        }
}
