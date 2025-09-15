<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Events;
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

        // Calculate statistics
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

    public function show($id)
    {
        // In a real application, this would fetch from database
        $event = [
            'id' => $id,
            'title' => 'Annual Company Meeting',
            'description' => 'Year-end company meeting to discuss achievements and future plans. This event will cover various topics including company performance, strategic planning, and employee recognition.',
            'date' => Carbon::now()->addDays(15),
            'time' => '09:00 AM',
            'location' => 'Main Conference Hall',
            'type' => 'company',
            'status' => 'upcoming',
            'attendees' => 150,
            'organizer' => 'HR Department',
            'agenda' => [
                'Opening Remarks (9:00 AM - 9:15 AM)',
                'Company Performance Review (9:15 AM - 10:00 AM)',
                'Strategic Planning Discussion (10:00 AM - 11:00 AM)',
                'Break (11:00 AM - 11:15 AM)',
                'Employee Recognition (11:15 AM - 12:00 PM)',
                'Q&A Session (12:00 PM - 12:30 PM)',
                'Closing Remarks (12:30 PM - 12:45 PM)'
            ],
            'requirements' => [
                'Business casual attire',
                'Bring notepad and pen',
                'Pre-registration required',
                'Lunch will be provided'
            ],
            'contact' => 'hr@company.com',
            'phone' => '+1 (555) 123-4567'
        ];

        return view('super_admin.events.show', compact('event'));
    }

    public function create()
    {
        return view('super_admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'description' => 'required|string',
            'date' => 'required|date',
            'time' => 'required',
            'duration' => 'nullable|numeric|min:0.5|max:24',
            'status' => 'required|string',
            'location' => 'required|string|max:255',
            'attendees' => 'nullable|integer|min:1',
            'organizer' => 'required|string|max:255',
            'agenda' => 'nullable|string',
            'requirements' => 'nullable|string',
            'contact_email' => 'nullable|email',
            'contact_phone' => 'nullable|string|max:50',
        ]);

        Events::create($validated);

        return redirect()->route('super_admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function update(Request $request, $id)
    {
        // In a real application, this would update database
        return redirect()->route('super_admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        // In a real application, this would delete from database
        return redirect()->route('super_admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
