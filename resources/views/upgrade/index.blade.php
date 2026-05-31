<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Upgrade Paket</h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if (session('error'))
                <div class="rounded-lg bg-red-50 border border-red-200 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 text-center">
                <h3 class="text-xl font-bold text-slate-900">
                    @if ($subscription?->status === 'expired')
                        Trial Anda Telah Berakhir
                    @else
                        Upgrade Xiway POS
                    @endif
                </h3>
                @if ($statusMessage)
                    <p class="mt-2 text-sm text-slate-600">{{ $statusMessage }}</p>
                @endif
                <p class="mt-4 text-sm text-slate-500">Pilih paket dan hubungi admin untuk aktivasi setelah pembayaran.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-3">
                @foreach ($plans as $plan)
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-5 flex flex-col">
                        <h4 class="text-lg font-bold text-slate-900">{{ $plan->name }}</h4>
                        <p class="mt-2 text-2xl font-extrabold text-indigo-600">
                            {{ format_rupiah($plan->price_monthly_idr) }}
                            <span class="text-sm font-normal text-slate-500">/bln</span>
                        </p>
                        <ul class="mt-4 text-sm text-slate-600 space-y-1 flex-1">
                            <li>{{ $plan->limit('max_users') === -1 ? 'Unlimited' : $plan->limit('max_users') }} pengguna</li>
                            <li>{{ $plan->limit('max_transactions_monthly') === -1 ? 'Unlimited' : number_format($plan->limit('max_transactions_monthly')) }} transaksi/bulan</li>
                        </ul>
                        <a href="{{ \App\Support\WhatsAppLink::upgradeUrl($plan->name) }}" target="_blank" rel="noopener"
                            class="mt-4 block w-full text-center rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-emerald-700">
                            Hubungi Admin untuk Berlangganan
                        </a>
                    </div>
                @endforeach
            </div>

            <p class="text-xs text-slate-500 text-center">Sudah bayar? Admin akan aktifkan langganan dalam 1×24 jam kerja.</p>
        </div>
    </div>
</x-app-layout>
