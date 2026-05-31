<?php

namespace App\Support;

use App\Models\Plan;

class PlanMarketing
{
    /** @var array<string, string> */
    private const FEATURE_LABELS = [
        'cashier' => 'Kasir & pembayaran (QRIS, tunai, transfer)',
        'reports_basic' => 'Laporan penjualan harian',
        'reports_export' => 'Export laporan',
        'products' => 'Kelola produk & foto',
        'categories' => 'Kategori menu',
        'expenses' => 'Catat pengeluaran',
        'assets' => 'Kelola aset toko',
        'order_addons' => 'Opsi ekstra menu (topping, susu, dll)',
    ];

    /** @var array<string, list<string>> */
    private const ROADMAP_LINES = [
        'business' => [
            'Shift kasir (segera)',
            'Void & audit (segera)',
            'Diskon & PPN (segera)',
            'Promo (segera)',
        ],
        'enterprise' => [
            'Semua fitur Business',
            'Meja & Open Bill (segera)',
            'Kitchen Display / KDS (segera)',
            'Multi-outlet dashboard (segera)',
            'Role & permission (segera)',
            'Activity log (segera)',
        ],
    ];

    /**
     * @param  Plan|object  $plan
     * @return list<string>
     */
    public static function featureLines(object $plan, bool $includeRoadmap = false): array
    {
        $limits = is_array($plan->limits ?? null) ? $plan->limits : (array) ($plan->limits ?? []);
        $slug = (string) ($plan->slug ?? '');
        $lines = [];

        $lines[] = self::outletLine((int) ($limits['max_outlets'] ?? 1));
        $lines[] = self::userLine((int) ($limits['max_users'] ?? 2));
        $lines[] = self::transactionLine((int) ($limits['max_transactions_monthly'] ?? 2000));

        foreach ($limits['features'] ?? [] as $feature) {
            if (isset(self::FEATURE_LABELS[$feature])) {
                $lines[] = self::FEATURE_LABELS[$feature];
            }
        }

        if ($includeRoadmap && isset(self::ROADMAP_LINES[$slug])) {
            foreach (self::ROADMAP_LINES[$slug] as $line) {
                $lines[] = $line;
            }
        }

        if ($slug === 'enterprise') {
            $lines[] = 'Dukungan prioritas via WhatsApp';
        }

        $lines[] = 'Gratis coba 14 hari';

        return $lines;
    }

    private static function outletLine(int $max): string
    {
        return $max === -1 ? 'Outlet tanpa batas' : $max.' outlet';
    }

    private static function userLine(int $max): string
    {
        return $max === -1 ? 'Pengguna tanpa batas' : $max.' pengguna';
    }

    private static function transactionLine(int $max): string
    {
        return $max === -1
            ? 'Transaksi tanpa batas'
            : number_format($max, 0, ',', '.').' transaksi/bulan';
    }
}
