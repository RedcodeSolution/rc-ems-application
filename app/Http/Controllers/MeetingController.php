<?php

namespace App\Http\Controllers;

use App\Models\Meeting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeetingController extends Controller
{

    public function join(Meeting $meeting)
    {
        if ($meeting->status === 'scheduled') {
            $meeting->update(['status' => 'ongoing']);
        }

        return redirect()->away($meeting->meeting_link);
    }
}
