<?php

namespace Tests\Unit;

use App\Models\Tenant;
use App\Models\User;
use App\Support\StaffPasswords;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StaffPasswordsTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_generates_missing_passwords_for_tenant_users(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'email' => 'admin@test.local']);
        $kasir = User::factory()->create(['role' => 'kasir', 'email' => 'kasir@test.local']);

        $tenant = Tenant::create([
            'name' => 'Toko Test',
            'slug' => 'toko-test',
            'owner_user_id' => $admin->id,
            'status' => Tenant::STATUS_ACTIVE,
            'onboarding_completed_at' => now(),
        ]);

        $tenant->users()->attach($admin->id, ['role' => 'admin', 'is_owner' => true]);
        $tenant->users()->attach($kasir->id, ['role' => 'kasir', 'is_owner' => false]);

        $passwords = StaffPasswords::resolveForTenant($tenant);

        $this->assertArrayHasKey('admin@test.local', $passwords);
        $this->assertArrayHasKey('kasir@test.local', $passwords);
        $this->assertSame($passwords['admin@test.local'], $passwords['kasir@test.local']);
    }
}
