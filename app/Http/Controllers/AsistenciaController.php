<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Asistencia;
use App\Models\Miembro;
use Carbon\Carbon;

class AsistenciaController extends Controller
{
    public function index(Request $request)
    {
        // Para el <select>
        $miembros = Miembro::with('membresia')->orderBy('nombre')->get();

        // Listado con búsqueda opcional
        $asistencias = Asistencia::with('miembro')
            ->when($request->q, function ($q) use ($request) {
                $q->whereHas('miembro', function ($qq) use ($request) {
                    $qq->where('nombre','like','%'.$request->q.'%')
                       ->orWhere('apellido','like','%'.$request->q.'%');
                })->orWhereDate('fecha', $request->q);
            })
            ->orderByDesc('fecha')
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('asistencias.index', compact('miembros','asistencias'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'miembro_id' => ['required','exists:miembros,id'],
            'fecha'      => ['required','date'],
            'presente'   => ['required','boolean'],
        ]);

        // Evitar duplicados el mismo día (opcional)
        $existe = Asistencia::where('miembro_id',$data['miembro_id'])
                    ->whereDate('fecha', Carbon::parse($data['fecha'])->toDateString())
                    ->exists();
        if ($existe) {
            return back()->with('error','Ya existe un registro para ese miembro en esa fecha.')
                         ->withInput();
        }

        Asistencia::create($data);

        return back()->with('ok','Asistencia registrada.');
    }

    public function destroy(Asistencia $asistencia)
    {
        $asistencia->delete();
        return back()->with('ok','Registro eliminado.');
    }
}
