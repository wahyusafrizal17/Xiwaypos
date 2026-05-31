<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('whatsapp', 20)->nullable()->after('email');
            $table->boolean('is_platform_admin')->default(false)->after('role');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->string('phone', 20)->nullable()->after('slug');
            $table->timestamp('onboarding_completed_at')->nullable()->after('status');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->foreignId('activated_by_user_id')->nullable()->after('plan_id')->constrained('users')->nullOnDelete();
            $table->timestamp('activated_at')->nullable()->after('activated_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('activated_by_user_id');
            $table->dropColumn('activated_at');
        });

        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['phone', 'onboarding_completed_at']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['whatsapp', 'is_platform_admin']);
        });
    }
};
