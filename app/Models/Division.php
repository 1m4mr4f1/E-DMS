<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Division extends Model
{
    // Agar bisa mengisi data lewat Seeder atau Controller
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Relasi: Satu Divisi memiliki banyak Pegawai (One-to-Many)
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'division_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class, 'division_id');
    }
}