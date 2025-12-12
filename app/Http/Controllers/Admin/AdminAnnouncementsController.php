<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Department;
use App\Models\Team;
use Illuminate\Http\Request;

class AdminAnnouncementsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Eager load all related departments and teams
        $announcements = Announcement::with(['departments.teams', 'teams'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        // Get all departments and their teams for dropdown
        $departments = Department::with(['teams' => function ($query) {
            $query->select('team_id', 'team_name', 'department_id');
        }])->get(['department_id', 'department_name']);

        // Dashboard counts
        $totalAnnouncements = Announcement::count();
        $publishedCount     = Announcement::where('status', 'published')->count();
        $scheduledCount     = Announcement::where('status', 'scheduled')->count();

        return view('admin.announcements.index', compact(
            'announcements',
            'totalAnnouncements',
            'publishedCount',
            'scheduledCount',
            'departments'
        ));
    }



    public function getTeamsByDepartment($departmentId)
    {
        if ($departmentId === 'all') {
            // If "All Departments" selected, return all teams
            $teams = Team::select('team_id', 'team_name')->get();
        } else {
            $teams = Team::where('department_id', $departmentId)
                ->select('team_id', 'team_name')
                ->get();
        }

        if ($teams->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => 'No teams found for this department.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'data' => $teams,
        ], 200);
    }


    /**
     * Store a newly created resource in storage.
     */

    public function store(Request $request)
    {
        // Pre-process inputs to handle "all" selection
        $deptIds = $request->input('department_id', []);
        $teamIds = $request->input('team_id', []);

        if (in_array('all', (array)$deptIds)) {
            $request->merge(['department_id' => []]);
        }
        
        if (in_array('all', (array)$teamIds)) {
            $request->merge(['team_id' => []]);
        }

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'priority'          => 'required|in:low,medium,high,urgent',
            'category'          => 'required|string|max:50',
            'content'           => 'required|string',
            'expires_at'        => 'nullable|date',
            'target_audience'   => 'nullable|array',
            'target_audience.*' => 'in:all,managers,department_heads',
            'department_id'     => 'nullable|array',
            'department_id.*'   => 'exists:departments,department_id',
            'team_id'           => 'nullable|array',
            'team_id.*'         => 'exists:teams,team_id',
        ]);

        // Determine status
        $status = 'published';
        if (!empty($validated['expires_at'])) {
            $status = now()->lt($validated['expires_at']) ? 'scheduled' : 'published';
        }

        // Create announcement
        $announcement = Announcement::create([
            'title'           => $validated['title'],
            'priority'        => $validated['priority'],
            'category'        => $validated['category'],
            'content'         => $validated['content'],
            'expires_at'      => $validated['expires_at'] ?? null,
            'target_audience' => $validated['target_audience'] ?? ['all'],
            'status'          => $status,
        ]);

        // Attach Departments
        if (!empty($validated['department_id'])) {
            $announcement->departments()->attach($validated['department_id']);
        }

        // Attach Teams
        if (!empty($validated['team_id'])) {
            $announcement->teams()->attach($validated['team_id']);
        }

        // Fetch employees based on selected departments/teams
        $employeesQuery = \App\Models\Employee::query();

        if (!empty($validated['department_id'])) {
            $employeesQuery->whereIn('department_id', $validated['department_id']);
        }

        if (!empty($validated['team_id'])) {
            $employeesQuery->whereHas('teams', function ($q) use ($validated) {
                $q->whereIn('teams.team_id', $validated['team_id']);
            });
        }

        $employees = $employeesQuery->get();

        // Attach employees to the announcement
        foreach ($employees as $employee) {
            $announcement->employees()->attach($employee->employee_id, ['is_read' => false]);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Announcement created successfully!',
            'data'    => $announcement->load('departments', 'teams'),
        ]);
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Pre-process inputs to handle "all" selection
        $deptIds = $request->input('department_id', []);
        $teamIds = $request->input('team_id', []);

        if (in_array('all', (array)$deptIds)) {
            $request->merge(['department_id' => []]);
        }
        
        if (in_array('all', (array)$teamIds)) {
            $request->merge(['team_id' => []]);
        }

        $validated = $request->validate([
            'title'             => 'required|string|max:255',
            'priority'          => 'required|in:low,medium,high,urgent',
            'category'          => 'required|string|max:50',
            'content'           => 'required|string',
            'expires_at'        => 'nullable|date',
            'target_audience'   => 'nullable|array',
            'target_audience.*' => 'in:all,managers,department_heads',
            'department_id'     => 'nullable|array',
            'department_id.*'   => 'exists:departments,department_id',
            'team_id'           => 'nullable|array',
            'team_id.*'         => 'exists:teams,team_id',
            'status'            => 'nullable|in:scheduled,published'
        ]);

        $announcement = Announcement::findOrFail($id);

        // Determine new status
        $status = $announcement->status;
        if (!empty($validated['expires_at'])) {
            $status = now()->lt($validated['expires_at']) ? 'scheduled' : 'published';
        }

        // Update main announcement
        $announcement->update([
            'title'           => $validated['title'],
            'priority'        => $validated['priority'],
            'category'        => $validated['category'],
            'content'         => $validated['content'],
            'expires_at'      => $validated['expires_at'] ?? null,
            'target_audience' => $validated['target_audience'] ?? ['all'],
            'status'          => $status,
        ]);

        // 🔄 Sync departments and teams (many-to-many pivot tables)
        $announcement->departments()->sync($validated['department_id'] ?? []);
        $announcement->teams()->sync($validated['team_id'] ?? []);

        // 🔄 Re-sync employees who should receive this announcement
        $employeesQuery = \App\Models\Employee::query();

        if (!empty($validated['department_id'])) {
            $employeesQuery->whereIn('department_id', $validated['department_id']);
        }

        if (!empty($validated['team_id'])) {
            $employeesQuery->whereHas('teams', function ($q) use ($validated) {
                $q->whereIn('teams.team_id', $validated['team_id']);
            });
        }

        $employees = $employeesQuery->get();

        // Remove old links and attach new ones
        $announcement->employees()->detach();
        foreach ($employees as $employee) {
            $announcement->employees()->attach($employee->employee_id, ['is_read' => false]);
        }

        return response()->json([
            'message' => 'Announcement updated successfully!',
            'announcement' => $announcement->load('departments', 'teams'),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $announcement = Announcement::findOrFail($id);

        // Delete related pivot records
        $announcement->departments()->detach();
        $announcement->teams()->detach();
        $announcement->employees()->detach();
        $announcement->delete();

        return redirect()
            ->route('admin.announcements')
            ->with('success', 'Announcement deleted successfully.');
    }
}
