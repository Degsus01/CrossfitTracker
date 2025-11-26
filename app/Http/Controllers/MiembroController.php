<?php

namespace App\Http\Controllers;

use App\Models\Miembro;
use App\Models\Membresia;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Models\Role;

class MiembroController extends Controller
{
    public function index()
{
    $miembros = \App\Models\Miembro::with('membresia')
        ->withCount('rutinas')
        ->orderBy('id', 'desc')
        ->paginate(10);

    return view('miembros.index', compact('miembros'));
}

    public function create()
    {
        $membresias = Membresia::orderBy('nombre')->get();
        return view('miembros.create', compact('membresias'));
    }

    public function store(Request $request)
{
    $data = $request->validate([
        'nombre'           => ['required','string','max:255'],
        'apellido'         => ['required','string','max:255'],
        'correo'           => ['required','email','unique:miembros,correo'],
        'telefono'         => ['nullable','string','max:50'],
        'fecha_nacimiento' => ['nullable','date'],
        'membresia_id'     => ['required','exists:membresias,id'],
    ]);

    // 1) Creamos el miembro
    $miembro = Miembro::create($data);

    // 2) Buscamos el rol "miembro" en la tabla roles
    $roleMiembro = Role::where('slug', 'miembro')->first();

    // 3) Creamos (o actualizamos) un usuario ligado a ese correo
    if ($roleMiembro) {
        $passwordPlano = 'miembro123'; // 🔑 contraseña de demo

        User::updateOrCreate(
            ['email' => $miembro->correo],  // buscar por email
            [
                'name'     => $miembro->nombre . ' ' . $miembro->apellido,
                'password' => bcrypt($passwordPlano),
                'role_id'  => $roleMiembro->id,
            ]
        );
    }

    return redirect()
        ->route('miembros.index')
        ->with('ok', 'Miembro creado correctamente. Usuario: '.$miembro->correo.' / Clave: miembro123');
}

    public function edit(Miembro $miembro)
    {
        $membresias = Membresia::orderBy('nombre')->get();
        return view('miembros.edit', compact('miembro', 'membresias'));
    }

    public function update(Request $request, Miembro $miembro)
    {
        $data = $request->validate([
            'nombre'           => ['required','string','max:255'],
            'apellido'         => ['required','string','max:255'],
            'correo'           => [
                'required','email',
                Rule::unique('miembros','correo')->ignore($miembro->id),
            ],
            'telefono'         => ['nullable','string','max:50'],
            'fecha_nacimiento' => ['nullable','date'],
            'membresia_id'     => ['required','exists:membresias,id'],
        ]);

        $miembro->update($data);

        return redirect()->route('miembros.index')
            ->with('ok', 'Miembro actualizado correctamente.');
    }

    public function destroy(Miembro $miembro)
    {
        $miembro->delete();

        return back()->with('ok', 'Miembro eliminado correctamente.');
    }
}
