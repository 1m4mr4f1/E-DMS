<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'folder_id',
        'title',
        'description',
        'file_path',
        'mime_type',
        'file_size',
        'uploaded_by',
        'division_id',
        'visibility',
        'expires_at',
        'version',
    ];

    // Relasi ke User yang mengunggah
    public function creator()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Relasi ke Divisi
    public function divisionRelation()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function getDivisionAttribute(): ?string
    {
        return $this->divisionRelation?->name;
    }

    public function getVisibilityBadgeClassAttribute(): string
    {
        return match($this->visibility) {
            'company_wide' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'division_only' => 'bg-slate-50 text-slate-600 border-slate-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    }

    /**
     * LOGIC: Hak Hapus
     * Hanya pemilik asli dokumen yang bisa menghapus
     */
    public function getIsOwnerAttribute(): bool
    {
        return auth()->check() && auth()->id() === $this->uploaded_by;
    }
}