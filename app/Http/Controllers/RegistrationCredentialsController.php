<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegistrationCredentialsController extends Controller
{
    public function dismiss(Request $request): JsonResponse
    {
        $request->session()->forget('registration_credentials');

        return response()->json(['ok' => true]);
    }
}
