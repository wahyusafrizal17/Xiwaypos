@extends('layouts.admin')

@section('title', $tenant->displayName())

@section('content')
    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-4 rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="grid gap-6 lg:grid-cols-2">
        <div class="bg-white rounded-xl border border-slate-200 p-6">
            <h3 class="font-bold text-lg mb-4">Info Tenant</h3>
            <dl class="space-y-2 text-sm">
                <div><dt class="text-slate-500">Nama</dt><dd class="font-medium">{{ $tenant->displayName() }}</dd></div>
                <div><dt class="text-slate-500">Slug</dt><dd>{{ $tenant->slug }}</dd></div>
                <div><dt class="text-slate-500">Owner</dt><dd>{{ $tenant->owner?->name }} ({{ $tenant->owner?->email }})</dd></div>
                <div><dt class="text-slate-500">WhatsApp</dt><dd>{{ $tenant->owner?->whatsapp ?? '—' }}</dd></div>
                <div><dt class="text-slate-500">Onboarding</dt><dd>{{ $tenant->onboarding_completed_at?->format('d/m/Y H:i') ?? 'Belum selesai' }}</dd></div>
                <div><dt class="text-slate-500">Subscription</dt><dd>{{ strtoupper($tenant->subscription?->status ?? '—') }} · {{ $tenant->subscription?->plan?->name }}</dd></div>
            </dl>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 p-6 space-y-6">
            <div>
                <h3 class="font-bold text-lg mb-4">Aktifkan Langganan</h3>
                <form method="POST" action="{{ route('platform.tenants.activate', $tenant) }}" class="space-y-3">
                    @csrf
                    <select name="plan_slug" class="w-full rounded-lg border-slate-300 text-sm" required>
                        @foreach ($plans as $plan)
                            <option value="{{ $plan->slug }}">{{ $plan->name }} — {{ format_rupiah($plan->price_monthly_idr) }}/bln</option>
                        @endforeach
                    </select>
                    <input type="number" name="months" value="1" min="1" max="24" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Durasi (bulan)">
                    <input type="text" name="note" class="w-full rounded-lg border-slate-300 text-sm" placeholder="Catatan (opsional)">
                    <button type="submit" class="w-full rounded-lg bg-indigo-600 py-2 text-sm font-semibold text-white">Aktifkan</button>
                </form>
            </div>

            <div>
                <h3 class="font-bold mb-2">Perpanjang Trial</h3>
                <form method="POST" action="{{ route('platform.tenants.extend-trial', $tenant) }}" class="flex gap-2">
                    @csrf
                    <input type="number" name="days" value="7" min="1" max="90" class="flex-1 rounded-lg border-slate-300 text-sm">
                    <button type="submit" class="rounded-lg bg-amber-500 px-4 py-2 text-sm font-semibold text-white">+Hari</button>
                </form>
            </div>

            <form method="POST" action="{{ route('platform.tenants.suspend', $tenant) }}" onsubmit="return confirm('Tangguhkan tenant ini?')">
                @csrf
                <button type="submit" class="w-full rounded-lg border border-red-300 text-red-700 py-2 text-sm font-semibold hover:bg-red-50">Tangguhkan Tenant</button>
            </form>
        </div>
    </div>
@endsection
