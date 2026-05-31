<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('plans')) {
            return;
        }

        DB::table('plans')->where('slug', 'business')->update([
            'price_monthly_idr' => 149000,
            'price_yearly_idr' => 1490000,
            'updated_at' => now(),
        ]);

        DB::table('plans')->where('slug', 'enterprise')->update([
            'price_monthly_idr' => 399000,
            'price_yearly_idr' => 3990000,
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('plans')) {
            return;
        }

        DB::table('plans')->where('slug', 'business')->update([
            'price_monthly_idr' => 249000,
            'price_yearly_idr' => 2490000,
            'updated_at' => now(),
        ]);

        DB::table('plans')->where('slug', 'enterprise')->update([
            'price_monthly_idr' => 799000,
            'price_yearly_idr' => 7990000,
            'updated_at' => now(),
        ]);
    }
};
