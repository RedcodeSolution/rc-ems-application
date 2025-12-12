<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
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

    public function notifications()
    {
        $perPage = 10;

        $query = Notification::where('target', 'super admin');

        // $notifications = $query->latest()->paginate($perPage);
        $notifications = $query->with('user')->latest()->paginate($perPage);

        $notificationStats = [
            'total'  => $query->count(),
            'unread' => (clone $query)->where('is_read', false)->count(),
            'read'   => (clone $query)->where('is_read', true)->count(),
        ];

        return view('super_admin.notifications', compact('notifications', 'notificationStats'));
    }

    public function markAsRead($id = null)
    {
        if ($id) {
            // Mark single notification
            $notification = Notification::findOrFail($id);
            $notification->is_read = true;
            $notification->save();

            return response()->json([
                'success' => true,
                'message' => 'Notification marked as read'
            ]);
        } else {
            // Bulk update (all)
            Notification::where('target', 'super admin')
                ->where('is_read', false)
                ->update(['is_read' => true]);

            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read'
            ]);
        }
    }


    public function deleteNotification($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }


    /**
     * Helper: get notification icon based on type
     */
    private function getNotificationIcon($type)
    {
        return match ($type) {
            'employee'     => 'fas fa-user',
            'leave'        => 'fas fa-calendar-alt',
            'security'     => 'fas fa-shield-alt',
            'project'      => 'fas fa-tasks',
            'system'       => 'fas fa-cogs',
            'announcement' => 'fas fa-bullhorn',
            'department'   => 'fas fa-building',
            'hr'           => 'fas fa-briefcase',
            default        => 'fas fa-bell',
        };
    }

    /**
     * Helper: get notification color class based on priority
     */
    private function getNotificationColor($priority)
    {
        return match ($priority) {
            'critical' => 'red',
            'high'     => 'orange',
            'medium'   => 'blue',
            'low'      => 'gray',
            default    => 'gray',
        };
    }
    public function latest()
    {
        $latestNotifications = Notification::where('target', 'super admin')
            ->where('is_read', false)
            ->latest()
            ->take(5)
            ->get();

        return response()->json($latestNotifications);
    }
}
