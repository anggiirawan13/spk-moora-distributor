<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                if (Auth::user()->is_admin != 1) {
                    return redirect()->route('distributor.index');
                } else {
                    return redirect()->route('dashboard.index');
                }
            }
        }

        return $next($request);
    }
}
