<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\SuperAdmin;
use App\Models\User;

class SuperAdminController extends Controller
{


    public function admins()
    {
        $admins = Admin::with('employee')->get();
        return view('super_admin.admins', compact('admins'));
    }

    /**
     * Display the All Notifications page.
     */
    public function notifications()
    {

        $notifications = collect();


        $notificationStats = [
            'total' => $notifications->count(),
            'unread' => $notifications->where('read', false)->count(),
            'critical' => $notifications->where('priority', 'critical')->count(),
            'high' => $notifications->where('priority', 'high')->count(),
            'medium' => $notifications->where('priority', 'medium')->count(),
            'low' => $notifications->where('priority', 'low')->count(),
        ];

        $notificationsByType = $notifications->groupBy('type');

        return view('super_admin.notifications', compact('notifications', 'notificationStats', 'notificationsByType'));
    }

    /**
     * Display the Super Admin Account Management page.
     */
    public function superAdminAccounts()
    {

        $superAdmins = SuperAdmin::all();

        $superAdminUsers = User::where('role', 'super_admin')->get();

        $totalSuperAdmins = $superAdmins->count();
        $activeSuperAdmins = $superAdminUsers->where('status', 'active')->count();
        $inactiveSuperAdmins = $superAdminUsers->where('status', 'inactive')->count();
        $recentLogins = $superAdminUsers->where('last_login_at', '>=', now()->subDays(7))->count();

        $recentActivities = [
            [
                'action' => 'Super Admin Login',
                'details' => 'Super Admin John Smith logged in from IP 192.168.1.100',
                'timestamp' => now()->subMinutes(15),
                'type' => 'login',
                'icon' => 'fas fa-sign-in-alt'
            ],
            [
                'action' => 'Account Created',
                'details' => 'New Super Admin account created for Sarah Wilson',
                'timestamp' => now()->subHours(2),
                'type' => 'create',
                'icon' => 'fas fa-user-plus'
            ],
            [
                'action' => 'Password Reset',
                'details' => 'Super Admin Mike Johnson reset their password',
                'timestamp' => now()->subHours(4),
                'type' => 'security',
                'icon' => 'fas fa-key'
            ],
            [
                'action' => 'Account Updated',
                'details' => 'Super Admin Lisa Brown updated their profile information',
                'timestamp' => now()->subHours(6),
                'type' => 'update',
                'icon' => 'fas fa-user-edit'
            ]
        ];

        $data = [
            'superAdmins' => $superAdmins,
            'superAdminUsers' => $superAdminUsers,
            'totalSuperAdmins' => $totalSuperAdmins,
            'activeSuperAdmins' => $activeSuperAdmins,
            'inactiveSuperAdmins' => $inactiveSuperAdmins,
            'recentLogins' => $recentLogins,
            'recentActivities' => $recentActivities
        ];

        return view('super_admin.super_admin_accounts', $data);
    }
    /**
     * Display the main Super Admin dashboard.
     */
    public function dashboard()
    {

        $todayMeetings = Meeting::getTodayMeetings();


        $dashboardStats = [
            'total_admins' => Admin::count(),
            'total_employees' => Employee::count(),
            'total_departments' => Department::count(),
            'active_projects' => Project::where('status', 'active')->count(),
            'pending_leaves' => Leave::where('status', 'pending')->count(),
            'recent_registrations' => Employee::where('created_at', '>=', now()->subDays(7))->count(),
            'system_alerts' => 3,
            'active_users' => Employee::where('employee_status', 'active')->count(),
        ];


        $recentActivities = [
            [
                'action' => 'New Employee Registration',
                'details' => 'John Doe joined Development Team',
                'timestamp' => now()->subMinutes(15),
                'type' => 'employee',
                'icon' => 'fas fa-user-plus'
            ],
            [
                'action' => 'System Alert Generated',
                'details' => 'Database backup completed successfully',
                'timestamp' => now()->subHours(3),
                'type' => 'system',
                'icon' => 'fas fa-exclamation-triangle'
            ],
            [
                'action' => 'Admin Role Updated',
                'details' => 'Manager permissions updated for HR Admin',
                'timestamp' => now()->subHours(5),
                'type' => 'admin',
                'icon' => 'fas fa-user-shield'
            ]
        ];

        // Get system health metrics
        $systemHealth = [
            'server_uptime' => '99.8%',
            'database_health' => 'Excellent',
            'storage_usage' => '67%',
            'active_sessions' => 234,
            'avg_response_time' => '1.2s',
            'error_rate' => '0.02%'
        ];

        $chartData = [
            'monthly_registrations' => [12, 18, 25, 20, 30, 28, 35, 42, 38, 45, 52, 48],
            'leave_requests' => [8, 12, 15, 10, 18, 22, 16, 25, 20, 28, 24, 30],
            'project_completion' => [2, 3, 1, 4, 2, 5, 3, 6, 4, 3, 5, 7]
        ];

        return view('super_admin.dashboard', compact('dashboardStats', 'recentActivities', 'systemHealth', 'chartData', 'todayMeetings'));
    }
}
