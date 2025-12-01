<?php

namespace App\Http\Controllers;

use App\Models\ClaseVirtual;
use App\Models\Entrenador;
use Illuminate\Http\Request;

class ClaseVirtualController extends Controller
{
    public function index(Request $request)
{
    // Query base
    $query = ClaseVirtual::with('entrenador')
        ->when($request->filled('desde'), fn($q) => $q->whereDate('fecha', '>=', $request->desde))
        ->when($request->filled('hasta'), fn($q) => $q->whereDate('fecha', '<=', $request->hasta))
        ->when($request->filled('plataforma'), fn($q) => $q->where('plataforma', $request->plataforma))
        ->when($request->filled('q'), function ($q) use ($request) {
            $q->where(function ($w) use ($request) {
                $w->where('titulo', 'like', '%'.$request->q.'%')
                  ->orWhereHas('entrenador', fn($e) => $e->where('nombre', 'like', '%'.$request->q.'%'));
            });
        });

    // 👉 OJO: NO FILTRAMOS POR entrenadores.user_id porque esa columna NO existe

    // Ordenar + paginar
    $clases = $query
        ->orderBy('fecha')
        ->orderBy('hora')
        ->paginate(10);

    return view('clases-virtuales.index', compact('clases'));
}
/**
     * Formulario para crear una nueva clase virtual (zona admin/entrenador).
     */
    public function create()
    {
        // si tienes tabla entrenadores, cargamos para el <select>
        $entrenadores = Entrenador::orderBy('nombre')->get();

        // esta vista debe existir: resources/views/clases-virtuales/create.blade.php
        return view('clases-virtuales.create', compact('entrenadores'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titulo'        => ['required','string','max:150'],
            'fecha'         => ['required','date'],
            'hora'          => ['nullable','date_format:H:i'],
            'plataforma'    => ['required','in:Zoom,Meet,Teams,Jitsi'],
            'duracion_min'  => ['nullable','integer','min:10','max:240'],
            'enlace'        => ['required','url','max:255'],
            'entrenador_id' => ['nullable','exists:entrenadores,id'],
            'descripcion'   => ['nullable','string','max:1000'],
        ]);

        ClaseVirtual::create($data);

        return redirect()->route('clases-virtuales.index')
            ->with('ok', 'Clase creada.');
    }

    public function edit(ClaseVirtual $clases_virtuale)
    {
        // Nota: por convención de Laravel, el parámetro se llama por el nombre de la ruta
        // 'clases-virtuales.update'. Aquí $clases_virtuale ES la clase a editar.
        $entrenadores = Entrenador::orderBy('nombre')->get();

        return view('clases-virtuales.edit', [
            'clase'        => $clases_virtuale,
            'entrenadores' => $entrenadores,
        ]);
    }

    public function update(Request $request, ClaseVirtual $clases_virtuale)
    {
        $data = $request->validate([
            'titulo'        => ['required','string','max:150'],
            'fecha'         => ['required','date'],
            'hora'          => ['nullable','date_format:H:i'],
            'plataforma'    => ['required','in:Zoom,Meet,Teams,Jitsi'],
            'duracion_min'  => ['nullable','integer','min:10','max:240'],
            'enlace'        => ['required','url','max:255'],
            'entrenador_id' => ['nullable','exists:entrenadores,id'],
            'descripcion'   => ['nullable','string','max:1000'],
        ]);

        $clases_virtuale->update($data);

        return redirect()->route('clases-virtuales.index')
            ->with('ok', 'Clase actualizada.');
    }

    public function destroy(ClaseVirtual $clases_virtuale)
    {
        $clases_virtuale->delete();
        return back()->with('ok', 'Clase eliminada.');
    }
}
