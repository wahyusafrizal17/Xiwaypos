<?php

namespace App\Support;

class TenantStorage
{
    public static function productPath(?string $filename = null): string
    {
        $tenantId = TenantContext::id() ?? 'shared';
        $base = $tenantId.'/products';

        return $filename !== null ? $base.'/'.$filename : $base;
    }

    public static function productUrl(?string $path): ?string
    {
        if ($path === null || $path === '') {
            return null;
        }

        return asset('uploads/'.$path);
    }

    public static function billingProofPath(?string $filename = null): string
    {
        $tenantId = TenantContext::id() ?? 'shared';
        $base = $tenantId.'/billing/proofs';

        return $filename !== null ? $base.'/'.$filename : $base;
    }

    public static function billingProofUrl(?string $path): ?string
    {
        return self::productUrl($path);
    }
}
