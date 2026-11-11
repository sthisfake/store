<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\SessionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CategoryController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/home', function () {
    return view('home');
});

// admin routes group
Route::prefix('admin')->name('admin.')->group(function () {


    Route::middleware('guest')->group(function () {
        Route::get('/login', [SessionController::class, 'showLogin'])->name('showLogin');
        Route::post('/login', [SessionController::class, 'login'])->name('login');
    });

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

        // logout 
        Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
    });
});