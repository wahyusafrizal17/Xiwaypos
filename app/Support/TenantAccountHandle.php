<?php

namespace App\Support;

use Illuminate\Support\Str;

class TenantAccountHandle
{
    public static function fromStoreName(string $storeName): string
    {
        $handle = Str::lower(Str::slug($storeName, ''));

        if ($handle === '') {
            return 'toko';
        }

        return $handle;
    }

    public static function staffEmail(string $rolePrefix, string $handle): string
    {
        return $rolePrefix.$handle.'@gmail.com';
    }
}
