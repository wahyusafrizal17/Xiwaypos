<?php

namespace App\Support;

use App\Models\Tenant;

class TenantContext
{
    protected static ?Tenant $tenant = null;

    public static function set(?Tenant $tenant): void
    {
        static::$tenant = $tenant;
    }

    public static function setById(?int $tenantId): void
    {
        if ($tenantId === null) {
            static::$tenant = null;

            return;
        }

        static::$tenant = Tenant::query()->find($tenantId);
    }

    public static function get(): ?Tenant
    {
        return static::$tenant;
    }

    public static function id(): ?int
    {
        return static::$tenant?->id;
    }

    public static function hasTenant(): bool
    {
        return static::$tenant !== null;
    }

    public static function requireId(): int
    {
        $id = static::id();

        if ($id === null) {
            throw new \RuntimeException('Tenant context is not set.');
        }

        return $id;
    }

    public static function clear(): void
    {
        static::$tenant = null;
    }
}
