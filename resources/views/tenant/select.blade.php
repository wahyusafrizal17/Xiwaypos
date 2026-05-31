<x-guest-layout>
    <div class="mb-6">
        <h1 class="text-xl font-bold text-slate-900">Pilih Bisnis</h1>
        <p class="mt-1 text-sm text-slate-600">Akun Anda terhubung ke beberapa bisnis. Pilih yang ingin dikelola.</p>
    </div>

    <div class="space-y-3">
        @foreach ($tenants as $tenant)
            <form method="POST" action="{{ route('tenant.switch') }}">
                @csrf
                <input type="hidden" name="tenant_id" value="{{ $tenant->id }}">
                <button type="submit" class="w-full rounded-lg border border-slate-200 bg-white px-4 py-3 text-left hover:border-indigo-400 hover:bg-indigo-50 transition">
                    <span class="font-semibold text-slate-900">{{ $tenant->displayName() }}</span>
                    <span class="block text-xs text-slate-500 mt-0.5">{{ $tenant->slug }}</span>
                </button>
            </form>
        @endforeach
    </div>
</x-guest-layout>
