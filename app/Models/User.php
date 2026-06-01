<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'whatsapp',
        'password',
        'role',
        'is_platform_admin',
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
            'is_platform_admin' => 'boolean',
        ];
    }

    /** @return BelongsToMany<Tenant, $this> */
    public function tenants(): BelongsToMany
    {
        return $this->belongsToMany(Tenant::class, 'tenant_user')
            ->withPivot(['role', 'is_owner'])
            ->withTimestamps();
    }

    /** @return HasMany<Transaction, $this> */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function tenantRole(): ?string
    {
        $tenantId = session('tenant_id');

        if ($tenantId === null) {
            return $this->role;
        }

        $pivot = $this->tenants()->where('tenants.id', $tenantId)->first()?->pivot;

        return $pivot?->role ?? $this->role;
    }

    public function isAdmin(): bool
    {
        return $this->tenantRole() === 'admin';
    }

    public function isOwner(): bool
    {
        $tenantId = session('tenant_id');

        if ($tenantId === null) {
            return $this->role === 'admin';
        }

        return (bool) $this->tenants()
            ->where('tenants.id', $tenantId)
            ->wherePivot('is_owner', true)
            ->exists();
    }

    public function isPlatformAdmin(): bool
    {
        return (bool) $this->is_platform_admin;
    }

    public function homeUrl(): string
    {
        if ($this->isPlatformAdmin()) {
            return route('platform.tenants.index', absolute: false);
        }

        return $this->isAdmin()
            ? route('dashboard', absolute: false)
            : route('cashier.index', absolute: false);
    }
}
