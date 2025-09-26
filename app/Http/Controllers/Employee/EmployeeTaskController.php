<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\Project;
use App\Models\TaskComment;
use App\Models\Tasks;
use Illuminate\Console\View\Components\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeTaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employee = Employee::where('employee_id', Auth::user()->employee_id)->firstOrFail();

        $teamIds = $employee->teams->pluck('team_id');

        // Fetch all projects related to those teams
        $projects = Project::whereIn('team_id', $teamIds)->get();

        $projectIds = $projects->pluck('project_id');
        $tasks = Tasks::whereIn('project_id', $projectIds)->get();

        $totalTasks   = $tasks->count();
        $completed    = $tasks->where('status', 'completed')->count();
        $inProgress   = $tasks->where('status', 'in_progress')->count();
        $todo         = $tasks->where('status', 'todo')->count();
        $pending      = $tasks->where('status', 'pending')->count();
        $overdue      = $tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count();

        return view('employees.tasks.index', compact(
            'tasks',
            'totalTasks',
            'completed',
            'inProgress',
            'todo',
            'pending',
            'overdue',
            'projects'
        ));
    }

    public function store(Request $request)
    {
        $employee = Employee::where('employee_id', Auth::user()->employee_id)->firstOrFail();

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'project_id'  => 'required|string|exists:projects,project_id',
            'due_date'    => 'required|date',
        ]);

        $task = Tasks::create([
            ...$validated,
            'assigned_by' => $employee->employee_id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully!',
            'task'    => $task
        ]);
    }


    public function show($id)
    {
        $task = Tasks::with(['project', 'assignedBy'])->findOrFail($id);

        return response()->json([
            'id'          => $task->id,
            'title'       => $task->title,
            'priority'    => $task->priority,
            'status'      => $task->status,
            'due_date'    => $task->due_date,
            'created_at'  => $task->created_at,
            'description' => $task->description,
            'progress'    => $task->progress,
            'project'     => $task->project?->project_name ?? 'N/A',
            'assigned_by_employee' => $task->assignedBy?->employee_name ?? 'N/A',
            'comments'    => $task->comments ?? []
        ]);
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /*
     * Update the specified resource in storage.
     */
    public function update(Request $request, $currentTaskId)
    {
        $task = Tasks::findOrFail($currentTaskId);

        $validated = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority'    => 'required|in:low,medium,high',
            'status'      => 'required|in:pending,todo,in_progress,completed',
            'project_id'  => 'required|string|exists:projects,project_id',
            'progress'    => 'nullable|integer|min:0|max:100',
            'due_date'    => 'required|date',
            'personal_notes'       => 'nullable|string',
        ]);

        $task->update($validated);
        return response()->json([
            'success' => true,
            'message' => 'Task updated successfully!',
            'task'    => $task->load(['project', 'assignedBy']),
        ]);
    }

    public function updateStatus(Request $request, string $id)
    {
        $task = Tasks::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,completed',
        ]);

        $task->status = $validated['status'];
        $task->save();

        return response()->json([
            'success' => true,
            'message' => 'Task status updated successfully!',
            'task'    => $task,
        ]);
    }

    public function addComment(Request $request, $taskId)
    {
        $employee = Employee::where('employee_id', Auth::user()->employee_id)->firstOrFail();

        $validated = $request->validate([
            'comment' => 'required|string|max:1000',
        ]);

        $comment = TaskComment::create([
            'task_id'    => $taskId,
            'employee_id' => $employee->employee_id,
            'comment'    => $validated['comment'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => $comment->load('employee'),
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
