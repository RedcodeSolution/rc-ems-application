<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\EmployeeActivity;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EmployeeLeaveController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // All leaves for stats
        $allLeaves = $user->leaves;

        // Last 3 recent leaves
        $recentLeaves = $user->leaves()->latest()->take(3)->get();

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
            'leaves' => $allLeaves,
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
            'supporting_doc' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('supporting_doc')) {
            $file = $request->file('supporting_doc');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('leaves', $filename, 'public');

            $validated['supporting_doc'] = $filename;
        }



        $validated['user_id'] = $user->id;

        $leave = Leave::create($validated);
        $leave->load('user');

        // Example notification (adjust message)
        (new NotificationService())->notify(
            title: 'New Leave Request',
            message: $leave->user->name . ' applied for ' . $leave->leave_type . ' leave.',
            type: 'leave',
            userId: null,
            target: 'admin',
            referenceId: $leave->leave_id
        );

        EmployeeActivity::create([
            'employee_id' => $user->employee_id,
            'type'        => 'leave_request',
            'action'      => 'Requested Leave',
            'details'     => "Applied for {$leave->leave_type} leave from {$leave->start_date} to {$leave->end_date}",
            'icon'        => 'fa-calendar-plus',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'Leave request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Leave $leave)
    {
        $user = Auth::user();
        if ($leave->user_id !== $user->id) {
            return response()->json(['error' => 'Leave not found for this user.'], 404);
        }
        $leave->load('employee');
        return view('employees.leaves.index', ['leave' => $leave]);
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Leave $leave)
    {
        if ($leave->status === 'approved') {
            return back()->withErrors(['error' => 'Cannot update an approved leave request.']);
        }

        $validated = $request->validate([
            'leave_type' => 'required|string|in:annual,sick,personal,maternity,paternity,emergency',
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'contact_info' => 'nullable|string',
            'supporting_doc' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:2048',
        ]);

        $validated['contact_number'] = $validated['contact_info'] ?? null;
        unset($validated['contact_info']);

        if ($request->hasFile('supporting_doc')) {
            $file = $request->file('supporting_doc');
            $filename = time() . '_' . $file->getClientOriginalName();

            if ($leave->supporting_doc && Storage::disk('public')->exists('leaves/' . $leave->supporting_doc)) {
                Storage::disk('public')->delete('leaves/' . $leave->supporting_doc);
            }

            $file->storeAs('leaves', $filename, 'public');
            $validated['supporting_doc'] = $filename;
        }

        $leave->update($validated);

        $user = Auth::user();
        EmployeeActivity::create([
            'employee_id' => $user->employee_id,
            'type'        => 'leave_update',
            'action'      => 'Updated Leave Request',
            'details'     => "Updated {$leave->leave_type} leave request",
            'icon'        => 'fa-edit',
        ]);

        return redirect()->route('employee.leaves.index')
            ->with('success', 'Leave request updated successfully.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Leave $leave)
    {
        if ($leave->status !== 'pending') {
            return response()->json(['error' => 'Cannot delete a ' . $leave->status . ' leave request.'], 403);
        }
        $leave->delete();

        $user = Auth::user();
        EmployeeActivity::create([
            'employee_id' => $user->employee_id,
            'type'        => 'leave_cancel',
            'action'      => 'Cancelled Leave Request',
            'details'     => "Cancelled pending {$leave->leave_type} leave request",
            'icon'        => 'fa-trash',
        ]);

        return redirect()->route('employee.leaves.index');
    }
}
