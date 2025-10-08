<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class EmployeeAttendanceController extends Controller
{
    /**
     * Display the specified attendance record.
     */
    public function show(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;

        // Optional: if a date is passed (e.g. /attendance/show?date=2024-01-15)
        $specificDate = $request->query('date');

        // Fetch all attendance records for this employee
        $attendances = Attendance::where('employee_id', $employeeId)
            ->orderBy('date', 'desc')
            ->get();

        // --- Summary stats ---
        $presentDays = $attendances->where('status', 'present')->count();
        $absentDays = $attendances->where('status', 'absent')->count();
        $lateArrivals = $attendances->where('status', 'late')->count();

        // --- Total hours calculation ---
        $totalHoursValue = $attendances->sum('hours_worked');
        $totalMinutes = round($totalHoursValue * 60);
        $totalHoursOnly = floor($totalMinutes / 60);
        $totalMinutesOnly = $totalMinutes % 60;
        $totalHours = sprintf('%dh %dm', $totalHoursOnly, $totalMinutesOnly);

        // --- Fetch today’s or specific attendance ---
        $targetDate = $specificDate ?? now('Asia/Colombo')->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $targetDate)
            ->first();

        // Default values
        $workingHoursNow = '0h 0m';
        $checkInTimeFormatted = null;
        $breakDurationFormatted = '0h 0m';
        $breakStatus = 'No break';
        $status = 'N/A';

        if ($attendance) {
            $status = ucfirst($attendance->status);

            // --- Format Check-in ---
            if ($attendance->check_in_time) {
                $checkInTimeFormatted = \Carbon\Carbon::parse($attendance->check_in_time)
                    ->timezone('Asia/Colombo')
                    ->format('g:i A');
            }

            // --- Break Duration ---
            if ($attendance->break_duration > 0) {
                $totalBreakMinutes = $attendance->break_duration * 60;
                $breakHours = floor($totalBreakMinutes / 60);
                $breakMinutes = $totalBreakMinutes % 60;
                $breakDurationFormatted = sprintf('%dh %dm', $breakHours, $breakMinutes);
            }

            // --- Break Status ---
            if ($attendance->is_on_break) {
                $breakStatus = 'On Break';
            } elseif ($attendance->break_end_time) {
                $breakStatus = 'Break Ended';
            } elseif ($attendance->break_start_time) {
                $breakStatus = 'Break Started';
            }

            // --- Working Hours ---
            if ($attendance->check_in_time && !$attendance->check_out_time) {
                $checkIn = \Carbon\Carbon::parse($attendance->check_in_time)->timezone('Asia/Colombo');
                $now = now('Asia/Colombo');

                $workedMinutes = $checkIn->diffInMinutes($now) - ($attendance->break_duration * 60);
                $workedMinutes = max($workedMinutes, 0);

                $hours = floor($workedMinutes / 60);
                $minutes = $workedMinutes % 60;
                $workingHoursNow = sprintf('%dh %dm', $hours, $minutes);
            } elseif ($attendance->check_out_time) {
                if ($attendance->hours_worked) {
                    $totalMinutes = $attendance->hours_worked * 60;
                    $hours = floor($totalMinutes / 60);
                    $minutes = $totalMinutes % 60;
                    $workingHoursNow = sprintf('%dh %dm', $hours, $minutes);
                }
            }
        }

        return view('employees.attendance.index', compact(
            'attendances',
            'presentDays',
            'absentDays',
            'lateArrivals',
            'totalHours',
            'attendance',
            'workingHoursNow',
            'checkInTimeFormatted',
            'breakDurationFormatted',
            'breakStatus',
            'status',
            'targetDate'
        ));
    }




    public function startBreak()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No attendance record found for today.']);
        }

        if ($attendance->is_on_break) {
            return response()->json(['success' => false, 'message' => 'Break already started.']);
        }

        $attendance->update([
            'break_start_time' => $now,
            'is_on_break' => true,
        ]);

        return response()->json(['success' => true, 'message' => 'Break started successfully.']);
    }


    public function endBreak()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No attendance record found for today.']);
        }

        if (!$attendance->is_on_break || !$attendance->break_start_time) {
            return response()->json(['success' => false, 'message' => 'No active break to end.']);
        }

        // Calculate break duration in hours
        $breakMinutes = $attendance->break_start_time->diffInMinutes($now);
        $breakHours = round($breakMinutes / 60, 2);

        // Add to existing total break duration
        $attendance->update([
            'break_end_time' => $now,
            'break_duration' => $attendance->break_duration + $breakHours,
            'is_on_break' => false,
        ]);

        return response()->json(['success' => true, 'message' => 'Break ended successfully.']);
    }


    public function getBreakStatus()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $today = now('Asia/Colombo')->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'on_break' => false]);
        }

        return response()->json([
            'success' => true,
            'on_break' => $attendance->is_on_break,
            'break_start_time' => $attendance->break_start_time
                ? \Carbon\Carbon::parse($attendance->break_start_time)->format('g:i A')
                : null
        ]);
    }

    public function startEmergency(Request $request)
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No attendance record found for today.']);
        }

        if ($attendance->is_on_emergency) {
            return response()->json(['success' => false, 'message' => 'Emergency already active.']);
        }

        $attendance->update([
            'is_on_emergency' => true,
            'emergency_type' => $request->emergency_type,
            'emergency_description' => $request->emergency_description,
            'emergency_start_time' => $now,
        ]);

        return response()->json(['success' => true, 'message' => 'Emergency break started.']);
    }

    public function endEmergency()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->is_on_emergency) {
            return response()->json(['success' => false, 'message' => 'No active emergency break found.']);
        }

        $startTime = Carbon::parse($attendance->emergency_start_time);
        $duration = $startTime->diffInMinutes($now) / 60;

        $attendance->update([
            'is_on_emergency' => false,
            'emergency_end_time' => $now,
            'emergency_duration' => $attendance->emergency_duration + $duration,
        ]);

        return response()->json(['success' => true, 'message' => 'Emergency break ended.']);
    }

    public function getEmergencyStatus()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;
        $today = now('Asia/Colombo')->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => true, 'emergency' => null]);
        }

        // ✅ Check emergency fields (NOT break fields)
        $isActive = $attendance->is_on_emergency && $attendance->emergency_start_time && !$attendance->emergency_end_time;
        $reason = $isActive ? $attendance->emergency_type : null;

        $totalEmergencies = Attendance::where('employee_id', $employeeId)
            ->whereNotNull('emergency_start_time')
            ->count();

        return response()->json([
            'success' => true,
            'emergency' => [
                'is_active' => $isActive,
                'reason' => $reason,
                'total' => $totalEmergencies,
            ],
        ]);
    }
}
