<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable, HasApiTokens;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nohp',
        'role',
        'is_it',
    ];

    protected $hidden = [
        'password',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_it' => 'boolean',
        ];
    }

    public function siswa(): HasOne
    {
        return $this->hasOne(Siswa::class);
    }

    public function guru(): HasOne
    {
        return $this->hasOne(Guru::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }

    public function hasPermission(string $menuKey): bool
    {
        if ($this->is_it) {
            return true;
        }

        return $this->permissions()
            ->where('menu_key', $menuKey)
            ->where('is_active', true)
            ->exists();
    }

    public function getActivePermissions(): array
    {
        if ($this->is_it) {
            return [];
        }

        return $this->permissions()
            ->where('is_active', true)
            ->pluck('menu_key')
            ->toArray();
    }
}
