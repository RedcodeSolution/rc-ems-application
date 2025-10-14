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

                $query->where('target', $user->role);

                $total = $query->count();
                $unread = (clone $query)->where('is_read', false)->count();
                $read = $total - $unread;

                $notifications = (clone $query)->with('user')->latest()->take(10)->get();

                $view->with([
                    'notifications' => $notifications,
                    'notificationStats' => [
                        'total'  => $total,
                        'unread' => $unread,
                        'read'   => $read,
                    ],
                ]);
            }
        });
    }
}
