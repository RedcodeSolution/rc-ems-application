<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Carbon\Carbon;

class SuperAdminEmployeeAttendanceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->wantsJson()) {
            $attendances = Attendance::with('user')
                ->orderBy('date', 'desc')
                ->get()
                ->map(function ($a) {
                    $formattedHours = null;
                    if (!is_null($a->hours_worked)) {
                        $totalMinutes = round($a->hours_worked * 60);
                        $hours = floor($totalMinutes / 60);
                        $minutes = $totalMinutes % 60;
                        $formattedHours = sprintf("%dh %02dm", $hours, $minutes);
                    }

                    return [
                        'id' => $a->id,
                        'employee_name' => $a->user?->name ?? 'Unknown',
                        'check_in_time' => optional($a->check_in_time)->toDateTimeString(),
                        'check_out_time' => optional($a->check_out_time)->toDateTimeString(),
                        'status' => $a->status,
                        'hours_worked' => $formattedHours ?? '-',
                        'date' => optional($a->date)->toDateString(),
                    ];
                });

            return response()->json(['attendances' => $attendances]);
        }



        return view('super_admin.attendance.index');
    }

    // public function index()
    // {
    //     $attendances = Attendance::with('user')
    //         ->orderBy('date', 'desc')
    //         ->get()
    //         ->map(function ($a) {
    //             $formattedHours = null;
    //             if (!is_null($a->hours_worked)) {
    //                 $totalMinutes = round($a->hours_worked * 60);
    //                 $hours = floor($totalMinutes / 60);
    //                 $minutes = $totalMinutes % 60;
    //                 $formattedHours = sprintf("%dh %02dm", $hours, $minutes);
    //             }

    //             return [
    //                 'id' => $a->id,
    //                 'employee_name' => $a->user?->name ?? 'Unknown',
    //                 'check_in_time' => optional($a->check_in_time)->toDateTimeString(),
    //                 'check_out_time' => optional($a->check_out_time)->toDateTimeString(),
    //                 'status' => $a->status,
    //                 'hours_worked' => $formattedHours ?? '-',
    //                 'date' => optional($a->date)->toDateString(),
    //             ];
    //         });

    //     return view('super_admin.attendance.index', compact('attendances'));
    // }
}
