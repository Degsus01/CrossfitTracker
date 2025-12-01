@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl leading-tight text-gray-900">Crear rutina</h2>
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
      <h3 class="text-lg font-semibold text-gray-900">Datos de la rutina</h3>
      <p class="text-sm text-gray-500">Define el nivel, tipo y duración estimada.</p>
    </div>

    <form action="{{ route('rutinas.store') }}" method="POST" class="px-6 pb-6 pt-5">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Nombre *</label>
          <input type="text" name="nombre" value="{{ old('nombre') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="WOD Fran, Fuerza torso, etc.">
          @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Nivel *</label>
          <select name="nivel" required
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            @php $niveles=['Inicial','Intermedio','Avanzado']; @endphp
            <option value="" disabled {{ old('nivel') ? '' : 'selected' }}>Seleccione…</option>
            @foreach($niveles as $n)
              <option value="{{ $n }}" @selected(old('nivel')===$n)>{{ $n }}</option>
            @endforeach
          </select>
          @error('nivel') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

{{-- Tipo --}}
<div>
  <label class="block text-sm font-medium text-gray-700">Tipo</label>
  <select name="tipo"
          class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    <option value="" {{ old('tipo') ? '' : 'selected' }}>—</option>
    <option value="Presencial" @selected(old('tipo')==='Presencial')>Presencial</option>
    <option value="Virtual"    @selected(old('tipo')==='Virtual')>Virtual</option>
  </select>
  @error('tipo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

        {{-- Categoría (Fuerza/HIIT/MetCon/...) --}}
<div>
  <label class="block text-sm font-medium text-gray-700">Categoría</label>
  <select name="categoria"
          class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    <option value="" {{ old('categoria', isset($rutina)?$rutina->categoria:null) ? '' : 'selected' }}>—</option>
    @php $cats = ['Fuerza','HIIT','MetCon','Movilidad','Técnica']; @endphp
    @foreach($cats as $c)
      <option value="{{ $c }}" @selected(old('categoria', isset($rutina)?$rutina->categoria:null)===$c)>{{ $c }}</option>
    @endforeach
  </select>
  @error('categoria') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>


        <div>
          <label class="block text-sm font-medium text-gray-700">Duración (min) *</label>
          <input type="number" min="5" max="300" name="duracion_min" value="{{ old('duracion_min') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="45">
          @error('duracion_min') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700">Entrenador (texto breve)</label>
          <input type="text" name="entrenador" value="{{ old('entrenador') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="Opcional">
          @error('entrenador') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Descripción</label>
          <textarea name="descripcion" rows="4"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Objetivo, bloques (calentamiento, parte principal, cool down), RM%, etc.">{{ old('descripcion') }}</textarea>
          @error('descripcion') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mt-7 flex items-center gap-3">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Guardar
        </button>
        <a href="{{ route('rutinas.index') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
