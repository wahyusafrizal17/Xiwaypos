<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $tenantTables = [
        'categories',
        'products',
        'order_addons',
        'transactions',
        'transaction_details',
        'expenses',
        'assets',
    ];

    public function up(): void
    {
        foreach ($this->tenantTables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('tenant_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        $this->backfillLegacyTenant();
    }

    public function down(): void
    {
        foreach (array_reverse($this->tenantTables) as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropConstrainedForeignId('tenant_id');
            });
        }
    }

    private function backfillLegacyTenant(): void
    {
        if (! Schema::hasTable('tenants')) {
            return;
        }

        $hasLegacyData = DB::table('categories')->whereNull('tenant_id')->exists()
            || DB::table('products')->whereNull('tenant_id')->exists()
            || DB::table('users')->exists();

        if (! $hasLegacyData) {
            return;
        }

        if (DB::table('tenants')->exists()) {
            $tenantId = (int) DB::table('tenants')->orderBy('id')->value('id');
        } else {
            $ownerId = DB::table('users')->where('role', 'admin')->orderBy('id')->value('id')
                ?? DB::table('users')->orderBy('id')->value('id');

            $tenantId = DB::table('tenants')->insertGetId([
                'name' => 'Xiway Demo',
                'slug' => 'xiway-demo',
                'owner_user_id' => $ownerId,
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        foreach ($this->tenantTables as $table) {
            DB::table($table)->whereNull('tenant_id')->update(['tenant_id' => $tenantId]);
        }

        if (Schema::hasTable('transaction_details') && Schema::hasTable('transactions')) {
            DB::statement('
                UPDATE transaction_details td
                INNER JOIN transactions t ON t.id = td.transaction_id
                SET td.tenant_id = t.tenant_id
                WHERE td.tenant_id IS NULL
            ');
        }

        $users = DB::table('users')->get(['id', 'role']);

        foreach ($users as $user) {
            $exists = DB::table('tenant_user')
                ->where('tenant_id', $tenantId)
                ->where('user_id', $user->id)
                ->exists();

            if ($exists) {
                continue;
            }

            DB::table('tenant_user')->insert([
                'tenant_id' => $tenantId,
                'user_id' => $user->id,
                'role' => $user->role ?? 'kasir',
                'is_owner' => ($user->role ?? '') === 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if (Schema::hasTable('subscriptions') && ! DB::table('subscriptions')->where('tenant_id', $tenantId)->exists()) {
            $planId = DB::table('plans')->where('slug', 'business')->value('id')
                ?? DB::table('plans')->orderBy('id')->value('id');

            if ($planId !== null) {
                DB::table('subscriptions')->insert([
                    'tenant_id' => $tenantId,
                    'plan_id' => $planId,
                    'status' => 'active',
                    'current_period_start' => now(),
                    'current_period_end' => now()->addYear(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
};
