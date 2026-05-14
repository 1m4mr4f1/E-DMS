<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 
        'prefix'
    ];

    /**
     * Relasi ke tabel documents
     * Sebuah kategori bisa dimiliki oleh banyak dokumen
     */
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}