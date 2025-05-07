<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        $user = auth()->guard('employee')->user();

        if (!$user || $user->role !== $role) {
            if ($user && $user->role === 'employee') {
                return redirect()->route('employee.profile');
            }

            if ($user && $user->role === 'admin') {
                return redirect()->route('dashboard');
            }

            return redirect()->route('login');
        }

        return $next($request);
    }
}
