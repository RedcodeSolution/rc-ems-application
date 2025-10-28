<?php
namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use App\Models\Notification;
use Illuminate\Notifications\DatabaseNotification;

class EmployeeNotificationController extends Controller
{
    public function index()
    {
        try {

            if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
                $notifications = Notification::where('recipient_role', 'employee')->orderBy('created_at', 'desc')->get();
            } elseif (auth()->check()) {
                $notifications = auth()->user()->notifications()->orderBy('created_at', 'desc')->get()->map(function ($n) {
                    return (object)[
                        'notifi_id' => $n->id,
                        'title'     => $n->data['title'] ?? ($n->data['message'] ?? 'Notification'),
                        'message'   => $n->data['message'] ?? '',
                        'type'      => $n->data['type'] ?? 'system',
                        'priority'  => $n->data['priority'] ?? 'low',
                        'is_read'   => ($n->read_at !== null),
                        'timestamp' => $n->created_at ?? now(),
                        'icon'      => $n->data['icon'] ?? 'fas fa-bell',
                        'color'     => $n->data['color'] ?? 'gray',
                        'user'      => (object)['name' => $n->data['from'] ?? 'System'],
                    ];
                });
            } else {

                $notifications = collect([
                    (object)[
                        'notifi_id' => 'emp_001',
                        'title' => 'Welcome to the Employee Portal',
                        'message' => 'This is a welcome notification for employees.',
                        'type' => 'system',
                        'priority' => 'low',
                        'is_read' => false,
                        'timestamp' => now()->subHours(3),
                        'icon' => 'fas fa-bell',
                        'color' => 'blue',
                        'user' => (object)['name' => 'System']
                    ],
                    (object)[
                        'notifi_id' => 'emp_002',
                        'title' => 'New Policy Update',
                        'message' => 'Please review the updated policies in HR.',
                        'type' => 'hr',
                        'priority' => 'high',
                        'is_read' => true,
                        'timestamp' => now()->subDays(1),
                        'icon' => 'fas fa-file-alt',
                        'color' => 'orange',
                        'user' => (object)['name' => 'HR Dept']
                    ],
                ]);
            }

            $total = $notifications->count();
            $unread = $notifications->where('is_read', false)->count();
            $read = $total - $unread;

            $notificationStats = [
                'total' => $total,
                'unread' => $unread,
                'read' => $read,
            ];

            return view('employees.notification.index', compact('notifications', 'notificationStats'));
        } catch (\Exception $e) {
            Log::error('EmployeeNotificationController@index error: '.$e->getMessage());
            $notifications = collect();
            $notificationStats = ['total' => 0, 'unread' => 0, 'read' => 0];
            return view('employees.notification.index', compact('notifications', 'notificationStats'));
        }
    }

    public function show($notifi_id)
    {

        if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
            $notification = Notification::where('notifi_id', $notifi_id)->first();
            if (!$notification) {
                return abort(404);
            }
            return view('employees.notification.show', compact('notification'));
        }

        $dbNotif = DatabaseNotification::find($notifi_id);
        if ($dbNotif) {

            $notification = (object)[
                'notifi_id' => $dbNotif->id,
                'title'     => $dbNotif->data['title'] ?? ($dbNotif->data['message'] ?? 'Notification'),
                'message'   => $dbNotif->data['message'] ?? '',
                'type'      => $dbNotif->data['type'] ?? 'system',
                'priority'  => $dbNotif->data['priority'] ?? 'low',
                'is_read'   => ($dbNotif->read_at !== null),
                'timestamp' => $dbNotif->created_at ?? now(),
                'icon'      => $dbNotif->data['icon'] ?? 'fas fa-bell',
                'color'     => $dbNotif->data['color'] ?? 'gray',
                'user'      => (object)['name' => $dbNotif->data['from'] ?? 'System'],
            ];
            return view('employees.notification.show', compact('notification'));
        }

        return redirect()->route('employee.notifications')->with('info', 'Notification preview not available.');
    }

    public function markAsRead($id)
    {
        try {
            if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
                $notification = Notification::where('notifi_id', $id)->first();
                if ($notification) {
                    $notification->is_read = true;
                    $notification->save();
                    return response()->json(['success' => true]);
                }
                return response()->json(['success' => false], 404);
            }

            $dbNotif = DatabaseNotification::find($id);
            if ($dbNotif) {
                $dbNotif->markAsRead();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false], 404);
        } catch (\Exception $e) {
            Log::error('EmployeeNotificationController@markAsRead error: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function markAllAsRead(Request $request)
    {
        try {
            if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
                Notification::where('recipient_role', 'employee')->update(['is_read' => true]);
                return response()->json(['success' => true]);
            }

            if (auth()->check()) {
                foreach (auth()->user()->unreadNotifications as $n) {
                    $n->markAsRead();
                }
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false], 404);
        } catch (\Exception $e) {
            Log::error('EmployeeNotificationController@markAllAsRead error: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function destroy($id)
    {
        try {
            if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
                $notification = Notification::where('notifi_id', $id)->first();
                if ($notification) {
                    $notification->delete();
                    return response()->json(['success' => true]);
                }
                return response()->json(['success' => false], 404);
            }

            $dbNotif = DatabaseNotification::find($id);
            if ($dbNotif) {
                $dbNotif->delete();
                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false], 404);
        } catch (\Exception $e) {
            Log::error('EmployeeNotificationController@destroy error: '.$e->getMessage());
            return response()->json(['success' => false], 500);
        }
    }

    public function latest()
    {
        try {
            if (Schema::hasColumn('notifications', 'recipient_role') && class_exists(Notification::class)) {
                $latest = Notification::where('recipient_role', 'employee')->orderBy('created_at', 'desc')->limit(5)->get();
                return response()->json(['success' => true, 'data' => $latest]);
            }

            if (auth()->check()) {
                $latest = auth()->user()->notifications()->orderBy('created_at', 'desc')->limit(5)->get()->map(function ($n) {
                    return [
                        'notifi_id' => $n->id,
                        'title' => $n->data['title'] ?? ($n->data['message'] ?? 'Notification'),
                        'is_read' => ($n->read_at !== null),
                    ];
                });
                return response()->json(['success' => true, 'data' => $latest]);
            }

            return response()->json(['success' => true, 'data' => []]);
        } catch (\Exception $e) {
            Log::error('EmployeeNotificationController@latest error: '.$e->getMessage());
            return response()->json(['success' => true, 'data' => []]);
        }
    }
}
