<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoClockOutEmployee
{
    public function handle(Logout $event)
    {
        $user = $event->user;

        if (!$user || !$user->employee_id) {
            return;
        }

        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();
        $attendance = Attendance::where('employee_id', $user->employee_id)
            ->whereDate('date', $today)
            ->first();

        if ($attendance && !$attendance->check_out_time) {
            $attendance->check_out_time = $now;

            if ($attendance->check_in_time) {
                $totalWorked = $attendance->check_in_time->diffInMinutes($now) / 60;
                $attendance->hours_worked = round($totalWorked - $attendance->break_duration, 2);
            }
            if ($attendance->hours_worked > 8) {
                $attendance->overtime_hours = round($attendance->hours_worked - 8, 2);
            }

            $attendance->save();
        }
    }
}
