<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\SubscriptionPaymentRequest;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class SubscriptionPaymentRequestTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_admin_can_submit_payment_proof(): void
    {
        Storage::fake('uploads');

        [$tenant, $admin] = $this->seedTenantWithAdmin();
        $plan = Plan::query()->where('slug', 'business')->firstOrFail();

        TenantContext::set($tenant);

        $response = $this->actingAs($admin)
            ->withSession(['tenant_id' => $tenant->id])
            ->post(route('billing.payment-proof.store'), [
            'plan_id' => $plan->id,
            'billing_cycle' => 'monthly',
            'reference_number' => 'TRF-001',
            'note' => 'Transfer BCA',
            'proof' => UploadedFile::fake()->image('bukti.jpg'),
        ]);

        $response->assertRedirect(route('billing.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('subscription_payment_requests', [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => SubscriptionPaymentRequest::STATUS_PENDING,
            'reference_number' => 'TRF-001',
        ]);
    }

    public function test_platform_admin_can_approve_payment_request(): void
    {
        Storage::fake('uploads');

        [$tenant, $admin] = $this->seedTenantWithAdmin();
        $platformAdmin = User::factory()->create([
            'role' => 'admin',
            'is_platform_admin' => true,
        ]);
        $plan = Plan::query()->where('slug', 'business')->firstOrFail();
        $subscription = $tenant->subscription;

        $path = UploadedFile::fake()->image('bukti.jpg')->store($tenant->id.'/billing/proofs', 'uploads');

        $paymentRequest = SubscriptionPaymentRequest::create([
            'tenant_id' => $tenant->id,
            'subscription_id' => $subscription->id,
            'requested_by_user_id' => $admin->id,
            'plan_id' => $plan->id,
            'billing_cycle' => SubscriptionPaymentRequest::CYCLE_MONTHLY,
            'amount_idr' => $plan->price_monthly_idr,
            'proof_path' => $path,
            'status' => SubscriptionPaymentRequest::STATUS_PENDING,
        ]);

        $response = $this->actingAs($platformAdmin)
            ->post(route('platform.payment-requests.approve', $paymentRequest));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $paymentRequest->refresh();
        $subscription->refresh();

        $this->assertSame(SubscriptionPaymentRequest::STATUS_APPROVED, $paymentRequest->status);
        $this->assertSame(Subscription::STATUS_ACTIVE, $subscription->status);
        $this->assertSame($plan->id, $subscription->plan_id);
    }

    /** @return array{0: Tenant, 1: User} */
    private function seedTenantWithAdmin(): array
    {
        $this->seed();

        $tenant = Tenant::query()->where('slug', 'xiway-demo')->firstOrFail();
        $admin = User::query()->where('email', 'admin@xiwaypos.test')->firstOrFail();

        return [$tenant, $admin];
    }
}
