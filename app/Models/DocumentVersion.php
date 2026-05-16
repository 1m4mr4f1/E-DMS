<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    protected $fillable = [
        'document_id',
        'version_number',
        'file_path',
        'file_original_name',
        'file_size_bytes',
        'file_mime_type',
        'uploaded_by',
        'label_snapshot',
        'visibility_snapshot',
        'is_current',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
