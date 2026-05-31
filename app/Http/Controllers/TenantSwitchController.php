<?php

namespace App\Http\Controllers;

use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantSwitchController extends Controller
{
    public function select(Request $request): View|RedirectResponse
    {
        $tenants = $request->user()->tenants()->orderBy('name')->get();

        if ($tenants->count() === 1) {
            $request->session()->put('tenant_id', $tenants->first()->id);
            TenantContext::set($tenants->first());

            return redirect($request->user()->homeUrl());
        }

        return view('tenant.select', compact('tenants'));
    }

    public function switch(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'tenant_id' => ['required', 'integer'],
        ]);

        $tenant = $request->user()->tenants()->where('tenants.id', $data['tenant_id'])->first();

        if ($tenant === null) {
            abort(403);
        }

        $request->session()->put('tenant_id', $tenant->id);
        TenantContext::set($tenant);

        return redirect($request->user()->homeUrl())->with('success', 'Beralih ke '.$tenant->displayName().'.');
    }
}
