<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PlanFeatureEnforcementTest extends TestCase
{
    use RefreshDatabase;

    public function test_starter_plan_blocks_expenses_route(): void
    {
        $this->seed();

        $tenant = Tenant::query()->where('slug', 'xiway-demo')->firstOrFail();
        $admin = User::query()->where('email', 'admin@xiwaypos.test')->firstOrFail();

        $starter = Plan::query()->where('slug', 'starter')->firstOrFail();
        $tenant->subscription?->update(['plan_id' => $starter->id]);

        TenantContext::set($tenant);

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('admin.expenses.index'))
            ->assertRedirect(route('billing.index'))
            ->assertSessionHas('error');
    }

    public function test_business_plan_allows_expenses_route(): void
    {
        $this->seed();

        $tenant = Tenant::query()->where('slug', 'xiway-demo')->firstOrFail();
        $admin = User::query()->where('email', 'admin@xiwaypos.test')->firstOrFail();

        $business = Plan::query()->where('slug', 'business')->firstOrFail();
        $tenant->subscription?->update(['plan_id' => $business->id]);

        TenantContext::set($tenant);

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('admin.expenses.index'))
            ->assertOk();
    }

    public function test_starter_plan_blocks_report_export(): void
    {
        $this->seed();

        $tenant = Tenant::query()->where('slug', 'xiway-demo')->firstOrFail();
        $admin = User::query()->where('email', 'admin@xiwaypos.test')->firstOrFail();

        $starter = Plan::query()->where('slug', 'starter')->firstOrFail();
        $tenant->subscription?->update(['plan_id' => $starter->id]);

        TenantContext::set($tenant);

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('admin.reports.export'))
            ->assertRedirect(route('billing.index'));
    }
}
