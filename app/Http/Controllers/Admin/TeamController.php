<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Department;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        $teams = Team::all();
        $departments = \App\Models\Department::all();
        return view('admin.teams.index', compact('teams', 'departments'));
    }

//    public function create()
//    {
//        $departments = Department::all();
//        $employees = Employee::all();
//        return view('admin.teams.create', compact('departments', 'employees'));
//    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'team_lead' => 'nullable|string|max:255',
            'max_team_size' => 'required|integer|min:1|max:50',
            'monthly_budget' => 'nullable|numeric|min:0',
            'team_status' => 'required|in:Active,Inactive,On Hold,Disbanded',
            'team_priority' => 'required|in:Low,Normal,High,Critical',
            'work_mode' => 'required|in:On-site,Remote,Hybrid,Flexible',
            'team_description' => 'nullable|string',
            'team_goals' => 'nullable|string',
            'skills_required' => 'nullable|string',
        ]);

        $team = Team::create([
            'team_name' => $validated['team_name'],
            'department_id' => $validated['department_id'],
            'team_lead' => $validated['team_lead'] ?? null,
            'max_team_size' => $validated['max_team_size'],
            'monthly_budget' => $validated['monthly_budget'] ?? null,
            'team_status' => $validated['team_status'],
            'team_priority' => $validated['team_priority'],
            'work_mode' => $validated['work_mode'],
            'team_description' => $validated['team_description'] ?? null,
            'team_goals' => $validated['team_goals'] ?? null,
            'skills_required' => $validated['skills_required'] ?? null,
        ]);

        return redirect()->route('admin.teams')->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */
    // app/Http/Controllers/TeamController.php

    public function show($id)
    {
        $team = Team::findOrFail($id);
        return view('teams.show', compact('team'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $team = Team::findOrFail($id);
        $employees = \App\Models\Employee::all();
        return view('teams.edit', compact('team', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);
        $request->validate([
            'team_name' => 'required|string|max:255',
            'employee_id' => 'nullable|exists:employees,employee_id',
        ]);
        $team->update($request->all());
        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }
    public function assignEmployeesForm($team_id)
    {
        $team = Team::with('employees')->findOrFail($team_id);
        $employees = Employee::all();
        return view('admin.teams.assign_employees', compact('team', 'employees'));
    }


    public function assignEmployees(Request $request, $team_id)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,employee_id',
        ]);

        $team = Team::findOrFail($team_id);
        $team->employees()->sync($request->employee_ids);

        return redirect()->route('admin.teams')->with('success', 'Team members updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }

}
