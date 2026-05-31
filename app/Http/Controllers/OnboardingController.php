<?php

namespace App\Http\Controllers;

use App\Services\OnboardingService;
use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    public function __construct(
        protected OnboardingService $onboarding
    ) {}

    public function index(): RedirectResponse
    {
        $tenant = TenantContext::get();

        if ($tenant === null) {
            return redirect()->route('login');
        }

        if ($tenant->hasCompletedOnboarding()) {
            return redirect()->route('dashboard');
        }

        return redirect()->to($this->onboarding->routeForStep($this->onboarding->currentStep($tenant)));
    }

    public function store(): View
    {
        $tenant = TenantContext::get();

        return view('onboarding.store', [
            'tenant' => $tenant,
            'step' => 1,
        ]);
    }

    public function saveStore(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'store_name' => ['required', 'string', 'max:255'],
            'store_address' => ['nullable', 'string', 'max:500'],
            'store_phone' => ['nullable', 'string', 'max:20'],
            'store_logo' => ['nullable', 'image', 'max:2048'],
        ]);

        $tenant = TenantContext::get();
        $this->onboarding->saveStoreProfile($tenant, $data);

        return redirect()->route('onboarding.categories');
    }

    public function categories(): View
    {
        return view('onboarding.categories', [
            'presets' => $this->onboarding->categoryPresets(),
            'step' => 2,
        ]);
    }

    public function saveCategories(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'categories' => ['required', 'array', 'min:1'],
            'categories.*' => ['string', 'max:100'],
        ]);

        $this->onboarding->saveCategories($data['categories']);

        return redirect()->route('onboarding.products');
    }

    public function products(): View|RedirectResponse
    {
        $this->onboarding->seedDefaultCategoriesIfEmpty();

        if (\App\Models\Category::query()->count() === 0) {
            return redirect()->route('onboarding.categories');
        }

        return view('onboarding.products', [
            'presets' => $this->onboarding->productPresets(),
            'categories' => \App\Models\Category::orderBy('nama_kategori')->pluck('nama_kategori'),
            'step' => 3,
        ]);
    }

    public function saveProducts(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'products' => ['required', 'array', 'min:1'],
            'products.*.nama_produk' => ['required', 'string', 'max:255'],
            'products.*.harga' => ['required', 'integer', 'min:0'],
            'products.*.kategori' => ['required', 'string', 'max:100'],
        ]);

        $this->onboarding->saveProducts($data['products']);

        return redirect()->route('onboarding.complete');
    }

    public function complete(): View|RedirectResponse
    {
        if (\App\Models\Product::query()->count() === 0) {
            return redirect()->route('onboarding.products');
        }

        $tenant = TenantContext::get();

        return view('onboarding.complete', [
            'tenant' => $tenant,
            'productCount' => \App\Models\Product::query()->count(),
            'categoryCount' => \App\Models\Category::query()->count(),
            'step' => 4,
        ]);
    }

    public function finish(Request $request): RedirectResponse
    {
        $tenant = TenantContext::get();
        $this->onboarding->markComplete($tenant);

        $request->session()->forget('registration_credentials');

        return redirect()
            ->route('cashier.index', ['first' => 1])
            ->with('success', 'Setup selesai! Mulai transaksi pertama Anda.');
    }
}
