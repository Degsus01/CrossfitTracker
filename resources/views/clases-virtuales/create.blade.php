@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl leading-tight text-gray-900">Nueva clase</h2>
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
  @if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
      <p class="font-semibold text-sm">Corrige los errores:</p>
      <ul class="list-disc pl-5 mt-1 text-sm space-y-1">
        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Datos de la clase</h3>
    </div>

    <form action="{{ route('admin.clases-virtuales.store') }}" method="POST" class="px-6 pb-6 pt-5">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Título *</label>
          <input type="text" name="titulo" value="{{ old('titulo') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="WOD en casa · Full body">
          @error('titulo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Fecha *</label>
          <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          @error('fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Hora (opcional)</label>
          <input type="time" name="hora" value="{{ old('hora') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          @error('hora') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Plataforma *</label>
          <select name="plataforma" required
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="" {{ old('plataforma') ? '' : 'selected' }}>Seleccione…</option>
            @foreach(['Zoom','Meet','Teams','Jitsi'] as $p)
              <option value="{{ $p }}" @selected(old('plataforma')===$p)>{{ $p }}</option>
            @endforeach
          </select>
          @error('plataforma') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Duración (min)</label>
          <input type="number" min="10" max="240" name="duracion_min" value="{{ old('duracion_min',60) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          @error('duracion_min') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Enlace *</label>
          <input type="url" name="enlace" value="{{ old('enlace') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="https://us02web.zoom.us/j/XXXXXXXX">
          @error('enlace') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Entrenador</label>
          <select name="entrenador_id"
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="" {{ old('entrenador_id') ? '' : 'selected' }}>—</option>
            @foreach($entrenadores as $e)
              <option value="{{ $e->id }}" @selected(old('entrenador_id')==$e->id)>{{ $e->nombre }}</option>
            @endforeach
          </select>
          @error('entrenador_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea name="descripcion" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Objetivo, materiales (banda, mancuernas), etc.">{{ old('descripcion') }}</textarea>
          @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mt-7 flex items-center gap-3">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Guardar
        </button>
        <a href="{{ route('clases-virtuales.index') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection


