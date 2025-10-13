<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\EmployeeSkill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        $employee = $user->employee()->with([
            'department.manager',
            'teams',
        ])->first();

        $skills = $employee->employeeSkills ?? [];

        if ($employee) {
            return view('employees.profile.index', [
                'employee' => $employee,
                'skills' => $skills,
            ]);
        }

        return response()->json(['error' => 'Employee data not found for this user.'], 404);
    }



    public function update(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($user->employee) {
            $user->employee->update($validated);
            // return ['employee' => $user->employee];
            return redirect()->route('employee.profile');
            // return response()->json(['message' => 'Employee profile updated successfully.'], 200);
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
