<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Admin;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLeaveController extends Controller
{
    public function index()
    {

        $adminLeaves = Leave::with(['employee', 'employee.department'])
            ->whereHas('employee', function ($query) {
                $query->where('role', 'admin');
            })
            ->get();

        $baseQuery = Leave::with(['employee', 'employee.department'])
            ->whereHas('employee', function ($query) {
                $query->whereIn('role', ['admin']);
            });

        $pendingLeaves  = (clone $baseQuery)->where('status', 'pending')->get();
        $approvedLeaves = (clone $baseQuery)->where('status', 'approved')->get();
        $approvedLeavesToday = (clone $baseQuery)->where('status', 'approved')
            ->whereDate('approved_date', Carbon::today())
            ->get()->count();
        $rejectedLeaves = (clone $baseQuery)->where('status', 'rejected')->get();

        // Counts
        $adminLeaveCount    = $adminLeaves->count();
        $pendingLeaveCount  = $pendingLeaves->count();
        $approvedLeaveCount = $approvedLeaves->count();
        $rejectedLeaveCount = $rejectedLeaves->count();

        return view('super_admin.admin_leaves.index', [
            'adminLeaves'        => $adminLeaves,
            'pendingLeaves'      => $pendingLeaves,
            'approvedLeaves'     => $approvedLeaves,
            'rejectedLeaves'     => $rejectedLeaves,
            'totalLeaves'    => $adminLeaveCount,
            'pendingCount'  => $pendingLeaveCount,
            'approvedCount' => $approvedLeaveCount,
            'rejectedCount' => $rejectedLeaveCount,
            'approvedLeavesToday' => $approvedLeavesToday
        ]);
    }

    public function show(String $leave_id)
    {
        $leave = Leave::find($leave_id);
        // return ['leave' => $leave];
        return view('super_admin.admin_leaves.show', compact('leave'));
    }

    public function approve(string $leave_id)
    {
        $user = Auth::user();
        $email = $user->email;
        $admin = Admin::where('email', $email)->first();
        $leave = Leave::findOrFail($leave_id);
        $leave->status = 'approved';
        $leave->approved_by   =  $admin->admin_id;
        $leave->approved_date = now();
        $leave->save();

        return response()->json(['success' => true]);
    }

    public function reject(Request $request, string $leave_id)
    {
        $user = Auth::user();
        $email = $user->email;
        $admin = Admin::where('email', $email)->first();
        $leave = Leave::findOrFail($leave_id);
        $leave->status = 'rejected';
        $leave->rejection_reason = $request->input('rejection_reason');
        $leave->rejected_by   =  $admin->admin_id;
        $leave->rejected_date = now();

        $leave->save();

        return response()->json(['success' => true]);
    }

    public function bulkApprove(Request $request)
    {
        $user = Auth::user();
        $email = $user->email;
        $admin = Admin::where('email', $email)->first();
        $leaveIds = $request->input('leave_ids', []);

        if (empty($leaveIds)) {
            return response()->json(['success' => false, 'error' => 'No leave requests selected.']);
        }

        Leave::whereIn('leave_id', $leaveIds)->update([
            'status'      => 'approved',
            'approved_by' => $admin->admin_id,
            'approved_date' => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => count($leaveIds) . ' leave request(s) approved successfully.'
        ]);
    }

    public function bulkReject(Request $request)
    {
        $user = Auth::user();
        $email = $user->email;
        $admin = Admin::where('email', $email)->first();
        $leaveIds = $request->input('leave_ids', []);
        $reason   = $request->input('rejection_reason');

        if (empty($leaveIds)) {
            return response()->json(['success' => false, 'error' => 'No leave requests selected.']);
        }

        if (empty($reason)) {
            return response()->json(['success' => false, 'error' => 'Rejection reason is required.']);
        }

        Leave::whereIn('leave_id', $leaveIds)->update([
            'status'           => 'rejected',
            'rejection_reason' => $reason,
            'rejected_by'      => $admin->admin_id,
            'rejected_date'    => now(),
        ]);

        return response()->json([
            'success' => true,
            'message' => count($leaveIds) . ' leave request(s) rejected successfully.'
        ]);
    }
}
