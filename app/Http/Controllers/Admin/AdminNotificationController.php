<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminNotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $perPage = 5;
        $notifications = Notification::where('target', 'admin')
            ->latest()
            ->paginate($perPage);

        $totalCount = Notification::where('target', 'admin')->count();
        $unreadCount = Notification::where('target', 'admin')->where('is_read', false)->count();
        $readCount   = $totalCount - $unreadCount;
        $actionCount = Notification::where('target', 'admin')
            ->where('is_read', false)
            ->whereIn('type', ['employee', 'leave'])
            ->count();

        return view('admin.notifications.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'readCount',
            'actionCount'
        ));
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show($notifi_id)
    {
        $notify = Notification::findOrFail($notifi_id);
        return response()->json($notify);
    }

    public function markAsRead($id)
    {
        $notification = Notification::findOrFail($id);
        $notification->is_read = 1;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'notification' => $notification
        ]);
    }

    public function markAllAsRead()
    {
        Notification::where('target', 'admin')
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    public function latest()
    {
        $latestNotifications = Notification::where('target', 'admin')
            ->latest()
            ->take(3)
            ->get();

        return response()->json($latestNotifications);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $notification = Notification::find($id);

        if (!$notification) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->delete();

        return response()->json([
            'success' => true,
            'message' => 'Notification deleted successfully'
        ]);
    }
}
