<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CSLayer2Middleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->role !== 'cs_layer2') {
            return redirect()->route('home')->with('error', 'Akses ditolak. Hanya CS Layer 2 yang dapat mengakses halaman ini.');
        }

        return $next($request);
    }
}