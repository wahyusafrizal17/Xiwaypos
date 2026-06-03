<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('marketing_funnel_events', function (Blueprint $table) {
            $table->id();
            $table->string('event', 32);
            $table->string('visitor_hash', 64)->nullable();
            $table->foreignId('tenant_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamp('created_at')->useCurrent();
            $table->index(['event', 'created_at']);
            $table->index(['visitor_hash', 'event', 'created_at']);
            $table->unique(['tenant_id', 'event'], 'marketing_funnel_tenant_event_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marketing_funnel_events');
    }
};
