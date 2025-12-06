<?php

namespace App\Http\Controllers;

use App\Models\AttendanceReport;
use App\Models\Asistencia;
use Carbon\Carbon;

class AttendanceReportController extends Controller
{
    public function store()
    {
        $inicio = Carbon::now()->subMonth();
        $fin = Carbon::now();

        $total = Asistencia::whereBetween('created_at', [$inicio, $fin])->count();

        AttendanceReport::create([
            'start_date' => $inicio,
            'end_date' => $fin,
            'total_asistencias' => $total,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Reporte de asistencia generado.');
    }
}
