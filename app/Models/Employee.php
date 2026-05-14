<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nip',
        'name',
        'email',
        'phone',
        'religion_id',
        'division_id',
        'position_id',
        'status',
        'joined_at',
    ];

    public function religion()
    {
        return $this->belongsTo(Religion::class, 'religion_id');
    }

    public function division()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'employee_id');
    }
}
