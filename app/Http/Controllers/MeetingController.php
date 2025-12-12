<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{


    public function join(Meeting $meeting)
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to join the meeting.');
        }

        if ($user->role === 'admin' || $user->role === 'super_admin') {
            if ($meeting->status === 'scheduled') {
                $meeting->update(['status' => 'ongoing']);
            }

            return redirect()->away($meeting->meeting_link);
        }

        if ($meeting->status === 'ongoing') {
            return redirect()->away($meeting->meeting_link);
        }

        return back()->with('error', 'Meeting not started yet. Please wait for the admin to start it.');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $meeting = Meeting::findOrFail($id);
            $status = $request->input('status'); // 'completed' or 'cancelled' or 'ongoing'

            if (!in_array($status, ['ongoing', 'completed', 'cancelled'])) {
                return response()->json(['success' => false, 'message' => 'Invalid status'], 400);
            }

            $meeting->update(['status' => $status]);

            // Notify employees if cancelled or completed
            if (in_array($status, ['completed', 'cancelled'])) {
                $title = $status === 'completed' ? 'Meeting Ended' : 'Meeting Cancelled';
                $message = "The " . $meeting->title . " has been " . $status . ".";
                $icon = $status === 'completed' ? 'check-circle' : 'times-circle';
                $bg = $status === 'completed' ? 'linear-gradient(135deg, #10b981 0%, #059669 100%)' : 'linear-gradient(135deg, #ef4444 0%, #b91c1c 100%)';

                // Create a global notification for all employees
                $targets = ['employee', 'admin', 'super_admin'];

                foreach ($targets as $target) {
                    \App\Models\Notification::create([
                        'id' => \Illuminate\Support\Str::uuid(),
                        'type' => 'system',
                        'title' => $title,
                        'message' => $message,
                        'target' => $target, 
                        'is_read' => false,
                        'icon' => $icon,
                        'bg' => $bg
                    ]);
                }
            }

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
