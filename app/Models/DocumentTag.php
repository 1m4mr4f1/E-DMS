<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentTag extends Model
{
    protected $table = 'document_tags';

    protected $fillable = [
        'document_id',
        'tag',
        'created_by',
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
