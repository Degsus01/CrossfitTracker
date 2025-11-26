@extends('layouts.app')

@section('header')
  <div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl leading-tight text-gray-900">Asistencias</h2>
  </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

  {{-- Mensajes flash --}}
  @if(session('ok'))
    <div class="rounded-lg bg-green-50 p-4 ring-1 ring-green-200 text-green-800">
      {{ session('ok') }}
    </div>
  @endif
  @if(session('error'))
    <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200 text-red-800">
      {{ session('error') }}
    </div>
  @endif

  {{-- Errores de validación --}}
  @if ($errors->any())
    <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
      <p class="font-semibold text-sm">Revisa los siguientes errores:</p>
      <ul class="list-disc pl-5 mt-1 text-sm space-y-1">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Card: Registro rápido --}}
  <div
    x-data="{
      hoy: '{{ now()->format('Y-m-d') }}',
      presente: '{{ old('presente', '1') }}',
    }"
    class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200"
  >
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Registro de Asistencias</h3>
      <p class="text-sm text-gray-500">Selecciona un miembro, define la fecha y marca si asistió.</p>
    </div>

    <form action="{{ route('asistencias.store') }}" method="POST" class="px-6 pb-6 pt-5">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

        {{-- Miembro --}}
        <div class="md:col-span-2">
          <label for="miembro_id" class="block text-sm font-medium text-gray-700">
            Miembro <span class="text-red-600">*</span>
          </label>
          <select id="miembro_id" name="miembro_id" required
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="" disabled {{ old('miembro_id') ? '' : 'selected' }}>Seleccione…</option>
            @foreach($miembros as $m)
              <option value="{{ $m->id }}" @selected(old('miembro_id') == $m->id)>
                {{ $m->nombre }} {{ $m->apellido }}
                @if($m->membresia?->nombre) — {{ $m->membresia->nombre }} @endif
              </option>
            @endforeach
          </select>
          @error('miembro_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Fecha --}}
        <div>
          <label for="fecha" class="block text-sm font-medium text-gray-700">Fecha</label>
          <div class="mt-1 flex gap-2">
            <input type="date" id="fecha" name="fecha"
                   value="{{ old('fecha', now()->format('Y-m-d')) }}"
                   class="block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <button type="button"
                    @click="$refs.fh.value = hoy; $refs.fh.dispatchEvent(new Event('input'));"
                    class="px-3 py-2 rounded-lg text-sm border border-gray-300 hover:bg-gray-50"
                    aria-label="Hoy">Hoy</button>
          </div>
          {{-- input referenciado para el botón Hoy --}}
          <input x-ref="fh" type="hidden">
          @error('fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Presente --}}
        <div>
          <label for="presente" class="block text-sm font-medium text-gray-700">Presente</label>
          <select id="presente" name="presente"
                  x-model="presente"
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="1">Sí</option>
            <option value="0">No</option>
          </select>
          @error('presente') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mt-6 flex items-center gap-3">
        <button type="submit"
                class="inline-flex items-center justify-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
          Registrar
        </button>
        <span class="text-xs text-gray-400">Tip: usa la tecla <kbd class="px-1 rounded border">Enter</kbd> para enviar.</span>
      </div>
    </form>
  </div>

  {{-- Card: Últimos registros --}}
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900">Listado</h3>
      {{-- Buscador simple (opcional si implementas en el controller) --}}
      <form action="{{ route('asistencias.index') }}" method="GET" class="hidden md:block">
        <input type="text" name="q" value="{{ request('q') }}"
               placeholder="Buscar miembro o fecha…"
               class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
      </form>
    </div>

    @if($asistencias->count() === 0)
      <div class="px-6 py-10 text-center text-gray-500">No hay registros</div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Miembro</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($asistencias as $a)
              <tr>
                <td class="px-6 py-3 text-sm text-gray-800">
                  {{ $a->miembro->nombre }} {{ $a->miembro->apellido }}
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">
                  {{ \Carbon\Carbon::parse($a->fecha)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-3">
                  @if($a->presente)
                    <span class="inline-flex items-center rounded-full bg-green-50 px-2.5 py-0.5 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20">
                      Presente
                    </span>
                  @else
                    <span class="inline-flex items-center rounded-full bg-red-50 px-2.5 py-0.5 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">
                      Ausente
                    </span>
                  @endif
                </td>
                <td class="px-6 py-3 text-right">
                  <form action="{{ route('asistencias.destroy', $a) }}" method="POST" onsubmit="return confirm('¿Eliminar este registro?')">
                    @csrf @method('DELETE')
                    <button class="text-sm text-red-600 hover:text-red-700">Eliminar</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4 border-t border-gray-100">
        {{ $asistencias->withQueryString()->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
