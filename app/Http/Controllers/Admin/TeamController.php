<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Team::with('employees');
        if ($request->has('search') && $request->search) {
            $query->where('team_name', 'like', '%' . $request->search . '%');
        }
        $teams = $query->get();
        $departments = \App\Models\Department::all();
        $employees = \App\Models\Employee::all(); // Add this line
        return view('admin.teams.index', compact('teams', 'departments', 'employees'));
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'team_lead' => 'nullable|exists:employees,employee_id',
            'max_team_size' => 'required|integer|min:1|max:50',
            'monthly_budget' => 'nullable|numeric|min:0',
            'team_status' => 'required|in:Active,Inactive,On Hold,Disbanded',
            'team_priority' => 'required|in:Low,Normal,High,Critical',
            'work_mode' => 'required|in:On-site,Remote,Hybrid,Flexible',
            'team_description' => 'nullable|string',
            'team_goals' => 'nullable|string',
            'skills_required' => 'nullable|string',
        ]);

        // Save the team lead as name instead of id
        $teamLeadName = null;
        if (!empty($validated['team_lead'])) {
            $employee = \App\Models\Employee::find($validated['team_lead']);
            $teamLeadName = $employee ? $employee->employee_name : null;
        }

        $team = Team::create([
            'team_name' => $validated['team_name'],
            'department_id' => $validated['department_id'],
            'team_lead' => $teamLeadName, // Save name instead of id
            'max_team_size' => $validated['max_team_size'],
            'monthly_budget' => $validated['monthly_budget'] ?? null,
            'team_status' => $validated['team_status'],
            'team_priority' => $validated['team_priority'],
            'work_mode' => $validated['work_mode'],
            'team_description' => $validated['team_description'] ?? null,
            'team_goals' => $validated['team_goals'] ?? null,
            'skills_required' => $validated['skills_required'] ?? null,
        ]);

        // Auto-add Team Lead to the Team (employee_team table)
        if (!empty($validated['team_lead'])) {
             $team->employees()->syncWithoutDetaching([$validated['team_lead']]);
        }

        return redirect()->route('admin.teams')->with('success', 'Team created successfully.');
    }

    /**
     * Display the specified resource.
     */

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
        $employees = Employee::all();
        return view('teams.edit', compact('team', 'employees'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $team = Team::findOrFail($id);

        $validated = $request->validate([
            'team_name' => 'required|string|max:255',
            'department_id' => 'required|exists:departments,department_id',
            'team_lead' => 'nullable|exists:employees,employee_id',
            'max_team_size' => 'required|integer|min:1|max:50',
            'monthly_budget' => 'nullable|numeric|min:0',
            'team_status' => 'required|in:Active,Inactive,On Hold,Disbanded',
            'team_priority' => 'required|in:Low,Normal,High,Critical',
            'work_mode' => 'required|in:On-site,Remote,Hybrid,Flexible',
            'team_description' => 'nullable|string',
            'team_goals' => 'nullable|string',
            'skills_required' => 'nullable|string',
        ]);

        // Save the team lead as name instead of id
        $teamLeadName = null;
        if (!empty($validated['team_lead'])) {
            $employee = \App\Models\Employee::find($validated['team_lead']);
            $teamLeadName = $employee ? $employee->employee_name : null;
        }

        $oldTeamLeadName = $team->team_lead;

        $team->update([
            'team_name' => $validated['team_name'],
            'department_id' => $validated['department_id'],
            'team_lead' => $teamLeadName,
            'max_team_size' => $validated['max_team_size'],
            'monthly_budget' => $validated['monthly_budget'] ?? null,
            'team_status' => $validated['team_status'],
            'team_priority' => $validated['team_priority'],
            'work_mode' => $validated['work_mode'],
            'team_description' => $validated['team_description'] ?? null,
            'team_goals' => $validated['team_goals'] ?? null,
            'skills_required' => $validated['skills_required'] ?? null,
        ]);

        // Auto-add Team Lead to the Team (employee_team table)
        if (!empty($validated['team_lead'])) {
             $team->employees()->syncWithoutDetaching([$validated['team_lead']]);
        }

        // If Team Lead has changed (matched by name), update Project Assignments
        if ($teamLeadName && $teamLeadName !== $oldTeamLeadName) {
            
            // 1. Remove Old Leader from Team and Projects
            if ($oldTeamLeadName) {
                $oldLead = \App\Models\Employee::where('employee_name', $oldTeamLeadName)->first();
                if ($oldLead) {
                    // Remove from Team
                    $team->employees()->detach($oldLead->employee_id);

                    // Remove from Active Projects
                    $teamProjects = $team->projects()
                        ->whereIn('status', ['In Progress', 'Planning', 'Testing', 'On Hold'])
                        ->get();
                    
                    foreach ($teamProjects as $project) {
                        $project->employees()->detach($oldLead->employee_id);
                    }
                }
            }

            // 2. Add New Leader to Active Projects
            // Find the new team lead employee object
            $newLead = \App\Models\Employee::where('employee_name', $teamLeadName)->first();
            
            if ($newLead) {
                // Get active projects (fetch again or reuse if optimized, but fetch is safer here)
                $teamProjects = $team->projects()
                    ->whereIn('status', ['In Progress', 'Planning', 'Testing', 'On Hold'])
                    ->get();

                foreach ($teamProjects as $project) {
                    // Attach new lead to project with role 'Team Lead'
                    $project->employees()->syncWithoutDetaching([
                        $newLead->employee_id => [
                            'role' => 'Team Lead',
                            'status' => 'Active',
                            'progress' => 0,
                            'assigned_date' => now(),
                            'deadline' => $project->end_date
                        ]
                    ]);
                }
            }
        }

        return redirect()->route('admin.teams')->with('success', 'Team updated successfully.');
    }
    public function assignEmployeesForm($team_id)
    {
        $team = Team::with('employees')->findOrFail($team_id);
        
        // Exclude the current team lead from the list of selectable members
        // Note: team_lead column stores the Name, so we filter by name
        $employees = Employee::where('employee_name', '!=', $team->team_lead ?? '')->get();

        return view('admin.teams.assign_employees', compact('team', 'employees'));
    }


    public function assignEmployees(Request $request, $team_id)
    {
        $request->validate([
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'exists:employees,employee_id',
        ]);

        $team = Team::findOrFail($team_id);
    
        // Get current employee IDs before syncing
        $currentEmployeeIds = $team->employees->pluck('employee_id')->toArray();
        $newEmployeeIds = $request->employee_ids;

        // CRITICAL: Ensure Team Lead is NOT removed (since they are excluded from the form)
        if ($team->team_lead) {
            $leader = Employee::where('employee_name', $team->team_lead)->first();
            if ($leader && !in_array($leader->employee_id, $newEmployeeIds)) {
                $newEmployeeIds[] = $leader->employee_id;
            }
        }

        // Calculate removed employees
        // We use the UPDATED $newEmployeeIds (including leader) to determine who is actually removed
        $removedEmployeeIds = array_diff($currentEmployeeIds, $newEmployeeIds);

        // 1. Sync Team Members
        $team->employees()->sync($newEmployeeIds);

        // Get all active projects for this team
        // Statuses based on Admin\ProjectController validation: Planning, In Progress, On Hold, Testing
        $teamProjects = $team->projects()
            ->whereIn('status', ['In Progress', 'Planning', 'Testing', 'On Hold']) 
            ->get();

        // 2. Remove detached members from Team Projects
        if (!empty($removedEmployeeIds)) {
            foreach ($teamProjects as $project) {
                $project->employees()->detach($removedEmployeeIds);
            }
        }

        // 3. Auto-assign New Team Projects to these Members
        foreach ($newEmployeeIds as $empId) {
            $employee = Employee::find($empId);
            if ($employee) {
                foreach ($teamProjects as $project) {
                    // Attach employee to project if not already attached
                    $project->employees()->syncWithoutDetaching([
                        $empId => [
                            'role' => 'Member',
                            'status' => 'Active', // Pivot status should be 'Active' to show up
                            'progress' => 0,
                            'assigned_date' => now(),
                            'deadline' => $project->end_date // Optional: inherit project deadline
                        ]
                    ]);
                }
            }
        }

        return redirect()->route('admin.teams')->with('success', 'Team members updated. Projects synced (New members added, removed members unassigned).');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $team = Team::findOrFail($id);
        $team->delete();
        return redirect()->route('admin.teams')->with('success', 'Team deleted successfully.');
    }

}
