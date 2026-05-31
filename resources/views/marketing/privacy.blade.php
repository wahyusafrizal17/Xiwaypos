@extends('layouts.marketing')

@section('title', 'Kebijakan Privasi — Xiway POS')

@section('body')
<div class="min-h-screen bg-slate-50 py-16">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        <a href="{{ route('marketing.home') }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Kebijakan Privasi</h1>
        <div class="mt-8 prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 space-y-4">
            <p>Xiway POS ("kami") menghormati privasi data pengguna. Dokumen ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda.</p>
            <h2 class="text-lg font-semibold text-slate-900">Data yang dikumpulkan</h2>
            <p>Nama bisnis, nama pemilik, email, nomor WhatsApp, data transaksi, produk, dan pengaturan toko yang Anda input ke sistem.</p>
            <h2 class="text-lg font-semibold text-slate-900">Penggunaan data</h2>
            <p>Data digunakan untuk menyediakan layanan POS, dukungan pelanggan, penagihan langganan, dan peningkatan produk.</p>
            <h2 class="text-lg font-semibold text-slate-900">Keamanan</h2>
            <p>Data setiap bisnis diisolasi secara terpisah (multi-tenant). Kami menerapkan praktik keamanan standar industri.</p>
            <h2 class="text-lg font-semibold text-slate-900">Kontak</h2>
            <p>Hubungi kami via WhatsApp di nomor yang tertera di website untuk pertanyaan privasi.</p>
        </div>
    </div>
</div>
@endsection
