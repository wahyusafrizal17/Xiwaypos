<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('xiway.tenancy_enabled', true)) {
            return $next($request);
        }

        $user = $request->user();

        if ($user === null) {
            TenantContext::clear();

            return $next($request);
        }

        $tenantId = $request->session()->get('tenant_id');

        if ($tenantId !== null) {
            $membership = $user->tenants()->where('tenants.id', $tenantId)->exists();

            if ($membership) {
                TenantContext::setById((int) $tenantId);

                return $next($request);
            }

            $request->session()->forget('tenant_id');
        }

        $firstTenant = $user->tenants()->orderBy('tenants.name')->first();

        if ($firstTenant !== null) {
            $request->session()->put('tenant_id', $firstTenant->id);
            TenantContext::set($firstTenant);
        } else {
            TenantContext::clear();
        }

        return $next($request);
    }
}
