@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-900">Mis asignaciones</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-4">
  @if($asignaciones->count() === 0)
    <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-200 text-center text-gray-500">
      No tienes rutinas asignadas aún.
    </div>
  @else
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rutina</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha asignación</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notas</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          @foreach($asignaciones as $r)
            <tr>
              <td class="px-6 py-3 text-sm text-gray-800">{{ $r->nombre }}</td>
              <td class="px-6 py-3 text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($r->pivot->fecha_asignacion)->format('d/m/Y') }}
              </td>
              <td class="px-6 py-3 text-sm text-gray-500">{{ $r->pivot->notas ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $asignaciones->links() }}
      </div>
    </div>
  @endif
</div>
@endsection
