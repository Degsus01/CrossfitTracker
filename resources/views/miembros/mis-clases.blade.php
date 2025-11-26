@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-900">Clases virtuales</h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
    @if($clases->count() === 0)
      <div class="px-6 py-10 text-center text-gray-500">No hay clases programadas.</div>
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
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100 bg-white">
            @foreach($clases as $c)
              @php
                $fecha = \Carbon\Carbon::parse($c->fecha);
              @endphp
              <tr>
                <td class="px-6 py-3 text-sm text-gray-900">{{ $c->titulo }}</td>
                <td class="px-6 py-3 text-sm text-gray-700">
                  {{ $fecha->format('d/m/Y') }}
                  @if($c->hora)
                    <span class="text-gray-400">· {{ \Carbon\Carbon::parse($c->hora)->format('H:i') }}</span>
                  @endif
                </td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $c->entrenador->nombre ?? '—' }}</td>
                <td class="px-6 py-3 text-sm text-gray-700">{{ $c->plataforma }}</td>
                <td class="px-6 py-3 text-sm text-indigo-600">
                  @if($c->enlace)
                    <a href="{{ $c->enlace }}" target="_blank" class="hover:text-indigo-700">Unirse</a>
                  @else
                    —
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $clases->links() }}
      </div>
    @endif
  </div>
</div>
@endsection
