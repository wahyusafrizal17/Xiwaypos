<?php

return [

    'tenancy_enabled' => env('TENANCY_ENABLED', true),

    'tenancy_strict' => env('TENANCY_STRICT', true),

    'trial_days' => (int) env('XIWAY_TRIAL_DAYS', 14),

    'trial_plan_slug' => env('XIWAY_TRIAL_PLAN_SLUG', 'enterprise'),

    'trial_skip_grace' => env('XIWAY_TRIAL_SKIP_GRACE', true),

    'grace_days_after_trial' => (int) env('XIWAY_GRACE_DAYS_TRIAL', 3),

    'grace_days_payment' => (int) env('XIWAY_GRACE_DAYS_PAYMENT', 7),

    'platform_whatsapp' => env('XIWAY_SUPPORT_WHATSAPP', '6281234567890'),

    'track_site_visits' => env('XIWAY_TRACK_SITE_VISITS', true),

    'app_url' => env('APP_URL', 'http://localhost'),

    'app_login_url' => env('APP_LOGIN_URL'),

    'marketing_register_url' => env('APP_REGISTER_URL'),

    'marketing_domain' => env('MARKETING_DOMAIN'),

    'app_domain' => env('APP_DOMAIN'),

    'landing_on_root' => env('LANDING_ON_ROOT', true),

    'billing_routes' => [
        'billing.index',
        'billing.payment-proof.store',
        'upgrade.index',
        'upgrade.contact',
        'onboarding.index',
        'onboarding.store',
        'onboarding.store.save',
        'onboarding.categories',
        'onboarding.categories.save',
        'onboarding.products',
        'onboarding.products.save',
        'onboarding.complete',
        'onboarding.finish',
        'tenant.select',
        'tenant.switch',
        'logout',
        'profile.edit',
        'profile.update',
        'profile.destroy',
    ],

    'onboarding_routes' => [
        'onboarding.index',
        'onboarding.store',
        'onboarding.store.save',
        'onboarding.categories',
        'onboarding.categories.save',
        'onboarding.products',
        'onboarding.products.save',
        'onboarding.complete',
        'onboarding.finish',
    ],

    'category_presets' => [
        'Minuman',
        'Makanan',
        'Snack',
        'Dessert',
    ],

    'product_presets' => [
        ['nama_produk' => 'Kopi Hitam', 'harga' => 15000, 'kategori' => 'Minuman'],
        ['nama_produk' => 'Es Teh Manis', 'harga' => 8000, 'kategori' => 'Minuman'],
        ['nama_produk' => 'Nasi Goreng', 'harga' => 25000, 'kategori' => 'Makanan'],
    ],

    'yearly_billing_free_months' => (int) env('XIWAY_YEARLY_FREE_MONTHS', 2),

    'billing_bank' => [
        'bank' => env('XIWAY_BILLING_BANK', 'BCA'),
        'account_number' => env('XIWAY_BILLING_ACCOUNT', '1234567890'),
        'account_name' => env('XIWAY_BILLING_ACCOUNT_NAME', 'PT Xiway POS'),
    ],

];
