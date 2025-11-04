<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeActivity;
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

        // Get all team IDs for this employee
        $teamIds = $employee->teams->pluck('team_id');

        // Get all projects belonging to those teams
        $projects = Project::whereIn('team_id', $teamIds)->get();
        $projectIds = $projects->pluck('project_id');

        // Get all related tasks
        $tasks = Tasks::whereIn('project_id', $projectIds)->get();

        // Count stats by status
        $totalTasks = $tasks->count();
        $todo       = $tasks->where('status', 'todo')->count();
        $inProgress = $tasks->where('status', 'in_progress')->count();
        $completed  = $tasks->where('status', 'completed')->count();
        $overdue    = $tasks->where('due_date', '<', now())
            ->where('status', '!=', 'completed')
            ->count();
        $onHold     = $tasks->where('status', 'on_hold')->count();

        return view('employees.tasks.index', compact(
            'tasks',
            'totalTasks',
            'todo',
            'inProgress',
            'completed',
            'overdue',
            'onHold',
            'projects'
        ));
    }


    public function store(Request $request)
    {
        $employee = Employee::where('employee_id', Auth::user()->employee_id)->firstOrFail();

        $validated = $request->validate([
            'title'          => 'required|string|max:255',
            'description'    => 'nullable|string',
            'priority'       => 'required|in:low,medium,high',
            'project_id'     => 'required|string|exists:projects,project_id',
            'due_date'       => 'required|date',
            'personal_notes' => 'nullable|string',
        ]);

        $task = Tasks::create([
            ...$validated,
            'assigned_by' => $employee->employee_id,
        ]);

        EmployeeActivity::create([
            'employee_id' => $employee->employee_id,
            'type'        => 'task',
            'icon'        => 'fa-plus-circle',
            'action'      => 'Created a new task',
            'details'     => "Task '{$task->title}' was created successfully.",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Task created successfully!',
            'task'    => $task
        ]);
    }

    public function show($id)
    {
        $task = Tasks::with(['project', 'assignedBy', 'comments.employee'])->findOrFail($id);

        return response()->json([
            'id'          => $task->id,
            'title'       => $task->title,
            'priority'    => $task->priority,
            'status'      => $task->status,
            'due_date'    => $task->due_date,
            'created_at'  => $task->created_at,
            'description' => $task->description,
            'progress'    => $task->progress,
            'personal_notes' => $task->personal_notes,
            'project'     => $task->project?->project_name ?? 'N/A',
            'assigned_by_employee' => $task->assignedBy?->employee_name ?? 'N/A',
            'comments'    => $task->comments ?? []
        ]);
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
            'status'         => 'required|in:todo,in_progress,completed,overdue,on_hold',
            'project_id'  => 'required|string|exists:projects,project_id',
            'progress'    => 'nullable|integer|min:0|max:100',
            'due_date'    => 'required|date',
            'personal_notes'       => 'nullable|string',
        ]);

        $task->update($validated);

        EmployeeActivity::create([
            'employee_id' => Auth::user()->employee_id,
            'type'        => 'task',
            'icon'        => 'fa-edit',
            'action'      => 'Updated task details',
            'details'     => "Task '{$task->title}' was updated.",
        ]);

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
            'status'   => 'required|in:todo,in_progress,completed',
            'progress' => 'nullable|integer|min:0|max:100',
        ]);

        $task->status = $validated['status'];

        if ($validated['status'] === 'completed') {
            $task->progress = 100;
        } elseif (isset($validated['progress'])) {
            $task->progress = $validated['progress'];
        }

        $task->save();

        EmployeeActivity::create([
            'employee_id' => Auth::user()->employee_id,
            'type'        => 'task',
            'icon'        => 'fa-tasks',
            'action'      => 'Updated task status',
            'details'     => "Task '{$task->title}' marked as {$task->status}.",
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Task status and progress updated successfully!',
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

        EmployeeActivity::create([
            'employee_id' => $employee->employee_id,
            'type'        => 'comment',
            'icon'        => 'fa-comment',
            'action'      => 'Added a comment',
            'details'     => "Commented on task ID #{$taskId}: '{$validated['comment']}'",
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully!',
            'comment' => $comment->load('employee'),
        ]);
    }
}
