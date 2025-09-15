<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Http\Request;

class EmployeeProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load(['employee.department', 'employee.teams', 'employee.employeeSkills']);

        if ($user->employee) {
            return view('employees.profile.index', ['employee' => $user->employee]);
        }

        return response()->json(['error' => 'Employee data not found for this user.'], 404);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'employee_name' => 'nullable|string|max:255',
            'contact_no' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
        ]);

        if ($user->employee) {
            $user->employee->update($validated);
            return response()->json(['message' => 'Employee profile updated successfully.'], 200);
        }

        return response()->json(['error' => 'Employee record not found.'], 404);
    }

    public function createSkills(Request $request, User $user)
    {
        $request->validate([
            'skills' => 'required|array',
            'skills.*.skill_name' => 'required|string|max:255',
            'skills.*.proficiency_level' => 'nullable|string|max:50',
            'skills.*.category' => 'nullable|string|max:50',
        ]);

        if (!$user->employee) {
            return response()->json(['error' => 'Employee record not found.'], 404);
        }

        $skills = $request->input('skills');
        foreach ($skills as $skill) {
            $user->employee->employeeSkills()->create($skill);
        }

        return response()->json(['message' => 'Skills added successfully.'], 201);
    }

    public function updateSkill(Request $request, User $user, string $skillId)
    {
        $request->validate([
            'skill_name' => 'required|string|max:255',
            'proficiency_level' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:50',
        ]);

        if (!$user->employee) {
            return response()->json(['error' => 'Employee record not found.'], 404);
        }

        $skill = $user->employee->employeeSkills()->findOrFail($skillId);
        $skill->update($request->only(['skill_name', 'proficiency_level', 'category']));

        return response()->json(['message' => 'Skill updated successfully.'], 200);
    }

    public function deleteSkill(User $user, string $skillId)
    {
        if (!$user->employee) {
            return response()->json(['error' => 'Employee record not found.'], 404);
        }

        $skill = $user->employee->employeeSkills()->findOrFail($skillId);
        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully.'], 200);
    }
}
