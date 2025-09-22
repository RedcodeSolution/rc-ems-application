<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Admin;
use App\Models\Employee;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    public function index()
    {

        $adminEmployees = Employee::whereHas('admin')->with(['admin', 'leaves' => function($query) {
            $query->orderBy('created_at', 'desc');
        }])->get();


        $adminLeaves = collect();
        foreach ($adminEmployees as $employee) {
            $adminLeaves = $adminLeaves->merge($employee->leaves);
        }


        $totalLeaves = $adminLeaves->count();
        $pendingLeaves = $adminLeaves->where('status', 'pending')->count();
        $approvedLeaves = $adminLeaves->where('status', 'approved')->count();
        $rejectedLeaves = $adminLeaves->where('status', 'rejected')->count();

        // Get recent admin leave requests (last 30 days)
        $recentLeaves = $adminLeaves->where('created_at', '>=', now()->subDays(30));

        // Group leaves by status for tabs
        $pendingLeavesList = $adminLeaves->where('status', 'pending');
        $approvedLeavesList = $adminLeaves->where('status', 'approved');
        $rejectedLeavesList = $adminLeaves->where('status', 'rejected');

        $data = [
            'adminLeaves' => $adminLeaves,
            'pendingLeaves' => $pendingLeavesList,
            'approvedLeaves' => $approvedLeavesList,
            'rejectedLeaves' => $rejectedLeavesList,
            'totalLeaves' => $totalLeaves,
            'pendingCount' => $pendingLeaves,
            'approvedCount' => $approvedLeaves,
            'rejectedCount' => $rejectedLeaves,
            'recentLeaves' => $recentLeaves,
            'adminEmployees' => $adminEmployees
        ];

        // Notify if there are pending leaves
        if ($pendingLeaves > 0) {
            Notification::create([
                'title' => 'Pending Leave Requests',
                'message' => "There are {$pendingLeaves} pending leave requests for admins.",
                'type' => 'leave',
                'priority' => 'critical',
                'read' => false,
                'from' => 'Leave System',
                'icon' => 'fas fa-calendar-times',
                'color' => 'red',
            ]);
        }

        return view('super_admin.admin_leaves.index', $data);
    }
}
