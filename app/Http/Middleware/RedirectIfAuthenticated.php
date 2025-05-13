<?php


namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            $user = Auth::user();

            // Redirect based on role
            if ($user->role === 'employee') {
                return redirect()->route('employee.profile');
            }

            if ($user->role === 'admin') {
                return redirect()->route('main.dashboard');
            }
        }

        return $next($request);
    }
}
