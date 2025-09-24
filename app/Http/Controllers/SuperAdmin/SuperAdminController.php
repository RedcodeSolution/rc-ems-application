<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminController extends Controller
{
    public function dashboard()
    {
        // Ensure Amal Perera exists with the correct password
        $email = 'amal@gmail.com';
        $password = '12345678';
        $name = 'Amal Perera';

        $superAdmin = SuperAdmin::where('super_admin_email', $email)->first();
        if (!$superAdmin) {
            SuperAdmin::create([
                'super_admin_id' => Str::uuid(),
                'super_admin_name' => $name,
                'super_admin_email' => $email,
                'password' => Hash::make($password),
                'status' => 'active',
                'role' => 'super_admin',
                'permissions' => json_encode(['user_management', 'system_settings', 'security', 'reports']),
            ]);
        }
        $chartData = [
            'monthly_registrations' => [10, 20, 15, 30], // replace with real query
            'leave_requests' => [5, 8, 3, 12],
            'project_completion' => [2, 4, 1, 6],
        ];

        // ...existing code for dashboard view...
        return view('super_admin.dashboard', compact('chartData'));
    }
}
