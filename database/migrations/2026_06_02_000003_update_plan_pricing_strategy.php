<?php

use App\Support\PlanPricing;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('plans')) {
            return;
        }

        $updates = [
            'starter' => [
                'price_monthly_idr' => 129000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(129000),
                'limits' => json_encode([
                    'max_outlets' => 1,
                    'max_users' => 2,
                    'max_transactions_monthly' => 2000,
                    'features' => ['cashier', 'reports_basic', 'products', 'categories'],
                ]),
            ],
            'business' => [
                'price_monthly_idr' => 249000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(249000),
                'limits' => json_encode([
                    'max_outlets' => 3,
                    'max_users' => 10,
                    'max_transactions_monthly' => 15000,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons'],
                ]),
            ],
            'enterprise' => [
                'price_monthly_idr' => 549000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(549000),
                'limits' => json_encode([
                    'max_outlets' => -1,
                    'max_users' => -1,
                    'max_transactions_monthly' => -1,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons', 'api', 'custom_domain'],
                ]),
            ],
        ];

        foreach ($updates as $slug => $data) {
            DB::table('plans')->where('slug', $slug)->update(array_merge($data, [
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('plans')) {
            return;
        }

        $reverts = [
            'starter' => [
                'price_monthly_idr' => 99000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(99000),
                'limits' => json_encode([
                    'max_outlets' => 1,
                    'max_users' => 2,
                    'max_transactions_monthly' => 3000,
                    'features' => ['cashier', 'reports_basic', 'products', 'categories'],
                ]),
            ],
            'business' => [
                'price_monthly_idr' => 149000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(149000),
            ],
            'enterprise' => [
                'price_monthly_idr' => 399000,
                'price_yearly_idr' => PlanPricing::yearlyPriceFromMonthly(399000),
            ],
        ];

        foreach ($reverts as $slug => $data) {
            DB::table('plans')->where('slug', $slug)->update(array_merge($data, [
                'updated_at' => now(),
            ]));
        }
    }
};
