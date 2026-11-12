<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\SessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;




Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

// admin routes group
Route::prefix('admin')->name('admin.')->group(function () {

    // unprotected admin routes
    Route::middleware('guest')->group(function () {
        Route::get('/login', [SessionController::class, 'showLogin'])->name('showLogin');
        Route::post('/login', [SessionController::class, 'login'])->name('login');
    });

    // protected admin routes
    Route::middleware(['auth', 'admin'])->group(function () {
        // dashboard section
        Route::get('/', DashboardController::class)->name('dashboard');

        // category section
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // product section
        Route::get('/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/products/{product}/images/{image}/primary',[ProductController::class, 'setPrimaryImage'])->name('products.images.primary');
        Route::delete('/products/{product}/images/{image}',[ProductController::class, 'destroyImage'])->name('products.images.destroy');

        // customer section
        Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
        Route::get('/customers/{customer}', [CustomerController::class, 'show'])->name('customers.show');
        Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
        Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
        Route::patch('/customers/{customer}/status', [CustomerController::class, 'updateStatus'])->name('customers.status');
        
        // order section 
        Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');

        // logout 
        Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
    });
});