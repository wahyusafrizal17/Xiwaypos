<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Support\PlanPricing;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'slug' => 'starter',
                'name' => 'Starter',
                'limits' => [
                    'max_outlets' => 1,
                    'max_users' => 2,
                    'max_transactions_monthly' => 2000,
                    'features' => ['cashier', 'reports_basic', 'products', 'categories'],
                ],
                'price_monthly_idr' => 129000,
                'sort_order' => 1,
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'limits' => [
                    'max_outlets' => 3,
                    'max_users' => 10,
                    'max_transactions_monthly' => 15000,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons'],
                ],
                'price_monthly_idr' => 249000,
                'sort_order' => 2,
            ],
            [
                'slug' => 'enterprise',
                'name' => 'Enterprise',
                'limits' => [
                    'max_outlets' => -1,
                    'max_users' => -1,
                    'max_transactions_monthly' => -1,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons', 'api', 'custom_domain'],
                ],
                'price_monthly_idr' => 549000,
                'sort_order' => 3,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['slug' => $plan['slug']],
                array_merge($plan, [
                    'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly($plan['price_monthly_idr']),
                    'is_active' => true,
                ])
            );
        }
    }
}
