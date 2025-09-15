<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Admin;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    public function index()
    {

        $adminEmployees = Employee::whereHas('admin')->with(['admin', 'leaves' => function($query) {
            $query->orderBy('applied_date', 'desc');
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
        $recentLeaves = $adminLeaves->where('applied_date', '>=', now()->subDays(30));

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

        return view('super_admin.admin_leaves.index', $data);
    }
}
