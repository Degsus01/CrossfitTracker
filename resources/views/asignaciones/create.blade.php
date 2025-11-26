@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Asignar rutina a miembro</h2>

    @if ($errors->any())
      <div class="alert alert-danger">
          <ul class="mb-0">
              @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
              @endforeach
          </ul>
      </div>
    @endif

    <form action="{{ route('asignaciones.store') }}" method="POST" class="card p-4 shadow-sm">
        @csrf
        <div class="row">
            <div class="col-md-5 mb-3">
                <label>Miembro</label>
                <select name="miembro_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @php $miembroPre = request('miembro_id'); @endphp
                    @foreach($miembros as $m)
                        <option value="{{ $m->id }}" @selected(old('miembro_id', $miembroPre) == $m->id)>
                            {{ $m->nombre }} {{ $m->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-5 mb-3">
                <label>Rutina</label>
                <select name="rutina_id" class="form-select" required>
                    <option value="">Seleccione...</option>
                    @foreach($rutinas as $r)
                        <option value="{{ $r->id }}" @selected(old('rutina_id') == $r->id)>
                            {{ $r->nombre }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-2 mb-3">
                <label>Fecha de asignación</label>
                <input type="date" name="fecha_asignacion" class="form-control"
                    value="{{ old('fecha_asignacion', now()->toDateString()) }}" required>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-success">Guardar</button>
            <a href="{{ route('asignaciones.index') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
</div>
@endsection
