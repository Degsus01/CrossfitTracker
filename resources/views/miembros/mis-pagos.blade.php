@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-900">Mis pagos</h2>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-4">
  @if($pagos->count() === 0)
    <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-200 text-center text-gray-500">
      Aún no tienes pagos registrados.
    </div>
  @else
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 overflow-hidden">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Método</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Referencia</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100 bg-white">
          @foreach($pagos as $p)
            <tr>
              <td class="px-6 py-3 text-sm text-gray-700">
                {{ \Carbon\Carbon::parse($p->fecha)->format('d/m/Y') }}
              </td>
              <td class="px-6 py-3 text-sm font-semibold text-gray-900">
                {{ number_format($p->monto, 0, ',', '.') }}
              </td>
              <td class="px-6 py-3 text-sm text-gray-700">{{ $p->metodo }}</td>
              <td class="px-6 py-3 text-sm text-gray-500">{{ $p->referencia ?? '—' }}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
      <div class="px-6 py-4 border-t border-gray-100">
        {{ $pagos->links() }}
      </div>
    </div>
  @endif
</div>
@endsection
