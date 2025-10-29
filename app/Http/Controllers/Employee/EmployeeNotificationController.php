<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use App\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Auth;

class EmployeeNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $perPage = 5;

        $notifications = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->paginate($perPage);

        $totalCount = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })->count();

        $unreadCount = Notification::where('target', 'employee')
            ->where('is_read', false)
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })->count();

        $readCount = $totalCount - $unreadCount;

        $actionCount = Notification::where('target', 'employee')
            ->where('is_read', false)
            ->whereIn('type', ['admin', 'leave', 'project', 'task'])
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })->count();

        return view('employees.notification.index', compact(
            'notifications',
            'totalCount',
            'unreadCount',
            'readCount',
            'actionCount'
        ));
    }

    /**
     * Show a single notification
     */
    public function show($notifi_id)
    {
        $user = Auth::user();
        $notify = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->where('notifi_id', $notifi_id)
            ->firstOrFail();

        return response()->json($notify);
    }

    /**
     * Mark one notification as read
     */
    public function markAsRead($id)
    {
        $user = Auth::user();
        $notification = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->findOrFail($id);

        $notification->is_read = 1;
        $notification->save();

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read',
            'notification' => $notification
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        $user = Auth::user();
        Notification::where('target', 'employee')
            ->where('is_read', 0)
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->update(['is_read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read'
        ]);
    }

    /**
     * Get the latest 3 notifications
     */
    public function latest()
    {
        $user = Auth::user();
        $latestNotifications = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->latest()
            ->take(3)
            ->get();

        return response()->json($latestNotifications);
    }

    /**
     * Delete a notification
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $notification = Notification::where('target', 'employee')
            ->where(function ($query) use ($user) {
                $query->whereNull('user_id')
                    ->orWhere('user_id', $user->id);
            })
            ->find($id);

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
