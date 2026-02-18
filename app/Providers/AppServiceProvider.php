<?php

namespace App\Providers;

use App\Models\Notification;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.url') . "/reset-password/$token?email={$notifiable->getEmailForPasswordReset()}";
        });

        View::composer('*', function ($view) {
            $user = Auth::user();

            if ($user) {
                $query = Notification::query();

                if ($user->role === 'super_admin') {
                    $query->where('target', 'super admin');
                } elseif ($user->role === 'admin') {
                    $query->where('target', 'admin');
                } elseif ($user->role === 'employee') {
                    $query->where('target', 'employee');
                } else {
                    $query->where('target', $user->role);
                }

                $notifications = $query->with('user')->latest()->paginate(10);

                $notificationStats = [
                    'total'  => $query->count(),
                    'unread' => (clone $query)->where('is_read', false)->count(),
                    'read'   => (clone $query)->where('is_read', true)->count(),
                ];

                $view->with(compact('notifications', 'notificationStats'));
            }
        });
    }
}
