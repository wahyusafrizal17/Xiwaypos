@extends('layouts.admin')

@section('title', 'Platform — Tenants')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold">Semua Tenant</h2>
        <form method="GET" class="flex gap-2">
            <select name="status" class="rounded-lg border-slate-300 text-sm" onchange="this.form.submit()">
                <option value="">Semua status</option>
                @foreach (['trialing', 'active', 'expired', 'grace', 'suspended'] as $s)
                    <option value="{{ $s }}" @selected($status === $s)>{{ strtoupper($s) }}</option>
                @endforeach
            </select>
        </form>
    </div>

    @if (session('success'))
        <div class="mb-4 rounded-lg bg-emerald-50 border border-emerald-200 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif

    <div class="bg-white rounded-xl border border-slate-200 overflow-hidden">
        <table class="min-w-full text-sm">
            <thead class="bg-slate-50 text-left text-slate-600">
                <tr>
                    <th class="px-4 py-3">Toko</th>
                    <th class="px-4 py-3">Owner</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Trial s/d</th>
                    <th class="px-4 py-3"></th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($tenants as $tenant)
                    <tr>
                        <td class="px-4 py-3 font-medium">{{ $tenant->displayName() }}</td>
                        <td class="px-4 py-3">{{ $tenant->owner?->email }}</td>
                        <td class="px-4 py-3">{{ strtoupper($tenant->subscription?->status ?? '—') }}</td>
                        <td class="px-4 py-3">{{ $tenant->subscription?->trial_ends_at?->format('d/m/Y') ?? '—' }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('platform.tenants.show', $tenant) }}" class="text-indigo-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $tenants->links() }}</div>
@endsection
