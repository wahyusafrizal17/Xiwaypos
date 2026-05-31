<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\SubscriptionGuard;
use App\Support\StaffPasswords;
use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        protected SubscriptionGuard $subscriptionGuard
    ) {}

    public function index(): View
    {
        $users = User::query()
            ->whereHas('tenants', fn ($q) => $q->where('tenants.id', session('tenant_id')))
            ->orderBy('name')
            ->paginate(15);

        $tenant = TenantContext::get();
        $staffPasswords = $tenant
            ? StaffPasswords::resolveForTenant($tenant, session('registration_credentials'))
            : [];

        return view('admin.users.index', compact('users', 'staffPasswords'));
    }

    public function create(): View
    {
        return view('admin.users.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $tenantId = (int) session('tenant_id');
        $currentCount = User::query()
            ->whereHas('tenants', fn ($q) => $q->where('tenants.id', $tenantId))
            ->count();

        if ($this->subscriptionGuard->maxUsers() !== -1
            && $currentCount >= $this->subscriptionGuard->maxUsers()) {
            return back()->withInput()->with('error', 'Batas pengguna paket tercapai. Upgrade paket untuk menambah user.');
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        $user->tenants()->attach($tenantId, [
            'role' => $data['role'],
            'is_owner' => false,
        ]);

        if ($tenant = TenantContext::get()) {
            StaffPasswords::set($tenant, $data['email'], $data['password']);
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dibuat.');
    }

    public function edit(User $user): View
    {
        $this->ensureTenantMember($user);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $this->ensureTenantMember($user);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'kasir'])],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        $data = $request->validate($rules);

        $previousEmail = $user->email;

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];

        if (! empty($data['password'] ?? null)) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $user->tenants()->updateExistingPivot((int) session('tenant_id'), [
            'role' => $data['role'],
        ]);

        if ($tenant = TenantContext::get()) {
            if (! empty($data['password'] ?? null)) {
                StaffPasswords::forget($tenant, $previousEmail);
                StaffPasswords::set($tenant, $data['email'], $data['password']);
            } elseif ($previousEmail !== $data['email']) {
                StaffPasswords::rename($tenant, $previousEmail, $data['email']);
            }
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna diperbarui.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        $this->ensureTenantMember($user);

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Tidak dapat menghapus akun yang sedang aktif.');
        }

        $tenantId = (int) session('tenant_id');

        if ((bool) $user->tenants()->where('tenants.id', $tenantId)->wherePivot('is_owner', true)->exists()) {
            return back()->with('error', 'Tidak dapat menghapus pemilik bisnis.');
        }

        $user->tenants()->detach($tenantId);

        if ($tenant = TenantContext::get()) {
            StaffPasswords::forget($tenant, $user->email);
        }

        if (! $user->tenants()->exists()) {
            $user->delete();
        }

        return redirect()->route('admin.users.index')->with('success', 'Pengguna dihapus dari bisnis ini.');
    }

    private function ensureTenantMember(User $user): void
    {
        $exists = $user->tenants()->where('tenants.id', session('tenant_id'))->exists();

        if (! $exists) {
            abort(404);
        }
    }
}
