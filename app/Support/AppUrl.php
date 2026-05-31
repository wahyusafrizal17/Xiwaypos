<?php

namespace App\Support;

class AppUrl
{
    public static function appBase(): string
    {
        return rtrim(config('xiway.app_url', config('app.url')), '/');
    }

    public static function register(string $utmCampaign = 'landing'): string
    {
        $base = config('xiway.marketing_register_url') ?: self::appBase().'/register';

        return $base.(str_contains($base, '?') ? '&' : '?').http_build_query([
            'utm_source' => 'landing',
            'utm_campaign' => $utmCampaign,
        ]);
    }

    public static function login(): string
    {
        return config('xiway.app_login_url') ?: self::appBase().'/login';
    }
}
