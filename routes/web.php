<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Auth\SessionController;
use App\Http\Controllers\Admin\DashboardController;

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
        Route::get('/', DashboardController::class)->name('dashboard');
        Route::post('/logout', [SessionController::class, 'logout'])->name('logout');
    });
});