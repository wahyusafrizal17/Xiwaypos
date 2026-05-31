<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use App\Support\StaffPasswords;
use App\Support\TenantAccountHandle;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantProvisioner
{
    /**
     * @param  array{name: string, tenant_name: string, whatsapp?: string|null}  $data
     * @return array{
     *     user: User,
     *     kasir: User,
     *     tenant: Tenant,
     *     credentials: array{
     *         password: string,
     *         admin: array{email: string, password: string},
     *         kasir: array{email: string, password: string}
     *     }
     * }
     */
    public function provision(array $data): array
    {
        return DB::transaction(function () use ($data) {
            $handle = TenantAccountHandle::fromStoreName($data['tenant_name']);
            $password = Str::password(12, letters: true, numbers: true, symbols: false);

            $adminEmail = $this->uniqueStaffEmail('admin', $handle);
            $kasirEmail = $this->uniqueStaffEmail('kasir', $handle);

            $admin = User::create([
                'name' => $data['name'],
                'email' => $adminEmail,
                'whatsapp' => $data['whatsapp'] ?? null,
                'password' => $password,
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);

            $kasir = User::create([
                'name' => 'Kasir '.$data['tenant_name'],
                'email' => $kasirEmail,
                'whatsapp' => $data['whatsapp'] ?? null,
                'password' => $password,
                'role' => 'kasir',
                'email_verified_at' => now(),
            ]);

            $slug = $this->uniqueSlug($data['tenant_name']);

            $tenant = Tenant::create([
                'name' => $data['tenant_name'],
                'slug' => $slug,
                'owner_user_id' => $admin->id,
                'phone' => $data['whatsapp'] ?? null,
                'status' => Tenant::STATUS_ACTIVE,
                'onboarding_completed_at' => now(),
            ]);

            $tenant->users()->attach($admin->id, [
                'role' => 'admin',
                'is_owner' => true,
            ]);

            $tenant->users()->attach($kasir->id, [
                'role' => 'kasir',
                'is_owner' => false,
            ]);

            $plan = Plan::query()
                ->where('slug', config('xiway.trial_plan_slug', 'enterprise'))
                ->where('is_active', true)
                ->firstOrFail();

            $trialDays = (int) config('xiway.trial_days', 14);

            Subscription::create([
                'tenant_id' => $tenant->id,
                'plan_id' => $plan->id,
                'status' => Subscription::STATUS_TRIALING,
                'trial_ends_at' => now()->addDays($trialDays),
                'current_period_start' => now(),
                'current_period_end' => now()->addDays($trialDays),
            ]);

            $tenant->setSetting('store_name', $data['tenant_name']);
            $tenant->setSetting('receipt_footer', 'Terima kasih sudah berkunjung!');

            $credentials = [
                'password' => $password,
                'admin' => [
                    'email' => $adminEmail,
                    'password' => $password,
                ],
                'kasir' => [
                    'email' => $kasirEmail,
                    'password' => $password,
                ],
            ];

            StaffPasswords::sync($tenant, [
                $adminEmail => $password,
                $kasirEmail => $password,
            ]);

            return [
                'user' => $admin,
                'kasir' => $kasir,
                'tenant' => $tenant,
                'credentials' => $credentials,
            ];
        });
    }

    private function uniqueStaffEmail(string $rolePrefix, string $handle): string
    {
        $email = TenantAccountHandle::staffEmail($rolePrefix, $handle);
        $suffix = 1;

        while (User::query()->where('email', $email)->exists()) {
            $email = TenantAccountHandle::staffEmail($rolePrefix, $handle.$suffix);
            $suffix++;
        }

        return $email;
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'toko';
        }

        $slug = $base;
        $i = 1;

        while (Tenant::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }
}
