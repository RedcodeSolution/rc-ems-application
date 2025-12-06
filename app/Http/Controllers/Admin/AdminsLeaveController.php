<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Department;
use App\Models\Leave;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminsLeaveController extends Controller
{


    public function index()
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Admin leaves data not found for this user.'], 404);
        }

        $allLeaves = collect();
        $recentLeaves = collect();
        $pendingCount = 0;

        if ($user) {
            $allLeaves = Leave::where('user_id', $user->id)->get();
            $recentLeaves = Leave::where('user_id', $user->id)->latest()->take(3)->get();
            $pendingCount = $allLeaves->where('status', 'pending')->count();
        }

        // Get all employee leaves with proper relationships
        // Get all employee leaves with proper relationships
        $employeeLeaves = Leave::with(['user.employee', 'user.employee.department'])
            ->whereHas('user.employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->get();

        // Current month/year and today
        $currentMonth = now()->month;
        $currentYear = now()->year;
        $today = now()->toDateString();

        $annualUsed = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->where('leave_type', 'annual')
            ->where('status', 'approved')
            ->sum('duration');

        $sickUsed = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->where('leave_type', 'sick')
            ->where('status', 'approved')
            ->sum('duration');

        $personalUsed = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->where('leave_type', 'personal')
            ->where('status', 'approved')
            ->sum('duration');

        // Count statistics for current month
        $employeeMonthlyCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->count();

        $employeePendingMonthlyCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'pending')
            ->count();

        $employeeRejectedMonthlyCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->where('status', 'rejected')
            ->count();

        $employeeApprovedMonthlyCount = $employeeMonthlyCount - $employeePendingMonthlyCount - $employeeRejectedMonthlyCount;

        // Get leaves by status with proper relationships
        $pendingLeaves = Leave::with(['user.employee', 'user.employee.department'])
            ->whereHas('user.employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'pending')
            ->get();

        $approvedLeaves = Leave::with(['user.employee', 'user.employee.department'])
            ->whereHas('user.employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'approved')
            ->get();

        $rejectedLeaves = Leave::with(['user.employee', 'user.employee.department'])
            ->whereHas('user.employee', function ($query) {
                $query->whereNotIn('role', ['admin', 'superadmin']);
            })
            ->where('status', 'rejected')
            ->get();

        // Calculate department stats properly
        $departmentStats = Department::with(['employees.leaves' => function ($query) {
            $query->where('status', 'approved');
        }])
            ->get()
            ->map(function ($dept) {
                $totalUsed = $dept->employees->flatMap->leaves->sum('duration');
                $total = 60;
                $percentage = $total > 0 ? round(($totalUsed / $total) * 100, 2) : 0;

                return [
                    'name' => $dept->department_name,
                    'used' => $totalUsed,
                    'total' => $total,
                    'percentage' => $percentage,
                ];
            });

        $annualTotal = 21;
        $sickTotal = 10;
        $personalTotal = 5;

        $adminAnnualUsed = Leave::where('user_id', $user->id)
            ->where('leave_type', 'annual')
            ->where('status', 'approved')
            ->sum('duration');

        $adminSickUsed = Leave::where('user_id', $user->id)
            ->where('leave_type', 'sick')
            ->where('status', 'approved')
            ->sum('duration');

        $adminPersonalUsed = Leave::where('user_id', $user->id)
            ->where('leave_type', 'personal')
            ->where('status', 'approved')
            ->sum('duration');


        // Calculate percentages
        $annualPercent = $annualTotal > 0 ? round(($adminAnnualUsed / $annualTotal) * 100) : 0;
        $sickPercent = $sickTotal > 0 ? round(($adminSickUsed / $sickTotal) * 100) : 0;
        $personalPercent = $personalTotal > 0 ? round(($adminPersonalUsed / $personalTotal) * 100) : 0;

        // Trend analysis
        $yesterday = now()->subDay()->toDateString();
        $employeeApprovedYesterdayCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereDate('approved_date', $yesterday)
            ->where('status', 'approved')
            ->count();

        $employeeApprovedTodayCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereDate('approved_date', $today)
            ->where('status', 'approved') 
            ->count();

        $approvedTrendDiff = $employeeApprovedTodayCount - $employeeApprovedYesterdayCount;

        // Last month comparison
        $lastMonth = now()->subMonth()->month;
        $lastMonthYear = now()->subMonth()->year;
        $employeeLastMonthCount = Leave::whereHas('user.employee', function ($query) {
            $query->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereMonth('created_at', $lastMonth)
            ->whereYear('created_at', $lastMonthYear)
            ->count();

        $monthlyTrendPercent = $employeeLastMonthCount > 0
            ? round((($employeeMonthlyCount - $employeeLastMonthCount) / $employeeLastMonthCount) * 100)
            : 0;

        // Rejection status
        $rejectedStatus = 'Normal range';
        if ($employeeRejectedMonthlyCount > 10) {
            $rejectedStatus = 'High';
        } elseif ($employeeRejectedMonthlyCount < 3) {
            $rejectedStatus = 'Low';
        }

        // Quick stats
        $avgProcessingTime = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->whereNotNull('approved_date')
            ->select(DB::raw('AVG(TIMESTAMPDIFF(HOUR, created_at, approved_date)) as avg_time'))
            ->value('avg_time') ?? 0;

        $totalLeaveRequests = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })->count();

        $approvalRate = $totalLeaveRequests > 0
            ? round((Leave::whereHas('user.employee', function ($q) {
                $q->whereNotIn('role', ['admin', 'superadmin']);
            })->where('status', 'approved')->count() / $totalLeaveRequests) * 100)
            : 0;

        $employeesOnLeaveToday = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->count();

        $overdueRequests = Leave::whereHas('user.employee', function ($q) {
            $q->whereNotIn('role', ['admin', 'superadmin']);
        })
            ->where('status', 'pending')
            ->whereDate('start_date', '<', $today)
            ->count();

        $departments = Department::select('department_id', 'department_name')->orderBy('department_name')->get();
        // ✅ Admin’s own used leave counts



        return view('admin.leaves.index', [
            'leaves' => $allLeaves,
            'recentLeaves' => $recentLeaves,
            'annualUsed' => $annualUsed,
            'sickUsed' => $sickUsed,
            'personalUsed' => $personalUsed,
            'annualTotal' => $annualTotal,
            'sickTotal' => $sickTotal,
            'personalTotal' => $personalTotal,
            'pendingCount' => $pendingCount,
            'employeeLeaves' => $employeeLeaves,
            'pendingLeaves' => $pendingLeaves,
            'approvedLeaves' => $approvedLeaves,
            'rejectedLeaves' => $rejectedLeaves,
            'employeeMonthlyCount' => $employeeMonthlyCount,
            'employeePendingMonthlyCount' => $employeePendingMonthlyCount,
            'employeeRejectedMonthlyCount' => $employeeRejectedMonthlyCount,
            'employeeApprovedTodayCount' => $employeeApprovedTodayCount,
            'leaveLabels' => ['Annual Leave', 'Sick Leave', 'Personal Leave'],
            'leaveData' => [$annualUsed, $sickUsed, $personalUsed],
            'trendLabels' => ['Approved', 'Rejected', 'Pending'],
            'trendData' => [
                'approved' => $employeeApprovedMonthlyCount,
                'rejected' => $employeeRejectedMonthlyCount,
                'pending' => $employeePendingMonthlyCount,
            ],
            'approvedTrendDiff' => $approvedTrendDiff,
            'monthlyTrendPercent' => $monthlyTrendPercent,
            'rejectedStatus' => $rejectedStatus,
            'annualPercent' => $annualPercent,
            'sickPercent' => $sickPercent,
            'personalPercent' => $personalPercent,
            'departmentStats' => $departmentStats,
            'avgProcessingTime' => round($avgProcessingTime, 1),
            'approvalRate' => $approvalRate,
            'employeesOnLeaveToday' => $employeesOnLeaveToday,
            'overdueRequests' => $overdueRequests,
            'departments' => $departments,

            'adminAnnualUsed' => $adminAnnualUsed,
            'adminSickUsed' => $adminSickUsed,
            'adminPersonalUsed' => $adminPersonalUsed,
        ]);
    }

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
            'supporting_doc' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        // Handle supporting document upload
        if ($request->hasFile('supporting_doc')) {
            $file = $request->file('supporting_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/leaves', $filename);
            $validated['supporting_doc'] = $filename;
        }

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';
        $validated['applied_date'] = now();

        $leave = Leave::create($validated);
        $leave->load('user');

        $notify = new NotificationService();

        if ($user->role === 'admin') {
            $notify->notify(
                title: 'Admin Leave Request',
                message: $user->name . ' (Admin) applied for ' . $leave->leave_type . ' leave.',
                type: 'leave',
                userId: null,
                target: 'superadmin',
                referenceId: $leave->leave_id
            );
        } else {

            $notify->notify(
                title: 'New Leave Request',
                message: $user->name . ' applied for ' . $leave->leave_type . ' leave.',
                type: 'leave',
                userId: null,
                target: 'admin',
                referenceId: $leave->leave_id
            );
        }


        return redirect()->route(
            $user->role === 'admin' ? 'admin.leaves.index' : 'employee.leaves.index'
        )->with('success', 'Leave request submitted successfully.');
    }

    public function show(Leave $leave)
    {
        $user = Auth::user();
        if ($leave->user_id !== $user->id) {
            return response()->json(['error' => 'Leave not found for this user.'], 404);
        }

        $leave->load([
            'user.employee.department',
            'approvedBy.employee'
        ]);

        return response()->json(['leave' => $leave]);
    }

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
            'contact_number' => 'nullable|string',
            'supporting_doc' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $startDate = Carbon::parse($validated['start_date']);
        $endDate = Carbon::parse($validated['end_date']);
        $validated['duration'] = $startDate->diffInDays($endDate) + 1;

        if ($request->hasFile('supporting_doc')) {
            $file = $request->file('supporting_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/leaves', $filename);
            $validated['supporting_doc'] = $filename;

            if ($leave->supporting_doc && Storage::exists('public/leaves/' . $leave->supporting_doc)) {
                Storage::delete('public/leaves/' . $leave->supporting_doc);
            }
        }

        $leave->update($validated);

        return redirect()->route('admin.leaves.index')->with('success', 'Leave updated successfully.');
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

        $admin = Admin::where('email', $user->email)->first();
        if (!$admin) {
            return response()->json(['error' => 'Admin record not found for this user'], 404);
        }

        $leave = Leave::findOrFail($leaveId);

        if ($validated['status'] === 'approved') {
            $leave->status        = 'approved';
            $leave->approved_by   = $admin->admin_id;
            $leave->approved_date = now();
            $leave->comments      = $validated['comments'] ?? null;

            $leave->rejected_by     = null;
            $leave->rejected_date   = null;
            $leave->rejection_reason = null;
        } elseif ($validated['status'] === 'rejected') {
            $leave->status          = 'rejected';
            $leave->rejected_by     = $admin->admin_id;
            $leave->rejected_date   = now();
            $leave->rejection_reason = $validated['rejection_reason'] ?? null;
            $leave->comments        = $validated['comments'] ?? null;

            $leave->approved_by   = null;
            $leave->approved_date = null;
        }
        $notify = new NotificationService();

        if ($leave->status === 'approved') {
            $notify->notify(
                title: 'Leave Approved',
                message: 'Your ' . $leave->leave_type . ' leave from ' .
                    $leave->start_date->format('M d, Y') . ' to ' .
                    $leave->end_date->format('M d, Y') . ' has been approved.',
                type: 'leave',
                userId: $leave->user_id,
                target: 'employee',
                referenceId: $leave->leave_id
            );
        } elseif ($leave->status === 'rejected') {
            $notify->notify(
                title: 'Leave Rejected',
                message: 'Your ' . $leave->leave_type . ' leave from ' .
                    $leave->start_date->format('M d, Y') . ' to ' .
                    $leave->end_date->format('M d, Y') . ' has been rejected.',
                type: 'leave',
                userId: $leave->user_id,
                target: 'employee',
                referenceId: $leave->leave_id
            );
        }

        $leave->save();
        return $request->ajax()
            ? response()->json(['message' => 'Leave status updated successfully'])
            : redirect()->route('admin.leaves.index')->with('success', 'Leave status updated successfully');
    }

    public function destroy(Leave $leave)
    {
        $user = Auth::user();

        if ($leave->user_id !== $user->id) {
            return response()->json(['error' => 'You do not have permission to delete this leave.'], 403);
        }

        if ($leave->status !== 'pending') {
            return response()->json(['error' => 'Cannot delete a ' . $leave->status . ' leave request.'], 403);
        }
        $leave->delete();

        return redirect()->route('admin.leaves.index')->with('success', 'Leave request deleted successfully.');
    }
}