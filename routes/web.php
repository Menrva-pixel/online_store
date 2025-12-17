<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CSLayer1Controller;
use App\Http\Controllers\CSLayer2Controller;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/product/{product}', [HomeController::class, 'showProduct'])->name('product.show');

// Authentication
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');
    
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', function () {
        Auth::logout();
        return redirect('/');
    })->name('logout');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{cart}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    
    // Admin routes
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('admin.products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('admin.products.store');
        Route::get('/products/import', [AdminController::class, 'showImportForm'])->name('admin.products.import');
        Route::post('/products/import', [AdminController::class, 'importProducts'])->name('admin.products.import.submit');
    });
    
    // CS Layer 1 routes
    Route::middleware('cs_layer1')->prefix('cs1')->group(function () {
        Route::get('/dashboard', [CSLayer1Controller::class, 'dashboard'])->name('cs1.dashboard');
        Route::post('/payment/{paymentProof}/verify', [CSLayer1Controller::class, 'verifyPayment'])->name('cs1.payment.verify');
        Route::post('/payment/{paymentProof}/reject', [CSLayer1Controller::class, 'rejectPayment'])->name('cs1.payment.reject');
        Route::get('/orders', [CSLayer1Controller::class, 'pendingOrders'])->name('cs1.orders');
    });
    
    // CS Layer 2 routes
    Route::middleware('cs_layer2')->prefix('cs2')->group(function () {
        Route::get('/dashboard', [CSLayer2Controller::class, 'dashboard'])->name('cs2.dashboard');
        Route::post('/orders/{order}/process', [CSLayer2Controller::class, 'processOrder'])->name('cs2.order.process');
        Route::post('/orders/{order}/ship', [CSLayer2Controller::class, 'shipOrder'])->name('cs2.order.ship');
        Route::post('/orders/{order}/complete', [CSLayer2Controller::class, 'completeOrder'])->name('cs2.order.complete');
    });
});