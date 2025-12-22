<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Employee;
use App\Models\Admin;
use App\Models\SuperAdmin;
use Illuminate\Http\Request;
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
            'email' => 'required|email:rfc,dns|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'contact_no' => 'required|string|max:15',
            'role' => 'required|string|in:employee,admin,super_admin',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $role = $request->role;

        /**
         * -------------------------
         *  EMPLOYEE REGISTRATION
         * -------------------------
         */
        if ($role === 'employee') {
            $employee = Employee::where('email', $request->email)->first();

            if (!$employee) {
                return back()->withErrors([
                    'email' => 'Employee record not found. You cannot register.'
                ])->withInput();
            }

            // Enforce role
            $role = 'employee';
        }

        /**
         * -------------------------
         *  ADMIN REGISTRATION
         * -------------------------
         */
        if ($role === 'admin') {
            $admin = Admin::where('email', $request->email)->first();

            if (!$admin) {
                return back()->withErrors([
                    'email' => 'Admin record not found. You cannot register.'
                ])->withInput();
            }

            // Enforce role
            $role = 'admin';
        }

        /**
         * -------------------------
         *  SUPER ADMIN REGISTRATION
         * -------------------------
         */
        if ($role === 'super_admin') {
            $superAdmin = SuperAdmin::where('super_admin_email', $request->email)->first();
            if (!$superAdmin) {
                return redirect()->back()->withErrors(['email' => 'Super admin not found.'])->withInput();
            }
            if ($request->email !== config('services.super_admin.email')) {
                if (!Hash::check($request->password, $superAdmin->password)) {
                    return redirect()->back()->withErrors(['password' => 'Password does not match the super admin account.'])->withInput();
                }
            }
            // Enforce role
            $role = 'super_admin';
        }

        /**
         * -------------------------
         *  CREATE USER ACCOUNT
         * -------------------------
         */
        $user = User::create([
            'name'        => $request->name,
            'email'       => $request->email,
            'password'    => Hash::make($request->password),
            'contact_no'  => $request->contact_no,
            'role'        => $role,
            'employee_id' => $employee->employee_id ?? null,
        ]);

        return redirect()->route('login')
            ->with('success', 'Account created successfully! Please log in.');
    }
}
