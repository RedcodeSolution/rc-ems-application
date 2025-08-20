<?php

namespace App\Console\Commands;

use App\Models\Meeting;
use Illuminate\Console\Command;
use Carbon\Carbon;

class GenerateDailyMeeting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'meeting:generate-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate daily stand-up meeting for today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        // Check if it's a weekday (Monday = 1, Friday = 5)
        if ($today->isWeekend()) {
            $this->info('Today is weekend. No daily stand-up meeting needed.');
            return 0;
        }

        // Check if meetings already exist for today
        $existingMeetings = Meeting::where('meeting_date', $today)
            ->where('type', 'daily_standup')
            ->get();

        if ($existingMeetings->count() >= 2) {
            $this->info('Daily stand-up meetings for today already exist.');
            foreach ($existingMeetings as $meeting) {
                $this->info("Meeting: {$meeting->title} - {$meeting->getFormattedTime()} - {$meeting->meeting_link}");
            }
            return 0;
        }

        // Create today's meetings (morning and evening)
        $meeting = Meeting::createDailyStandup();

        $this->info('Daily stand-up meetings generated successfully!');
        $this->info("Meeting Title: {$meeting->title}");
        $this->info("Meeting Date: {$meeting->meeting_date->format('Y-m-d')}");
        $this->info("Meeting Time: {$meeting->getFormattedTime()}");
        $this->info("Meeting Link: {$meeting->meeting_link}");

        // Show all meetings for today
        $allMeetings = Meeting::getTodayMeetings();
        if ($allMeetings->count() > 1) {
            $this->info("\nAll meetings for today:");
            foreach ($allMeetings as $meeting) {
                $this->info("- {$meeting->title}: {$meeting->getFormattedTime()} - {$meeting->meeting_link}");
            }
        }

        return 0;
    }
} 