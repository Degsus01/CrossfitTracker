@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Editar miembro</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.miembros.update', $miembro) }}" method="POST" class="card p-3 shadow-sm">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Nombre</label>
                <input type="text" name="nombre" class="form-control"
                       value="{{ old('nombre', $miembro->nombre) }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Apellido</label>
                <input type="text" name="apellido" class="form-control"
                       value="{{ old('apellido', $miembro->apellido) }}" required>
            </div>
        </div>

        <div class="mb-3">
            <label>Correo</label>
            <input type="email" name="correo" class="form-control"
                   value="{{ old('correo', $miembro->correo) }}" required>
        </div>

        <div class="mb-3">
            <label>Teléfono</label>
            <input type="text" name="telefono" class="form-control"
                   value="{{ old('telefono', $miembro->telefono) }}">
        </div>

        <div class="mb-3">
            <label>Fecha de nacimiento</label>
            <input type="date" name="fecha_nacimiento" class="form-control"
                   value="{{ old('fecha_nacimiento', $miembro->fecha_nacimiento) }}">
        </div>

        <div class="mb-3">
            <label>Membresía</label>
            <select name="id_membresia" class="form-select" required>
                @foreach($membresias as $m)
                    <option value="{{ $m->id }}"
                        {{ (int)old('id_membresia', $miembro->id_membresia) === $m->id ? 'selected' : '' }}>
                        {{ $m->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('miembros.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
