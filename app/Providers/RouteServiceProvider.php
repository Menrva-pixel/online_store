<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
        
        // Register middleware aliases
        Route::aliasMiddleware('admin', \App\Http\Middleware\AdminMiddleware::class);
        Route::aliasMiddleware('cs_layer1', \App\Http\Middleware\CSLayer1Middleware::class);
        Route::aliasMiddleware('cs_layer2', \App\Http\Middleware\CSLayer2Middleware::class);
    }
}