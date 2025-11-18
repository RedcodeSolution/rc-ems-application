<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\EmployeeActivity;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Authenticate
            $request->authenticate();

            // Regenerate session
            $request->session()->regenerate();

            $user = Auth::user();
            $user->last_login_at = now();
            $user->save();

            // Flash success message
            session()->flash('success', 'Login successful! Welcome back, ' . $user->name);

            // ROLE BASED REDIRECT
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));

                case 'employee':
                    EmployeeActivity::create([
                        'employee_id' => $user->employee_id,
                        'type'        => 'system',
                        'icon'        => 'sign-in-alt',
                        'action'      => 'Logged In',
                        'details'     => 'Logged in at ' . now()->format('h:i A'),
                    ]);
                    return redirect()->intended(route('employee.dashboard'));

                case 'super_admin':
                    return redirect()->intended(route('super_admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {

            // Show error message
            return back()->withErrors([
                'login' => 'Incorrect email, password, or role.'
            ])->withInput();
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();

        if ($user && $user->role === 'employee') {
            EmployeeActivity::create([
                'employee_id' => $user->employee_id,
                'type'        => 'system',
                'icon'        => 'sign-out-alt',
                'action'      => 'Logged Out',
                'details'     => 'Logged out at ' . now()->format('h:i A'),
            ]);
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        session()->flash('success', 'You have logged out successfully.');
        return redirect()->route('login.form');
    }
}
