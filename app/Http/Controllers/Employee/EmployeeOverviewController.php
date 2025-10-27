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
        $userId = $user->id;
        $employeeId = $user->employee_id;
        $today = Carbon::today();

        // --- Attendance Tracking ---
        $todayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        $todayHoursDecimal = $todayAttendance ? $todayAttendance->hours_worked : 0;

        $yesterday = Carbon::yesterday();
        $yesterdayAttendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $yesterday)
            ->first();

        $yesterdayHoursDecimal = $yesterdayAttendance ? $yesterdayAttendance->hours_worked : 0;

        $hourChangePercent = $yesterdayHoursDecimal > 0
            ? (($todayHoursDecimal - $yesterdayHoursDecimal) / $yesterdayHoursDecimal) * 100
            : 0;

        $hours = floor($todayHoursDecimal);
        $minutes = round(($todayHoursDecimal - $hours) * 60);
        $todayHours = sprintf('%dh %02dm', $hours, $minutes);

        // --- Project and Team Data ---
        $projectCount = Project::whereHas('employees', function ($q) use ($employeeId) {
            $q->where('employee_project.employee_id', $employeeId);
        })->count();

        $teamCount = Team::whereHas('employees', function ($q) use ($employeeId) {
            $q->where('employee_team.employee_id', $employeeId);
        })->count();

        // --- Notifications ---
        $unreadNotifications = Notification::where('user_id', $userId)
            ->where('is_read', false)
            ->count();

        // --- Documents ---
        $documentCount = Document::where('employee_id', $employeeId)->count();

        // --- Leaves (already uses user_id) ---
        $approvedLeaveCount = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->count();

        // --- Teams, Projects, Tasks ---
        $employee = Employee::where('employee_id', $employeeId)->firstOrFail();
        $teamIds = $employee->teams->pluck('team_id');
        $projects = Project::whereIn('team_id', $teamIds)->get();
        $projectIds = $projects->pluck('project_id');
        $tasks = Tasks::whereIn('project_id', $projectIds)->get();

        // --- Announcements ---
        $announcements = Announcement::whereJsonContains('target_audience', ['all'])
            ->where('status', 'published')
            ->orderBy('priority', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // --- Attendance Chart Data ---
        $attendanceData = collect();
        $allDates = collect();
        $period = request()->get('period', 'week');

        switch ($period) {
            case 'month':
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $format = 'd';
                $allDates = collect(range(1, now()->endOfMonth()->day))
                    ->map(fn($d) => str_pad($d, 2, '0', STR_PAD_LEFT));
                break;

            case 'year':
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $format = 'M';
                $allDates = collect(range(1, 12))
                    ->map(fn($m) => Carbon::create()->month($m)->format('M'));
                break;

            default:
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $format = 'D';
                $allDates = collect(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']);
        }

        $attendanceData = Attendance::where('user_id', $userId)
            ->whereBetween('date', [$start, $end])
            ->get()
            ->groupBy(fn($a) => Carbon::parse($a->date)->format($format))
            ->map(fn($group) => [
                'present' => $group->where('status', 'present')->count(),
                'absent' => $group->where('status', 'absent')->count(),
                'late' => $group->where('status', 'late')->count(),
                'half_day' => $group->where('status', 'half_day')->count(),
            ]);

        $attendanceLabels = $allDates;
        $presentData = $allDates->map(fn($label) => $attendanceData[$label]['present'] ?? 0);
        $absentData = $allDates->map(fn($label) => $attendanceData[$label]['absent'] ?? 0);
        $lateData = $allDates->map(fn($label) => $attendanceData[$label]['late'] ?? 0);
        $halfDayData = $allDates->map(fn($label) => $attendanceData[$label]['half_day'] ?? 0);

        $totalPresent = $presentData->sum();
        $totalAbsent = $absentData->sum();
        $totalLate = $lateData->sum();
        $totalHalfDay = $halfDayData->sum();
        $totalDays = $totalPresent + $totalAbsent + $totalLate + $totalHalfDay;

        // --- Meetings ---
        Meeting::createDailyStandup();
        $todayMeetings = Meeting::getTodayMeetings();

        // --- Return to View ---
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

            'total_present' => $totalPresent,
            'total_absent' => $totalAbsent,
            'total_late' => $totalLate,
            'total_halfday' => $totalHalfDay,
            'total_days' => $totalDays,
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
