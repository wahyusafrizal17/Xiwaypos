<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categories', function (Blueprint $table) {
            $table->unique(['tenant_id', 'nama_kategori']);
        });

        Schema::table('order_addons', function (Blueprint $table) {
            $table->dropUnique(['kode']);
            $table->unique(['tenant_id', 'kode']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->index(['tenant_id', 'kategori_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index(['tenant_id', 'status', 'created_at']);
            $table->index(['tenant_id', 'created_at']);
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->index(['tenant_id', 'product_id']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->index(['tenant_id', 'tanggal']);
        });

        Schema::table('assets', function (Blueprint $table) {
            $table->index(['tenant_id', 'tanggal_perolehan']);
        });
    }

    public function down(): void
    {
        Schema::table('assets', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'tanggal_perolehan']);
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'tanggal']);
        });

        Schema::table('transaction_details', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'product_id']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'status', 'created_at']);
            $table->dropIndex(['tenant_id', 'created_at']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['tenant_id', 'kategori_id']);
        });

        Schema::table('order_addons', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'kode']);
            $table->unique(['kode']);
        });

        Schema::table('categories', function (Blueprint $table) {
            $table->dropUnique(['tenant_id', 'nama_kategori']);
        });
    }
};
