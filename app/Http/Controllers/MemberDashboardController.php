<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Miembro;
use App\Models\AsignacionRutina;
use App\Models\Pago;
use App\Models\ClaseVirtual;
use Illuminate\Http\Request;


class MemberDashboardController extends Controller
{
       protected function miembroFromUser($user): ?Miembro
    {
        if (! $user) {
            return null;
        }

        return Miembro::with('membresia')
            ->where('correo', $user->email)
            ->first();
    }
    public function index(Request $request)
    {
        $user    = $request->user();
        $miembro = $this->miembroFromUser($user);

        if (! $miembro) {
            // Si no tiene miembro asociado, lo mando a miembros.index o muestro mensaje
            return redirect()
                ->route('mi.asignaciones')
                ->with('ok', 'Aún no tienes un perfil de miembro asociado. Pide al administrador que te registre con tu correo.');
        }

         $resumen = [
        'membresia'         => $miembro->membresia,
        'total_asistencias' => $miembro->asistencias()->count(),
        'ultimo_pago'       => $miembro->pagos()->latest('fecha')->first(),
    ];

    // ---- Progreso: últimas 6 meses de asistencias y pagos ----
    $asistenciasPorMes = $miembro->asistencias()
        ->selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, COUNT(*) as total')
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    $pagosPorMes = $miembro->pagos()
        ->selectRaw('DATE_FORMAT(fecha, "%Y-%m") as mes, SUM(monto) as total')
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

    return view('dashboards.miembro', [
        'miembro'            => $miembro,
        'resumen'            => $resumen,
        'asistenciasPorMes'  => $asistenciasPorMes,
        'pagosPorMes'        => $pagosPorMes,
    ]);
}

    /**
     * Listado de rutinas asignadas al miembro logueado.
     */
    public function misAsignaciones(Request $request)
    {
        $miembro = $this->miembroFromUser($request->user());
        abort_unless($miembro, 403);

        // Asumiendo relación many-to-many Miembro ↔ Rutina
        $asignaciones = $miembro->rutinas()
            ->withPivot('fecha_asignacion', 'notas')
            ->orderByDesc('pivot_fecha_asignacion')
            ->paginate(10);

        return view('miembros.mis-asignaciones', compact('miembro', 'asignaciones'));
    }

    /**
     * Pagos realizados por el miembro.
     */
    public function misPagos(Request $request)
    {
        $miembro = $this->miembroFromUser($request->user());
        abort_unless($miembro, 403);

        $pagos = $miembro->pagos()
            ->orderByDesc('fecha')
            ->paginate(10);

        return view('miembros.mis-pagos', compact('miembro', 'pagos'));
    }

    /**
     * Clases virtuales disponibles (para ver enlaces y unirse).
     * Aquí no filtramos por miembro porque la tabla de clases
     * no guarda a qué miembros están inscritas.
     */
    public function clasesVirtuales(Request $request)
    {
        $clases = ClaseVirtual::orderBy('fecha')
            ->orderBy('hora')
            ->paginate(10);

        return view('miembros.mis-clases', compact('clases'));
    }
}
