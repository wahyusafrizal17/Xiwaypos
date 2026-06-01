@extends('layouts.marketing')

@section('title', 'Syarat & Ketentuan — Xiway POS')
@section('meta_description', 'Syarat dan ketentuan penggunaan layanan Xiway POS untuk bisnis cafe, restoran, dan UMKM.')
@section('canonical', \App\Support\MarketingSeo::canonical('/terms'))
@section('meta_robots', 'index, follow')

@section('body')
<div class="min-h-screen bg-slate-50 py-16">
    <div class="mx-auto max-w-3xl px-4 sm:px-6">
        <a href="{{ route('marketing.home') }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
        <h1 class="mt-6 text-3xl font-bold text-slate-900">Syarat & Ketentuan</h1>
        <div class="mt-8 prose prose-slate max-w-none text-sm leading-relaxed text-slate-600 space-y-4">
            <p>Dengan menggunakan Xiway POS, Anda setuju dengan syarat berikut.</p>
            <h2 class="text-lg font-semibold text-slate-900">Layanan</h2>
            <p>Xiway POS menyediakan perangkat lunak POS berbasis cloud untuk UMKM F&B. Fitur tergantung paket langganan yang aktif.</p>
            <h2 class="text-lg font-semibold text-slate-900">Trial & Langganan</h2>
            <p>Trial 14 hari gratis. Setelah trial berakhir, layanan POS dapat dibatasi hingga langganan diaktifkan oleh admin.</p>
            <h2 class="text-lg font-semibold text-slate-900">Tanggung jawab pengguna</h2>
            <p>Anda bertanggung jawab atas keakuratan data transaksi dan keamanan akun. Jangan bagikan password ke pihak tidak berwenang.</p>
            <h2 class="text-lg font-semibold text-slate-900">Perubahan</h2>
            <p>Kami dapat memperbarui syarat ini. Perubahan material akan diberitahukan via email atau notifikasi in-app.</p>
        </div>
    </div>
</div>
@endsection
