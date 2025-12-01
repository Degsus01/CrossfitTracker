@extends('layouts.app')

@section('header')
  <div class="flex items-center justify-between">
    <h2 class="font-semibold text-xl leading-tight text-gray-900">Pagos</h2>

    {{-- 🔒 Solo admin puede crear pagos --}}
@auth
  @if(auth()->user()->rol === 'admin')
    <a href="{{ route('admin.pagos.create') }}"
       class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-700">
      <svg class="h-4 w-4 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M10 5a1 1 0 011 1v3h3a1 1 0 010 2h-3v3a1 1 0 01-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"/></svg>
      Nuevo Pago
    </a>
  @endif
@endauth

  </div>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
  @if(session('ok'))
    <div class="rounded-lg bg-green-50 p-4 ring-1 ring-green-200 text-green-800">{{ session('ok') }}</div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    <div class="px-6 py-5 border-b border-gray-100 flex items-center justify-between">
      <h3 class="text-lg font-semibold text-gray-900">Listado</h3>

      {{-- Filtro visible para todos --}}
      <form method="GET" class="flex items-center gap-2">
        <select name="miembro_id" onchange="this.form.submit()"
                class="rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
          <option value="">-- Filtrar por miembro --</option>
          @foreach($miembros as $m)
            <option value="{{ $m->id }}" @selected(request('miembro_id')==$m->id)>
              {{ $m->nombre }} {{ $m->apellido }}
            </option>
          @endforeach
        </select>
        @if(request('miembro_id'))
          <a href="{{ route('admin.pagos.index') }}" class="text-sm text-gray-500 hover:text-gray-700">Limpiar</a>
        @endif
      </form>
    </div>

    @if($pagos->count() === 0)
      <div class="px-6 py-10 text-center text-gray-500">Sin registros</div>
    @else
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Miembro</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referencia</th>

              {{-- 🔒 Columna Acciones solo si es admin --}}
              @auth
                @if(auth()->user()->rol === 'admin')
                  <th class="px-6 py-3"></th>
                @endif
              @endauth
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($pagos as $p)
              <tr>
                <td class="px-6 py-3 text-sm text-gray-800">
                  {{ $p->miembro->nombre }} {{ $p->miembro->apellido }}
                  @if($p->miembro->membresia?->nombre)
                    <span class="ml-2 text-xs text-gray-400">({{ $p->miembro->membresia->nombre }})</span>
                  @endif
                </td>
                <td class="px-6 py-3 text-sm text-gray-600">{{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}</td>
                <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                  {{ number_format($p->monto, 0, ',', '.') }}
                </td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $p->metodo }}</td>
                <td class="px-6 py-3 text-sm text-gray-500">{{ $p->referencia ?? '—' }}</td>

                {{-- 🔒 Acciones (Editar/Eliminar) solo para admin --}}
                @auth
                  @if(auth()->user()->rol === 'admin')
                    <td class="px-6 py-3 text-right">
                      <div class="flex justify-end gap-3">
                        <a href="{{ route('admin.pagos.edit',$p) }}" class="text-sm text-indigo-600 hover:text-indigo-700">Editar</a>
                        <form action="{{ route('admin.pagos.destroy',$p) }}" method="POST" onsubmit="return confirm('¿Eliminar pago?')">
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
        {{ $pagos->withQueryString()->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
