<?php

namespace Database\Seeders;

use App\Models\Asset;
use App\Models\Category;
use App\Models\Expense;
use App\Models\Plan;
use App\Models\Product;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PlanSeeder::class);

        TransactionDetail::query()->withoutGlobalScopes()->delete();
        Transaction::query()->withoutGlobalScopes()->delete();
        Expense::query()->withoutGlobalScopes()->delete();
        Asset::query()->withoutGlobalScopes()->delete();
        Product::query()->withoutGlobalScopes()->delete();
        Category::query()->withoutGlobalScopes()->delete();
        Subscription::query()->delete();
        Tenant::query()->delete();
        User::query()->delete();

        $admin = User::create([
            'name' => 'Administrator Xiway',
            'email' => 'admin@xiwaypos.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        $kasir = User::create([
            'name' => 'Kasir Demo',
            'email' => 'kasir@xiwaypos.test',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'email_verified_at' => now(),
        ]);

        $tenant = Tenant::create([
            'name' => 'Xiway Demo Cafe',
            'slug' => 'xiway-demo',
            'owner_user_id' => $admin->id,
            'status' => Tenant::STATUS_ACTIVE,
            'onboarding_completed_at' => now(),
        ]);

        $tenant->users()->attach($admin->id, ['role' => 'admin', 'is_owner' => true]);
        $tenant->users()->attach($kasir->id, ['role' => 'kasir', 'is_owner' => false]);

        $businessPlan = Plan::query()->where('slug', 'business')->firstOrFail();

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $businessPlan->id,
            'status' => Subscription::STATUS_ACTIVE,
            'current_period_start' => now(),
            'current_period_end' => now()->addYear(),
        ]);

        $tenant->setSetting('store_name', 'Xiway Demo Cafe');
        $tenant->setSetting('receipt_footer', 'Terima kasih sudah berkunjung!');

        TenantContext::set($tenant);

        $makanan = Category::create(['nama_kategori' => 'Makanan']);
        $minuman = Category::create(['nama_kategori' => 'Minuman']);
        $snack = Category::create(['nama_kategori' => 'Snack']);

        $rows = [
            ['nama_produk' => 'Nasi Goreng Spesial', 'harga' => 25000, 'kategori_id' => $makanan->id],
            ['nama_produk' => 'Mie Ayam Bakso', 'harga' => 22000, 'kategori_id' => $makanan->id],
            ['nama_produk' => 'Ayam Geprek', 'harga' => 22000, 'kategori_id' => $makanan->id],
            ['nama_produk' => 'Kopi Hitam', 'harga' => 8000, 'kategori_id' => $minuman->id],
            ['nama_produk' => 'Es Teh Manis', 'harga' => 6000, 'kategori_id' => $minuman->id],
            ['nama_produk' => 'Jus Jeruk', 'harga' => 12000, 'kategori_id' => $minuman->id],
            ['nama_produk' => 'Air Mineral', 'harga' => 4000, 'kategori_id' => $minuman->id],
            ['nama_produk' => 'Keripik Singkong', 'harga' => 10000, 'kategori_id' => $snack->id],
            ['nama_produk' => 'Wafer Coklat', 'harga' => 8000, 'kategori_id' => $snack->id],
        ];

        foreach ($rows as $r) {
            Product::create($r);
        }

        $this->call(OrderAddonSeeder::class);

        $assets = [
            ['nama' => 'Mesin Espresso La Marzocco Linea Mini', 'tanggal_perolehan' => now()->subMonths(8)->toDateString(), 'harga_perolehan' => 75000000, 'catatan' => 'Mesin utama bar'],
            ['nama' => 'Grinder Mahlkonig E65S', 'tanggal_perolehan' => now()->subMonths(8)->toDateString(), 'harga_perolehan' => 28000000, 'catatan' => null],
            ['nama' => 'Kulkas Display Glass Door', 'tanggal_perolehan' => now()->subMonths(6)->toDateString(), 'harga_perolehan' => 12000000, 'catatan' => null],
            ['nama' => 'POS Tablet & Printer Thermal', 'tanggal_perolehan' => now()->subMonths(3)->toDateString(), 'harga_perolehan' => 8500000, 'catatan' => null],
        ];
        foreach ($assets as $a) {
            Asset::create($a);
        }

        $expenses = [
            ['tanggal' => now()->startOfMonth()->toDateString(), 'kategori' => Expense::CATEGORY_RENT, 'nama' => 'Sewa tempat bulan ini', 'jumlah' => 7500000, 'catatan' => 'Pembayaran kontrak ruko'],
            ['tanggal' => now()->subDays(5)->toDateString(), 'kategori' => Expense::CATEGORY_MAINTENANCE, 'nama' => 'Servis berkala mesin espresso', 'jumlah' => 850000, 'catatan' => 'Ganti gasket & kalibrasi'],
            ['tanggal' => now()->subDays(2)->toDateString(), 'kategori' => Expense::CATEGORY_UTILITY, 'nama' => 'Tagihan listrik & air', 'jumlah' => 1450000, 'catatan' => null],
            ['tanggal' => now()->subDays(10)->toDateString(), 'kategori' => Expense::CATEGORY_SUPPLIES, 'nama' => 'Restock biji kopi & susu', 'jumlah' => 3200000, 'catatan' => null],
        ];
        foreach ($expenses as $e) {
            Expense::create($e);
        }

        User::create([
            'name' => 'Platform Admin',
            'email' => 'platform@xiwaypos.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_platform_admin' => true,
            'email_verified_at' => now(),
        ]);

        TenantContext::clear();
    }
}
