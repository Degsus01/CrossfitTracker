@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl leading-tight text-gray-900">
    Reporte financiero
  </h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-4">
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-3">Resumen</h3>
    <dl class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
      <div class="p-4 rounded-xl bg-indigo-50">
        <dt class="text-gray-500">Total recaudado</dt>
        <dd class="text-2xl font-bold text-indigo-700">
          {{ number_format($resumen['total'],0,',','.') }}
        </dd>
      </div>
      <div class="p-4 rounded-xl bg-emerald-50">
        <dt class="text-gray-500">Hoy</dt>
        <dd class="text-2xl font-bold text-emerald-700">
          {{ number_format($resumen['hoy'],0,',','.') }}
        </dd>
      </div>
      <div class="p-4 rounded-xl bg-amber-50">
        <dt class="text-gray-500">Últimos 7 días</dt>
        <dd class="text-2xl font-bold text-amber-700">
          {{ number_format($resumen['ultimos7'],0,',','.') }}
        </dd>
      </div>
    </dl>
  </div>

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-3">Ingresos por mes</h3>
    <table class="min-w-full text-sm">
      <thead>
        <tr class="border-b">
          <th class="text-left py-2">Mes</th>
          <th class="text-left py-2">Total</th>
        </tr>
      </thead>
      <tbody>
        @forelse($porMes as $fila)
          <tr class="border-b last:border-0">
            <td class="py-2">{{ $fila->mes }}</td>
            <td class="py-2">{{ number_format($fila->total,0,',','.') }}</td>
          </tr>
        @empty
          <tr><td colspan="2" class="py-4 text-gray-500">Sin datos.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
