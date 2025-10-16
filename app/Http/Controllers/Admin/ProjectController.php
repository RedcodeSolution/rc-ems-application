<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Project;
use App\Models\Team;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function index()
    {
        $projects = Project::with(['team' => function ($q) {
            $q->withCount('employees');
        }])->get();

        $teams = Team::withCount('employees')->get();
        $departments = Department::all();
        $employees = Employee::with(['department', 'projects'])->get();
        return view('admin.projects.index', compact('projects', 'teams', 'departments', 'employees'));
    }


    public function create()
    {
        $teams = Team::all();
        return view('admin.projects.create', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_name'   => 'required|string|max:255',
            'client'         => 'nullable|string|max:255',
            'team_id'        => 'required|exists:teams,team_id',
            'status'         => 'required|in:Planning,In Progress,On Hold,Testing,Completed,Cancelled',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'description'    => 'nullable|string',
            'milestone_info' => 'nullable|string',
        ]);

        // Auto-generate project ID
        $lastProject = Project::orderBy('project_id', 'desc')->first();
        if ($lastProject) {
            $lastId = intval(substr($lastProject->project_id, 3));
            $newId = 'PRJ' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'PRJ001';
        }

        // Create project
        $project = Project::create([
            'project_id'     => $newId,
            'project_name'   => $request->project_name,
            'client'         => $request->client,
            'team_id'        => $request->team_id,
            'status'         => $request->status,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'description'    => $request->description,
            'milestone_info' => $request->milestone_info,
        ]);

        $team = Team::with('employees')->find($request->team_id);

        if ($team && $team->employees->isNotEmpty()) {
            foreach ($team->employees as $employee) {
                $project->employees()->attach($employee->employee_id, [
                    'role' => $employee->employee_id == $team->team_lead ? 'Team Lead' : 'Member',
                    'status' => 'Active',
                    'assigned_date' => now(),
                    'progress' => 0,
                    'deadline' => $request->end_date,
                ]);
            }
        }


        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }


    public function edit($project_id)
    {
        $project = Project::with('team')->find($project_id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.'
            ], 404);
        }

        $teams = Team::all();

        return response()->json([
            'success' => true,
            'project' => $project,
            'teams' => $teams
        ]);
    }


    public function update(Request $request, $project_id)
    {
        $project = Project::find($project_id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.'
            ], 404);
        }

        $validated = $request->validate([
            'project_name'   => 'required|string|max:255',
            'client'         => 'nullable|string|max:255',
            'team_id'        => 'required|string',
            'status'         => 'required|in:Planning,In Progress,On Hold,Testing,Completed,Cancelled',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'description'    => 'nullable|string',
            'milestone_info' => 'nullable|string',
        ]);

        $oldStatus = $project->status;

        $project->update($validated);

        if ($oldStatus !== 'Completed' && $project->status === 'Completed') {
            $notify = new NotificationService();
            $notify->notify(
                title: 'Project Completed',
                message: "The project '{$project->project_name}' has been marked as completed.",
                type: 'project',
                userId: null,
                target: 'admin',
                referenceId: $project->id
            );
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Project updated successfully!',
                'project' => $project
            ]);
        }

        return redirect()->route('admin.projects.index')->with('success', 'Project updated successfully.');
    }


    public function show($id)
    {

        $project = Project::with('team')->findOrFail($id);

        return response()->json([
            'success' => true,
            'project' => $project
        ]);
    }


    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();

        return redirect()->route('admin.projects.index')->with('success', 'Department deleted successfully!');
    }


    public function getProjectsByEmployee($employeeName)
    {
        $employee = Employee::where('employee_name', $employeeName)->first();

        if (!$employee) {
            return response()->json([]);
        }

        // Fetch only the projects this employee is assigned to
        $projects = $employee->projects()
            ->with(['team'])
            ->get();

        return response()->json($projects);
    }


    public function getEmployeeAssignments($id)
    {
        $employee = Employee::with(['department', 'projects' => function ($query) {
            $query->withPivot(['role', 'status', 'assigned_date']);
        }])->find($id);

        if (!$employee) {
            return response()->json(['message' => 'Employee not found'], 404);
        }

        $assignments = $employee->projects->map(function ($project) use ($employee) {
            return [
                'id' => $project->id,
                'project_name' => $project->project_name,
                'role' => $project->pivot->role,
                'status' => strtolower($project->pivot->status ?? $project->status ?? 'active'),
                'assigned_date' => $project->pivot->assigned_date,
                'progress' => match ($project->status) {
                    'Planning' => 10,
                    'In Progress' => 50,
                    'Testing' => 70,
                    'Completed' => 100,
                    'On Hold' => 30,
                    default => 0,
                },
                'deadline' => $project->end_date,
                'priority' => $project->priority ?? 'medium',
                'department' => $employee->department->department_name ?? 'N/A',
            ];
        });



        return response()->json([
            'employee_name' => $employee->employee_name,
            'assignments' => $assignments,
        ]);
    }
}
