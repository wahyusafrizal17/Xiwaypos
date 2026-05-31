<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OnboardingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_redirects_to_dashboard(): void
    {
        $response = $this->post(route('register'), [
            'tenant_name' => 'Warung Makan',
            'name' => 'Budi Owner',
            'whatsapp' => '081234567890',
        ]);

        $response->assertRedirect(route('dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('tenants', [
            'slug' => 'warung-makan',
        ]);
        $this->assertNotNull(Tenant::query()->where('slug', 'warung-makan')->value('onboarding_completed_at'));
    }

    public function test_incomplete_onboarding_redirects_from_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tenant = Tenant::create([
            'name' => 'Kafe Baru',
            'slug' => 'kafe-baru',
            'owner_user_id' => $admin->id,
            'status' => Tenant::STATUS_ACTIVE,
            'onboarding_completed_at' => null,
        ]);
        $tenant->users()->attach($admin->id, ['role' => 'admin', 'is_owner' => true]);

        $plan = Plan::query()->where('slug', 'starter')->firstOrFail();
        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_TRIALING,
            'trial_ends_at' => now()->addDays(14),
            'current_period_start' => now(),
            'current_period_end' => now()->addDays(14),
        ]);

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->get(route('dashboard'))
            ->assertRedirect(route('onboarding.store'));
    }

    public function test_onboarding_can_be_completed(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $tenant = Tenant::create([
            'name' => 'Toko Kopi',
            'slug' => 'toko-kopi',
            'owner_user_id' => $admin->id,
            'status' => Tenant::STATUS_ACTIVE,
            'onboarding_completed_at' => null,
        ]);
        $tenant->users()->attach($admin->id, ['role' => 'admin', 'is_owner' => true]);
        $tenant->setSetting('store_name', 'Toko Kopi');

        $plan = Plan::query()->where('slug', 'starter')->firstOrFail();
        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => Subscription::STATUS_TRIALING,
            'trial_ends_at' => now()->addDays(14),
            'current_period_start' => now(),
            'current_period_end' => now()->addDays(14),
        ]);

        $this->actingAs($admin)->withSession(['tenant_id' => $tenant->id]);

        $this->post(route('onboarding.store.save'), [
            'store_name' => 'Toko Kopi',
            'store_phone' => '021123456',
        ])->assertRedirect(route('onboarding.categories'));

        $this->post(route('onboarding.categories.save'), [
            'categories' => ['Minuman', 'Makanan'],
        ])->assertRedirect(route('onboarding.products'));

        $this->post(route('onboarding.products.save'), [
            'products' => [
                ['nama_produk' => 'Kopi', 'harga' => 15000, 'kategori' => 'Minuman'],
            ],
        ])->assertRedirect(route('onboarding.complete'));

        $this->post(route('onboarding.finish'))
            ->assertRedirect(route('cashier.index', ['first' => 1]));

        $tenant->refresh();
        $this->assertNotNull($tenant->onboarding_completed_at);
    }
}
