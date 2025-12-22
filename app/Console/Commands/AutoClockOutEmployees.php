<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attendance;
use App\Models\EmployeeActivity;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AutoClockOutEmployees extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:auto-clockout';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Auto clock out employees who maintain checked-in status at 5 PM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now('Asia/Colombo');
        $today = $now->toDateString();

        Log::info("Running Auto Clockout Command for date: {$today}");

        $attendances = Attendance::whereDate('date', $today)
            ->whereNotNull('check_in_time')
            ->whereNull('check_out_time')
            ->with(['user.employee'])
            ->get();

        $count = 0;

        foreach ($attendances as $attendance) {
            try {
                // 1. Handle Active Breaks
                if ($attendance->is_on_break && $attendance->break_start_time) {
                    $breakStart = Carbon::parse($attendance->break_start_time);
                    $breakMinutes = $breakStart->diffInMinutes($now);
                    $breakHours = round($breakMinutes / 60, 2);
                    $attendance->break_duration += $breakHours;
                    $attendance->break_end_time = $now;
                    $attendance->is_on_break = false;
                }

                // 2. Handle Active Emergencies
                if ($attendance->is_on_emergency && $attendance->emergency_start_time) {
                    $emergencyStart = Carbon::parse($attendance->emergency_start_time);
                    $emergencyDuration = $emergencyStart->diffInMinutes($now) / 60;
                    $attendance->emergency_duration += $emergencyDuration;
                    $attendance->is_on_emergency = false;
                    $attendance->emergency_end_time = $now;
                }

                // 3. Calculate Hours Worked
                $checkInTime = Carbon::parse($attendance->check_in_time);
                $totalWorkedMinutes = $checkInTime->diffInMinutes($now);
                $totalWorkedHours = $totalWorkedMinutes / 60;

                $netHoursWorked = round($totalWorkedHours - ($attendance->break_duration + $attendance->emergency_duration), 2);
                $netHoursWorked = max($netHoursWorked, 0);
                
                $overtime = $netHoursWorked > 8 ? round($netHoursWorked - 8, 2) : 0;

                // 4. Update Status (Half-day check)
                $halfDayLimit = Carbon::createFromTime(12, 30, 0, 'Asia/Colombo');
                $status = $now->lessThan($halfDayLimit) ? 'halfday' : $attendance->status;

                // 5. Update Attendance Record
                $attendance->update([
                    'check_out_time' => $now,
                    'hours_worked' => $netHoursWorked,
                    'overtime_hours' => $overtime,
                    'clock_out_note' => 'Auto-clocked out by system (5 PM)',
                    'is_on_emergency' => false,
                    'is_on_break' => false,
                    'status' => $status,
                ]);

                // 6. Log Activity
                if ($attendance->user && $attendance->user->employee) {
                    EmployeeActivity::create([
                        'employee_id' => $attendance->user->employee->employee_id,
                        'type'        => 'attendance',
                        'icon'        => 'sign-out-alt',
                        'action'      => 'Auto Clocked Out',
                        'details'     => 'System auto-clockout at ' . $now->format('h:i A') . 
                                         ' — Worked ' . $netHoursWorked . ' hrs',
                    ]);
                }

                $count++;

            } catch (\Exception $e) {
                Log::error("Failed to auto-clockout attendance ID {$attendance->id}: " . $e->getMessage());
            }
        }

        $this->info("Successfully auto-clocked out {$count} employees.");
        Log::info("Auto Clockout Command finished. Processed {$count} records.");
    }
}
