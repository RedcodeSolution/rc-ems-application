<?php

namespace App\Http\Controllers;
use App\Models\Notification;

class SuperAdminController extends Controller
{
    public function notifications()
    {

        $notifications = Notification::orderBy('created_at', 'desc')->get()->map(function ($notification) {
            return [
                'id' => $notification->id,
                'title' => $notification->title,
                'message' => $notification->message,
                'type' => $notification->type,
                'priority' => $notification->priority,
                'read' => $notification->read,
                'timestamp' => $notification->created_at,
                'from' => $notification->from ?? 'System',
                'icon' => $notification->icon ?? 'fas fa-bell',
                'color' => $notification->color ?? 'blue',
            ];
        });


        $notificationStats = [
            'total' => $notifications->count(),
            'unread' => $notifications->where('read', false)->count(),
            'critical' => $notifications->where('priority', 'critical')->count(),
            'high' => $notifications->where('priority', 'high')->count(),
        ];

        return view('super_admin.notifications', [
            'notificationStats' => $notificationStats,
            'notifications' => $notifications,
        ]);
    }

}




