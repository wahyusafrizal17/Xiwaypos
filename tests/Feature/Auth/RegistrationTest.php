<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Support\StaffPasswords;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_new_users_can_register_with_auto_generated_accounts(): void
    {
        $response = $this->post('/register', [
            'tenant_name' => 'Xiway Stack',
            'name' => 'Wahyu Safrizal',
            'whatsapp' => '081318960576',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
        $response->assertSessionHas('registration_credentials');

        $this->assertDatabaseHas('users', [
            'email' => 'adminxiwaystack@gmail.com',
            'name' => 'Wahyu Safrizal',
            'role' => 'admin',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'kasirxiwaystack@gmail.com',
            'name' => 'Kasir Xiway Stack',
            'role' => 'kasir',
        ]);

        $admin = User::query()->where('email', 'adminxiwaystack@gmail.com')->firstOrFail();
        $kasir = User::query()->where('email', 'kasirxiwaystack@gmail.com')->firstOrFail();

        $this->assertTrue($admin->tenants()->exists());
        $this->assertTrue($kasir->tenants()->exists());

        $tenant = $admin->tenants()->firstOrFail();
        $storedPassword = StaffPasswords::get($tenant, 'adminxiwaystack@gmail.com');
        $this->assertNotNull($storedPassword);

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Akun admin & kasir sudah dibuat')
            ->assertSee('adminxiwaystack@gmail.com')
            ->assertSee('kasirxiwaystack@gmail.com');

        $this->post(route('registration-credentials.dismiss'))
            ->assertOk()
            ->assertSessionMissing('registration_credentials');

        $this->get(route('dashboard'))
            ->assertOk()
            ->assertDontSee('Akun admin & kasir sudah dibuat')
            ->assertDontSee('Saya sudah menyimpan');

        $this->get(route('admin.users.index'))
            ->assertOk()
            ->assertSee($storedPassword);
    }
}
