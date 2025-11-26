@extends('layouts.app')

@section('header')
  <div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl text-gray-100 leading-tight">Miembros</h2>
  </div>
@endsection

@section('content')
<div class="max-w-5xl mx-auto">
  {{-- Flash OK --}}
  @if(session('ok'))
    <div class="mb-4 rounded-lg bg-green-50 p-4 ring-1 ring-green-200 text-green-800">
      {{ session('ok') }}
    </div>
  @endif

  <div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Registrar nuevo miembro</h1>
    <p class="text-gray-500 mt-1">Campos con <span class="text-red-600">*</span> son obligatorios.</p>
  </div>

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Datos personales</h3>
      <p class="text-sm text-gray-500">Usa datos reales para mantener historial de asistencias y pagos.</p>
    </div>

    @if ($errors->any())
      <div class="px-6 pt-6">
        <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
          <p class="font-semibold text-sm text-red-800">Revisa los siguientes errores:</p>
          <ul class="list-disc pl-5 mt-1 text-sm text-red-700 space-y-1">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    @endif

    <form action="{{ route('admin.miembros.store') }}" method="POST" class="px-6 pb-6 pt-4">
      @csrf

      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        {{-- Nombre --}}
        <div>
          <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre <span class="text-red-600">*</span></label>
          <input type="text" id="nombre" name="nombre" value="{{ old('nombre') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="Ej. Daniela" autocomplete="given-name">
          @error('nombre') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Apellido --}}
        <div>
          <label for="apellido" class="block text-sm font-medium text-gray-700">Apellido <span class="text-red-600">*</span></label>
          <input type="text" id="apellido" name="apellido" value="{{ old('apellido') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="Ej. Gómez" autocomplete="family-name">
          @error('apellido') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Correo (¡clave: name="correo"!) --}}
        <div class="md:col-span-2">
          <label for="correo" class="block text-sm font-medium text-gray-700">Correo electrónico <span class="text-red-600">*</span></label>
          <input type="email" id="correo" name="correo" value="{{ old('correo') }}" required
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="nombre@correo.com" autocomplete="email">
          @error('correo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Teléfono --}}
        <div>
          <label for="telefono" class="block text-sm font-medium text-gray-700">Teléfono</label>
          <input type="tel" id="telefono" name="telefono" value="{{ old('telefono') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="+57 300 000 0000" autocomplete="tel">
          @error('telefono') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Fecha de nacimiento --}}
        <div>
          <label for="fecha_nacimiento" class="block text-sm font-medium text-gray-700">Fecha de nacimiento</label>
          <input type="date" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          @error('fecha_nacimiento') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Membresía --}}
<div class="md:col-span-2">
  <label for="membresia_id" class="block text-sm font-medium text-gray-700">
    Membresía <span class="text-red-600">*</span>
  </label>
  <select id="membresia_id" name="membresia_id" required
          class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
    <option value="" disabled {{ old('membresia_id') ? '' : 'selected' }}>Seleccione…</option>
    @foreach($membresias as $m)
      <option value="{{ $m->id }}" @selected(old('membresia_id') == $m->id)>
        {{ $m->nombre }} — {{ number_format($m->precio, 0, ',', '.') }} ({{ $m->duracion_dias }} días)
      </option>
    @endforeach
  </select>
  @error('membresia_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
  <p class="mt-1 text-xs text-gray-400">Los precios y duración provienen de tu tabla de membresías.</p>
</div>


        {{-- Notas / objetivos (opcional) --}}
        <div class="md:col-span-2">
          <label for="notas" class="block text-sm font-medium text-gray-700">Notas (opcional)</label>
          <textarea id="notas" name="notas" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Objetivos, lesiones, restricciones, etc.">{{ old('notas') }}</textarea>
          @error('notas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      {{-- Acciones --}}
      <div class="mt-8 flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          Guardar
        </button>
        <a href="{{ route('miembros.index') }}"
           class="inline-flex items-center justify-center rounded-lg px-4 py-2.5 text-sm font-semibold text-gray-700 hover:text-gray-900 hover:bg-gray-100">
          Cancelar
        </a>
      </div>
    </form>
  </div>
</div>
@endsection
