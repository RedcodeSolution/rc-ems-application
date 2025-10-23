<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attendance;
use App\Models\Employee;

class AdminAttendanceController extends Controller
{
    /**
     * Return all employees’ attendance records as JSON (for your AJAX front-end).
     */
    public function index(Request $request)
    {
        // Fetch attendance with related employee data
        $query = Attendance::with('employee')
            ->select('id', 'employee_id', 'attendance_date', 'check_in_time', 'check_out_time', 'status', 'total_hours')
            ->orderByDesc('attendance_date');

        // Optional filters (future-ready)
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }
        if ($request->has('search') && $request->search !== '') {
            $query->whereHas('employee', function ($q) use ($request) {
                $q->where('employee_name', 'like', '%' . $request->search . '%');
            });
        }

        $attendances = $query->get();

        // If the request expects JSON (like your JS fetch), return JSON
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'attendances' => $attendances->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'employee_name' => $a->employee->employee_name ?? 'Unknown',
                        'check_in_time' => $a->check_in_time,
                        'check_out_time' => $a->check_out_time,
                        'status' => $a->status,
                        'total_hours' => $a->total_hours,
                        'date' => $a->attendance_date,
                    ];
                }),
            ]);
        }

        // If normal HTTP (admin manually visits page), load Blade
        return view('admin.attendance.index');
    }
}
