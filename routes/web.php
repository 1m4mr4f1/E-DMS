<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\NotificationController;

// Halaman Utama / Landing Page
Route::get('/', function () {
    return view('welcome');
});

// Jalur Otentikasi (Login & Logout)
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

// Area Proteksi (Wajib Login)
Route::middleware(['auth'])->group(function () {
    
    // Dashboard Utama
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Manajemen Profil User
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

    // Manajemen Data User / Pengguna (CRUD)
    Route::resource('users', UserController::class)->except(['show']);

    // Manajemen Dokumen Core (CRUD, Download, & Restore)
    Route::get('documents/versions/{version}/download', [DocumentController::class, 'downloadVersion'])->name('documents.versions.download');
    Route::post('/documents/{id}/restore', [DocumentController::class, 'restore'])->name('documents.restore');
    Route::resource('documents', DocumentController::class);

    // Fitur Pembagian Dokumen (Document Sharing)
    Route::post('documents/{document}/share', [DocumentController::class, 'share'])->name('documents.share');
    Route::delete('documents/share/{id}', [DocumentController::class, 'revokeShare'])->name('documents.share.revoke');

    // Sistem Notifikasi Interaktif (PENGUATAN KEAMANAN: Wajib Menggunakan POST untuk Mencegah Serangan CSRF)
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll'])->name('notifications.read-all');
});