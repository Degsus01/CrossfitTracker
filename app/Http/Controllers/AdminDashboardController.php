<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Cargamos miembros con su membresía y el último pago
        $miembros = Miembro::with([
                'membresia',
                'pagos' => fn($q) => $q->orderByDesc('fecha')->limit(1),
            ])->get();

        // Total de miembros
        $totalMiembros = $miembros->count();

        // Usamos el accessor membresia_estado que agregamos al modelo Miembro
        $miembrosActivos = $miembros->filter(
            fn ($m) => $m->membresia_estado === 'activa'
        )->count();

        $membresiasVencidas = $miembros->filter(
            fn ($m) => $m->membresia_estado === 'vencida'
        )->count();

        return view('dashboards.admin', compact(
            'totalMiembros',
            'miembrosActivos',
            'membresiasVencidas'
        ));
    }
}
