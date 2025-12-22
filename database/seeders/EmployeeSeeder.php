<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure a department exists
        $department = \App\Models\Department::first();
        if (!$department) {
            $department = \App\Models\Department::create([
                'department_name' => 'IT Department',
                'description' => 'Information Technology',
                'location' => 'Main Building',
                'phone' => '1234567890',
                'email' => 'it@company.com',
                'budget' => 50000,
                'status' => 'Active',
            ]);
        }

        // Ensure an admin exists
        $admin = \App\Models\Admin::first();
        if (!$admin) {
            $adminId = Str::uuid()->toString();
            $admin = \App\Models\Admin::create([
                'admin_id' => $adminId,
                'admin_name' => 'System Admin',
                'role' => 'admin',
                'department_id' => $department->department_id,
                'email' => 'admin@company.com',
                'contact_no' => '9876543210',
                'status' => 'Active',
            ]);
            // If create fillable doesn't include admin_id (it doesn't), we need to set it manually if not handled by model events
            // But since I passed it in create array, hopefully it works if unguarded or I force it.
            // Actually Admin model fillable doesn't have admin_id. 
            // So let's force set it.
            $admin->admin_id = $adminId;
            $admin->save();
        }

        // Create 20 employees
        Employee::factory()->count(20)->create([
            'department_id' => $department->department_id,
            'admin_id' => $admin->admin_id,
        ])->each(function ($employee) {
            // Create user for the employee
            User::create([
                'name' => $employee->employee_name,
                'email' => $employee->email,
                'password' => Hash::make('password'), // Default password
                'role' => 'employee',
                'contact_no' => $employee->contact_no,
                'employee_id' => $employee->employee_id,
                'email_verified_at' => now(),
            ]);
        });
    }
}
