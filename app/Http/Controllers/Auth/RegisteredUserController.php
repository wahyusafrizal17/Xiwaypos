<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\MarketingFunnelTracker;
use App\Services\TenantProvisioner;
use App\Support\StaffPasswords;
use App\Support\TenantContext;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, TenantProvisioner $provisioner, MarketingFunnelTracker $funnelTracker): RedirectResponse
    {
        $data = $request->validate([
            'tenant_name' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'whatsapp' => ['required', 'string', 'max:20', 'regex:/^(\+?62|0)[0-9]{8,13}$/'],
        ], [
            'whatsapp.regex' => 'Format WhatsApp tidak valid. Gunakan 08xx atau 628xx.',
        ]);

        $result = $provisioner->provision([
            'name' => $data['name'],
            'tenant_name' => $data['tenant_name'],
            'whatsapp' => $data['whatsapp'],
        ]);

        event(new Registered($result['user']));

        Auth::login($result['user']);

        $request->session()->regenerate();
        $request->session()->put('tenant_id', $result['tenant']->id);
        $request->session()->put('registration_credentials', $result['credentials']);
        StaffPasswords::importFromSession($result['tenant'], $result['credentials']);
        TenantContext::set($result['tenant']);

        $funnelTracker->recordRegisterSubmit($request, $result['tenant']);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Selamat datang! Lengkapi data toko Anda di menu Admin.');
    }
}
