<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegistrationController::class, 'register'])->name('register');

Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])
    ->middleware('guest')
    ->name('register.form');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
    ->middleware('guest')
    ->name('login.form');

Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
    ->middleware('guest')
    ->name('password.email');

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/reset-password', [NewPasswordController::class, 'store'])
    ->middleware('guest')
    ->name('password.store');

Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)
    ->middleware(['auth', 'signed', 'throttle:6,1'])
    ->name('verification.verify');

Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
    ->middleware(['auth', 'throttle:6,1'])
    ->name('verification.send');

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');


// routes/web.php
Route::middleware('auth')->get('/api/notifications/me', [NotificationController::class, 'myNotifications']);
// Dashboard route moved to web.php - removed duplicate


//Route::middleware('guest')->group(function () {
//    Route::get('/register', [RegistrationController::class, 'showRegistrationForm'])->name('register.form');
//    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login.form');
//    Route::get('/forgot-password', fn () => view('auth.forgot-password'))->name('password.request');
//    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
//    Route::get('/reset-password/{token}', fn ($token) => view('auth.reset-password', ['token' => $token]))->name('password.reset');
//    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
//});
//
//Route::middleware('auth')->group(function () {
//    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
//    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])->name('verification.send');
//    Route::get('/verify-email/{id}/{hash}', VerifyEmailController::class)->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
//    Route::get('/api/notifications/me', [NotificationController::class, 'myNotifications']);
//    Route::get('/dashboard', fn () => view('dashboard'))->name('dashboard');
//});
