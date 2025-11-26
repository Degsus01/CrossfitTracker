@extends('layouts.app')

@section('header')
  <div class="flex items-center justify-between">
    <div>
      <h2 class="font-semibold text-xl leading-tight text-gray-900">Asignaciones de rutinas</h2>
      <p class="text-sm text-gray-500">Gestiona qué rutina tiene cada miembro.</p>
    </div>
  </div>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

  {{-- Flash --}}
  @if(session('ok'))
    <div class="rounded-lg bg-green-50 p-4 ring-1 ring-green-200 text-green-800">{{ session('ok') }}</div>
  @endif
  @if(session('error'))
    <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200 text-red-800">{{ session('error') }}</div>
  @endif
  @if($errors->any())
    <div class="rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
      <p class="font-semibold text-sm">Corrige los errores:</p>
      <ul class="list-disc pl-5 mt-1 text-sm space-y-1">
        @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  {{-- Formulario rápido --}}
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Nueva asignación</h3>
    </div>
    <form action="{{ route('asignaciones.store') }}" method="POST" class="px-6 pb-6 pt-5 grid grid-cols-1 md:grid-cols-5 gap-4">
      @csrf

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Miembro *</label>
        <select name="miembro_id" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          <option value="">Seleccione…</option>
          @foreach($miembros as $m)
            <option value="{{ $m->id }}" @selected(old('miembro_id')==$m->id)>{{ $m->nombre }} {{ $m->apellido }}</option>
          @endforeach
        </select>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">Rutina *</label>
        <select name="rutina_id" required class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
          <option value="">Seleccione…</option>
          @foreach($rutinas as $r)
            <option value="{{ $r->id }}" @selected(old('rutina_id')==$r->id)>{{ $r->nombre }} @if($r->nivel)· {{ $r->nivel }} @endif</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">Fecha *</label>
        <input type="date" name="fecha_asignacion" value="{{ old('fecha_asignacion', now()->format('Y-m-d')) }}"
               required class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
      </div>

      <div class="md:col-span-5">
        <label class="block text-sm font-medium text-gray-700">Notas (opcional)</label>
        <input type="text" name="notas" value="{{ old('notas') }}"
               class="mt-1 w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
               placeholder="Ej.: enfoque en técnica, carga moderada…">
      </div>

      <div class="md:col-span-5">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Guardar
        </button>
      </div>
    </form>
  </div>

  {{-- Filtros + listado --}}
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between gap-3">
      <h3 class="text-lg font-semibold text-gray-900">Asignaciones</h3>
      <form method="GET" class="flex items-center gap-2">
        <select name="miembro_id" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
          <option value="">— Filtrar por miembro —</option>
          @foreach($miembros as $m)
            <option value="{{ $m->id }}" @selected(request('miembro_id')==$m->id)>{{ $m->nombre }} {{ $m->apellido }}</option>
          @endforeach
        </select>
        <button class="rounded-lg border px-3 text-sm hover:bg-gray-50">Filtrar</button>
        @if(request('miembro_id'))
          <a href="{{ route('asignaciones.index') }}" class="rounded-lg border px-3 text-sm text-gray-600 hover:bg-gray-50">Limpiar</a>
        @endif
      </form>
    </div>

    @if($asignaciones->count()===0)
      <div class="px-6 py-10 text-center text-gray-500">No hay asignaciones registradas.</div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Miembro</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rutina</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha asignación</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Detalles</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($asignaciones as $a)
              <tr>
                <td class="px-6 py-3 text-sm text-gray-800">
                  {{ $a->m_nombre }} {{ $a->m_apellido }}
                </td>
                <td class="px-6 py-3 text-sm text-gray-800">
                  <div class="flex items-center gap-2">
                    <span class="font-medium">{{ $a->r_nombre }}</span>
                    @if($a->nivel)
                      <span class="inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 ring-1 ring-gray-200">{{ $a->nivel }}</span>
                    @endif
                    @if($a->tipo)
                      <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[11px] font-medium ring-1 ring-inset
                        {{ $a->tipo==='Virtual' ? 'bg-sky-50 text-sky-700 ring-sky-600/20' : 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' }}">
                        {{ $a->tipo }}
                      </span>
                    @endif
                  </div>
                </td>
                <td class="px-6 py-3 text-sm text-gray-700">
                  {{ \Carbon\Carbon::parse($a->fecha_asignacion)->format('d/m/Y') }}
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">
                  @if($a->entrenador)
                    <span class="text-gray-500">Entrenador:</span> {{ $a->entrenador }}
                  @endif
                  @if($a->duracion_minutos)
                    <span class="text-gray-400 mx-2">·</span>
                    <span class="text-gray-500">Duración:</span> {{ $a->duracion_minutos }} min
                  @endif
                </td>
                <td class="px-6 py-3 text-right">
                  <form action="{{ route('asignaciones.destroy', $a->id) }}" method="POST" onsubmit="return confirm('¿Eliminar esta asignación?')">
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
        {{ $asignaciones->withQueryString()->links() }}
      </div>
    @endif
  </div>
</div>
@endsection

