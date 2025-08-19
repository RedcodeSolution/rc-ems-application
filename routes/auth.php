<?php

use Illuminate\Support\Facades\Route;

// Registration (frontend only)
Route::get('/register', function () {
    return view('auth.register');
})->middleware('guest')->name('register.form');

Route::post('/register', function () {
    // Dummy registration logic
    return redirect()->route('login.form');
})->name('register');

Route::get('/login', function () {
    return view('auth.login');
})->middleware('guest')->name('login.form');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $role = $request->input('role', 'employee');
    if ($role === 'admin') {
        return redirect('/admin/dashboard');
    } elseif ($role === 'super_admin') {
        return redirect('/super_admin/dashboard');
    } elseif ($role === 'employee') {
        return redirect('/employee/dashboard');
    }
    // Default fallback
    return redirect('/employee/dashboard');
})->name('login');


Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function () {

    return redirect()->route('password.request')->with('status', 'Password reset link sent!');
})->middleware('guest')->name('password.email');


Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function () {

    return redirect()->route('login.form')->with('status', 'Password reset!');
})->middleware('guest')->name('password.store');


Route::get('/verify-email/{id}/{hash}', function () {

    return redirect()->route('dashboard')->with('status', 'Email verified!');
})->middleware(['auth', 'signed', 'throttle:6,1'])->name('verification.verify');

Route::post('/email/verification-notification', function () {

    return back()->with('status', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


Route::post('/logout', function () {
    auth()->logout(); // Actually log out the user
    return redirect()->route('welcome');
})->middleware('auth')->name('logout');


Route::middleware('auth')->get('/api/notifications/me', function () {
    $notifications = [
        ['id' => 1, 'message' => 'Welcome to the system!', 'read' => false],
        ['id' => 2, 'message' => 'Your profile was updated.', 'read' => true]
    ];
    return response()->json($notifications);
});
