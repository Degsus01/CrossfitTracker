<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;


class AuthenticatedSessionController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        // Autenticar credenciales
        $request->authenticate();

        // Regenerar la sesión
        $request->session()->regenerate();

        // Obtener rol desde relación roles
        $role = optional($request->user()->role)->slug;

        return redirect()->intended(match ($role) {
            'admin'      => route('admin.dashboard'),
            'entrenador' => route('entrenador.dashboard'),
            'miembro'    => route('mi.dashboard'),
            default      => route('home'),
        });
    }

    public function destroy(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
