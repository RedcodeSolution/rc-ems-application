<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeLeaveController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if ($user) {
            return response()->json(['leaves' => $user->employee->leaves]);
        }
        return response()->json(['error' => 'Employee leaves data not found for this user.'], 404);
    }

    public function store(Request $request, Leave $leave)
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

        return response()->json(['message' => 'Leave request submitted successfully.', 'leave' => $leave], 201);
        // return redirect()->route('employee.leaves.index');
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
        return response()->json(['leave' => $leave], 200);
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
            'reason' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'supporting_doc' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $leave->update($validated);
        return response()->json(['message' => 'Leave updated successfully.'], 200);
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

        return response()->json(['message' => 'Leave deleted successfully.'], 200);
    }
}
