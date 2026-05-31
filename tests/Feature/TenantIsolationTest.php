<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_access_other_tenant_product_via_kasir_scope(): void
    {
        $this->seed();

        $tenantA = Tenant::query()->where('slug', 'xiway-demo')->firstOrFail();
        $tenantB = Tenant::create([
            'name' => 'Toko Lain',
            'slug' => 'toko-lain',
            'status' => Tenant::STATUS_ACTIVE,
            'onboarding_completed_at' => now(),
        ]);

        TenantContext::set($tenantB);
        $categoryB = Category::create(['nama_kategori' => 'Minuman']);
        Product::create([
            'nama_produk' => 'Produk Tenant B',
            'harga' => 10000,
            'kategori_id' => $categoryB->id,
        ]);
        TenantContext::clear();

        $admin = User::query()->where('email', 'admin@xiwaypos.test')->firstOrFail();

        $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenantA->id])
            ->get(route('cashier.index'))
            ->assertOk()
            ->assertDontSee('Produk Tenant B');
    }

    public function test_tenant_signup_creates_trial_subscription(): void
    {
        $response = $this->post(route('register'), [
            'tenant_name' => 'Kopi Senja',
            'name' => 'Owner Kopi',
            'whatsapp' => '081234567893',
        ]);

        $response->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('tenants', ['slug' => 'kopi-senja']);
        $this->assertDatabaseHas('users', ['email' => 'adminkopisenja@gmail.com']);
        $this->assertDatabaseHas('subscriptions', ['status' => 'trialing']);

        $enterprise = \App\Models\Plan::query()->where('slug', 'enterprise')->firstOrFail();
        $tenant = Tenant::query()->where('slug', 'kopi-senja')->firstOrFail();
        $this->assertSame($enterprise->id, $tenant->subscription?->plan_id);
    }
}
