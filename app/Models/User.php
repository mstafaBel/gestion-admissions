<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_SURVEILLANT = 'surveillant_general';
    public const ROLE_SECRETAIRE = 'secretaire';

    public const ROLES = [
        self::ROLE_ADMIN => 'Administrateur',
        self::ROLE_SURVEILLANT => 'Surveillant général',
        self::ROLE_SECRETAIRE => 'Secrétaire',
    ];

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'service_id',
        'telephone',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isSurveillant(): bool
    {
        return $this->role === self::ROLE_SURVEILLANT;
    }

    public function isSecretaire(): bool
    {
        return $this->role === self::ROLE_SECRETAIRE;
    }

    public function getRoleLibelleAttribute(): string
    {
        return self::ROLES[$this->role] ?? $this->role;
    }
}
