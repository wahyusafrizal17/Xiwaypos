<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\SubscriptionEvent;
use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use App\Support\TenantStorage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OnboardingService
{
    /** @return list<string> */
    public function categoryPresets(): array
    {
        return config('xiway.category_presets', []);
    }

    /** @return list<array{nama_produk: string, harga: int, kategori: string}> */
    public function productPresets(): array
    {
        return config('xiway.product_presets', []);
    }

    public function currentStep(Tenant $tenant): string
    {
        if ($tenant->onboarding_completed_at !== null) {
            return 'complete';
        }

        if ($tenant->setting('store_address') === null && $tenant->phone === null) {
            return 'store';
        }

        if (Category::query()->count() === 0) {
            return 'categories';
        }

        if (Product::query()->count() === 0) {
            return 'products';
        }

        return 'complete';
    }

    public function routeForStep(string $step): string
    {
        return match ($step) {
            'store' => route('onboarding.store'),
            'categories' => route('onboarding.categories'),
            'products' => route('onboarding.products'),
            default => route('onboarding.complete'),
        };
    }

    /**
     * @param  array{store_name: string, store_address?: string|null, store_phone?: string|null, store_logo?: UploadedFile|null}  $data
     */
    public function saveStoreProfile(Tenant $tenant, array $data): void
    {
        $tenant->update([
            'name' => $data['store_name'],
            'phone' => $data['store_phone'] ?? null,
        ]);

        $tenant->setSetting('store_name', $data['store_name']);

        if (! empty($data['store_address'])) {
            $tenant->setSetting('store_address', $data['store_address']);
        }

        if (! empty($data['store_phone'])) {
            $tenant->setSetting('store_phone', $data['store_phone']);
        }

        if (! empty($data['store_logo']) && $data['store_logo'] instanceof UploadedFile) {
            $path = $data['store_logo']->store(TenantStorage::productPath('branding'), 'uploads');
            $tenant->setSetting('store_logo', $path);
        }
    }

    /** @param  list<string>  $categoryNames */
    public function saveCategories(array $categoryNames): void
    {
        foreach ($categoryNames as $name) {
            $name = trim($name);
            if ($name === '') {
                continue;
            }

            Category::query()->firstOrCreate(['nama_kategori' => $name]);
        }
    }

    /**
     * @param  list<array{nama_produk: string, harga: int|string, kategori: string}>  $products
     */
    public function saveProducts(array $products): void
    {
        foreach ($products as $row) {
            $name = trim($row['nama_produk'] ?? '');
            if ($name === '') {
                continue;
            }

            $category = Category::query()->where('nama_kategori', $row['kategori'] ?? '')->first();
            if ($category === null) {
                continue;
            }

            Product::query()->create([
                'nama_produk' => $name,
                'harga' => (int) $row['harga'],
                'kategori_id' => $category->id,
            ]);
        }
    }

    public function markComplete(Tenant $tenant): void
    {
        $tenant->update(['onboarding_completed_at' => now()]);
    }

    public function seedDefaultCategoriesIfEmpty(): void
    {
        if (Category::query()->exists()) {
            return;
        }

        $this->saveCategories(['Minuman', 'Makanan']);
    }
}
