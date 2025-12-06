<?php

namespace App\Http\Controllers;

use App\Models\FinancialReport;
use App\Models\Pago;
use Carbon\Carbon;

class FinancialReportController extends Controller
{
    public function store()
    {
        // Último mes (ajusta si quieres otro rango)
        $inicio = Carbon::now()->subMonth()->startOfDay();
        $fin    = Carbon::now()->endOfDay();

        // OJO: usa las columnas reales de tu tabla pagos
        // en tu proyecto tienes columna 'fecha' y columna 'monto'
        $total = Pago::whereBetween('fecha', [$inicio, $fin])->sum('monto');

        $totalPendiente = 0; // si luego calculas deudas, lo actualizamos

        // 🟢 AQUÍ: guardar en financial_reports, NO en attendance_reports
        FinancialReport::create([
            'start_date'      => $inicio->toDateString(),
            'end_date'        => $fin->toDateString(),
            'total_ingresos'  => $total,
            'total_pendiente' => $totalPendiente,
            'created_by'      => auth()->id(),
        ]);

        return back()->with('success', 'Reporte financiero generado correctamente.');
    }
}
