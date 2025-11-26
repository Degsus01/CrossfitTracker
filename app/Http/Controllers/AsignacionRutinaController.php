<?php

namespace App\Http\Controllers;

use App\Models\AsignacionRutina;
use App\Models\Miembro;
use App\Models\Rutina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AsignacionRutinaController extends Controller
{
    public function index(Request $r)
    {
        $miembros = Miembro::orderBy('nombre')->get();
        $rutinas  = Rutina::orderBy('nombre')->get();

        // listado uniendo pivote + relaciones para mostrar en la tabla
        $asignaciones = DB::table('asignaciones_rutinas as ar')
            ->join('miembros as m', 'm.id', '=', 'ar.miembro_id')
            ->join('rutinas  as r', 'r.id', '=', 'ar.rutina_id')
            ->leftJoin('entrenadores as e', 'e.id', '=', 'r.entrenador_id')
            ->when($r->filled('miembro_id'), fn($q) => $q->where('ar.miembro_id', $r->miembro_id))
            ->select([
                'ar.id', 'ar.fecha_asignacion', 'ar.notas',
                'm.nombre as m_nombre', 'm.apellido as m_apellido',
                'r.nombre as r_nombre', 'r.nivel', 'r.tipo', 'r.duracion_minutos',
                'e.nombre as entrenador'
            ])
            ->orderByDesc('ar.fecha_asignacion')
            ->orderByDesc('ar.id')
            ->paginate(10);

        return view('asignaciones.index', compact('miembros','rutinas','asignaciones'));
    }
 public function create(Request $request)
    {
        $miembros = Miembro::orderBy('nombre')->get();
        $rutinas  = Rutina::orderBy('nombre')->get();

        // tu vista create.blade.php tal como la tienes
        return view('asignaciones.create', compact('miembros', 'rutinas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'miembro_id'       => ['required','exists:miembros,id'],
            'rutina_id'        => ['required','exists:rutinas,id'],
            'fecha_asignacion' => ['required','date'],
            'notas'            => ['nullable','string','max:500'],
        ]);

        // evita duplicados exactos (misma rutina el mismo día)
        $yaExiste = DB::table('asignaciones_rutinas')
            ->where('miembro_id', $data['miembro_id'])
            ->where('rutina_id',  $data['rutina_id'])
            ->whereDate('fecha_asignacion', $data['fecha_asignacion'])
            ->exists();

        if ($yaExiste) {
            return back()->with('error', 'Este miembro ya tiene esa rutina asignada en esa fecha.')
                         ->withInput();
        }

        DB::table('asignaciones_rutinas')->insert([
            'miembro_id'       => $data['miembro_id'],
            'rutina_id'        => $data['rutina_id'],
            'fecha_asignacion' => $data['fecha_asignacion'],
            'notas'            => $data['notas'] ?? null,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return redirect()->route('asignaciones.index')->with('ok', 'Rutina asignada correctamente.');
    }

    public function destroy(int $asignacion)
{
    \DB::table('asignaciones_rutinas')->where('id', $asignacion)->delete();
    return back()->with('ok', 'Asignación eliminada.');
}

}
