<?php

namespace App\Http\Controllers;

use App\Models\Rutina;
use App\Models\Entrenador;
use Illuminate\Http\Request;

class RutinaController extends Controller
{
    public function index()
    {
        
        $rutinas = Rutina::with('entrenador')->paginate(10);
        return view('rutinas.index', compact('rutinas'));
    }

    public function create()
    {
        if (auth()->user()->rol === 'invitado') {
    abort(403, 'No tienes permiso para realizar esta acción.');
}
        $entrenadores = Entrenador::orderBy('nombre')->get();
        $niveles = ['Básico','Intermedio','Avanzado']; // sugerido
        return view('rutinas.create', compact('entrenadores','niveles'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->rol === 'invitado') {
    abort(403, 'No tienes permiso para realizar esta acción.');
}

        $data = $request->validate([
            'nombre'            => 'required|string|max:120',
            'descripcion'       => 'nullable|string',
            'duracion_minutos'  => 'nullable|integer|min:1',
            'nivel'             => 'nullable|string|max:30',
            'entrenador_id'     => 'nullable|exists:entrenadores,id',
            'tipo'              => 'nullable|string|in:Presencial,Virtual',
            'categoria'         => 'nullable|string|in:Fuerza,HIIT,MetCon,Movilidad,Técnica',
        ]);

        Rutina::create($data);
        return redirect()->route('rutinas.index')->with('ok', 'Rutina creada');
    }

    public function edit(Rutina $rutina)
    {
        $entrenadores = Entrenador::orderBy('nombre')->get();
        $niveles = ['Básico','Intermedio','Avanzado'];
        return view('rutinas.edit', compact('rutina','entrenadores','niveles'));
    }

    public function update(Request $request, Rutina $rutina)
    {
        $data = $request->validate([
            'nombre'            => 'required|string|max:120',
            'descripcion'       => 'nullable|string',
            'duracion_minutos'  => 'nullable|integer|min:1',
            'nivel'             => 'nullable|string|max:30',
            'entrenador_id'     => 'nullable|exists:entrenadores,id',
            'tipo'              => 'nullable|string|in:Presencial,Virtual',
            'categoria'         => 'nullable|string|in:Fuerza,HIIT,MetCon,Movilidad,Técnica',
        ]);

        $rutina->update($data);
        return redirect()->route('rutinas.index')->with('ok', 'Rutina actualizada');
    }

    public function destroy(Rutina $rutina)
    {
        $rutina->delete();
        return back()->with('ok', 'Rutina eliminada');
    }
}
