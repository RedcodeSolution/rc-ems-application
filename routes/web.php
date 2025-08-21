<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\NotificationController;
use App\Models\Leave;
use App\Models\Meeting;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\MeetingController;

// Guest route - Welcome page
Route::get('/', function () {
    return view('guest');
})->middleware('guest')->name('welcome');

// Authentication required routes
Route::middleware('auth')->group(function () {
    // Dashboard routes with enhanced data
    Route::get('/admin/dashboard', function () {
        // Get real data from database
        $employees = Employee::with(['department', 'projects', 'leaves'])->get();
        $projects = Project::with(['employees', 'team'])->get();
        $leaves = Leave::with(['employee'])->get();

        // Get today's meetings (morning and evening)
        $todayMeetings = \App\Models\Meeting::getTodayMeetings();
        if ($todayMeetings->count() == 0) {
            Meeting::createDailyStandup();
            $todayMeetings = Meeting::getTodayMeetings();
        }


        $totalEmployees = $employees->count();
        $activeProjects = $projects->where('status', 'active')->count();
        $pendingLeaves = $leaves->where('status', 'pending')->count();
        $approvedLeaves = $leaves->where('status', 'approved')->count();
        $rejectedLeaves = $leaves->where('status', 'rejected')->count();

        // Get employee project assignments and leave counts
        $employeeData = $employees->map(function($employee) {
            return [
                'employee' => $employee,
                'project_count' => $employee->projects->count(),
                'leave_count' => $employee->leaves->count(),
                'pending_leaves' => $employee->leaves->where('status', 'pending')->count(),
                'approved_leaves' => $employee->leaves->where('status', 'approved')->count(),
                'rejected_leaves' => $employee->leaves->where('status', 'rejected')->count(),
                'projects' => $employee->projects->map(function($project) {
                    return [
                        'project' => $project,
                        'role' => $project->pivot->role_in_project,
                        'assigned_date' => $project->pivot->assigned_date
                    ];
                })
            ];
        });

        $dashboardData = [
            'totalEmployees' => $totalEmployees,
            'activeProjects' => $activeProjects,
            'pendingTasks' => 23,
            'revenue' => '$2.4M', // Placeholder
            'newJoinings' => 12, // Placeholder
            'pendingLeaves' => $pendingLeaves,
            'efficiency' => '94.2%', // Placeholder
            'employeeData' => $employeeData,
            'employees' => $employees, // Add employees variable
            'projects' => $projects,
            'leaves' => $leaves,
            'approvedLeaves' => $approvedLeaves,
            'rejectedLeaves' => $rejectedLeaves,
            'todayMeetings' => $todayMeetings
        ];
        return view('admin.dashboard', $dashboardData);
    })->name('admin.dashboard');

    Route::get('/employee/dashboard', function () {
        // Get today's meetings (morning and evening)
        $todayMeetings = \App\Models\Meeting::getTodayMeetings();
        if ($todayMeetings->count() == 0) {
            \App\Models\Meeting::createDailyStandup();
            $todayMeetings = \App\Models\Meeting::getTodayMeetings();
        }

        return view('employees.dashboard', compact('todayMeetings'));
    })->name('employee.dashboard');

    // Employee profile route (frontend only)
    Route::get('/employees/profile', function () {
        return view('employees.profile.index');
    })->name('employee.profile');

    // Employee documents route (frontend only)
    Route::get('/employees/documents', function () {
        return view('employees.documents.index');
    })->name('employee.documents');

    // Employee projects route (frontend only)
    Route::get('/employees/projects', function () {
        return view('employees.projects.index');
    })->name('employee.projects');

    // Employee tasks route (frontend only)
    Route::get('/employees/tasks', function () {
        return view('employees.tasks.index');
    })->name('employee.tasks');

    // Employee attendance route (frontend only)
    Route::get('/employees/attendance', function () {
        return view('employees.attendance.index');
    })->name('employee.attendance');

    // Employee leave management routes
    Route::resource('employees/leaves', \App\Http\Controllers\Employee\LeaveController::class)
        ->names([
            'index' => 'employee.leaves.index',
            'show' => 'employee.leaves.show',
            'create' => 'employee.leaves.create',
            'store' => 'employee.leaves.store',
            'edit' => 'employee.leaves.edit',
            'update' => 'employee.leaves.update',
            'destroy' => 'employee.leaves.destroy',
        ])
        ->parameters(['leaves' => 'leave']);

    // Additional leave routes
    Route::patch('/employees/leaves/{leave}/cancel', [App\Http\Controllers\Employee\LeaveController::class, 'cancel'])
        ->name('employee.leaves.cancel');
    Route::get('/employees/leaves/{leave}/download', [App\Http\Controllers\Employee\LeaveController::class, 'downloadDocument'])
        ->name('employee.leaves.download');

    // Employee ratings routes
    Route::resource('employees/ratings', \App\Http\Controllers\Employee\EmployeeRatingController::class)
        ->names([
            'index' => 'employee.ratings.index',
            'show' => 'employee.ratings.show',
        ])
        ->parameters(['ratings' => 'rating']);

    // Employee rating submission and data routes
    Route::post('/employees/ratings', [\App\Http\Controllers\Employee\EmployeeRatingController::class, 'store'])->name('employee.ratings.store');
    Route::get('/employees/ratings/employee/{employeeId}', [\App\Http\Controllers\Employee\EmployeeRatingController::class, 'getEmployeeRatings'])->name('employee.ratings.employee');

    Route::get('/super_admin/dashboard', [SuperAdminController::class, 'dashboard'])->name('super_admin.dashboard');
    Route::get('/super_admin/admins', [SuperAdminController::class, 'admins'])->name('super_admin.admins');
    Route::get('/super_admin/admin_roles', [SuperAdminController::class, 'adminRoles'])->name('super_admin.admin_roles');
    Route::get('/super_admin/settings', [SuperAdminController::class, 'settings'])->name('super_admin.settings');
    Route::get('/super_admin/security_settings', [SuperAdminController::class, 'securitySettings'])->name('super_admin.security_settings');
    Route::get('/super_admin/database_settings', [SuperAdminController::class, 'databaseSettings'])->name('super_admin.database_settings');
    Route::get('/super_admin/announcements', [SuperAdminController::class, 'announcements'])->name('super_admin.announcements');
    Route::get('/super_admin/system_alerts', [SuperAdminController::class, 'systemAlerts'])->name('super_admin.system_alerts');
    Route::get('/super_admin/system_alerts/create', [SuperAdminController::class, 'createSystemAlert'])->name('super_admin.system_alerts.create');
    Route::post('/super_admin/system_alerts', [SuperAdminController::class, 'storeSystemAlert'])->name('super_admin.system_alerts.store');
    Route::get('/super_admin/notifications', [SuperAdminController::class, 'notifications'])->name('super_admin.notifications');
    Route::get('/super_admin/super_admin_accounts', [SuperAdminController::class, 'superAdminAccounts'])->name('super_admin.super_admin_accounts');
    Route::get('/super_admin/employee_ratings', [SuperAdminController::class, 'employeeRatings'])->name('super_admin.employee_ratings');
    Route::post('/super_admin/employee_ratings', [SuperAdminController::class, 'storeEmployeeRating'])->name('super_admin.employee_ratings.store');

    // Super Admin Admin Leave Management Routes
    Route::prefix('super_admin/admin-leaves')->name('super_admin.admin_leaves.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'show'])->name('show');
        Route::post('/{id}/approve', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'reject'])->name('reject');
        Route::post('/bulk-approve', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'bulkApprove'])->name('bulk_approve');
        Route::post('/bulk-reject', [\App\Http\Controllers\SuperAdmin\AdminLeaveController::class, 'bulkReject'])->name('bulk_reject');
    });

    // Super Admin Events Management Routes
    Route::prefix('super_admin/events')->name('super_admin.events.')->group(function () {
        Route::get('/', [\App\Http\Controllers\SuperAdmin\EventController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\SuperAdmin\EventController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\SuperAdmin\EventController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\SuperAdmin\EventController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [\App\Http\Controllers\SuperAdmin\EventController::class, 'edit'])->name('edit');
        Route::put('/{id}', [\App\Http\Controllers\SuperAdmin\EventController::class, 'update'])->name('update');
        Route::delete('/{id}', [\App\Http\Controllers\SuperAdmin\EventController::class, 'destroy'])->name('destroy');
    });

    // Redirect authenticated users to their respective dashboards
    Route::get('/dashboard', function () {
        $user = auth()->check() ? auth()->user() : null;
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user && $user->role === 'super_admin') {
            return redirect()->route('super_admin.dashboard');
        }
        return redirect()->route('employee.dashboard');
    })->name('dashboard');

    // Profile management
    Route::get('/admin/profile', [AdminController::class, 'profile'])->name('admin.profile');
    Route::post('/admin/profile/update', [AdminController::class, 'updateProfile'])->name('admin.profile.update');

    // Admin section routes (UI pages with enhanced data)
    Route::prefix('admin')->name('admin.')->group(function () {
        // Employee Management
        Route::get('/employees', function () {
            $employees = \App\Models\Employee::with(['department', 'admin', 'teams'])->get();
            $departments = \App\Models\Department::all();
            $admins = \App\Models\Admin::all();
            $teams = \App\Models\Team::all();
            return view('admin.employees.index', compact('employees', 'departments', 'admins', 'teams'));
        })->name('employees');

        // Management Overview
        Route::get('/management', function () {
            return view('admin.management.index');
        })->name('management');

        // Project Management
        Route::get('/projects', function () {
            return view('admin.projects.index');
        })->name('projects');

        // Team Management
        Route::get('/teams', function () {
            return view('admin.teams.index');
        })->name('teams');

        // Leave Management
        Route::get('/leaves', function () {
            return view('admin.leaves.index');
        })->name('leaves');

        // Reports & Analytics with enhanced data
        Route::get('/reports', function () {
            $reportData = [
                'totalRevenue' => '$2.4M',
                'activeProjects' => 47,
                'employeeCount' => 156,
                'efficiency' => '94.2%'
            ];
            return view('admin.reports.index', $reportData);
        })->name('reports');

        // Announcements
        Route::get('/announcements', function () {
            return view('admin.announcements.index');
        })->name('announcements');

        // Administration (alias for management)
        Route::get('/administration', function () {
            return view('admin.management.index');
        })->name('administration');

        // Admin Management
        Route::get('/admins', [AdminController::class, 'index'])->name('admins');

        // Document Management
        Route::get('/documents', function () {
            return view('admin.documents.index');
        })->name('documents');

        // Notification Center
        Route::get('/notifications', function () {
            return view('admin.notifications.index');
        })->name('notifications');

        // System Settings
        Route::get('/system', function () {
            return view('admin.system.index');
        })->name('system');

        // Other/Miscellaneous
        Route::get('/other', function () {
            return view('admin.other.index');
        })->name('other');

        // Employee Ratings Management
        Route::resource('employeeRatings', \App\Http\Controllers\Admin\EmployeeRatingsController::class);
    });

    // Authentication
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
});

// Resource routes for CRUD operations
Route::middleware('auth')->group(function () {
    // Employee management
    Route::resource('employees', EmployeeController::class);

    // Department management
    Route::resource('departments', DepartmentController::class);

    // Admin management
    Route::resource('admins', AdminController::class);
    Route::post('/admin/store', [AdminController::class, 'store'])->name('admin.store');

    // Super admin management
    Route::resource('super_admins', SuperAdminController::class);

    // Project management
    Route::resource('projects', ProjectController::class);

    // Team management
    Route::resource('teams', TeamController::class);
    Route::get('teams/{team}/assign-employees', [TeamController::class, 'assignEmployeesForm'])->name('teams.assignEmployeesForm');
    Route::post('teams/{team}/assign-employees', [TeamController::class, 'assignEmployees'])->name('teams.assignEmployees');

    // Leave management
    Route::resource('leaves', LeaveController::class);

    // Report management
    Route::resource('reports', ReportController::class);
    Route::get('/admin/reports/download/{id}', [ReportController::class, 'download'])->name('reports.download');

    // Announcement management
    Route::resource('announcements', AnnouncementController::class);

    // Notification management
    Route::resource('notifications', NotificationController::class);

    // Document management
    Route::resource('documents', DocumentController::class);

    // Meeting management
    Route::resource('meetings', MeetingController::class);
    Route::get('/meetings/dashboard', [MeetingController::class, 'dashboard'])->name('meetings.dashboard');
    Route::get('/meetings/generate-today', [MeetingController::class, 'generateTodayMeeting'])->name('meetings.generate-today');
    Route::get('/meetings/{meeting}/join', [MeetingController::class, 'joinMeeting'])->name('meetings.join');
    Route::get('/meetings/today', [MeetingController::class, 'getTodayMeeting'])->name('meetings.today');
    Route::patch('/meetings/{meeting}/status', [MeetingController::class, 'updateStatus'])->name('meetings.update-status');
});

// Employee-specific routes
Route::middleware('auth')->prefix('employee')->name('employee.')->group(function () {
    // Leave Management
    Route::get('/leaves', function () {
        return view('employees.leaves.index');
    })->name('leaves.index');

    // Announcements
    Route::get('/announcements', function () {
        // Sample data - would be fetched from database
        $announcements = collect([
            (object) [
                'announcement_id' => 'ann_001',
                'title' => 'System Maintenance - December 1st',
                'content' => 'Scheduled system maintenance will take place on Sunday, December 1st, from 2:00 AM to 4:00 AM.',
                'priority' => 'urgent',
                'category' => 'system',
                'author' => 'IT Department',
                'created_at' => now()->subDays(2),
                'expires_at' => now()->addDays(3),
                'target_audience' => 'All Employees',
                'is_read' => false,
                'views' => 127,
                'likes' => 23
            ],
            (object) [
                'announcement_id' => 'ann_002',
                'title' => 'New Employee Onboarding Process',
                'content' => 'We have updated our employee onboarding process to make it more streamlined and efficient.',
                'priority' => 'high',
                'category' => 'hr',
                'author' => 'HR Department',
                'created_at' => now()->subDays(5),
                'expires_at' => null,
                'target_audience' => 'All Employees',
                'is_read' => true,
                'views' => 89,
                'likes' => 34
            ]
        ]);

        return view('employees.announcements.index', [
            'announcements' => $announcements,
            'totalAnnouncements' => 8,
            'unreadAnnouncements' => 3,
            'urgentAnnouncements' => 2
        ]);
    })->name('announcements');
});

require __DIR__.'/auth.php';
