<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role as SpatieRole;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'division_id',
        'full_name',
        'email',
        'password',
        'role_id',
        'avatar_url',
        'is_active',
        'last_login_at',
        'remember_token',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function divisionRelation()
    {
        return $this->belongsTo(Division::class, 'division_id');
    }

    public function roleRelation()
    {
        return $this->belongsTo(SpatieRole::class, 'role_id');
    }

    public function getDivisionAttribute(): ?string
    {
        return $this->divisionRelation?->name;
    }
}