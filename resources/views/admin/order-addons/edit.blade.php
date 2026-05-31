@extends('layouts.admin')

@section('title', 'Edit opsi ekstra')

@section('breadcrumbs')
    <a href="{{ route('dashboard') }}">Beranda</a>
    <span class="vx-sep">/</span>
    <a href="{{ route('admin.order-addons.index') }}">Opsi Ekstra</a>
    <span class="vx-sep">/</span>
    <span class="vx-current">Edit</span>
@endsection

@section('page_header')
    <div>
        <h1>Edit opsi ekstra</h1>
        <p>{{ $orderAddon->label }}</p>
    </div>
@endsection

@section('content')
    <div class="mx-auto max-w-xl">
        <div class="vx-card vx-card-pad">
            <form method="POST" action="{{ route('admin.order-addons.update', $orderAddon) }}" class="space-y-5">
                @csrf
                @method('PUT')
                @include('admin.order-addons._form', ['orderAddon' => $orderAddon])
                <div class="flex flex-col-reverse gap-2 sm:flex-row sm:items-center sm:justify-end">
                    <a href="{{ route('admin.order-addons.index') }}" class="vx-btn vx-btn-ghost">Batal</a>
                    <x-primary-button>Perbarui</x-primary-button>
                </div>
            </form>
        </div>
    </div>
@endsection
