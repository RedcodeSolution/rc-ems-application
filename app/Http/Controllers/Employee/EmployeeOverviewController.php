<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Models\Attendance;
use App\Models\Document;
use App\Models\Employee;
use App\Models\Leave;
use App\Models\Meeting;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Tasks;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeOverviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $today = Carbon::today();


        $todayAttendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        $todayHoursDecimal = $todayAttendance ? $todayAttendance->hours_worked : 0;


        $yesterday = Carbon::yesterday();
        $yesterdayAttendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $yesterday)
            ->first();
        $yesterdayHoursDecimal = $yesterdayAttendance ? $yesterdayAttendance->hours_worked : 0;


        if ($yesterdayHoursDecimal > 0) {
            $hourChangePercent = (($todayHoursDecimal - $yesterdayHoursDecimal) / $yesterdayHoursDecimal) * 100;
        } else {
            $hourChangePercent = 0;
        }

        $hours = floor($todayHoursDecimal);
        $minutes = round(($todayHoursDecimal - $hours) * 60);
        $todayHours = sprintf('%dh %02dm', $hours, $minutes);



        $projectCount = Project::whereHas('employees', function ($q) use ($employeeId) {
            $q->where('employee_project.employee_id', $employeeId);
        })->count();


        $teamCount = Team::whereHas('employees', function ($q) use ($employeeId) {
            $q->where('employee_team.employee_id', $employeeId);
        })->count();


        $unreadNotifications = Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();


        $documentCount = Document::where('employee_id', $employeeId)->count();

        $approvedLeaveCount = Leave::where('employee_id', $employeeId)
            ->where('status', 'approved')
            ->count();

        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();
        $teamIds = $employee->teams->pluck('team_id');
        $projects = Project::whereIn('team_id', $teamIds)->get();
        $projectIds = $projects->pluck('project_id');
        $tasks = Tasks::whereIn('project_id', $projectIds)->get();


        $announcements = Announcement::whereJsonContains('target_audience', ["all"])
            ->where('status', 'published')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();


        $period = request()->get('period', 'week');
        switch ($period) {
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $format = 'd';
                break;
            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $format = 'M';
                break;
            default:
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $format = 'D';
        }

        $attendanceData = Attendance::where('employee_id', $employeeId)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->groupBy(fn($a) => Carbon::parse($a->date)->format($format))
            ->map(fn($group) => [
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'late' => $group->where('status', 'late')->count(),
                'half_day' => $group->where('status', 'half_day')->count(),
            ]);

        $attendanceLabels = $attendanceData->keys()->values();
        $presentData = $attendanceData->pluck('present')->values();
        $absentData = $attendanceData->pluck('absent')->values();
        $lateData = $attendanceData->pluck('late')->values();
        $halfDayData = $attendanceData->pluck('half_day')->values();
        Meeting::createDailyStandup();
        $todayMeetings = Meeting::getTodayMeetings();
        return view('employees.dashboard', [
            'employee' => $user->name,
            'today_hours_worked' => $todayHours,
            'assigned_project_count' => $projectCount,
            'teamCount' => $teamCount,
            'unread_notifications' => $unreadNotifications,
            'document_count' => $documentCount,
            'approved_leave_count' => $approvedLeaveCount,
            'tasks' => $tasks,
            'announcements' => $announcements,
            'todayMeetings' => $todayMeetings,

            'attendance_labels' => $attendanceLabels,
            'present_data' => $presentData,
            'absent_data' => $absentData,
            'late_data' => $lateData,
            'halfday_data' => $halfDayData,
            'selected_period' => $period,
            'hour_change_percent' => round($hourChangePercent, 1),
        ]);
    }

    public function join(Meeting $meeting)
    {
        if ($meeting->status === 'scheduled') {
            $meeting->update(['status' => 'ongoing']);
        }

        return redirect()->away($meeting->meeting_link);
    }
}
