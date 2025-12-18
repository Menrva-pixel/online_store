<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CSLayer1Controller;
use App\Http\Controllers\CSLayer2Controller;

// Public routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/product/{product}', [HomeController::class, 'showProduct'])->name('product.show');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    
    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});

// ========== AUTHENTICATED ROUTES ==========
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Cart routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::put('/cart/update/{cart}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('/cart/remove/{cart}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/clear', [CartController::class, 'clearCart'])->name('cart.clear');
    Route::get('/cart/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/cart/totals', [CartController::class, 'calculateTotals'])->name('cart.totals');
    
    // Checkout routes
    Route::get('/checkout', [CheckoutController::class, 'showCheckout'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');
    
    // Payment routes
    Route::get('/payment/{order}', [CheckoutController::class, 'showPayment'])->name('payment.show');
    Route::post('/payment/{order}/upload', [CheckoutController::class, 'uploadPayment'])->name('payment.upload');
    
    // Order routes (customer)
    Route::get('/orders', [CheckoutController::class, 'listOrders'])->name('orders.index');
    Route::get('/orders/{order}', [CheckoutController::class, 'showOrder'])->name('orders.show');
    Route::delete('/orders/{order}/cancel', [CheckoutController::class, 'cancelOrder'])->name('orders.cancel');
    
    // ========== ADMIN ROUTES ==========
    Route::prefix('admin')->name('admin.')->middleware(['auth', 'role.admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Product management
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        
        // Import products
        Route::get('/products/import', [AdminController::class, 'showImportForm'])->name('products.import');
        Route::post('/products/import', [AdminController::class, 'importProducts'])->name('products.import.submit');
        
        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
        
        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
    });
    
    // ========== CS LAYER 1 ROUTES ==========
    Route::prefix('cs1')->name('cs1.')->middleware('role.cs1')->group(function () { 
        // Dashboard
        Route::get('/dashboard', [CSLayer1Controller::class, 'dashboard'])->name('dashboard');
        
        // Payment verification
        Route::get('/payments/pending', [CSLayer1Controller::class, 'pendingPayments'])->name('payments.pending');
        Route::get('/payments/verified', [CSLayer1Controller::class, 'verifiedPayments'])->name('payments.verified');
        
        // Payment detail & actions
        Route::get('/payments/{paymentProof}', [CSLayer1Controller::class, 'showPayment'])->name('payments.show');
        Route::post('/payments/{paymentProof}/verify', [CSLayer1Controller::class, 'verifyPayment'])->name('payments.verify');
        Route::post('/payments/{paymentProof}/reject', [CSLayer1Controller::class, 'rejectPayment'])->name('payments.reject');
        
        // Proof download/view
        Route::get('/payments/{paymentProof}/download', [CSLayer1Controller::class, 'downloadProof'])->name('payments.download');
        Route::get('/payments/{paymentProof}/view', [CSLayer1Controller::class, 'viewProof'])->name('payments.view');
        
        // Orders waiting payment
        Route::get('/orders/pending', [CSLayer1Controller::class, 'pendingOrders'])->name('orders.pending');
    });
    
    // ========== CS LAYER 2 ROUTES ==========
    Route::prefix('cs2')->name('cs2.')->middleware('role.cs2')->group(function () { 
        // Dashboard
        Route::get('/dashboard', [CSLayer2Controller::class, 'dashboard'])->name('dashboard');
        
        // Order processing
        Route::get('/orders', [CSLayer2Controller::class, 'orders'])->name('orders.index');
        Route::get('/orders/{order}', [CSLayer2Controller::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{order}/process', [CSLayer2Controller::class, 'processOrder'])->name('orders.process');
        Route::post('/orders/{order}/ship', [CSLayer2Controller::class, 'shipOrder'])->name('orders.ship');
        Route::post('/orders/{order}/complete', [CSLayer2Controller::class, 'completeOrder'])->name('orders.complete');
        Route::post('/orders/{order}/cancel', [CSLayer2Controller::class, 'cancelOrder'])->name('orders.cancel');
        
        // Printing
        Route::get('/orders/{order}/print', [CSLayer2Controller::class, 'printPackingSlip'])->name('orders.print');
        Route::get('/orders/{order}/labels', [CSLayer2Controller::class, 'downloadShippingLabels'])->name('orders.labels');
    });
});

        // Product management
        Route::get('/products', [AdminController::class, 'products'])->name('products');
        Route::get('/products/create', [AdminController::class, 'createProduct'])->name('products.create');
        Route::post('/products', [AdminController::class, 'storeProduct'])->name('products.store');
        Route::get('/products/{product}/edit', [AdminController::class, 'editProduct'])->name('products.edit');
        Route::put('/products/{product}', [AdminController::class, 'updateProduct'])->name('products.update');
        Route::delete('/products/{product}', [AdminController::class, 'deleteProduct'])->name('products.delete');
        Route::get('/products/import', [AdminController::class, 'showImportForm'])->name('products.import');
        Route::post('/products/import', [AdminController::class, 'importProducts'])->name('products.import.submit');

        // User management
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users/{user}/role', [AdminController::class, 'updateUserRole'])->name('users.role');
        Route::delete('/users/{user}', [AdminController::class, 'destroyUser'])->name('users.destroy');

        // Order management
        Route::get('/orders', [AdminController::class, 'orders'])->name('orders');
        Route::get('/orders/{order}', [AdminController::class, 'showOrder'])->name('orders.show');
        Route::post('/orders/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('orders.status');
        // ========== TEST ROUTES ==========
        Route::get('/test-simple', function() {
            return 'Test Simple Route';
        });