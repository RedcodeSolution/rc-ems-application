<?php

namespace App\Console;

use App\Http\Middleware\AdminOnly;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Generate daily stand-up meeting every weekday at 8:00 AM
        $schedule->command('meeting:generate-daily')
                 ->weekdays()
                 ->at('08:00')
                 ->withoutOverlapping()
                 ->appendOutputTo(storage_path('logs/meetings.log'));
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }




}
