<?php

namespace Tests;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Database\Seeders\PlanSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * @return array{user: User, tenant: Tenant}
     */
    protected function createUserWithTenant(array $userAttributes = []): array
    {
        $this->seed(PlanSeeder::class);

        $user = User::factory()->create($userAttributes);

        $tenant = Tenant::create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant-'.uniqid(),
            'owner_user_id' => $user->id,
            'status' => Tenant::STATUS_ACTIVE,
        ]);

        $user->tenants()->attach($tenant->id, [
            'role' => $user->role ?? 'admin',
            'is_owner' => true,
        ]);

        $plan = Plan::query()->where('slug', 'business')->firstOrFail();

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_ACTIVE,
            'current_period_start' => now(),
            'current_period_end' => now()->addYear(),
        ]);

        return ['user' => $user, 'tenant' => $tenant];
    }

    protected function actingAsTenantUser(?User $user = null, ?Tenant $tenant = null): User
    {
        if ($user === null || $tenant === null) {
            ['user' => $user, 'tenant' => $tenant] = $this->createUserWithTenant();
        }

        $this->actingAs($user);
        $this->withSession(['tenant_id' => $tenant->id]);

        return $user;
    }
}
