<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;

    Route::get('/', function () {
        return view('welcome');
});

    //LOGIN ROUTES
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    // LOGOUT ROUTE
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // DASHBOARD ROUTE
    Route::get('/dashboard', function () {
    return view('dashboard');
    })->middleware(['auth'])->name('dashboard');

