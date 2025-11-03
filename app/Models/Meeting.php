<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'meeting_link',
        'meeting_date',
        'start_time',
        'end_time',
        'status',
        'type',
        'is_recurring',
        'recurring_days'
    ];

    protected $casts = [
        'meeting_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'recurring_days' => 'array',
        'is_recurring' => 'boolean'
    ];

    public static function generateMeetingLink()
    {
        return config('app.default_meeting_link', env('DEFAULT_MEETING_LINK', 'https://meet.google.com/new'));
    }

    /**
     * Create daily stand-up meetings for today (morning and evening)
     */
    public static function createDailyStandup()
    {
        $today = Carbon::today();

        // Check if meetings already exist for today
        $existingMeetings = self::where('meeting_date', $today)
            ->where('type', 'daily_standup')
            ->get();

        if ($existingMeetings->count() >= 2) {
            return $existingMeetings->first(); // Return first meeting for compatibility
        }

        $meetings = [];
        $morningMeeting = self::where('meeting_date', $today)
            ->where('type', 'daily_standup')
            ->where('start_time', '09:00:00')
            ->first();

        if (!$morningMeeting) {
            $morningMeeting = self::create([
                'title' => 'Morning Stand-up Meeting',
                'description' => 'Morning daily stand-up meeting for all team members',
                'meeting_link' => self::generateMeetingLink(),
                'meeting_date' => $today,
                'start_time' => '09:00:00', // 9 AM
                'end_time' => '09:30:00',   // 9:30 AM
                'status' => 'scheduled',
                'type' => 'daily_standup',
                'is_recurring' => true,
                'recurring_days' => [1, 2, 3, 4, 5] // Monday to Friday
            ]);
            $meetings[] = $morningMeeting;
        }

        $eveningMeeting = self::where('meeting_date', $today)
            ->where('type', 'daily_standup')
            ->where('start_time', '17:00:00')
            ->first();

        if (!$eveningMeeting) {
            $eveningMeeting = self::create([
                'title' => 'Evening Stand-up Meeting',
                'description' => 'Evening daily stand-up meeting for all team members',
                'meeting_link' => self::generateMeetingLink(),
                'meeting_date' => $today,
                'start_time' => '17:00:00', // 5 PM
                'end_time' => '17:30:00',   // 5:30 PM
                'status' => 'scheduled',
                'type' => 'daily_standup',
                'is_recurring' => true,
                'recurring_days' => [1, 2, 3, 4, 5] // Monday to Friday
            ]);
            $meetings[] = $eveningMeeting;
        }

        return $meetings[0] ?? $morningMeeting ?? $eveningMeeting;
    }

    /**
     * Get today's meeting (for backward compatibility)
     */
    public static function getTodayMeeting()
    {
        return self::where('meeting_date', Carbon::today())
            ->where('type', 'daily_standup')
            ->first();
    }

    /**
     * Get all today's meetings (morning and evening)
     */
    public static function getTodayMeetings()
    {
        return self::where('meeting_date', Carbon::today())
            ->where('type', 'daily_standup')
            ->orderBy('start_time')
            ->get();
    }

    /**
     * Get upcoming meetings
     */
    public static function getUpcomingMeetings($limit = 5)
    {
        return self::where('meeting_date', '>=', Carbon::today())
            ->where('status', 'scheduled')
            ->orderBy('meeting_date')
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Check if meeting is currently ongoing
     */
    public function isOngoing()
    {
        $now = Carbon::now();
        $meetingStart = Carbon::parse($this->meeting_date->format('Y-m-d') . ' ' . $this->start_time);
        $meetingEnd = Carbon::parse($this->meeting_date->format('Y-m-d') . ' ' . $this->end_time);

        return $now->between($meetingStart, $meetingEnd);
    }

    /**
     * Get formatted meeting time
     */
    public function getFormattedTime()
    {
        // Handle both string and Carbon instances
        $startTime = is_string($this->start_time) ? Carbon::parse($this->start_time) : $this->start_time;
        $endTime = is_string($this->end_time) ? Carbon::parse($this->end_time) : $this->end_time;

        return $startTime->format('H:i') . ' - ' . $endTime->format('H:i');
    }

    /**
     * Get meeting duration in minutes
     */
    public function getDuration()
    {
        // Handle both string and Carbon instances
        $start = is_string($this->start_time) ? Carbon::parse($this->start_time) : $this->start_time;
        $end = is_string($this->end_time) ? Carbon::parse($this->end_time) : $this->end_time;

        return $start->diffInMinutes($end);
    }
}
