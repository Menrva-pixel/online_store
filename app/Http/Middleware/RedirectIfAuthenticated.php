<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
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
                $user = Auth::user();
                
                // Redirect berdasarkan role
                if ($user->isAdmin()) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->isCSLayer1()) {
                    return redirect()->route('cs1.dashboard');
                } elseif ($user->isCSLayer2()) {
                    return redirect()->route('cs2.dashboard');
                } else {
                    return redirect()->route('home');
                }
            }
        }

        return $next($request);
    }
}