<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function index()
    {
        $projects = Project::all();
        return view('admin.projects.index', compact('projects'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'project_name'   => 'required|string|max:255',
            'client'         => 'nullable|string|max:255',
//            'team_id'        => 'required|exists:team,team_id',
            'status'         => 'required|in:Planning,In Progress,On Hold,Testing,Completed,Cancelled',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'description'    => 'nullable|string',
            'milestone_info' => 'nullable|string',
        ]);


        $lastProject = Project::orderBy('project_id', 'desc')->first();
        if ($lastProject) {
            $lastId = intval(substr($lastProject->project_id, 3));
            $newId = 'PRJ' . str_pad($lastId + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'PRJ001';
        }

        Project::create([
            'project_id'     => $newId,
            'project_name'   => $request->project_name,
            'client'         => $request->client,
//            'team_id'        => $request->team_id,
            'status'         => $request->status,
            'start_date'     => $request->start_date,
            'end_date'       => $request->end_date,
            'description'    => $request->description,
            'milestone_info' => $request->milestone_info,
        ]);

        return redirect()->route('admin.projects.index')->with('success', 'Project created successfully!');
    }

    public function edit($project_id)
    {
        $project = Project::find($project_id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'project' => $project
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
//            'team_id'        => 'required|string',
            'status'         => 'required|in:Planning,In Progress,On Hold,Testing,Completed,Cancelled',
            'start_date'     => 'nullable|date',
            'end_date'       => 'nullable|date|after_or_equal:start_date',
            'description'    => 'nullable|string',
            'milestone_info' => 'nullable|string',
        ]);

        $project->update($validated);

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

        $project = Project::findOrFail($id);

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

}
