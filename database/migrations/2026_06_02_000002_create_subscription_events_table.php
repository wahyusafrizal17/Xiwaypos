<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subscription_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('from_status', 32)->nullable();
            $table->string('to_status', 32);
            $table->foreignId('actor_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->index(['subscription_id', 'created_at']);
        });

        Schema::create('trial_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('days_remaining');
            $table->string('channel', 32)->default('email');
            $table->timestamp('sent_at');
            $table->timestamps();

            $table->unique(['tenant_id', 'days_remaining', 'channel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trial_notifications');
        Schema::dropIfExists('subscription_events');
    }
};
