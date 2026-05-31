<?php

namespace App\Rules;

use App\Support\TenantContext;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;

class ExistsInTenant implements ValidationRule
{
    public function __construct(
        protected string $table,
        protected string $column = 'id',
    ) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! config('xiway.tenancy_enabled', true) || ! TenantContext::hasTenant()) {
            $exists = DB::table($this->table)->where($this->column, $value)->exists();
            if (! $exists) {
                $fail('Data yang dipilih tidak valid.');
            }

            return;
        }

        $exists = DB::table($this->table)
            ->where($this->column, $value)
            ->where('tenant_id', TenantContext::requireId())
            ->exists();

        if (! $exists) {
            $fail('Data yang dipilih tidak valid.');
        }
    }
}
