<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Models\Admin;
use App\Models\Department;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class AdminController
{
    public function index()
    {

        $departments = Department::all();
        $admins = Admin::with('department')->get();
        return view('super_admin.admins', compact('admins', 'departments',));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'admin_name'    => 'required|string|max:255',
            'role'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,department_id',
            'email'         => 'required|email|unique:admins,email',
            'contact_no'    => 'required|string|max:20',
            'status'        => 'required|string',
        ]);

        Admin::create($validated);


        Notification::create([
            'title' => 'Admin Account Created',
            'message' => 'A new admin account has been created: ' . $validated['admin_name'],
            'type' => 'admin',
            'priority' => 'high',
            'read' => false,
            'from' => auth()->user()->name ?? 'System',
            'icon' => 'fas fa-user-plus',
            'color' => 'blue',
        ]);

        if (auth()->check() && auth()->user()->role === 'admin') {
            \App\Models\AdminActivity::create([
                'user_id' => auth()->id(),
                'type'    => 'admin',
                'icon'    => 'user-plus',
                'action'  => 'Created Admin',
                'details' => 'Created new admin: ' . $validated['admin_name'],
            ]);
        }

        return redirect()->route('super_admin.admins')
            ->with('success', 'Administrator added successfully!');
    }

    public function edit($admin_id)
    {
        $admin = Admin::findOrFail($admin_id);
        $departments = Department::all();

        return response()->json([
            'success' => true,
            'admin' => $admin,
            'departments' => $departments
        ]);
    }


    public function update(Request $request, $admin_id)
    {
        $admin = Admin::findOrFail($admin_id);

        $validated = $request->validate([
            'admin_name'    => 'required|string|max:255',
            'role'          => 'required|string|max:255',
            'department_id' => 'nullable|exists:departments,department_id',
            'email'         => 'required|email|unique:admins,email,' . $admin->admin_id . ',admin_id',
            'contact_no'    => 'required|string|max:20',
            'status'        => 'required|string',
        ]);

        $admin->update($validated);
        $notify = new NotificationService();
        $notify->notify(
            title: 'Administrator Updated',
            message: "Administrator {$admin->admin_name}'s profile has been updated.",
            type: 'admin',
            userId: null,
            target: 'super admin',
            referenceId: $admin->admin_id
        );

        if (auth()->check() && auth()->user()->role === 'admin') {
            \App\Models\AdminActivity::create([
                'user_id' => auth()->id(),
                'type'    => 'admin',
                'icon'    => 'user-edit',
                'action'  => 'Updated Admin',
                'details' => 'Updated admin profile: ' . $admin->admin_name,
            ]);
        }

        return redirect()->route('super_admin.admins')->with('success', 'Admin updated successfully!');
    }

    public function show($id)
    {
        $admin = Admin::with('department')->find($id);

        if (!$admin) {
            return response()->json([
                'success' => false,
                'message' => 'Admin not found!'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'admin' => $admin
        ]);
    }

    public function destroy($id)
    {
        $admin = Admin::findOrFail($id);
        $adminName = $admin->admin_name;
        $admin->delete();

        $notify = new NotificationService();
        $notify->notify(
            title: 'Administrator Removed',
            message: "Administrator {$adminName} was deleted from the system.",
            type: 'admin',
            userId: null, // <-- Always use null unless you have a valid user ID
            target: 'super admin',
            referenceId: $id
        );

        return redirect()->route('super_admin.admins')->with('success', 'Admin deleted successfully!');
    }
}
