<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Authenticated;
use App\Models\Attendance;
use Carbon\Carbon;

class AutoClockInEmployee
{
    public function handle(Authenticated $event)
    {
        $user = $event->user;
        if (!$user->employee_id) {
            return;
        }
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();
        $nineAM = Carbon::today('Asia/Colombo')->setTime(9, 0, 0);

        $attendance = Attendance::firstOrCreate(
            ['employee_id' => $user->employee_id, 'date' => $today],
            ['check_in_time' => $now, 'status' => 'present']
        );
        if ($now->gt($nineAM)) {
            $attendance->status = 'late';
        }

        $attendance->save();
    }
}
