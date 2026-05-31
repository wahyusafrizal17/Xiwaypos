<?php

namespace Tests\Unit;

use App\Support\TenantAccountHandle;
use PHPUnit\Framework\TestCase;

class TenantAccountHandleTest extends TestCase
{
    public function test_it_builds_handle_from_store_name(): void
    {
        $this->assertSame('xiwaystack', TenantAccountHandle::fromStoreName('Xiway Stack'));
        $this->assertSame('kopisenja', TenantAccountHandle::fromStoreName('Kopi Senja'));
    }

    public function test_it_builds_staff_email(): void
    {
        $this->assertSame(
            'adminxiwaystack@gmail.com',
            TenantAccountHandle::staffEmail('admin', 'xiwaystack')
        );

        $this->assertSame(
            'kasirxiwaystack@gmail.com',
            TenantAccountHandle::staffEmail('kasir', 'xiwaystack')
        );
    }
}
