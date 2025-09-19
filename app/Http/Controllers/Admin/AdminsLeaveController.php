<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminsLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Admin leaves data not found for this user.'], 404);
        }

        // Admin's own leaves (if needed)
        $allLeaves = $user->employee->leaves;
        $recentLeaves = $user->employee->leaves()->latest()->take(3)->get();

        // Stats calculation for admin
        $annualUsed   = $allLeaves->where('leave_type', 'annual')->where('status', 'approved')->sum('duration');
        $sickUsed     = $allLeaves->where('leave_type', 'sick')->where('status', 'approved')->sum('duration');
        $personalUsed = $allLeaves->where('leave_type', 'personal')->where('status', 'approved')->sum('duration');
        $pendingCount = $allLeaves->where('status', 'pending')->count();

        // Totals (could come from config/DB)
        $annualTotal   = 21;
        $sickTotal     = 10;
        $personalTotal = 5;

        // Get all employee leaves (excluding admins/superadmins)
        $employeeLeaves = Leave::with('employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->get();

        // Current month/year and today
        $currentMonth = now()->month;
        $currentYear  = now()->year;
        $today        = now()->toDateString();

        // Count of all employee requests this month
        $employeeMonthlyCount = Leave::whereHas('employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        // Count of pending employee requests this month
        $employeePendingMonthlyCount = Leave::whereHas('employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'pending')
            ->count();

        // Count of rejected employee requests this month
        $employeeRejectedMonthlyCount = Leave::whereHas('employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'rejected')
            ->count();

        // Count of approved employee requests today
        $employeeApprovedTodayCount = Leave::whereHas('employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereDate('created_at', $today)
            ->where('status', 'approved')
            ->count();

        // Get leaves by status
        $pendingLeaves = Leave::with('employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'pending')
            ->get();

        $approvedLeaves = Leave::with('employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'approved')
            ->get();

        $rejectedLeaves = Leave::with('employee')
            ->whereHas('employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'rejected')
            ->get();

        return view('admin.leaves.index', [
            'leaves'                       => $allLeaves,
            'recentLeaves'                 => $recentLeaves,
            'annualUsed'                   => $annualUsed,
            'sickUsed'                     => $sickUsed,
            'personalUsed'                 => $personalUsed,
            'annualTotal'                  => $annualTotal,
            'sickTotal'                    => $sickTotal,
            'personalTotal'                => $personalTotal,
            'pendingCount'                 => $pendingCount,
            'employeeLeaves'               => $employeeLeaves,
            'pendingLeaves'                => $pendingLeaves,
            'approvedLeaves'               => $approvedLeaves,
            'rejectedLeaves'               => $rejectedLeaves,
            'employeeMonthlyCount'         => $employeeMonthlyCount,
            'employeePendingMonthlyCount'  => $employeePendingMonthlyCount,
            'employeeRejectedMonthlyCount' => $employeeRejectedMonthlyCount,
            'employeeApprovedTodayCount'   => $employeeApprovedTodayCount,
        ]);
    }



    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'leave_type' => 'required|string|max:255',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'duration' => 'required|integer|min:1',
            'reason' => 'required|string',
            'contact_number' => 'nullable|string|max:20',
            'supporting_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['employee_id'] = $user->employee->employee_id;
        Leave::create($validated);
        // return ['leave' => $validated];
        return redirect()->route('admin.leaves.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $user = Auth::user();
        if ($leave->employee_id !== $user->employee->employee_id) {
            return response()->json(['error' => 'Leave not found for this user.'], 404);
        }
        $leave->load('employee');
        // return view('admin.leaves.index', ['leave' => $leave]);
        return ['leave' => $leave];
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Leave $leave)
    {
        if ($leave->status === 'approved') {
            return response()->json(['error' => 'Cannot update an approved leave request.'], 403);
        }
        $validated = $request->validate([
            'leave_type' => 'required|string|in:annual,sick,personal,maternity,paternity,emergency',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'contact_info' => 'nullable|string',
            'supporting_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);


        $leave->update($validated);
        return redirect()->route('admin.leaves.index');
    }


    public function updateLeaveStatus(Request $request, $leaveId)
    {
        $user = Auth::user();

        if (!in_array($user->role, ['admin', 'superadmin'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status'           => 'required|in:approved,rejected',
            'comments'         => 'nullable|string',
            'rejection_reason' => 'nullable|string|required_if:status,rejected',
        ]);

        $leave = Leave::findOrFail($leaveId);

        if ($validated['status'] === 'approved') {
            $leave->status        = 'approved';
            $leave->approved_by   = $user->id;
            $leave->approved_date = now();
            $leave->comments      = $validated['comments'] ?? null;

            $leave->rejected_by     = null;
            $leave->rejected_date   = null;
            $leave->rejection_reason = null;
        } elseif ($validated['status'] === 'rejected') {
            $leave->status          = 'rejected';
            $leave->rejected_by     = $user->id;
            $leave->rejected_date   = now();
            $leave->rejection_reason = $validated['rejection_reason'] ?? null;
            $leave->comments        = $validated['comments'] ?? null;


            $leave->approved_by   = null;
            $leave->approved_date = null;
        }

        $leave->save();

        return response()->json([
            'message' => "Leave {$validated['status']} successfully.",
            'leave'   => $leave
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        $user = Auth::user();
        if ($leave->employee_id !== $user->employee->employee_id) {
            return response()->json(['error' => 'You do not have permission to delete this leave.'], 403);
        }
        if ($leave->status !== 'pending') {
            return response()->json(['error' => 'Cannot delete a ' . $leave->status . ' leave request.'], 403);
        }
        $leave->delete();

        return redirect()->route('admin.leaves.index');
    }
}
