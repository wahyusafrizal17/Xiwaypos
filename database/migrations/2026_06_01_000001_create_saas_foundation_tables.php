<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('owner_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('status', 32)->default('active');
            $table->timestamps();
        });

        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('name');
            $table->json('limits');
            $table->unsignedInteger('price_monthly_idr')->default(0);
            $table->unsignedInteger('price_yearly_idr')->default(0);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('plan_id')->constrained()->restrictOnDelete();
            $table->string('status', 32)->default('trialing');
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('grace_ends_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();

            $table->index(['tenant_id', 'status']);
        });

        Schema::create('tenant_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->string('key');
            $table->text('value')->nullable();
            $table->string('type', 32)->default('string');
            $table->timestamps();

            $table->unique(['tenant_id', 'key']);
        });

        Schema::create('tenant_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('role', 32)->default('kasir');
            $table->boolean('is_owner')->default(false);
            $table->timestamps();

            $table->unique(['tenant_id', 'user_id']);
        });

        $this->seedPlans();
    }

    private function seedPlans(): void
    {
        $now = now();
        $plans = [
            [
                'slug' => 'starter',
                'name' => 'Starter',
                'limits' => json_encode([
                    'max_outlets' => 1,
                    'max_users' => 2,
                    'max_transactions_monthly' => 3000,
                    'features' => ['cashier', 'reports_basic', 'products', 'categories'],
                ]),
                'price_monthly_idr' => 99000,
                'price_yearly_idr' => 990000,
                'is_active' => true,
                'sort_order' => 1,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'business',
                'name' => 'Business',
                'limits' => json_encode([
                    'max_outlets' => 3,
                    'max_users' => 10,
                    'max_transactions_monthly' => 15000,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons'],
                ]),
                'price_monthly_idr' => 149000,
                'price_yearly_idr' => 1490000,
                'is_active' => true,
                'sort_order' => 2,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'slug' => 'enterprise',
                'name' => 'Enterprise',
                'limits' => json_encode([
                    'max_outlets' => -1,
                    'max_users' => -1,
                    'max_transactions_monthly' => -1,
                    'features' => ['cashier', 'reports_basic', 'reports_export', 'products', 'categories', 'expenses', 'assets', 'order_addons', 'api', 'custom_domain'],
                ]),
                'price_monthly_idr' => 399000,
                'price_yearly_idr' => 3990000,
                'is_active' => true,
                'sort_order' => 3,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        foreach ($plans as $plan) {
            if (! DB::table('plans')->where('slug', $plan['slug'])->exists()) {
                DB::table('plans')->insert($plan);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_settings');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');
    }
};
