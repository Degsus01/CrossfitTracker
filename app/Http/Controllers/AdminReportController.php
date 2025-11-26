<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Pago;
use Illuminate\Http\Request;

class AdminReportController extends Controller
{
    public function asistencias()
    {
        $resumen = [
            'total'    => Asistencia::count(),
            'hoy'      => Asistencia::whereDate('fecha', today())->count(),
            'ultimos7' => Asistencia::whereDate('fecha', '>=', now()->subDays(7))->count(),
        ];

        $porDia = Asistencia::selectRaw('fecha, COUNT(*) as total')
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        return view('reportes.asistencias', compact('resumen', 'porDia'));
    }

    public function finanzas()
    {
        $resumen = [
            'total'    => Pago::sum('monto'),
            'hoy'      => Pago::whereDate('fecha', today())->sum('monto'),
            'ultimos7' => Pago::whereDate('fecha', '>=', now()->subDays(7))->sum('monto'),
        ];

        $porMes = Pago::selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, SUM(monto) as total')
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        return view('reportes.finanzas', compact('resumen', 'porMes'));
    }
}
