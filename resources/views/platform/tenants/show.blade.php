@extends('layouts.admin')

@section('title', $tenant->displayName())

@section('breadcrumbs')
    <a href="{{ route('platform.dashboard') }}">Beranda</a>
    <span class="vx-sep">/</span>
    <a href="{{ route('platform.tenants.index') }}">Kelola Tenant</a>
    <span class="vx-sep">/</span>
    <span class="vx-current">{{ $tenant->displayName() }}</span>
@endsection

@php
    $subStatus = $tenant->subscription?->status;
    $statusBadge = match ($subStatus) {
        'trialing' => 'vx-badge-warning',
        'active' => 'vx-badge-success',
        'expired', 'suspended' => 'vx-badge-danger',
        'grace', 'past_due' => 'vx-badge-warning',
        default => 'vx-badge-slate',
    };
    $tenantInitial = \Illuminate\Support\Str::of($tenant->displayName())->trim()->substr(0, 1)->upper();
@endphp

@section('page_header')
    <div class="flex w-full flex-wrap items-end justify-between gap-4">
        <div class="min-w-0">
            <a href="{{ route('platform.tenants.index') }}" class="vx-btn vx-btn-ghost vx-btn-sm mb-3">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/></svg>
                Kembali ke daftar tenant
            </a>
            <div class="flex flex-wrap items-center gap-4">
                <span class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-[var(--vx-primary-soft)] text-xl font-bold text-[var(--vx-primary)]" aria-hidden="true">
                    {{ $tenantInitial }}
                </span>
                <div class="min-w-0">
                    <div class="flex flex-wrap items-center gap-2">
                        <h1>{{ $tenant->displayName() }}</h1>
                        <span class="vx-badge {{ $statusBadge }}">{{ strtoupper($subStatus ?? '—') }}</span>
                        @if ($tenant->subscription?->plan)
                            <span class="vx-badge vx-badge-primary">{{ $tenant->subscription->plan->name }}</span>
                        @endif
                    </div>
                    <p class="mt-1">{{ $tenant->owner?->name }} · {{ $tenant->owner?->email }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-5 rounded-[var(--vx-radius)] border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-5 rounded-[var(--vx-radius)] border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="mb-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-primary">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-8.25 4.5-8.25-4.5M12 12v9.75M20.25 7.5v9l-8.25 4.5L3.75 16.5v-9L12 3l8.25 4.5Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Total produk</p>
                <p class="vx-stat-value">{{ number_format($productsCount, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-success">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .896-3 2s1.343 2 3 2 3 .896 3 2-1.343 2-3 2m0-8V6m0 12v-2m9-4a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Pendapatan total</p>
                <p class="vx-stat-value">{{ format_rupiah($totalRevenue) }}</p>
            </div>
        </div>
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-violet">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.5 9 7.5l3.75 3.75L21 4.5M21 4.5h-4.5M21 4.5V9m0 10.5H3"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Pendapatan bulan ini</p>
                <p class="vx-stat-value">{{ format_rupiah($monthlyRevenue) }}</p>
            </div>
        </div>
        <div class="vx-stat">
            <span class="vx-stat-icon vx-bg-info">
                <svg viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M3.75 6.75A1.5 1.5 0 0 1 5.25 5.25h13.5A1.5 1.5 0 0 1 20.25 6.75v10.5a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V6.75ZM7.5 11.25h.008v.008H7.5v-.008Z"/></svg>
            </span>
            <div class="min-w-0">
                <p class="vx-stat-label">Transaksi lunas</p>
                <p class="vx-stat-value">{{ number_format($paidTransactionCount, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-2">
            <div class="vx-card">
                <div class="vx-card-head">
                    <div>
                        <h2>Info Tenant</h2>
                        <p>Data bisnis dan kontak owner</p>
                    </div>
                </div>
                <div class="vx-card-pad">
                    <dl class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">Nama toko</dt>
                            <dd class="mt-1 text-sm font-semibold text-[var(--vx-text)]">{{ $tenant->displayName() }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">Slug</dt>
                            <dd class="mt-1 font-mono text-sm text-[var(--vx-text-soft)]">{{ $tenant->slug }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">Owner</dt>
                            <dd class="mt-1 text-sm font-semibold text-[var(--vx-text)]">{{ $tenant->owner?->name }}</dd>
                            <dd class="text-xs text-[var(--vx-text-mute)]">{{ $tenant->owner?->email }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">WhatsApp</dt>
                            <dd class="mt-1 text-sm text-[var(--vx-text)]">{{ $tenant->owner?->whatsapp ?? '—' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">Onboarding</dt>
                            <dd class="mt-1 text-sm text-[var(--vx-text)]">{{ $tenant->onboarding_completed_at?->format('d/m/Y H:i') ?? 'Belum selesai' }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-semibold uppercase tracking-wide text-[var(--vx-text-mute)]">Trial berakhir</dt>
                            <dd class="mt-1 text-sm text-[var(--vx-text)]">{{ $tenant->subscription?->trial_ends_at?->format('d/m/Y') ?? '—' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="vx-table-wrap">
                <div class="vx-card-head">
                    <div>
                        <h2>Akun Tenant</h2>
                        <p>Admin dan kasir yang terhubung ke bisnis ini</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="vx-table">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tenant->users as $user)
                                @php
                                    $pivotRole = $user->pivot->role ?? $user->role;
                                    $isOwner = (bool) ($user->pivot->is_owner ?? false);
                                @endphp
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <span class="vx-thumb-placeholder" aria-hidden="true">
                                                {{ \Illuminate\Support\Str::of($user->name)->trim()->substr(0, 1)->upper() }}
                                            </span>
                                            <span class="font-semibold text-[var(--vx-text)]">{{ $user->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-[var(--vx-text-soft)]">{{ $user->email }}</td>
                                    <td>
                                        @if ($password = $staffPasswords[$user->email] ?? null)
                                            <code class="inline-block rounded-lg bg-[var(--vx-primary-soft)] px-2.5 py-1 font-mono text-xs font-semibold text-[var(--vx-primary-text)]">{{ $password }}</code>
                                        @else
                                            <span class="text-[var(--vx-text-mute)]">Tidak tercatat</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($pivotRole === 'admin')
                                            <span class="vx-badge vx-badge-violet">Admin</span>
                                        @else
                                            <span class="vx-badge vx-badge-slate">Kasir</span>
                                        @endif
                                        @if ($isOwner)
                                            <span class="ml-1 text-xs text-[var(--vx-text-mute)]">Owner</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-10 text-center text-sm text-[var(--vx-text-mute)]">Belum ada akun terhubung.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <p class="border-t border-[var(--vx-border-soft)] px-5 py-3 text-xs text-[var(--vx-text-mute)]">
                    Password dari catatan registrasi atau pembuatan akun di outlet. Jika kosong, minta client reset dari menu Pengguna.
                </p>
            </div>
        </div>

        <aside class="space-y-6">
            <div class="vx-card vx-card-pad">
                <h2 class="text-base font-semibold text-[var(--vx-text)]">Aktifkan Langganan</h2>
                <p class="mt-1 text-xs text-[var(--vx-text-mute)]">Set paket dan durasi langganan tenant.</p>

                <form method="POST" action="{{ route('platform.tenants.activate', $tenant) }}" class="mt-5 space-y-4">
                    @csrf
                    <div class="vx-field">
                        <label class="vx-label" for="plan_slug">Paket</label>
                        <select name="plan_slug" id="plan_slug" class="vx-select" required>
                            @foreach ($plans as $plan)
                                <option value="{{ $plan->slug }}">{{ $plan->name }} — {{ format_rupiah($plan->price_monthly_idr) }}/bln</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="vx-field">
                        <label class="vx-label" for="months">Durasi (bulan)</label>
                        <input type="number" name="months" id="months" value="1" min="1" max="24" class="vx-input" required>
                    </div>
                    <div class="vx-field">
                        <label class="vx-label" for="note">Catatan</label>
                        <input type="text" name="note" id="note" class="vx-input" placeholder="Opsional">
                    </div>
                    <button type="submit" class="vx-btn vx-btn-primary w-full">Aktifkan langganan</button>
                </form>
            </div>

            <div class="vx-card vx-card-pad">
                <h2 class="text-base font-semibold text-[var(--vx-text)]">Perpanjang Trial</h2>
                <p class="mt-1 text-xs text-[var(--vx-text-mute)]">Tambah masa trial untuk tenant ini.</p>

                <form method="POST" action="{{ route('platform.tenants.extend-trial', $tenant) }}" class="mt-5 flex gap-2">
                    @csrf
                    <div class="vx-field flex-1">
                        <label class="vx-label" for="days">Hari</label>
                        <input type="number" name="days" id="days" value="7" min="1" max="90" class="vx-input" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="vx-btn vx-btn-soft">+ Hari</button>
                    </div>
                </form>
            </div>

            <div class="vx-card vx-card-pad border-[var(--vx-danger-soft)]">
                <h2 class="text-base font-semibold text-[var(--vx-danger)]">Zona berbahaya</h2>
                <p class="mt-1 text-xs text-[var(--vx-text-mute)]">Tenant tidak bisa mengakses aplikasi saat ditangguhkan.</p>

                <form method="POST" action="{{ route('platform.tenants.suspend', $tenant) }}" class="mt-5" onsubmit="return confirm('Tangguhkan tenant ini?')">
                    @csrf
                    <button type="submit" class="vx-btn vx-btn-ghost w-full border-red-200 text-red-700 hover:border-red-300 hover:bg-red-50 hover:text-red-800">
                        Tangguhkan tenant
                    </button>
                </form>
            </div>
        </aside>
    </div>
@endsection
