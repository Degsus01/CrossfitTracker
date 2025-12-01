<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        // Normalizar roles permitidos
        $allowed = collect($roles)
            ->flatMap(fn ($r) => explode(',', (string) $r))
            ->map(fn ($r) => strtolower(trim($r)))
            ->filter()
            ->values()
            ->all();

         // ⬅️ Rol del usuario, tomado de la relación role->slug
        $user = Auth::user();
        $userRole = strtolower(
            (string)($user->role->slug ?? $user->rol ?? '')  // usa slug, y si acaso existe aún 'rol', sirve de respaldo
        );

        // Si pusieras '*' en el middleware, deja pasar a cualquiera autenticado
        $allowAny = in_array('*', $allowed, true);

        if (! $allowAny && ! in_array($userRole, $allowed, true)) {
            abort(403, 'No tienes permiso para acceder a esta sección.');
        }

        return $next($request);
    }
}
