<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
// dd($user->hasRole(1));
                if ($user->hasRole(3)) {
                    return redirect()->route('admin.dashboard');
                }

                if ($user->hasRole(2)) {
                    return redirect()->route('tutor.dashboard');
                }

                if ($user->hasRole(1)) {
                    return redirect()->route('student.dashboard');
                }

                // Default fallback
                return redirect('/home');
            }
        }

        return $next($request);
    }
}
