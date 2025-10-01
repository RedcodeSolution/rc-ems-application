<?php

namespace App\Services;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class NotificationService
{
    public function notify(string $title, string $message, string $type = null, $userId = null, $referenceId = null, $target = 'admin')
    {
        return Notification::create([
            'notifi_id' => 'NOTI-' . date('Y') . '-' . Str::random(6),
            'user_id'   => $userId ?? Auth::id(),
            'title'     => $title,
            'message'   => $message,
            'type'      => $type,
            'is_read'   => false,
            'reference_id'  => $referenceId,
            'reference_type' => $type,
            'target'    => $target,
        ]);
    }

    public function markAsRead(Notification $notification)
    {
        $notification->update(['is_read' => true]);
    }

    public function unread()
    {
        return Notification::where('is_read', false)->latest()->get();
    }

    public function all()
    {
        return Notification::latest()->get();
    }
}
