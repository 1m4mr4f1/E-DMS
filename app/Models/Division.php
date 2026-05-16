<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $table = 'divisions';

    protected $fillable = [
        'code',
        'name',
        'parent_division_id',
        'is_active',
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'division_id');
    }
}
