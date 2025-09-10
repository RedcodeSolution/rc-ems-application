<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminAccountsController extends Controller
{
    public function index()
    {
        $superAdmins = SuperAdmin::all();

        // Example statistics (customize as needed)
        $totalSuperAdmins = $superAdmins->count();
        $activeSuperAdmins = $superAdmins->count(); // If you have a status field, filter by status
        $inactiveSuperAdmins = 0; // If you have a status field, filter by status
        $recentLogins = 0; // If you track logins, calculate here

        // Pass accounts and stats to the view
        return view('super_admin.super_admin_accounts', [
            'superAdminUsers' => $superAdmins,
            'totalSuperAdmins' => $totalSuperAdmins,
            'activeSuperAdmins' => $activeSuperAdmins,
            'inactiveSuperAdmins' => $inactiveSuperAdmins,
            'recentLogins' => $recentLogins,
            'recentActivities' => [] // Fill if you have activity data
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:super_admins,super_admin_email',
            'password' => 'required|string|min:8|confirmed',
            'permissions' => 'nullable|array'
        ]);

        $superAdmin = SuperAdmin::create([
            'super_admin_id' => Str::uuid(),
            'super_admin_name' => $validated['name'],
            'super_admin_email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'permissions' => json_encode($validated['permissions'] ?? []),

        ]);

        return response()->json(['success' => true, 'super_admin' => $superAdmin]);
    }

    public function show($id)
    {
        $user = SuperAdmin::findOrFail($id);

        // Example: If you want to add login info, you can add those fields here
        $data = [
            'id' => $user->super_admin_id,
            'name' => $user->super_admin_name,
            'email' => $user->super_admin_email,
            'created' => $user->created_at ? $user->created_at->format('F d, Y') : '',
            'status' => 'active', // You can change this if you have a status field
            'lastLogin' => 'Never', // Replace with actual last login if available
            'loginCount' => 0, // Replace with actual login count if available
            'lastIp' => '', // Replace with actual last IP if available
            'permissions' => collect(json_decode($user->permissions, true))->map(function($perm) {
                // Map permission keys to readable names
                switch ($perm) {
                    case 'user_management': return 'User Management';
                    case 'system_settings': return 'System Settings';
                    case 'security': return 'Security Settings';
                    case 'reports': return 'Reports & Analytics';
                    case 'backup': return 'Backup & Restore';
                    case 'logs': return 'System Logs';
                    default: return ucfirst(str_replace('_', ' ', $perm));
                }
            })->toArray()
        ];

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $user = SuperAdmin::findOrFail($id);

        // Ensure permissions is always an array for validation
        if (!$request->has('permissions')) {
            $request->merge(['permissions' => []]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:super_admins,super_admin_email,' . $id . ',super_admin_id',
            'permissions' => 'nullable|array'
        ]);

        $user->super_admin_name = $validated['name'];
        $user->super_admin_email = $validated['email'];
        $user->permissions = json_encode($validated['permissions'] ?? []);
        $user->save();

        return response()->json(['success' => true, 'super_admin' => $user]);
    }
}
