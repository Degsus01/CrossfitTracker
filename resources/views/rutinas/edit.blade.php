@extends('layouts.app')

@section('content')
<h2>Editar rutina</h2>

<form action="{{ route('rutinas.update', $rutina) }}" method="POST" class="card p-3 shadow-sm">
  @csrf @method('PUT')

  <div class="mb-3">
    <label>Nombre</label>
    <input name="nombre" class="form-control" value="{{ old('nombre',$rutina->nombre) }}" required>
  </div>

  <div class="mb-3">
    <label>Descripción</label>
    <textarea name="descripcion" class="form-control" rows="3">{{ old('descripcion',$rutina->descripcion) }}</textarea>
  </div>

  <div class="row">
    <div class="col-md-4 mb-3">
      <label>Duración (minutos)</label>
      <input type="number" name="duracion_minutos" class="form-control" value="{{ old('duracion_minutos',$rutina->duracion_minutos) }}">
    </div>
    <div class="col-md-4 mb-3">
      <label>Nivel</label>
      <select name="nivel" class="form-select">
        <option value="">— Seleccione —</option>
        @foreach($niveles as $n)
          <option value="{{ $n }}" @selected(old('nivel',$rutina->nivel)===$n)>{{ $n }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4 mb-3">
      <label>Entrenador</label>
      <select name="entrenador_id" class="form-select">
        <option value="">Sin asignar</option>
        @foreach($entrenadores as $e)
          <option value="{{ $e->id }}" @selected((int)old('entrenador_id',$rutina->entrenador_id)===$e->id)>{{ $e->nombre }} {{ $e->apellido }}</option>
        @endforeach
      </select>
    </div>
  </div>

<div class="mb-3">
    <label for="tipo" class="form-label">Tipo de Rutina</label>
    <select name="tipo" id="tipo" class="form-control" required>
        <option value="Presencial" {{ $rutina->tipo == 'Presencial' ? 'selected' : '' }}>Presencial</option>
        <option value="Virtual" {{ $rutina->tipo == 'Virtual' ? 'selected' : '' }}>Virtual</option>
    </select>
</div>


  <div class="d-flex gap-2">
    <button class="btn btn-primary">Guardar cambios</button>
    <a href="{{ route('rutinas.index') }}" class="btn btn-secondary">Cancelar</a>
  </div>
</form>
@endsection
