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
            // Validate and authenticate the user
            $request->authenticate();

            // Regenerate the session to prevent session fixation attacks
            $request->session()->regenerate();


            // Redirect based on user role
            $user = Auth::user();

            $user->last_login_at = now();
            $user->save();

            if ($user->role === 'admin') {

                return redirect()->intended(route('admin.dashboard'));
            } elseif ($user->role === 'employee') {
                EmployeeActivity::create([
                    'employee_id' => $user->employee_id,
                    'type'        => 'system',
                    'icon'        => 'sign-in-alt',
                    'action'      => 'Logged In',
                    'details'     => 'Logged in at ' . Carbon::now()->format('h:i A'),
                ]);
                return redirect()->intended(route('employee.dashboard'));
            } elseif ($user->role === 'super_admin') {
                return redirect()->intended(route('super_admin.dashboard'));
            }

            return redirect()->intended(route('dashboard'));
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirect back to the login page with an error message
            return redirect()->route('login.form')->withErrors(['login' => 'Invalid credentials. Please try again.']);
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user && $user->role === 'employee' && $user->employee_id) {
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

        return redirect()->route('login.form');
    }
}
