<?php

use App\Http\Controllers\Admin\AdminAnnouncementsController;
use App\Http\Controllers\Admin\AdminNotificationController;
use App\Http\Controllers\Admin\AdminsLeaveController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\TeamController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

use App\Http\Controllers\DocumentController;
use App\Http\Controllers\Employee\EmployeeAttendanceController;
use App\Http\Controllers\Employee\EmployeeLeaveController;
use App\Http\Controllers\Employee\EmployeeProfileController;
use App\Http\Controllers\Employee\EmployeeTaskController;
use App\Http\Controllers\LeaveController;
use App\Http\Controllers\MeetingController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SuperAdmin\AdminController;
use App\Http\Controllers\SuperAdmin\SuperAdminController;

use App\Http\Controllers\SuperAdmin\AdminLeaveController;
use App\Http\Controllers\SuperAdmin\EmployeeRatingController;
use App\Http\Controllers\SuperAdmin\EventController;
use App\Http\Controllers\SuperAdmin\SuperAdminAccountsController;
// use App\Http\Controllers\SuperAdmin\SuperAdminController as SuperAdminSuperAdminController;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Team;
use Illuminate\Support\Facades\Route;

//use App\Http\Controllers\Auth\AuthenticatedSessionController;


Route::get('/', function () {
    return view('guest');
})->middleware('guest')->name('welcome');

Route::middleware('auth')->group(function () {
    // Dashboard routes with enhanced data
    Route::get('/admin/dashboard', function () {
        $employees = \App\Models\Employee::with(['department', 'projects', 'leaves'])->get();
        $projects = \App\Models\Project::with(['employees', 'team'])->get();
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
        $employeeData = $employees->map(function ($employee) {
            return [
                'employee' => $employee,
                'project_count' => $employee->projects->count(),
                'leave_count' => $employee->leaves->count(),
                'pending_leaves' => $employee->leaves->where('status', 'pending')->count(),
                'approved_leaves' => $employee->leaves->where('status', 'approved')->count(),
                'rejected_leaves' => $employee->leaves->where('status', 'rejected')->count(),
                'projects' => $employee->projects->map(function ($project) {
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
            'revenue' => '$2.4M',
            'newJoinings' => 12,
            'pendingLeaves' => $pendingLeaves,
            'efficiency' => '94.2%',
            'employeeData' => $employeeData,
            'employees' => $employees,
            'projects' => $projects,
            'leaves' => $leaves,
            'approvedLeaves' => $approvedLeaves,
            'rejectedLeaves' => $rejectedLeaves,
            'todayMeetings' => $todayMeetings
        ];
        return view('admin.dashboard', $dashboardData);
    })->name('admin.dashboard');

    Route::get('/employee/dashboard', function () {
        $todayMeetings = \App\Models\Meeting::getTodayMeetings();
        if ($todayMeetings->count() == 0) {
            \App\Models\Meeting::createDailyStandup();
            $todayMeetings = \App\Models\Meeting::getTodayMeetings();
        }

        return view('employees.dashboard', compact('todayMeetings'));
    })->name('employee.dashboard');

    //employee profile managment
    Route::get('/employees/profile', [EmployeeProfileController::class, 'show'])->name('employee.profile');
    Route::put('/employees/profile', [EmployeeProfileController::class, 'update'])->name('employee.profile.update');

    //employee profile skills managment
    Route::get('/employees/profile/skills', [EmployeeProfileController::class, 'getSkills'])->name('employee.skills.index');
    Route::post('/employees/profile/skills', [EmployeeProfileController::class, 'createSkills'])->name('employee.skills.create');
    Route::put('/employees/profile/skills/{skillId}', [EmployeeProfileController::class, 'updateSkill'])->name('employee.skills.update');
    Route::delete('/employees/profile/skills/{skillId}', [EmployeeProfileController::class, 'deleteSkill'])->name('employee.skills.delete');

    //employee leaves managment
    Route::get('/employees/leaves', [EmployeeLeaveController::class, 'index'])->name('employee.leaves.index');
    // Route::get('/employees/leaves', [EmployeeLeaveController::class, 'showRecent'])->name('employee.leaves.recent');
    Route::post('/employees/leaves', [EmployeeLeaveController::class, 'store'])->name('employee.leaves.create');
    Route::get('/employees/leaves/{leave}', [EmployeeLeaveController::class, 'show'])->name('employee.leaves.show');
    Route::put('/employees/leaves/{leave}', [EmployeeLeaveController::class, 'update'])->name('employee.leaves.update');
    Route::delete('/employees/leaves/{leave}', [EmployeeLeaveController::class, 'destroy'])->name('employee.leaves.destroy');

    Route::get('/employees/documents', function () {
        return view('employees.documents.index');
    })->name('employee.documents');

    // Employee projects route (frontend only)
    Route::get('/employees/projects', function () {
        return view('employees.projects.index');
    })->name('employee.projects');

    // Employee tasks route (frontend only)
    Route::put('/employees/tasks/{id}', [EmployeeTaskController::class, 'update'])->name('employee.update');

    Route::get('/employees/tasks/{id}', [EmployeeTaskController::class, 'show'])->name('employee.tasks');
    Route::get('/employees/tasks', [EmployeeTaskController::class, 'index'])->name('employee.tasks');

    Route::post('/employees/tasks', [EmployeeTaskController::class, 'store'])->name('tasks.store');
    Route::patch('/employees/tasks/{id}/status', [EmployeeTaskController::class, 'updateStatus'])
        ->name('employee.tasks.updateStatus');
    Route::post('/employees/tasks/{id}/comments', [EmployeeTaskController::class, 'addComment'])
        ->name('employee.tasks.addComment');

    // Employee attendance 
    Route::middleware(['auth'])->prefix('employees')->group(function () {

        Route::get('/attendance', [EmployeeAttendanceController::class, 'show'])
            ->name('employee.attendance');

        // Clock in/out + breaks
        Route::post('/attendance/clock-in', [EmployeeAttendanceController::class, 'clockIn'])
            ->name('employee.attendance.clockin');

        Route::post('/attendance/start-break', [EmployeeAttendanceController::class, 'startBreak'])
            ->name('employee.attendance.startbreak');

        Route::post('/attendance/end-break', [EmployeeAttendanceController::class, 'endBreak'])
            ->name('employee.attendance.endbreak');

        Route::post('/attendance/clock-out', [EmployeeAttendanceController::class, 'clockOut'])
            ->name('employee.attendance.clockout');

        Route::get('/attendance/break-status', [EmployeeAttendanceController::class, 'getBreakStatus'])
            ->name('employee.attendance.getBreakStatus');

        Route::put('/attendance/emergency/start', [EmployeeAttendanceController::class, 'startEmergency'])->name('employee.attendance.emergency.start');
        Route::put('/attendance/emergency/end', [EmployeeAttendanceController::class, 'endEmergency'])->name('employee.attendance.emergency.end');
        Route::get('/attendance/emergency/status', [EmployeeAttendanceController::class, 'getEmergencyStatus']);
    });

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
    Route::get('/super_admin/system_stats', [SuperAdminController::class, 'systemStats'])->name('super_admin.system_stats');
    Route::get('/super_admin/admins', [SuperAdminController::class, 'admins'])->name('super_admin.admins');
    Route::get('/super_admin/notifications', [SuperAdminController::class, 'notifications'])->name('super_admin.notifications');
    Route::post('/notifications/{id}/read', [SuperAdminController::class, 'markAsRead'])
        ->name('super_admin.notifications.markAsRead');

    Route::post('/notifications/read-all', [SuperAdminController::class, 'markAsRead'])
        ->name('notifications.readAll');

    Route::delete('/notifications/{id}', [SuperAdminController::class, 'deleteNotification'])
        ->name('notifications.delete');


    Route::get('/super_admin/employee_ratings', [EmployeeRatingController::class, 'employeeRatings'])->name('super_admin.employee_ratings');
    Route::post('/super_admin/employee_ratings', [EmployeeRatingController::class, 'storeEmployeeRating'])->name('super_admin.employee_ratings.store');
    Route::get('/super_admin/employee_ratings/employee/{employeeId}', [EmployeeRatingController::class, 'getEmployeeRatings'])->name('super_admin.employee_ratings.employee');

    // Super Admin Admin Leave Management Routes
    Route::prefix('super_admin/admin-leaves')->name('super_admin.admin_leaves.')->group(function () {
        Route::get('/', [AdminLeaveController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminLeaveController::class, 'show'])->name('show');
        Route::put('/{id}/approve', [AdminLeaveController::class, 'approve'])->name('approve');
        Route::put('/{id}/reject', [AdminLeaveController::class, 'reject'])->name('reject');
        Route::put('/bulk-approve', [AdminLeaveController::class, 'bulkApprove'])->name('bulk_approve');
        Route::put('/bulk-reject', [AdminLeaveController::class, 'bulkReject'])->name('bulk_reject');
    });

    // Super Admin Events Management Routes
    Route::prefix('super_admin/events')->name('super_admin.events.')->group(function () {
        Route::get('/', [EventController::class, 'index'])->name('index');
        Route::get('/create', [EventController::class, 'create'])->name('create');
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
            $employees = Employee::with(['department', 'admin'])->get();
            $departments = Department::all();
            $admins = Admin::all();
            $teams = Team::all(); // <-- Add this line
            return view('admin.employees.index', compact('employees', 'departments', 'admins', 'teams'));
        })->name('employees');


        Route::match(['put', 'patch'], '/employees/{employee}', [EmployeeController::class, 'update'])->name('employees.update');



        Route::get('/projects', function () {
            return view('admin.projects.index');
        })->name('projects');

        // Leave Management
        Route::put('/leaves/{leave}/status', [AdminsLeaveController::class, 'updateLeaveStatus'])->name('leaves.updateLeaveStatus');
        Route::put('/leaves/{leave}', [AdminsLeaveController::class, 'update'])->name('leaves.update');
        Route::get('/leaves', [AdminsLeaveController::class, 'index'])->name('leaves.index');
        Route::post('/leaves', [AdminsLeaveController::class, 'store'])->name('leaves.create');
        Route::get('/leaves/{leave}', [AdminsLeaveController::class, 'show'])->name('leaves.show');
        Route::delete('/leaves/{leave}', [AdminsLeaveController::class, 'destroy'])->name('leaves.destroy');

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
        Route::post('/announcements', [AdminAnnouncementsController::class, 'store'])->name('announcements.store');
        Route::put('/announcements/{id}', [AdminAnnouncementsController::class, 'update'])->name('announcements.update');
        Route::delete('/announcements/{id}', [AdminAnnouncementsController::class, 'destroy'])->name('announcements.destroy');
        Route::get('/announcements', [AdminAnnouncementsController::class, 'index'])->name('announcements');

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

        Route::get('/notifications', [AdminNotificationController::class, 'index'])->name('notifications');
        Route::get('/notifications/{notifi_id}', [AdminNotificationController::class, 'show'])
            ->name('admin.notifications.show');

        Route::post('/notifications/{id}/mark-as-read', [AdminNotificationController::class, 'markAsRead'])
            ->name('admin.notifications.markAsRead');

        Route::post('/notifications/mark-all-as-read', [AdminNotificationController::class, 'markAllAsRead'])
            ->name('admin.notifications.markAllAsRead');

        Route::delete('/notifications/{id}', [AdminNotificationController::class, 'destroy'])
            ->name('notifications.destroy');

        Route::get('/admin/notifications/latest', [AdminNotificationController::class, 'latest'])
            ->name('notifications.latest');

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

    Route::resource('super_admins', EmployeeRatingController::class);


    Route::resource('projects', ProjectController::class);

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
    // Route::get('/leaves', function () {
    //     return view('employees.leaves.index');
    // })->name('leaves.index');

    // Announcements
    Route::get('/announcements', function () {

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

//department Management
Route::get('/admin/department', [DepartmentController::class, 'index'])->name('admin.departments.index');
Route::get('/admin/create', [ProjectController::class, 'create'])->name('admin.departments.create');
Route::post('/admin/department', [DepartmentController::class, 'store'])->name('admin.departments.store');
Route::get('/departments/{departmentId}/edit', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
Route::put('/departments/{departmentId}', [DepartmentController::class, 'update'])->name('admin.departments.update');
Route::get('/departments/{departmentId}/show', [DepartmentController::class, 'show'])->name('admin.departments.show');
Route::delete('/departments/{departmentId}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');


//project Management
Route::get('/admin/project', [ProjectController::class, 'index'])->name('admin.projects.index');
Route::get('/projects/create', [ProjectController::class, 'create'])->name('admin.projects.create');
Route::post('/admin/projects', [ProjectController::class, 'store'])->name('admin.projects.store');
Route::get('/projects/{projectId}/edit', [ProjectController::class, 'edit'])->name('admin.projects.edit');
Route::put('/projects/{projectId}', [ProjectController::class, 'update'])->name('admin.projects.update');
Route::get('/projects/{projectId}/show', [ProjectController::class, 'show'])->name('admin.projects.show');
Route::delete('/projects/{projectId}', [ProjectController::class, 'destroy'])->name('admin.projects.destroy');

//team management
Route::get('/admin/teams', [TeamController::class, 'index'])->name('admin.teams');
Route::post('/admin/teams', [TeamController::class, 'store'])->name('teams.store');
Route::get('/admin/teams/{team}/edit', [TeamController::class, 'edit'])->name('teams.edit');
Route::put('/admin/teams/{team}', [TeamController::class, 'update'])->name('teams.update');
Route::get('/admin/teams/{team}/show', [TeamController::class, 'show'])->name('teams.show');
Route::delete('/admin/teams/{team}', [TeamController::class, 'destroy'])->name('teams.destroy');
Route::get('/admin/teams/{team}/manage-members', [TeamController::class, 'assignEmployeesForm'])->name('teams.assignEmployeesForm');
Route::post('/admin/teams/{team}/manage-members', [TeamController::class, 'assignEmployees'])->name('teams.assignEmployees');

// admin profile management
Route::prefix('super-admin')->name('super_admin.')->group(function () {
    Route::get('/admins', [AdminController::class, 'index'])->name('admins');
    Route::post('/admins/store', [AdminController::class, 'store'])->name('store');
    Route::get('/admins/{adminId}/edit', [AdminController::class, 'edit'])->name('edit');
    Route::put('/admins/{adminId}', [AdminController::class, 'update'])->name('update');
    Route::get('/{adminId}/show', [AdminController::class, 'show'])->name('show');
    Route::delete('/admins/{adminId}', [AdminController::class, 'destroy'])->name('destroy');
});


Route::get('/admin/employeeRatings/employee/{employeeId}', [App\Http\Controllers\Admin\EmployeeRatingsController::class, 'employeeRatingsJson']);
Route::get('/super_admin/super_admin_accounts', [SuperAdminAccountsController::class, 'index'])->name('super_admin.super_admin_accounts');
Route::post('/super_admin/super_admin_accounts', [SuperAdminAccountsController::class, 'store'])->name('super_admin_accounts.store');
Route::get('/super_admin/super_admin_accounts/{id}', [SuperAdminAccountsController::class, 'show'])->name('super_admin_accounts.show');
Route::put('/super_admin/super_admin_accounts/{id}', [SuperAdminAccountsController::class, 'update'])->name('super_admin_accounts.update');
Route::delete('/super_admin/super_admin_accounts/{id}', [SuperAdminAccountsController::class, 'destroy'])->name('super_admin_accounts.destroy');


require __DIR__ . '/auth.php';
