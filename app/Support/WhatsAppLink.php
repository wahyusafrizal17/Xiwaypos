<?php

namespace App\Support;

class WhatsAppLink
{
    public static function supportUrl(?string $message = null): string
    {
        $phone = preg_replace('/\D+/', '', (string) config('xiway.platform_whatsapp', ''));
        $text = $message ?? self::defaultMessage();

        return 'https://wa.me/'.$phone.'?text='.rawurlencode($text);
    }

    public static function upgradeUrl(string $planName): string
    {
        $tenant = TenantContext::get();
        $user = auth()->user();

        $message = implode("\n", array_filter([
            'Halo Xiway POS, saya ingin berlangganan.',
            $tenant ? 'Toko: '.$tenant->displayName() : null,
            $user ? 'Email: '.$user->email : null,
            $user?->whatsapp ? 'WA: '.$user->whatsapp : null,
            'Paket: '.$planName,
        ]));

        return self::supportUrl($message);
    }

    public static function defaultMessage(): string
    {
        $tenant = TenantContext::get();
        $user = auth()->user();

        $parts = ['Halo Xiway POS, saya butuh bantuan.'];
        if ($tenant) {
            $parts[] = 'Toko: '.$tenant->displayName();
        }
        if ($user) {
            $parts[] = 'Email: '.$user->email;
        }

        return implode("\n", $parts);
    }
}
