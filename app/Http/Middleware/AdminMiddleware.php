<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        

        if (!in_array($user->role, ['admin', 'super_admin'])) {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        return $next($request);
    }
}