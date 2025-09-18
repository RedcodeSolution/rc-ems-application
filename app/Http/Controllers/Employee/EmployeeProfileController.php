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
        $skills = $user->employee->employeeSkills;
        if ($user) {
            return view('employees.profile.index', ['employee' => $user->employee, 'skills' => $skills]);
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

    public function createSkills(Request $request)
        {
            $user = Auth::user();

            if (!$user->employee) {
                return response()->json(['error' => 'Employee record not found.'], 404);
            }

            $validated = $request->validate([
                'skill_name' => 'required|string|max:255',
                'skill_level' => 'nullable|string|max:50',
                'skill_category' => 'nullable|string|max:50',
            ]);

            $employeeId = $user->employee->employee_id;

            $exists = EmployeeSkill::where('employee_id', $employeeId)
                        ->where('skill_name', $validated['skill_name'])
                        ->exists();

            if ($exists) {
                return response()->json(['error' => 'This skill already exists for the employee.'], 409);
            }

            $validated['employee_id'] = $employeeId;

            EmployeeSkill::create($validated);

            return redirect()->route('employee.profile');
        }

    public function updateSkill(Request $request, string $skillId)
{
    $user = Auth::user();

    if (!$user->employee) {
        return response()->json(['error' => 'Employee record not found.'], 404);
    }

    $validated = $request->validate([
        'skill_name' => 'required|string|max:255',
        'proficiency_level' => 'nullable|string|max:50',
        'category' => 'nullable|string|max:50',
    ]);

    $skill = $user->employee->skills()->findOrFail($skillId);
    $exists = $user->employee->skills()
        ->where('skill_name', $validated['skill_name'])
        ->where('id', '!=', $skill->id) 
        ->exists();

    if ($exists) {
        return response()->json(['error' => 'This skill already exists for the employee.'], 409);
    }

    $skill->update($validated);

    return response()->json(['message' => 'Skill updated successfully.'], 200);
}


    public function deleteSkill(string $skillId)
    {
        $user = Auth::user();

        if (!$user->employee) {
            return response()->json(['error' => 'Employee record not found.'], 404);
        }

        $skill = $user->employee->skills()->find($skillId);

        if (!$skill) {
            return response()->json(['error' => 'Skill not found for this employee.'], 404);
        }

        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully.'], 200);
    }
}
