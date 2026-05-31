@extends('layouts.admin')

@section('title', 'Verifikasi Langganan')

@section('page_header')
    <div>
        <h1>Verifikasi Langganan</h1>
        <p>Tinjau bukti pembayaran dari client dan aktifkan atau tolak pengajuan.</p>
    </div>
@endsection

@section('content')
    @if (session('success'))
        <div class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="mb-5 rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">{{ session('error') }}</div>
    @endif

    <div class="vx-card vx-card-pad-sm mb-5 flex flex-wrap items-center justify-between gap-3">
        <div>
            <p class="text-sm font-semibold text-[var(--vx-text)]">
                @if ($pendingCount > 0)
                    {{ $pendingCount }} pengajuan menunggu verifikasi
                @else
                    Tidak ada pengajuan pending
                @endif
            </p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <select name="status" class="vx-select w-auto min-w-[160px]" onchange="this.form.submit()">
                <option value="">Semua status</option>
                <option value="pending" @selected($status === 'pending')>Pending</option>
                <option value="approved" @selected($status === 'approved')>Disetujui</option>
                <option value="rejected" @selected($status === 'rejected')>Ditolak</option>
            </select>
        </form>
    </div>

    <div class="space-y-4">
        @forelse ($requests as $paymentRequest)
            <div class="vx-card vx-card-pad">
                <div class="flex flex-col gap-5 lg:flex-row lg:items-start lg:justify-between">
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2 mb-2">
                            <h3 class="text-base font-bold text-[var(--vx-text)]">{{ $paymentRequest->tenant?->displayName() }}</h3>
                            @php
                                $badgeClass = match ($paymentRequest->status) {
                                    'pending' => 'vx-badge-warning',
                                    'approved' => 'vx-badge-success',
                                    'rejected' => 'vx-badge-danger',
                                    default => 'vx-badge-slate',
                                };
                            @endphp
                            <span class="vx-badge {{ $badgeClass }}">{{ strtoupper($paymentRequest->status) }}</span>
                        </div>

                        <dl class="grid gap-2 text-sm sm:grid-cols-2">
                            <div>
                                <dt class="text-[var(--vx-text-mute)]">Paket</dt>
                                <dd class="font-semibold">{{ $paymentRequest->plan?->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-[var(--vx-text-mute)]">Siklus</dt>
                                <dd>{{ $paymentRequest->billingCycleLabel() }}</dd>
                            </div>
                            <div>
                                <dt class="text-[var(--vx-text-mute)]">Nominal</dt>
                                <dd class="font-semibold">{{ format_rupiah($paymentRequest->amount_idr) }}</dd>
                            </div>
                            <div>
                                <dt class="text-[var(--vx-text-mute)]">Diajukan</dt>
                                <dd>{{ $paymentRequest->created_at->format('d/m/Y H:i') }} · {{ $paymentRequest->requester?->name }}</dd>
                            </div>
                            @if ($paymentRequest->reference_number)
                                <div>
                                    <dt class="text-[var(--vx-text-mute)]">Referensi</dt>
                                    <dd class="font-mono text-xs">{{ $paymentRequest->reference_number }}</dd>
                                </div>
                            @endif
                            @if ($paymentRequest->note)
                                <div class="sm:col-span-2">
                                    <dt class="text-[var(--vx-text-mute)]">Catatan client</dt>
                                    <dd>{{ $paymentRequest->note }}</dd>
                                </div>
                            @endif
                            @if ($paymentRequest->rejection_reason)
                                <div class="sm:col-span-2">
                                    <dt class="text-[var(--vx-text-mute)]">Alasan ditolak</dt>
                                    <dd class="text-red-700">{{ $paymentRequest->rejection_reason }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    <div class="flex w-full flex-col gap-3 lg:w-72 shrink-0">
                        @if ($paymentRequest->proofUrl())
                            <a href="{{ $paymentRequest->proofUrl() }}" target="_blank" rel="noopener" class="block overflow-hidden rounded-xl border border-[var(--vx-border-soft)] bg-[var(--vx-bg)]">
                                @if (str_ends_with(strtolower($paymentRequest->proof_path), '.pdf'))
                                    <div class="flex h-40 items-center justify-center text-sm font-semibold text-[var(--vx-text-soft)]">Lihat PDF bukti</div>
                                @else
                                    <img src="{{ $paymentRequest->proofUrl() }}" alt="Bukti pembayaran" class="h-40 w-full object-cover">
                                @endif
                            </a>
                        @endif

                        @if ($paymentRequest->isPending())
                            <form method="POST" action="{{ route('platform.payment-requests.approve', $paymentRequest) }}">
                                @csrf
                                <button type="submit" class="vx-btn vx-btn-primary w-full" onclick="return confirm('Setujui dan aktifkan langganan ini?')">
                                    Setujui & Aktifkan
                                </button>
                            </form>
                            <form method="POST" action="{{ route('platform.payment-requests.reject', $paymentRequest) }}" class="space-y-2">
                                @csrf
                                <input type="text" name="rejection_reason" class="vx-input" placeholder="Alasan penolakan" required maxlength="1000">
                                <button type="submit" class="vx-btn vx-btn-ghost w-full text-red-700 border-red-200 hover:bg-red-50">
                                    Tolak Pengajuan
                                </button>
                            </form>
                        @elseif ($paymentRequest->reviewer)
                            <p class="text-xs text-[var(--vx-text-mute)]">
                                Diproses {{ $paymentRequest->reviewed_at?->format('d/m/Y H:i') }}
                                oleh {{ $paymentRequest->reviewer->name }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="vx-card vx-card-pad text-center text-sm text-[var(--vx-text-soft)]">
                Belum ada pengajuan langganan.
            </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $requests->links() }}</div>
@endsection
