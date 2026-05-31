<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Tenant extends Model
{
    public const STATUS_ACTIVE = 'active';

    public const STATUS_SUSPENDED = 'suspended';

    public const STATUS_ARCHIVED = 'archived';

    protected $fillable = [
        'name',
        'slug',
        'owner_user_id',
        'status',
        'phone',
        'onboarding_completed_at',
    ];

    protected function casts(): array
    {
        return [
            'onboarding_completed_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<User, $this> */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    /** @return BelongsToMany<User, $this> */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'tenant_user')
            ->withPivot(['role', 'is_owner'])
            ->withTimestamps();
    }

    /** @return HasOne<Subscription, $this> */
    public function subscription(): HasOne
    {
        return $this->hasOne(Subscription::class)->latestOfMany();
    }

    /** @return HasMany<TenantSetting, $this> */
    public function settings(): HasMany
    {
        return $this->hasMany(TenantSetting::class);
    }

    public function setting(string $key, mixed $default = null): mixed
    {
        $row = $this->settings()->where('key', $key)->first();

        if ($row === null) {
            return $default;
        }

        return match ($row->type) {
            'json' => json_decode($row->value, true),
            'boolean' => filter_var($row->value, FILTER_VALIDATE_BOOLEAN),
            'integer' => (int) $row->value,
            default => $row->value,
        };
    }

    public function setSetting(string $key, mixed $value, string $type = 'string'): void
    {
        $stored = match ($type) {
            'json' => json_encode($value),
            'boolean' => $value ? '1' : '0',
            default => (string) $value,
        };

        $this->settings()->updateOrCreate(
            ['key' => $key],
            ['value' => $stored, 'type' => $type]
        );
    }

    public function displayName(): string
    {
        return (string) ($this->setting('store_name', $this->name) ?: $this->name);
    }

    public function hasCompletedOnboarding(): bool
    {
        return $this->onboarding_completed_at !== null;
    }

    public function logoUrl(): ?string
    {
        $path = $this->setting('store_logo');

        return $path ? \App\Support\TenantStorage::productUrl($path) : null;
    }
}
