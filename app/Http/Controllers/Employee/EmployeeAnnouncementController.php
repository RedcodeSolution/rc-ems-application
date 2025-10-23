<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EmployeeAnnouncementController extends Controller
{
    public function index()
    {
        $employee = Auth::user();

        if (!$employee) {
            abort(401, 'Unauthorized');
        }

        $details = DB::table('employee_announcement_details')
            ->where('employee_id', $employee->employee_id)
            ->select('announcement_id', 'is_read')
            ->get();

        $announcementIds = $details->pluck('announcement_id');

        $announcements = Announcement::whereIn('announcement_id', $announcementIds)
            ->orderByDesc('created_at')
            ->get();

        $announcements->map(function ($announcement) use ($details) {
            $detail = $details->firstWhere('announcement_id', $announcement->announcement_id);
            $announcement->is_read = $detail ? $detail->is_read : 0;
            return $announcement;
        });

        $totalAnnouncements = $announcements->count();
        $unreadAnnouncements = $announcements->where('is_read', 0)->count();
        $urgentAnnouncements = $announcements->where('priority', 'urgent')->count();

        return view('employees.announcements.index', [
            'announcements' => $announcements,
            'employee' => $employee,
            'totalAnnouncements' => $totalAnnouncements,
            'unreadAnnouncements' => $unreadAnnouncements,
            'urgentAnnouncements' => $urgentAnnouncements,
        ]);
    }



    public function show($id)
    {
        $employee = Auth::user();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $hasAccess = DB::table('employee_announcement_details')
            ->where('employee_id', $employee->employee_id)
            ->where('announcement_id', $id)
            ->exists();

        if (!$hasAccess) {
            return response()->json([
                'status' => 'error',
                'message' => 'You do not have access to this announcement.'
            ], 403);
        }

        $announcement = Announcement::with(['departments', 'teams'])
            ->where('announcement_id', $id)
            ->first();

        if (!$announcement) {
            return response()->json([
                'status' => 'error',
                'message' => 'Announcement not found.'
            ], 404);
        }

        $data = [
            'announcement_id' => $announcement->announcement_id,
            'title' => $announcement->title,
            'priority' => ucfirst($announcement->priority),
            'category' => $announcement->category,
            'content' => $announcement->content,
            'created_at' => $announcement->created_at->format('Y-m-d H:i:s'),
            'expires_at' => $announcement->expires_at ? $announcement->expires_at->format('Y-m-d H:i:s') : null,
            'author' => 'HR Department',
            'departments' => $announcement->departments->pluck('department_name')->toArray(),
            'teams' => $announcement->teams->pluck('team_name')->toArray(),
        ];

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function markAsRead($id)
    {
        $employee = Auth::user();

        if (!$employee) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized access.'
            ], 401);
        }

        $record = DB::table('employee_announcement_details')
            ->where('employee_id', $employee->employee_id)
            ->where('announcement_id', $id)
            ->first();

        if (!$record) {
            return response()->json([
                'status' => 'error',
                'message' => 'You are not linked to this announcement.'
            ], 403);
        }

        DB::table('employee_announcement_details')
            ->where('employee_id', $employee->employee_id)
            ->where('announcement_id', $id)
            ->update(['is_read' => true]);

        return response()->json([
            'status' => 'success',
            'message' => 'Announcement marked as read.'
        ]);
    }
}
