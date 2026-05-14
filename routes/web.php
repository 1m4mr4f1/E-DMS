<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DocumentController;

    Route::get('/', function () {
        return view('welcome');
});

    //LOGIN ROUTES
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');
    // LOGOUT ROUTE
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // DASHBOARD ROUTE
    Route::middleware(['auth'])->group(function () {
        
        // Semua Role bisa akses Dashboard
        Route::get('/dashboard', function () {
            return view('dashboard');
        })->name('dashboard');

        // DOCUMENT ROUTES
        Route::get('/documents', [DocumentController::class, 'index'])->name('documents.index');
        Route::get('/documents/create', [DocumentController::class, 'create'])->name('documents.create');
        Route::post('/documents', [DocumentController::class, 'store'])->name('documents.store');
        Route::delete('/documents/{document}', [DocumentController::class, 'destroy'])->name('documents.destroy');

        // Fitur Khusus admin & manager
        Route::middleware(['role:admin|manager'])->group(function () {
            Route::get('/approvals', function () { return "Halaman Approval"; });
        });

        // Fitur Khusus admin saja
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/audit-logs', function () { return "Halaman Audit Logs"; });
            Route::get('/users', function () { return "Halaman Users Management"; });
            Route::get('/settings', function () { return "Halaman System Settings"; });
        });
    });


    
