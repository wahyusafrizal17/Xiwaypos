<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantMember
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! config('xiway.tenancy_enabled', true)) {
            return $next($request);
        }

        $user = $request->user();

        if ($user === null) {
            return redirect()->route('login');
        }

        if ($user->isPlatformAdmin()) {
            $routeName = (string) ($request->route()?->getName() ?? '');

            if (str_starts_with($routeName, 'platform.') || str_starts_with($routeName, 'profile.')) {
                return $next($request);
            }

            return redirect()->route('platform.tenants.index');
        }

        if (! TenantContext::hasTenant()) {
            if ($user->tenants()->exists()) {
                return redirect()->route('tenant.select');
            }

            abort(403, 'Akun Anda belum terhubung ke bisnis manapun.');
        }

        $isMember = $user->tenants()
            ->where('tenants.id', TenantContext::requireId())
            ->exists();

        if (! $isMember) {
            abort(403, 'Anda tidak memiliki akses ke bisnis ini.');
        }

        return $next($request);
    }
}
