<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use App\Support\TenantStorage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id',
        'nama_produk',
        'harga',
        'kategori_id',
        'gambar',
    ];

    protected function casts(): array
    {
        return [
            'harga' => 'integer',
        ];
    }

    /** @return BelongsTo<Category, $this> */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'kategori_id');
    }

    /** @return HasMany<TransactionDetail, $this> */
    public function transactionDetails(): HasMany
    {
        return $this->hasMany(TransactionDetail::class, 'product_id');
    }

    public function imageUrl(): ?string
    {
        return TenantStorage::productUrl($this->gambar);
    }

    /** Menu minuman/kopi: perlu pilih Ice/Hot di kasir. */
    public function requiresSuhuPilihan(): bool
    {
        $nama = mb_strtolower($this->category?->nama_kategori ?? '');

        return (bool) preg_match(
            '/minuman|kopi|coffee|teh|latte|espresso|brew|jus|juice|soda|matcha|milk|drink|mocktail|mocha|frappe|shake|americano|cappuccino|bubble/i',
            $nama
        );
    }

    /** Biji / susu tambahan — sama cakupan kategori dengan suhu (minuman/kopi). */
    public function allowsOrderAddons(): bool
    {
        return $this->requiresSuhuPilihan();
    }
}
