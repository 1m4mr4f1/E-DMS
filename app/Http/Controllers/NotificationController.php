<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function __construct(protected NotificationService $notificationService)
    {
    }

    // Mengubah parameter penerimaan untuk menangani request POST secara aman
    public function read(Request $request, $id): RedirectResponse
    {
        $userId = auth()->id();

        $notification = $this->notificationService->getForUser((int) $id, $userId);

        if (!$notification) {
            return redirect()->route('documents.index')->with('error', 'Notifikasi tidak ditemukan.');
        }

        $this->notificationService->markAsRead((int) $id, $userId);

        return redirect()->route('documents.show', $notification->document_id);
    }

    public function readAll(): RedirectResponse
    {
        $this->notificationService->markAllAsRead(auth()->id());

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai terbaca.');
    }
}