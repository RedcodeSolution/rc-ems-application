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
}
