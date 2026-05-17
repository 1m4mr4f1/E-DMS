<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    public function read($id): RedirectResponse
    {
        // Cari data notifikasi berdasarkan ID dan kepemilikan user yang login
        $notification = DB::table('notifications')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$notification) {
            return redirect()->route('documents.index')->with('error', 'Notifikasi tidak ditemukan.');
        }

        // Jalankan update status baca sesuai kolom is_read milikmu
        DB::table('notifications')
            ->where('id', $id)
            ->update([
                'is_read' => true,
                'updated_at' => now()
            ]);

        // Arahkan langsung ke halaman detail dokumen menggunakan document_id dari tabelmu
        return redirect()->route('documents.show', $notification->document_id);
    }

    public function readAll(): RedirectResponse
    {
        // Tandai semua notifikasi milik user yang bersangkutan menjadi true
        DB::table('notifications')
            ->where('user_id', auth()->id())
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'updated_at' => now()
            ]);

        return back()->with('success', 'Semua notifikasi berhasil ditandai sebagai terbaca.');
    }
}