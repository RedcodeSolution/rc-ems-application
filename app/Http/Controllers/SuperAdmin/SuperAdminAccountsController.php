<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;
use App\Models\SuperAdminActivity;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SuperAdminAccountsController extends Controller
{
    public function index()
    {
        $superAdmins = SuperAdmin::all();

        $totalSuperAdmins = $superAdmins->count();
        $activeSuperAdmins = $superAdmins->where('status', 'active')->count();
        $inactiveSuperAdmins = $superAdmins->where('status', 'inactive')->count();
        $recentLogins = 0;

        $superAdmins->transform(function ($user) {
            $user->last_login = $user->last_login ?? null;
            return $user;
        });

        // Fetch recent activities from DB (last 10)
        $recentActivities = SuperAdminActivity::orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($activity) {
                return [
                    'type' => $activity->type,
                    'icon' => $activity->icon,
                    'action' => $activity->action,
                    'details' => $activity->details,
                    'timestamp' => $activity->created_at
                ];
            });

        return view('super_admin.super_admin_accounts', [
            'superAdminUsers' => $superAdmins,
            'totalSuperAdmins' => $totalSuperAdmins,
            'activeSuperAdmins' => $activeSuperAdmins,
            'inactiveSuperAdmins' => $inactiveSuperAdmins,
            'recentLogins' => $recentLogins,
            'recentActivities' => $recentActivities
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

        // Log activity
        SuperAdminActivity::create([
            'super_admin_id' => $superAdmin->super_admin_id,
            'type' => 'create',
            'icon' => 'fas fa-user-plus',
            'action' => 'Account Created',
            'details' => "Super admin account created: {$superAdmin->super_admin_name}"
        ]);

        return response()->json(['success' => true, 'super_admin' => $superAdmin]);
    }

    public function show($id)
    {
        $user = SuperAdmin::findOrFail($id);

        $data = [
            'id' => $user->super_admin_id,
            'name' => $user->super_admin_name,
            'email' => $user->super_admin_email,
            'created' => $user->created_at ? $user->created_at->format('F d, Y') : '',
            'status' => $user->status ?? 'active',
            'role' => $user->role ?? 'super_admin',
            'lastLogin' => 'Never',
            'loginCount' => 0,
            'lastIp' => '',
            'permissions' => collect(json_decode($user->permissions, true))->map(function($perm) {
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

        if (!$request->has('permissions')) {
            $request->merge(['permissions' => []]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:super_admins,super_admin_email,' . $id . ',super_admin_id',
            'status' => 'required|string|in:active,inactive,suspended',
            'role' => 'required|string|in:super_admin,system_admin,security_admin',
            'permissions' => 'nullable|array'
        ]);

        $user->super_admin_name = $validated['name'];
        $user->super_admin_email = $validated['email'];
        $user->status = $validated['status'];
        $user->role = $validated['role'];
        $user->permissions = json_encode($validated['permissions'] ?? []);
        $user->save();

        // Log activity
        SuperAdminActivity::create([
            'super_admin_id' => $user->super_admin_id,
            'type' => 'update',
            'icon' => 'fas fa-edit',
            'action' => 'Account Updated',
            'details' => "Account updated: {$user->super_admin_name}"
        ]);

        return response()->json(['success' => true, 'super_admin' => $user]);
    }

    // Add this method for password change
    public function changePassword(Request $request, $id)
    {
        $user = SuperAdmin::findOrFail($id);

        $validated = $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed'
        ]);

        if (!\Hash::check($validated['current_password'], $user->password)) {
            return response()->json(['success' => false, 'message' => 'Current password incorrect.'], 422);
        }

        $user->password = \Hash::make($validated['new_password']);
        $user->save();

        // Log activity
        SuperAdminActivity::create([
            'super_admin_id' => $user->super_admin_id,
            'type' => 'security',
            'icon' => 'fas fa-key',
            'action' => 'Password Changed',
            'details' => "Password changed for: {$user->super_admin_name}"
        ]);

        return response()->json(['success' => true]);
    }

    // Add this method for deleting a super admin account
    public function destroy($id)
    {
        $user = SuperAdmin::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Account not found.'], 404);
        }

        $userName = $user->super_admin_name;
        $user->delete();

        // Log activity
        SuperAdminActivity::create([
            'super_admin_id' => $id,
            'type' => 'delete',
            'icon' => 'fas fa-trash',
            'action' => 'Account Deleted',
            'details' => "Super admin account deleted: {$userName}"
        ]);

        return response()->json(['success' => true]);
    }
}
