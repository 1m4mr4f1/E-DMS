<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Document extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'category_id',
        'division_id',
        'created_by',
        'current_version_id',
        'label',
        'visibility',
        'deleted_by',
        'scheduled_purge_at',
    ];

    protected $casts = [
        'scheduled_purge_at' => 'datetime',
    ];

    // Relasi ke User yang mengunggah
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Divisi
    public function divisionRelation()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function currentVersion()
    {
        return $this->belongsTo(DocumentVersion::class, 'current_version_id');
    }

    public function versions()
    {
        return $this->hasMany(DocumentVersion::class)->orderBy('version_number');
    }

    public function tags()
    {
        return $this->hasMany(DocumentTag::class);
    }

    public function getDivisionAttribute(): ?string
    {
        return $this->divisionRelation?->name;
    }

    public function getVisibilityBadgeClassAttribute(): string
    {
        return match($this->visibility) {
            'public' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'private' => 'bg-slate-50 text-slate-600 border-slate-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ($this->label) {
            'fix' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
            'draft' => 'bg-amber-50 text-amber-700 border-amber-100',
            default => 'bg-slate-50 text-slate-600 border-slate-100',
        };
    }

    public function getDocumentNumberAttribute(): string
    {
        return 'DOC-' . str_pad($this->id, 4, '0', STR_PAD_LEFT);
    }

    public function getFirstUploaderAttribute(): ?User
    {
        return $this->versions->first()?->uploader;
    }

    public function getEditorsAttribute()
    {
        return $this->versions->skip(1)
            ->map(fn ($version) => $version->uploader?->employee?->name ?? $version->uploader?->name)
            ->filter()
            ->unique()
            ->values();
    }

    /**
     * LOGIC: Hak Hapus
     * Hanya pemilik asli dokumen yang bisa menghapus
     */
    public function getIsOwnerAttribute(): bool
    {
        return auth()->check() && auth()->id() === $this->created_by;
    }
}