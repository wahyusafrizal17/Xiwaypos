<?php

namespace App\Support;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Str;

class StaffPasswords
{
    private const SETTING_KEY = 'staff_passwords';

    /** @return array<string, string> */
    public static function all(Tenant $tenant): array
    {
        $passwords = $tenant->setting(self::SETTING_KEY, []);

        return is_array($passwords) ? $passwords : [];
    }

    public static function get(Tenant $tenant, string $email): ?string
    {
        return self::all($tenant)[$email] ?? null;
    }

    /**
     * @return array<string, string>
     */
    public static function resolveForTenant(Tenant $tenant, ?array $sessionCredentials = null): array
    {
        self::importFromSession($tenant, $sessionCredentials);
        self::ensureForTenantUsers($tenant);

        return self::all($tenant);
    }

    /**
     * @param  array{admin?: array{email?: string, password?: string}, kasir?: array{email?: string, password?: string}, password?: string}|null  $credentials
     */
    public static function importFromSession(Tenant $tenant, ?array $credentials): bool
    {
        if ($credentials === null) {
            return false;
        }

        $passwords = self::all($tenant);
        $changed = false;

        foreach (['admin', 'kasir'] as $role) {
            $email = $credentials[$role]['email'] ?? null;
            $password = $credentials[$role]['password'] ?? $credentials['password'] ?? null;

            if (! is_string($email) || $email === '' || ! is_string($password) || $password === '') {
                continue;
            }

            if (($passwords[$email] ?? null) === $password) {
                continue;
            }

            $passwords[$email] = $password;
            $changed = true;
        }

        if ($changed) {
            self::sync($tenant, $passwords);
        }

        return $changed;
    }

    public static function ensureForTenantUsers(Tenant $tenant): bool
    {
        $tenant->loadMissing('users');

        $passwords = self::all($tenant);
        $missingUsers = $tenant->users->filter(
            fn (User $user) => empty($passwords[$user->email])
        );

        if ($missingUsers->isEmpty()) {
            return false;
        }

        $plain = Str::password(12, letters: true, numbers: true, symbols: false);

        foreach ($missingUsers as $user) {
            $user->forceFill(['password' => $plain])->save();
            $passwords[$user->email] = $plain;
        }

        self::sync($tenant, $passwords);

        return true;
    }

    public static function set(Tenant $tenant, string $email, string $password): void
    {
        $passwords = self::all($tenant);
        $passwords[$email] = $password;

        $tenant->setSetting(self::SETTING_KEY, $passwords, 'json');
    }

    public static function forget(Tenant $tenant, string $email): void
    {
        $passwords = self::all($tenant);

        if (! array_key_exists($email, $passwords)) {
            return;
        }

        unset($passwords[$email]);
        $tenant->setSetting(self::SETTING_KEY, $passwords, 'json');
    }

    public static function rename(Tenant $tenant, string $fromEmail, string $toEmail): void
    {
        $passwords = self::all($tenant);

        if (! array_key_exists($fromEmail, $passwords)) {
            return;
        }

        $passwords[$toEmail] = $passwords[$fromEmail];
        unset($passwords[$fromEmail]);

        $tenant->setSetting(self::SETTING_KEY, $passwords, 'json');
    }

    /** @param  array<string, string>  $passwordsByEmail */
    public static function sync(Tenant $tenant, array $passwordsByEmail): void
    {
        $tenant->setSetting(self::SETTING_KEY, $passwordsByEmail, 'json');
    }
}
