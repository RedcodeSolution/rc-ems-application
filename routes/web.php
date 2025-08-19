<?php

use Illuminate\Support\Facades\Route;


Route::get('/', fn() => view('guest'))->middleware('guest')->name('welcome');

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/profile', fn() => view('admin.profile.index'))->name('profile');
    Route::get('/profile/update', fn() => view('admin.profile_update'))->name('profile.update');

    Route::get('/employees', fn() => view('admin.employees.index'))->name('employees');
    Route::get('/management', fn() => view('admin.management.index'))->name('management');
    Route::get('/projects', fn() => view('admin.projects.index'))->name('projects');
    Route::get('/projects/create', fn() => view('admin.projects.create'))->name('projects.create');
    Route::get('/teams', fn() => view('admin.teams.index'))->name('teams');
    Route::get('/leaves', fn() => view('admin.leaves.index'))->name('leaves');
    Route::get('/reports', fn() => view('admin.reports.index'))->name('reports');
    Route::get('/announcements', fn() => view('admin.announcements.index'))->name('announcements');
    Route::get('/administration', fn() => view('admin.management.index'))->name('administration');
    Route::get('/admins', fn() => view('admin.admins.index'))->name('admins');
    Route::get('/documents', fn() => view('admin.documents.index'))->name('documents');
    Route::get('/notifications', fn() => view('admin.notifications.index'))->name('notifications');
    Route::get('/system', fn() => view('admin.system.index'))->name('system');
    Route::get('/other', fn() => view('admin.other.index'))->name('other');
    Route::get('/employeeRatings', fn() => view('admin.employeeRatings.index'))->name('employeeRatings.index');


    Route::post('/projects', fn() => back()->with('status', 'Project created!'))->name('projects.store');
    Route::post('/reports', fn() => back()->with('status', 'Report saved!'))->name('reports.store');
    Route::post('/leaves', fn() => back()->with('status', 'Leave saved!'))->name('leaves.store');
    Route::post('/teams', fn() => back()->with('status', 'Team created!'))->name('teams.store');
    Route::post('/announcements', fn() => back()->with('status', 'Announcement saved!'))->name('announcements.store');
    Route::post('/employeeRatings', fn() => back()->with('status', 'Rating submitted!'))->name('employeeRatings.store');

    // Dummy PUT/PATCH routes
    Route::match(['put', 'patch'], '/teams/{id}', fn($id) => back()->with('status', "Team {$id} updated!"))->name('teams.update');
    Route::match(['put', 'patch'], '/announcements/{id}', fn($id) => back()->with('status', "Announcement {$id} updated!"))->name('announcements.update');
});

/*
|--------------------------------------------------------------------------
| Employee Routes
|--------------------------------------------------------------------------
*/
Route::prefix('employees')->name('employee.')->group(function () {
    Route::get('/dashboard', fn() => view('employees.dashboard'))->name('dashboard');
    Route::get('/profile', fn() => view('employees.profile.index'))->name('profile');
    Route::get('/documents', fn() => view('employees.documents.index'))->name('documents');
    Route::get('/projects', fn() => view('employees.projects.index'))->name('projects');
    Route::get('/tasks', fn() => view('employees.tasks.index'))->name('tasks');
    Route::get('/attendance', fn() => view('employees.attendance.index'))->name('attendance');
    Route::get('/create', fn() => view('employees.create'))->name('create');
    Route::get('/leaves', fn() => view('employees.leaves.index'))->name('leaves.index');
    Route::get('/ratings', fn() => view('employees.ratings.index'))->name('ratings.index');
    Route::get('/ratings/{id}', fn($id) => view('employees.ratings.show', ['id' => $id]))->name('ratings.show');
    Route::get('/announcements', fn() => view('employees.announcements.index'))->name('announcements');


    Route::post('/', fn() => back()->with('status', 'Employee created!'))->name('store');
});


Route::get('/employees/create', fn() => view('employees.create'))->name('employees.create');

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('super_admin')->name('super_admin.')->group(function () {
    Route::get('/dashboard', fn() => view('super_admin.dashboard'))->name('dashboard');
    Route::get('/admins', fn() => view('super_admin.admins'))->name('admins');
    Route::get('/announcements', fn() => view('super_admin.announcements'))->name('announcements');
    Route::get('/notifications', fn() => view('super_admin.notifications'))->name('notifications');
    Route::get('/super_admin_accounts', fn() => view('super_admin.super_admin_accounts'))->name('super_admin_accounts');
    Route::get('/employee_ratings', fn() => view('super_admin.employee_ratings'))->name('employee_ratings');

    Route::get('/admin-leaves', fn() => view('super_admin.admin_leaves.index'))->name('admin_leaves.index');
    Route::get('/admin-leaves/{id}', fn($id) => view('super_admin.admin_leaves.show', ['id' => $id]))->name('admin_leaves.show');

    Route::get('/events', fn() => view('super_admin.events.index'))->name('events.index');
    Route::get('/events/create', fn() => view('super_admin.events.create'))->name('events.create');
    Route::get('/events/{id}', fn($id) => view('super_admin.events.show', ['id' => $id]))->name('events.show');
    Route::get('/events/{id}/edit', fn($id) => view('super_admin.events.edit', ['id' => $id]))->name('events.edit');
});

/*
|--------------------------------------------------------------------------
| Global Routes
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function (\Illuminate\Http\Request $request) {
    return match ($request->query('role')) {
        'admin' => redirect()->route('admin.dashboard'),
        'super_admin' => redirect()->route('super_admin.dashboard'),
        default => redirect()->route('employee.dashboard'),
    };
})->name('dashboard');


Route::post('/logout', fn() => redirect()->route('welcome'))->name('logout');
Route::post('/register', fn() => redirect()->route('welcome'))->name('register');

Route::get('/leaves', fn() => view('admin.leaves.index'))->name('leaves.index');
Route::get('/departments', fn() => view('admin.departments.index'))->name('departments.index');


Route::middleware('auth')->get('/api/notifications/me', fn() => response()->json([
    ['id' => 1, 'message' => 'Welcome!', 'read' => false],
    ['id' => 2, 'message' => 'Profile updated.', 'read' => true],
]));

require __DIR__.'/auth.php';
