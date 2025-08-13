<?php
//
//namespace App\Http\Middleware;
//
//use Closure;
//use Illuminate\Support\Facades\Auth;
//
//class EnsureSuperAdmin
//{
//    public function handle($request, Closure $next)
//    {
//        if (Auth::user() && Auth::user()->role === 'super_admin') {
//            return $next($request);
//        }
//
//        return redirect()->route('home')->with('error', 'Access denied. Only super admins can perform this action.');
//    }
//}


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        // Logic to check if the user is a super admin
        if (!$request->user() || !$request->user()->isSuperAdmin()) {
            abort(403, 'Unauthorized action.');
        }

        return $next($request);
    }
}
