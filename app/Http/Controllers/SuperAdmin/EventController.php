<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index()
    {
               $events = [
            [
                'id' => 1,
                'title' => 'Annual Company Meeting',
                'description' => 'Year-end company meeting to discuss achievements and future plans',
                'date' => Carbon::now()->addDays(15),
                'time' => '09:00 AM',
                'location' => 'Main Conference Hall',
                'type' => 'company',
                'status' => 'upcoming',
                'attendees' => 150,
                'organizer' => 'HR Department'
            ],
            [
                'id' => 2,
                'title' => 'Team Building Workshop',
                'description' => 'Interactive workshop to strengthen team collaboration and communication',
                'date' => Carbon::now()->addDays(8),
                'time' => '02:00 PM',
                'location' => 'Training Room A',
                'type' => 'workshop',
                'status' => 'upcoming',
                'attendees' => 45,
                'organizer' => 'Training Department'
            ],
            [
                'id' => 3,
                'title' => 'Product Launch Event',
                'description' => 'Launch of our new software product with demonstrations and Q&A',
                'date' => Carbon::now()->addDays(25),
                'time' => '06:00 PM',
                'location' => 'Grand Ballroom',
                'type' => 'product',
                'status' => 'upcoming',
                'attendees' => 200,
                'organizer' => 'Marketing Team'
            ],
            [
                'id' => 4,
                'title' => 'Client Presentation',
                'description' => 'Presentation to key clients about our latest services and solutions',
                'date' => Carbon::now()->subDays(3),
                'time' => '10:00 AM',
                'location' => 'Client Meeting Room',
                'type' => 'client',
                'status' => 'completed',
                'attendees' => 25,
                'organizer' => 'Sales Department'
            ],
            [
                'id' => 5,
                'title' => 'Employee Recognition Ceremony',
                'description' => 'Annual ceremony to recognize outstanding employee contributions',
                'date' => Carbon::now()->addDays(30),
                'time' => '07:00 PM',
                'location' => 'Auditorium',
                'type' => 'recognition',
                'status' => 'upcoming',
                'attendees' => 300,
                'organizer' => 'HR Department'
            ],
            [
                'id' => 6,
                'title' => 'Technology Conference',
                'description' => 'Industry conference showcasing latest technology trends and innovations',
                'date' => Carbon::now()->addDays(45),
                'time' => '09:00 AM',
                'location' => 'Convention Center',
                'type' => 'conference',
                'status' => 'upcoming',
                'attendees' => 500,
                'organizer' => 'IT Department'
            ]
        ];


        $totalEvents = count($events);
        $upcomingEvents = collect($events)->where('status', 'upcoming')->count();
        $completedEvents = collect($events)->where('status', 'completed')->count();
        $thisMonthEvents = collect($events)->filter(function($event) {
            return $event['date']->isCurrentMonth();
        })->count();

        $data = [
            'events' => $events,
            'totalEvents' => $totalEvents,
            'upcomingEvents' => $upcomingEvents,
            'completedEvents' => $completedEvents,
            'thisMonthEvents' => $thisMonthEvents
        ];

        return view('super_admin.events.index', $data);
    }
}
