@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl leading-tight text-gray-900">Editar clase</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  @if ($errors->any()) {{-- igual bloque de errores --}} @endif

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
    <div class="px-6 py-5 border-b border-gray-100"><h3 class="text-lg font-semibold text-gray-900">Datos de la clase</h3></div>

    <form action="{{ route('clases-virtuales.update',$clase) }}" method="POST" class="px-6 pb-6 pt-5">
      @csrf @method('PUT')

      {{-- mismos campos que create, pero con old(...,$clase->campo) --}}
      {{-- … --}}
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Título *</label>
          <input type="text" name="titulo" value="{{ old('titulo',$clase->titulo) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Fecha *</label>
          <input type="date" name="fecha" value="{{ old('fecha',$clase->fecha?->format('Y-m-d')) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Hora</label>
          <input type="time" name="hora" value="{{ old('hora',$clase->hora) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Plataforma *</label>
          <select name="plataforma" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @foreach(['Zoom','Meet','Teams','Jitsi'] as $p)
              <option value="{{ $p }}" @selected(old('plataforma',$clase->plataforma)===$p)>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700">Duración (min)</label>
          <input type="number" min="10" max="240" name="duracion_min" value="{{ old('duracion_min',$clase->duracion_min) }}" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Enlace *</label>
          <input type="url" name="enlace" value="{{ old('enlace',$clase->enlace) }}" required class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Entrenador</label>
          <select name="entrenador_id" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="">—</option>
            @foreach($entrenadores as $e)
              <option value="{{ $e->id }}" @selected(old('entrenador_id',$clase->entrenador_id)==$e->id)>{{ $e->nombre }}</option>
            @endforeach
          </select>
        </div>
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea name="descripcion" rows="3" class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">{{ old('descripcion',$clase->descripcion) }}</textarea>
        </div>
      </div>

      <div class="mt-7 flex items-center gap-3">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Actualizar
        </button>
        <a href="{{ route('clases-virtuales.index') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
