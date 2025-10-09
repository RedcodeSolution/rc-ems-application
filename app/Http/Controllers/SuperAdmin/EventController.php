<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Events;
use App\Services\NotificationService;

class EventController extends Controller
{
    public function index()
    {
        $events = Events::all();

        $totalEvents = $events->count();
        $upcomingEvents = $events->where('status', 'upcoming')->count();
        $completedEvents = $events->where('status', 'completed')->count();
        $thisMonthEvents = $events->filter(function ($event) {
            return \Carbon\Carbon::parse($event->date)->isCurrentMonth();
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
        $event = Events::findOrFail($id);

        $agenda = $event->agenda ? explode("\n", $event->agenda) : [];
        $requirements = $event->requirements ? explode("\n", $event->requirements) : [];

        $eventData = [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'date' => \Carbon\Carbon::parse($event->date),
            'time' => $event->time,
            'location' => $event->location,
            'type' => $event->type,
            'status' => $event->status,
            'attendees' => $event->attendees,
            'organizer' => $event->organizer,
            'agenda' => $agenda,
            'requirements' => $requirements,
            'contact' => $event->contact_email,
            'phone' => $event->contact_phone,
        ];

        return view('super_admin.events.show', ['event' => $eventData]);
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

        $event = Events::create($validated);

        $notify = new NotificationService();
        $notify->notify(
            title: 'New Event Created',
            message: "Event '{$event->title}' has been created and scheduled on " . Carbon::parse($event->date)->format('M d, Y'),
            type: 'event',
            userId: null,
            target: 'super admin',
            referenceId: $event->id
        );

        return redirect()->route('super_admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit($id)
    {
        $event = Events::findOrFail($id);

        $agenda = $event->agenda ? explode("\n", $event->agenda) : [];
        $requirements = $event->requirements ? explode("\n", $event->requirements) : [];

        $eventData = [
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'date' => \Carbon\Carbon::parse($event->date),
            'time' => $event->time,
            'duration' => $event->duration,
            'location' => $event->location,
            'type' => $event->type,
            'status' => $event->status,
            'attendees' => $event->attendees,
            'organizer' => $event->organizer,
            'agenda' => $agenda,
            'requirements' => $requirements,
            'contact' => $event->contact_email,
            'phone' => $event->contact_phone,
        ];

        return view('super_admin.events.edit', ['event' => $eventData]);
    }

    public function update(Request $request, $id)
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

        $event = Events::findOrFail($id);
        $event->update($validated);

        $notify = new NotificationService();
        $notify->notify(
            title: 'Event Updated',
            message: "Event '{$event->title}' has been updated.",
            type: 'event',
            userId: null,
            target: 'super admin',
            referenceId: $event->id
        );

        return redirect()->route('super_admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy($id)
    {
        $event = Events::find($id);
        if ($event) {
            $title = $event->title;
            $event->delete();

            $notify = new NotificationService();
            $notify->notify(
                title: 'Event Cancelled',
                message: "Event '{$title}' has been deleted/cancelled.",
                type: 'event',
                userId: null,
                target: 'super admin',
                referenceId: $id
            );

            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false, 'error' => 'Event not found'], 404);
    }
}
