<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CSLayer1Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'cs_layer1') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Hanya CS Layer 1 yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}