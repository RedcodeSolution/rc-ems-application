<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveController extends Controller
{
    public function index()
{
    $user = Auth::user();

    if (!$user || !$user->employee) {
        return response()->json(['error' => 'Employee leaves data not found for this user.'], 404);
    }

    // All leaves for stats
    $allLeaves = $user->employee->leaves;

    // Last 3 recent leaves
    $recentLeaves = $user->employee->leaves()->latest()->take(3)->get();

    // Stats calculation
    $annualUsed   = $allLeaves->where('leave_type', 'annual')->where('status', 'approved')->sum('duration');
    $sickUsed     = $allLeaves->where('leave_type', 'sick')->where('status', 'approved')->sum('duration');
    $personalUsed = $allLeaves->where('leave_type', 'personal')->where('status', 'approved')->sum('duration');
    $pendingCount = $allLeaves->where('status', 'pending')->count();

    // Totals (could come from config or DB)
    $annualTotal   = 21;
    $sickTotal     = 10;
    $personalTotal = 5;

    return view('employees.leaves.index', [
        'leaves'=>$allLeaves,
        'recentLeaves'  => $recentLeaves,
        'annualUsed'    => $annualUsed,
        'sickUsed'      => $sickUsed,
        'personalUsed'  => $personalUsed,
        'annualTotal'   => $annualTotal,
        'sickTotal'     => $sickTotal,
        'personalTotal' => $personalTotal,
        'pendingCount'  => $pendingCount,
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
            'supporting_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $validated['employee_id'] = $user->employee->employee_id;

        Leave::create($validated);
        return redirect()->route('employee.leaves.index');
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
        return view('employees.leaves.index',['leave' => $leave]);
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
        return redirect()->route('employee.leaves.index');
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

        return redirect()->route('employee.leaves.index');
    }
}
