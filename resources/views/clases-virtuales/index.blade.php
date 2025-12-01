@extends('layouts.app')

@section('header')
  <div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl leading-tight text-gray-900">Clases virtuales</h2>

    @auth
      @php
        // admin y entrenador pueden crear/editar clases
        $puedeGestionar = in_array(auth()->user()->rol, ['admin', 'entrenador']);
      @endphp

      @if($puedeGestionar)
        <a href="{{ route('admin.clases-virtuales.create') }}"
           class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
          <svg class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor">
            <path d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/>
          </svg>
          Nueva clase
        </a>
      @endif
    @endauth
  </div>
@endsection


@section('content')
<div class="max-w-7xl mx-auto space-y-4"
     x-data="{copiado:false, copy(txt){navigator.clipboard.writeText(txt).then(()=>{copiado=true; setTimeout(()=>copiado=false,1200)})}}">

  @if(session('ok'))
    <div class="rounded-lg bg-green-50 p-4 ring-1 ring-green-200 text-green-800">{{ session('ok') }}</div>
  @endif

  {{-- Filtros --}}
  <form method="GET" class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-4 grid grid-cols-1 md:grid-cols-4 gap-3">
    <input type="date" name="desde" value="{{ request('desde') }}"
           class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Desde">
    <input type="date" name="hasta" value="{{ request('hasta') }}"
           class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" placeholder="Hasta">
    <select name="plataforma" class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
      <option value="">Plataforma</option>
      @foreach(['Zoom','Meet','Teams','Jitsi'] as $p)
        <option value="{{ $p }}" @selected(request('plataforma')===$p)>{{ $p }}</option>
      @endforeach
    </select>
    <div class="flex gap-2">
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Buscar título/entrenador…"
             class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
      <button class="rounded-lg border px-3 text-sm hover:bg-gray-50">Filtrar</button>

      @if(request()->hasAny(['desde','hasta','plataforma','q']))
  <a href="{{ route('clases-virtuales.index') }}"
     class="rounded-lg border px-3 text-sm text-gray-600 hover:bg-gray-50">
    Limpiar
  </a>
@endif

    </div>
  </form>

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    @if($clases->count()===0)
      <div class="px-6 py-10 text-center text-gray-500">Sin registros</div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Título</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha/Hora</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Entrenador</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Plataforma</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enlace</th>
              <th class="px-6 py-3"></th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($clases as $c)
              @php
                $fecha = \Carbon\Carbon::parse($c->fecha);
                $past  = $fecha->isPast();
              @endphp
              <tr class="{{ $past ? 'opacity-80' : '' }}">
                <td class="px-6 py-3 text-sm font-medium text-gray-900">{{ $c->titulo }}</td>
                <td class="px-6 py-3 text-sm text-gray-700">
                  {{ $fecha->format('d/m/Y') }}
                  @if($c->hora)
                    <span class="text-gray-400">· {{ \Carbon\Carbon::parse($c->hora)->format('H:i') }}</span>
                  @endif
                  @if($past)
                    <span class="ml-2 inline-flex items-center rounded-full bg-gray-100 px-2 py-0.5 text-[11px] font-medium text-gray-600 ring-1 ring-gray-200">Finalizada</span>
                  @else
                    <span class="ml-2 inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[11px] font-medium text-emerald-700 ring-1 ring-emerald-600/20">Próxima</span>
                  @endif
                </td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $c->entrenador->nombre ?? '—' }}</td>
                <td class="px-6 py-3">
                  @php $plat=$c->plataforma; @endphp
                  <span class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium ring-1 ring-inset
                    {{ $plat==='Zoom'?'bg-sky-50 text-sky-700 ring-sky-600/20' :
                       ($plat==='Meet'?'bg-green-50 text-green-700 ring-green-600/20' :
                       ($plat==='Teams'?'bg-indigo-50 text-indigo-700 ring-indigo-600/20':'bg-violet-50 text-violet-700 ring-violet-600/20')) }}">
                    {{ $plat ?? '—' }}
                  </span>
                </td>
                <td class="px-6 py-3 text-sm text-gray-700">
                  @if($c->enlace)
                    <div class="flex items-center gap-2">
                      <a href="{{ $c->enlace }}" target="_blank" class="text-indigo-600 hover:text-indigo-700">Unirse</a>
                      <button type="button" class="text-gray-500 hover:text-gray-700" @click="copy('{{ $c->enlace }}')">Copiar</button>
                    </div>
                  @else
                    —
                  @endif
                </td>
                <td class="px-6 py-3 text-right">
  @auth
    @php
      $puedeGestionar = in_array(auth()->user()->rol, ['admin', 'entrenador']);
    @endphp

    @if($puedeGestionar)
      <div class="flex justify-end gap-3">
        <a href="{{ route('admin.clases-virtuales.edit', $c) }}"
           class="text-sm text-indigo-600 hover:text-indigo-700">Editar</a>

        <form action="{{ route('admin.clases-virtuales.destroy', $c) }}"
              method="POST"
              onsubmit="return confirm('¿Eliminar clase?')">
          @csrf
          @method('DELETE')
          <button class="text-sm text-red-600 hover:text-red-700">Eliminar</button>
        </form>
      </div>
    @endif
  @endauth
</td>

              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $clases->withQueryString()->links() }}
      </div>
    @endif
  </div>

  <div x-show="copiado" class="fixed bottom-6 right-6 rounded-lg bg-black/80 text-white text-sm px-3 py-2">
    ¡Enlace copiado!
  </div>
</div>
@endsection
