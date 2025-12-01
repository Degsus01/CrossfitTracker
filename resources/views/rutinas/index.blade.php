@extends('layouts.app') 

@section('header')
  <div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl leading-tight text-gray-900">Rutinas</h2>

    {{-- 🔒 Solo admin y entrenador pueden crear rutinas --}}
    @auth
      @php
        // Tomamos el slug del rol desde la relación roles
        $rol = strtolower(optional(auth()->user()->role)->slug);
      @endphp

      @if (in_array($rol, ['admin', 'entrenador']))
        <a href="{{ route('rutinas.create') }}" 
           class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
          <svg class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
          </svg>
          Nueva rutina
        </a>
      @endif
    @endauth
  </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto">
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Listado</h3>
    </div>

    @if($rutinas->count() === 0)
      <div class="px-6 py-10 text-center text-gray-500">Sin registros</div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nombre</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nivel</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tipo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Duración (min)</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrenador</th>

              {{-- 🔒 Columna Acciones solo visible si es admin --}}
              @auth
                @if(auth()->user()->rol === 'admin')
                  <th class="px-6 py-3"></th>
                @endif
              @endauth
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($rutinas as $r)
              <tr>
                <td class="px-6 py-3 text-sm text-gray-500">#{{ $r->id }}</td>
                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $r->nombre }}</td>

                {{-- Nivel con colores dinámicos --}}
                <td class="px-6 py-3">
                  @php $niv = $r->nivel; @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset
                    {{ $niv === 'Inicial' ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20' :
                       ($niv === 'Intermedio' ? 'bg-amber-50 text-amber-700 ring-amber-600/20' :
                       'bg-red-50 text-red-700 ring-red-600/20') }}">
                    {{ $r->nivel }}
                  </span>
                </td>

                <td class="px-6 py-3 text-sm text-gray-700">{{ $r->tipo }}</td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $r->duracion_min }}</td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $r->entrenador ?? '—' }}</td>

                {{-- 🔒 Acciones solo visibles para admin --}}
                @auth
                  @if(auth()->user()->rol === 'admin')
                    <td class="px-6 py-3 text-right">
                      <div class="flex justify-end gap-3">
                        <a href="{{ route('rutinas.edit', $r) }}" class="text-sm text-indigo-600 hover:text-indigo-700">
                          Editar
                        </a>
                        <form action="{{ route('rutinas.destroy', $r) }}" method="POST"
                              onsubmit="return confirm('¿Eliminar la rutina \"{{ $r->nombre }}\"?')">
                          @csrf @method('DELETE')
                          <button class="text-sm text-red-600 hover:text-red-700">Eliminar</button>
                        </form>
                      </div>
                    </td>
                  @endif
                @endauth
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <div class="px-6 py-4 border-t border-gray-100">
        {{ $rutinas->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
