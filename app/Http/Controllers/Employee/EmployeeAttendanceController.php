<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\Leave;
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
        $userId = $user->id;

        $specificDate = $request->query('date');

        // Fetch all attendance records for this user
        $attendances = Attendance::where('user_id', $userId)
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

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $targetDate)
            ->first();

        $isClockedIn = Attendance::where('user_id', $userId)
            ->whereDate('date', now('Asia/Colombo')->toDateString())
            ->whereNull('check_out_time')
            ->exists();

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
                $checkIn = Carbon::parse($attendance->check_in_time)->timezone('Asia/Colombo');
                $now = now('Asia/Colombo');

                $workedMinutes = $checkIn->diffInMinutes($now);

                $breakMinutes = ($attendance->break_duration ?? 0) * 60;
                $emergencyMinutes = ($attendance->emergency_duration ?? 0) * 60;

                if ($attendance->is_on_break && $attendance->break_start_time) {
                    $breakMinutes += Carbon::parse($attendance->break_start_time)->diffInMinutes($now);
                }

                if ($attendance->is_on_emergency && $attendance->emergency_start_time) {
                    $emergencyMinutes += Carbon::parse($attendance->emergency_start_time)->diffInMinutes($now);
                }

                $netWorked = max($workedMinutes - $breakMinutes - $emergencyMinutes, 0);
                $hours = floor($netWorked / 60);
                $minutes = $netWorked % 60;
                $workingHoursNow = sprintf('%dh %dm', $hours, $minutes);
            }
        }

        // --- Check if user has approved leave today ---
        $today = now('Asia/Colombo')->toDateString();

        $hasLeaveToday = Leave::where('user_id', $userId)
            ->where('status', 'approved')
            ->whereDate('start_date', '<=', $today)
            ->whereDate('end_date', '>=', $today)
            ->exists();

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
            'targetDate',
            'isClockedIn',
            'hasLeaveToday'
        ));
    }



    public function clockIn()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;

        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();
        $nineAM = Carbon::today('Asia/Colombo')->setTime(9, 0, 0);

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if ($attendance && $attendance->check_in_time) {
            return response()->json(['success' => false, 'message' => 'Already clocked in today.']);
        }

        $attendance = Attendance::create([
            'user_id' => $userId,
            'date' => $today,
            'check_in_time' => $now,
            'status' => $now->gt($nineAM) ? 'late' : 'present',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clock-in successful.',
            'status' => $attendance->status
        ]);
    }

    public function clockOut()
    {
        $user = Auth::user();
        $employeeId = $user->employee_id;

        if (!$employeeId) {
            return response()->json(['success' => false, 'message' => 'Employee not found.']);
        }

        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('employee_id', $employeeId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in_time) {
            return response()->json(['success' => false, 'message' => 'You must clock in before clocking out.']);
        }

        if ($attendance->check_out_time) {
            return response()->json(['success' => false, 'message' => 'Already clocked out today.']);
        }

        // --- Calculate emergency break duration if ongoing ---
        if ($attendance->is_on_emergency && $attendance->emergency_start_time) {
            $emergencyDuration = Carbon::parse($attendance->emergency_start_time)->diffInMinutes($now) / 60;
            $attendance->emergency_duration += $emergencyDuration;
            $attendance->is_on_emergency = false;
            $attendance->emergency_end_time = $now;
        }

        // --- Total worked hours calculation ---
        $totalWorked = Carbon::parse($attendance->check_in_time)->diffInMinutes($now) / 60;

        // Deduct both normal break & emergency break durations
        $hoursWorked = round(
            $totalWorked - ($attendance->break_duration + $attendance->emergency_duration),
            2
        );

        $hoursWorked = max($hoursWorked, 0); // Avoid negative values

        // --- Overtime calculation ---
        $overtime = $hoursWorked > 8 ? round($hoursWorked - 8, 2) : 0;

        // --- Save attendance ---
        $attendance->update([
            'check_out_time' => $now,
            'hours_worked' => $hoursWorked,
            'overtime_hours' => $overtime,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Clock-out successful.',
            'hours_worked' => $hoursWorked,
            'overtime_hours' => $overtime,
        ]);
    }


    public function getDetailsById($id)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated']);
        }

        $userId = $user->id;

        $attendance = Attendance::where('user_id', $userId)
            ->where('id', $id)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'Record not found']);
        }

        return response()->json([
            'success' => true,
            'attendance' => [
                'id' => $attendance->id,
                'date' => $attendance->date,
                'status' => $attendance->status,
                'check_in_time' => $attendance->check_in_time,
                'check_out_time' => $attendance->check_out_time,
                'break_duration' => $attendance->break_duration,
                'total_hours' => $attendance->hours_worked,
                'notes' => $attendance->notes,
            ],
        ]);
    }

    public function startBreak()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('user_id', $userId)
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

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No attendance record found for today.']);
        }

        if (!$attendance->is_on_break || !$attendance->break_start_time) {
            return response()->json(['success' => false, 'message' => 'No active break to end.']);
        }

        $breakStart = Carbon::parse($attendance->break_start_time);
        $breakMinutes = $breakStart->diffInMinutes($now);
        $breakHours = round($breakMinutes / 60, 2);

        $attendance->update([
            'break_end_time' => $now,
            'break_duration' => $attendance->break_duration + $breakHours,
            'is_on_break' => false,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Break ended successfully.',
            'break_duration' => $attendance->break_duration + $breakHours
        ]);
    }


    public function getBreakStatus()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $now = now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'on_break' => false]);
        }

        $checkInTimeFormatted = $attendance->check_in_time
            ? Carbon::parse($attendance->check_in_time)
                ->timezone('Asia/Colombo')
                ->format('g:i A')
            : null;

        $workingHoursNow = '0h 0m';

        if ($attendance->check_in_time && !$attendance->check_out_time) {
            $checkIn = Carbon::parse($attendance->check_in_time)->timezone('Asia/Colombo');

            // Base worked minutes
            $workedMinutes = $checkIn->diffInMinutes($now);

            // Deduct stored breaks
            $breakMinutes = ($attendance->break_duration ?? 0) * 60;
            $emergencyMinutes = ($attendance->emergency_duration ?? 0) * 60;

            // Deduct ongoing breaks
            if ($attendance->is_on_break && $attendance->break_start_time) {
                $breakMinutes += Carbon::parse($attendance->break_start_time)->diffInMinutes($now);
            }

            // Deduct ongoing emergency breaks
            if ($attendance->is_on_emergency && $attendance->emergency_start_time) {
                $emergencyMinutes += Carbon::parse($attendance->emergency_start_time)->diffInMinutes($now);
            }

            // Net worked time
            $netWorked = max($workedMinutes - $breakMinutes - $emergencyMinutes, 0);
            $workingHoursNow = sprintf('%dh %dm', floor($netWorked / 60), $netWorked % 60);
        } elseif ($attendance->check_out_time && $attendance->hours_worked) {
            $totalMinutes = round($attendance->hours_worked * 60);
            $hours = floor($totalMinutes / 60);
            $minutes = $totalMinutes % 60;
            $workingHoursNow = sprintf('%dh %dm', $hours, $minutes);
        }

        return response()->json([
            'success' => true,
            'on_break' => $attendance->is_on_break,
            'on_emergency' => $attendance->is_on_emergency,
            'break_start_time' => $attendance->break_start_time
                ? Carbon::parse($attendance->break_start_time)->timezone('Asia/Colombo')->format('g:i A')
                : null,
            'emergency_start_time' => $attendance->emergency_start_time
                ? Carbon::parse($attendance->emergency_start_time)->timezone('Asia/Colombo')->format('g:i A')
                : null,
            'status' => ucfirst($attendance->status ?? 'N/A'),
            'check_in_time' => $checkInTimeFormatted,
            'working_hours' => $workingHoursNow,
            'checked_out' => (bool) $attendance->check_out_time,
        ]);
    }

    public function startEmergency(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('user_id', $userId)
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
            'emergency_type' => $request->input('emergency_type'),
            'emergency_description' => $request->input('emergency_description'),
            'emergency_start_time' => $now,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Emergency break started successfully.',
            'emergency_type' => $request->input('emergency_type'),
            'start_time' => $now->format('g:i A'),
        ]);
    }

    public function endEmergency()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json(['success' => false, 'message' => 'No attendance record found for today.']);
        }

        if (!$attendance->is_on_emergency || !$attendance->emergency_start_time) {
            return response()->json(['success' => false, 'message' => 'No active emergency break to end.']);
        }

        $startTime = Carbon::parse($attendance->emergency_start_time);
        $duration = round($startTime->diffInMinutes($now) / 60, 2);

        $attendance->update([
            'is_on_emergency' => false,
            'emergency_end_time' => $now,
            'emergency_duration' => $attendance->emergency_duration + $duration,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Emergency break ended successfully.',
            'duration' => $duration . ' hours',
            'ended_at' => $now->format('g:i A'),
        ]);
    }


    public function getEmergencyStatus()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not authenticated.']);
        }

        $userId = $user->id;
        $today = now('Asia/Colombo')->toDateString();

        $attendance = Attendance::where('user_id', $userId)
            ->whereDate('date', $today)
            ->first();

        if (!$attendance) {
            return response()->json([
                'success' => true,
                'emergency' => [
                    'is_active' => false,
                    'reason' => null,
                    'total' => 0,
                ],
            ]);
        }

        $isActive = $attendance->is_on_emergency && $attendance->emergency_start_time && !$attendance->emergency_end_time;
        $reason = $isActive ? $attendance->emergency_type : null;

        $totalEmergencies = Attendance::where('user_id', $userId)
            ->whereNotNull('emergency_start_time')
            ->count();

        return response()->json([
            'success' => true,
            'emergency' => [
                'is_active' => $isActive,
                'reason' => $reason,
                'total' => $totalEmergencies,
                'started_at' => $isActive
                    ? Carbon::parse($attendance->emergency_start_time)->timezone('Asia/Colombo')->format('g:i A')
                    : null,
            ],
        ]);
    }
}
