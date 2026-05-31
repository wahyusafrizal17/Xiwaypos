<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;

class UpgradeController extends Controller
{
    public function index(): RedirectResponse
    {
        return redirect()->to(route('billing.index').'#paket');
    }
}
