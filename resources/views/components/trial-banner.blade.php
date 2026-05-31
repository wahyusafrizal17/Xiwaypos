@php
    $days = $trialDaysRemaining ?? null;
    $urgency = $trialUrgency ?? null;
    $status = $subscriptionStatus ?? null;
@endphp

@if ($days !== null && $status === 'trialing')
    <div @class([
        'px-4 py-2 text-center text-sm font-medium border-b',
        'bg-neutral-900 text-white border-b-2 border-[#E01010]' => $urgency === 'info',
        'bg-amber-50 text-amber-900 border-amber-100' => $urgency === 'notice',
        'bg-orange-50 text-orange-900 border-orange-100' => $urgency === 'warning',
        'bg-red-50 text-red-900 border-red-100' => $urgency === 'critical',
    ])>
        Trial Anda tersisa <strong>{{ $days }} hari</strong>.
        <a href="{{ route('upgrade.index') }}" class="underline font-semibold ml-1">Upgrade sekarang</a>
    </div>
@elseif ($status === 'expired')
    <div class="px-4 py-2 text-center text-sm font-medium bg-red-600 text-white">
        Trial berakhir. <a href="{{ route('upgrade.index') }}" class="underline font-bold">Upgrade paket</a> untuk melanjutkan.
    </div>
@elseif ($status === 'grace')
    <div class="px-4 py-2 text-center text-sm font-medium bg-orange-100 text-orange-900 border-b border-orange-200">
        Masa tenggang — segera upgrade agar transaksi tidak terhenti.
        <a href="{{ route('upgrade.index') }}" class="underline font-semibold ml-1">Upgrade</a>
    </div>
@endif
