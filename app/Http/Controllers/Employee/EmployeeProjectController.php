<?php

namespace App\Http\Controllers\Employee;

use App\Models\Employee;
use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeProjectController
{

    public function index()
    {
        // Get the logged-in employee ID
        $employeeId = Auth::user()?->employee_id;

        // If no employee ID, return empty collection
        if (!$employeeId) {
            $employeeProjects = collect(); // empty collection
        } else {
            // Fetch projects assigned to this employee with pivot info and team info
            $employeeProjects = DB::table('employee_project')
                ->join('projects', 'employee_project.project_id', '=', 'projects.project_id')
                ->leftJoin('teams', 'projects.team_id', '=', 'teams.team_id')
                ->where('employee_project.employee_id', $employeeId)
                ->select(
                    'employee_project.employee_id',
                    'employee_project.project_id',
                    'employee_project.role',
                    'employee_project.status as pivot_status',
                    'employee_project.progress',
                    'employee_project.deadline',
                    'projects.project_name',
                    'projects.description',
                    'projects.status as project_status',
                    'projects.start_date',
                    'projects.end_date',
                    'teams.team_name'
                )
                ->get();
        }

        // Pass data to Blade view
        return view('employees.projects.index', compact('employeeProjects'));
    }

    public function show($projectId)
    {
        // Get project with team info
        $project = Project::with('team')->where('project_id', $projectId)->first();

        if (!$project) {
            return response()->json(['project' => null], 404);
        }

        // Include pivot info if employee-specific (optional)
        $pivotData = $project->employees()
            ->where('employee_id', auth()->user()->employee_id)
            ->first();

        $role = $pivotData?->pivot->role ?? 'N/A';
        $progress = $pivotData?->pivot->progress ?? 0;
        $deadline = $pivotData?->pivot->deadline?->format('Y-m-d') ?? null;

        return response()->json([
            'project' => [
                'project_id' => $project->project_id,
                'project_name' => $project->project_name,
                'description' => $project->description,
                'client' => $project->client,
                'team' => [
                    'team_name' => $project->team?->team_name ?? 'N/A'
                ],
                'role' => $role,
                'progress' => $progress,
                'deadline' => $deadline,
                'status' => $project->status,
            ]
        ]);
    }
}
