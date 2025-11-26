<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\EmployeeActivity;
use App\Models\EmployeeSkill;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $userId = $user->id;
        $employee = $user->employee()->with([
            'department.manager',
            'teams',
        ])->first();

        $employeeId = $user->employee_id;

        $skills = $employee->employeeSkills ?? [];

        $allLeaves = $user->employee->leaves;

        $annualUsed   = $allLeaves->where('leave_type', 'annual')->where('status', 'approved')->sum('duration');

        $annualTotal   = 21;

        $leaveRemaining = $annualTotal - $annualUsed;

        $projectCount = Project::whereHas('employees', function ($q) use ($employeeId) {
            $q->where('employee_project.employee_id', $employeeId);
        })->count();

        $documentCount = Document::where('employee_id', $employeeId)->count();

        $attendanceCount = Attendance::where('user_id', $userId)->count();


        if ($employee) {
            return view('employees.profile.index', [
                'employee' => $employee,
                'skills' => $skills,
                'leaveRemaining' => $leaveRemaining,
                'projectCount' => $projectCount,
                'documentCount' => $documentCount,
                'attendanceCount' => $attendanceCount
            ]);
        }

        return response()->json(['error' => 'Employee data not found for this user.'], 404);
    }


    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'employee_name' => 'required|string|max:255',
            'contact_no' => [
                'required',
                'nullable',
                'string',
                'max:20',
                'regex:/^[0-9\s\-\(\)]+$/',
                'min:10',
            ],
            'email' => [
                'required',
                'email',
                'max:255',
            ]
        ]);

        $user->update([
            'name'       => $validated['employee_name'],
            'email'      => $validated['email'],
            'contact_no' => $validated['contact_no'],
        ]);

        if ($user->employee) {
            $user->employee->update($validated);

            EmployeeActivity::create([
                'employee_id' => $user->employee->employee_id,
                'type'        => 'profile_update',
                'icon'        => 'user-edit',
                'action'      => 'Updated Profile',
                'details'     => 'Profile details were updated successfully.',
            ]);

            return redirect()->route('employee.profile');
        }

        return response()->json(['error' => 'Employee record not found.'], 404);
    }

    public function getSkills()
    {
        $user = Auth::user();
        if (!$user->employee) {
            return response()->json(['error' => 'Employee record not found.'], 404);
        }
        $skills = $user->employee->skills;
        return response()->json(['skills' => $skills], 200);
    }

    public function saveSkills(Request $request)
    {
        $request->validate([
            'skills' => 'nullable|array',
            'skills.*.name' => 'required|string|max:255',
            'skills.*.level' => 'required|string|max:50',
            'skills.*.category' => 'required|string|max:100',
        ]);

        $employee = Auth::user()->employee;

        // Delete all skills first
        $employee->skills()->delete();

        // Only recreate if any skills are passed
        if (!empty($request->skills)) {
            foreach ($request->skills as $skill) {
                $employee->skills()->create([
                    'skill_name' => $skill['name'],
                    'skill_level' => $skill['level'],
                    'skill_category' => $skill['category'],
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => empty($request->skills)
                ? 'All skills removed successfully.'
                : 'Skills updated successfully.',
        ]);
    }
}
