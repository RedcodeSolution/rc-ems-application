<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeRating;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\SuperAdmin;

class SuperAdminController extends Controller
{
    public function index()
    {
        $superAdmins = SuperAdmin::all();
        return view('super_admin.index', compact('superAdmins'));
    }


    public function show(SuperAdmin $superAdmin)
    {
        return view('super_admin.show', compact('superAdmin'));
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

        // Get quick stats for charts (mock data)
        $chartData = [
            'monthly_registrations' => [12, 18, 25, 20, 30, 28, 35, 42, 38, 45, 52, 48],
            'leave_requests' => [8, 12, 15, 10, 18, 22, 16, 25, 20, 28, 24, 30],
            'project_completion' => [2, 3, 1, 4, 2, 5, 3, 6, 4, 3, 5, 7]
        ];

        return view('super_admin.dashboard', compact('dashboardStats', 'recentActivities', 'systemHealth', 'chartData', 'todayMeetings'));
    }
    public function admins()
    {
        $admins = Admin::with('employee')->get();
        return view('super_admin.admins', compact('admins'));
    }

    /**
     * Display the Admin Roles management page.
     */
    public function adminRoles()
    {
        $admins = Admin::with('employee')->get();

        $availableRoles = [
            'super_admin' => 'Super Administrator',
            'admin' => 'Administrator',
            'manager' => 'Manager',
            'supervisor' => 'Supervisor'
        ];

        $availablePermissions = [
            'create_admin' => 'Create Admin',
            'edit_admin' => 'Edit Admin',
            'delete_admin' => 'Delete Admin',
            'view_reports' => 'View Reports',
            'manage_employees' => 'Manage Employees',
            'manage_departments' => 'Manage Departments',
            'system_settings' => 'System Settings'
        ];

        return view('super_admin.admin_roles', compact('admins', 'availableRoles', 'availablePermissions'));
    }


    /**
     * Show the form for creating a new system alert.
     */
    public function createSystemAlert()
    {
        return view('super_admin.create_system_alert');
    }

    /**
     * Store a newly created system alert.
     */
    public function storeSystemAlert(Request $request)
    {

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
     * Display the Employee Ratings page for Super Admin.
     */
    public function employeeRatings()
    {
        // Get all employee ratings with employee and rater information
        $ratings = EmployeeRating::with(['employee', 'rater'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Calculate statistics
        $totalRatings = $ratings->count();
        $averageRating = $totalRatings > 0 ? $ratings->avg('rating') : 0;
        $fiveStarRatings = $ratings->where('rating', 5)->count();
        $fourStarRatings = $ratings->where('rating', 4)->count();
        $threeStarRatings = $ratings->where('rating', 3)->count();
        $twoStarRatings = $ratings->where('rating', 2)->count();
        $oneStarRatings = $ratings->where('rating', 1)->count();

        // Get recent ratings (last 30 days)
        $recentRatings = $ratings->where('created_at', '>=', now()->subDays(30));

        // Calculate rating distribution percentages
        $ratingDistribution = [
            '5_star' => $totalRatings > 0 ? round(($fiveStarRatings / $totalRatings) * 100, 1) : 0,
            '4_star' => $totalRatings > 0 ? round(($fourStarRatings / $totalRatings) * 100, 1) : 0,
            '3_star' => $totalRatings > 0 ? round(($threeStarRatings / $totalRatings) * 100, 1) : 0,
            '2_star' => $totalRatings > 0 ? round(($twoStarRatings / $totalRatings) * 100, 1) : 0,
            '1_star' => $totalRatings > 0 ? round(($oneStarRatings / $totalRatings) * 100, 1) : 0,
        ];

        $topRatedEmployees = $ratings->groupBy('employee_id')
            ->map(function ($employeeRatings) {
                return [
                    'employee' => $employeeRatings->first()->employee,
                    'average_rating' => $employeeRatings->avg('rating'),
                    'total_ratings' => $employeeRatings->count(),
                    'recent_ratings' => $employeeRatings->where('created_at', '>=', now()->subDays(30))->count()
                ];
            })
            ->sortByDesc('average_rating')
            ->take(5);

        $data = [
            'ratings' => $ratings,
            'totalRatings' => $totalRatings,
            'averageRating' => $averageRating,
            'fiveStarRatings' => $fiveStarRatings,
            'fourStarRatings' => $fourStarRatings,
            'threeStarRatings' => $threeStarRatings,
            'twoStarRatings' => $twoStarRatings,
            'oneStarRatings' => $oneStarRatings,
            'recentRatings' => $recentRatings,
            'ratingDistribution' => $ratingDistribution,
            'topRatedEmployees' => $topRatedEmployees
        ];

        return view('super_admin.employee_ratings', $data);
    }

    public function storeEmployeeRating(Request $request)
    {
    }
}
