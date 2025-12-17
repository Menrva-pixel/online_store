<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CSLayer1Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Cek jika user adalah cs_layer1 atau admin
        if (!in_array($user->role, ['cs_layer1', 'admin'])) {
            abort(403, 'Unauthorized access. CS Layer 1 role required.');
        }

        return $next($request);
    }
}
