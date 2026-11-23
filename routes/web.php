<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\SessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Storefront\ProductController as StorefrontProductController;
use App\Http\Controllers\Storefront\CategoryController as StorefrontCategoryController;
use App\Http\Controllers\Admin\HomeSliderController;
use App\Http\Controllers\Admin\HomeCategoryController;
use App\Http\Controllers\Auth\CustomerSessionController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Storefront\ReviewController as StorefrontReviewController;
use App\Http\Controllers\Storefront\CartController;
use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\OrderController as AccountOrderController;
use App\Http\Controllers\Storefront\CheckoutController;




// home page
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::redirect('/home', '/');

// product page
Route::get('/products/{product:slug}',[StorefrontProductController::class, 'show'])->name('products.show');

// category page
Route::get('/categories/{category:slug}',[StorefrontCategoryController::class, 'show'])->name('categories.show');

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

        // review section
        Route::get('/reviews', [ReviewController::class, 'index'])->name('reviews.index');
        Route::patch('/reviews/{review}/approve', [ReviewController::class, 'approve'])->name('reviews.approve');
        Route::patch('/reviews/{review}/reject', [ReviewController::class, 'reject'])->name('reviews.reject');

        // home slider section
        Route::get('/home-slider', [HomeSliderController::class, 'index'])->name('home-slider.index');
        Route::post('/home-slider/{product}', [HomeSliderController::class, 'store'])->name('home-slider.store');
        Route::patch('/home-slider/{product}/order', [HomeSliderController::class, 'updateOrder'])->name('home-slider.order');
        Route::delete('/home-slider/{product}', [HomeSliderController::class, 'destroy'])->name('home-slider.destroy');

        // home category section
        Route::get('/home-categories', [HomeCategoryController::class, 'index'])->name('home-categories.index');
        Route::post('/home-categories', [HomeCategoryController::class, 'store'])->name('home-categories.store');
        Route::put('/home-categories/{tile}', [HomeCategoryController::class, 'update'])->name('home-categories.update');
        Route::delete('/home-categories/{tile}', [HomeCategoryController::class, 'destroy'])->name('home-categories.destroy');

        // logout 
        Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
    });
});

// user auth
Route::middleware('guest')->group(function () {

    Route::get('/login', [CustomerSessionController::class, 'show'])->name('login');
    Route::post('/login', [CustomerSessionController::class, 'login'])->middleware('throttle:5,1')->name('login.store');
    Route::get('/register', [RegistrationController::class, 'show'])->name('register');
    Route::post('/register', [RegistrationController::class, 'register'])->middleware('throttle:5,1')->name('register.store');

});

Route::post('/logout', [CustomerSessionController::class, 'logout'])->middleware('auth')->name('logout');

// profile section
Route::prefix('account')->name('account.')->middleware(['auth', 'customer'])->group(function () {
        Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
        Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::put('/password', [ProfileController::class, 'updatePassword'])->name('password.update');
        Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
        Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
        Route::patch('/addresses/{address}/default', [AddressController::class, 'setDefault'])->name('addresses.default');
        Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
        Route::get('/orders', [AccountOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AccountOrderController::class, 'show'])->name('orders.show');
});

// checkout and payment section
Route::middleware(['auth', 'customer'])->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    // block is the way to block the same session to request
    Route::post('/checkout/pay', [CheckoutController::class, 'pay'])->block(10, 10)->name('checkout.pay');
});

// review section
Route::post('/products/{product:slug}/reviews',[StorefrontReviewController::class, 'store'])->middleware(['auth', 'customer'])->name('products.reviews.store');

// cart section
Route::post('/cart/items/{product:slug}',[CartController::class, 'store'])->name('cart.items.store');
Route::patch('/cart/items/{product:slug}',[CartController::class, 'update'])->name('cart.items.update');
Route::delete('/cart/items/{product:slug}',[CartController::class, 'destroy'])->name('cart.items.destroy');
Route::delete('/cart',[CartController::class, 'clear'])->name('cart.clear');