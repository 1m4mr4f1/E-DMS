<?php

namespace App\Notifications;

use App\Models\Document;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class DocumentSharedNotification extends Notification
{
    use Queueable;

    protected $document;
    protected $sharer;
    protected $permission;

    public function __construct(Document $document, User $sharer, string $permission)
    {
        $this->document = $document;
        $this->sharer = $sharer;
        $this->permission = $permission;
    }

    // Menginstruksikan Laravel untuk menyimpan notifikasi ini HANYA ke Database
    public function via($notifiable): array
    {
        return ['database'];
    }

    // Merangkai isi pesan yang akan disimpan ke database
    public function toArray($notifiable): array
    {
        $permissionText = $this->permission === 'edit' ? 'hak akses Edit' : 'hak akses View Only';

        return [
            'document_id' => $this->document->id,
            'title' => 'Akses Dokumen Baru',
            'message' => "{$this->sharer->full_name} telah membagikan dokumen '{$this->document->name}' ke divisi Anda dengan {$permissionText}.",
            'url' => route('documents.show', $this->document->id),
            'icon' => 'share' // Bisa digunakan untuk menampilkan ikon khusus di UI nanti
        ];
    }
}