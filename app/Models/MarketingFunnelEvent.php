<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarketingFunnelEvent extends Model
{
    public const EVENT_LANDING_VIEW = 'landing_view';

    public const EVENT_REGISTER_VIEW = 'register_view';

    public const EVENT_REGISTER_SUBMIT = 'register_submit';

    public const EVENT_FIRST_SALE = 'first_sale';

    public $timestamps = false;

    protected $fillable = [
        'event',
        'visitor_hash',
        'tenant_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    /** @return BelongsTo<Tenant, $this> */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
