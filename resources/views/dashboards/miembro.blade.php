@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-900">Mi Panel</h2>
@endsection

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

  {{-- Resumen arriba --}}
  @isset($miembro)
    <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-6 grid gap-4 md:grid-cols-3">
      <div>
        <p class="text-sm text-gray-500">Nombre</p>
        <p class="text-lg font-semibold text-gray-900">
          {{ $miembro->nombre }} {{ $miembro->apellido }}
        </p>
        <p class="text-sm text-gray-500 mt-2">
  Estado membresía:
  <span class="font-semibold">
    {{ ucfirst($miembro->membresia_estado) }}
  </span>

  @if(!is_null($miembro->membresia_dias_restantes))
    ({{ $miembro->membresia_dias_restantes }} días restantes)
  @endif
</p>

        @if($miembro->membresia)
          <p class="mt-1 text-sm text-gray-500">
            Membresía: <span class="font-medium">{{ $miembro->membresia->nombre }}</span>
          </p>
        @endif
      </div>

      <div>
        <p class="text-sm text-gray-500">Total asistencias</p>
        <p class="text-2xl font-bold text-indigo-600">
          {{ $resumen['total_asistencias'] ?? 0 }}
        </p>
      </div>

      <div>
        <p class="text-sm text-gray-500">Último pago</p>
        @if($resumen['ultimo_pago'] ?? null)
          <p class="text-sm text-gray-800">
            {{ \Carbon\Carbon::parse($resumen['ultimo_pago']->fecha)->format('d/m/Y') }} ·
            $ {{ number_format($resumen['ultimo_pago']->monto, 0, ',', '.') }}
          </p>
        @else
          <p class="text-sm text-gray-400">Sin pagos registrados aún.</p>
        @endif
      </div>
    </div>
  @endisset

  {{-- Tarjetas de accesos rápidos --}}
  <div class="grid gap-4 sm:grid-cols-3">
    <a href="{{ route('mi.asignaciones') }}" class="card-link">
      <div class="p-4 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 h-full flex flex-col justify-between">
        <div>
          <h3 class="font-semibold text-gray-900">Mis asignaciones</h3>
          <p class="text-sm text-gray-500 mt-1">Ver las rutinas que tu entrenador te ha asignado.</p>
        </div>
        <span class="mt-3 text-sm text-indigo-600 font-semibold">Ver detalles →</span>
      </div>
    </a>

    <a href="{{ route('mi.pagos') }}" class="card-link">
      <div class="p-4 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 h-full flex flex-col justify-between">
        <div>
          <h3 class="font-semibold text-gray-900">Mis pagos</h3>
          <p class="text-sm text-gray-500 mt-1">Historial de pagos y estado de tu membresía.</p>
        </div>
        <span class="mt-3 text-sm text-indigo-600 font-semibold">Ver pagos →</span>
      </div>
    </a>

    <a href="{{ route('mi.clases') }}" class="card-link">
      <div class="p-4 rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 h-full flex flex-col justify-between">
        <div>
          <h3 class="font-semibold text-gray-900">Clases virtuales</h3>
          <p class="text-sm text-gray-500 mt-1">Ver enlaces y horarios de clases online.</p>
        </div>
        <span class="mt-3 text-sm text-indigo-600 font-semibold">Ver clases →</span>
      </div>
    </a>
  </div>
</div>

{{-- PROGRESO Y GRÁFICAS --}}
<div class="mt-8 grid grid-cols-1 lg:grid-cols-2 gap-6">
  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
    <h3 class="text-sm font-semibold text-gray-900 mb-2">Asistencias por mes</h3>
    <canvas id="chartAsistencias" height="140"></canvas>
  </div>

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200 p-5">
    <h3 class="text-sm font-semibold text-gray-900 mb-2">Pagos por mes (COP)</h3>
    <canvas id="chartPagos" height="140"></canvas>
  </div>
</div>

@push('scripts')
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script>
    const labelsAsis = @json($asistenciasPorMes->pluck('mes'));
    const dataAsis   = @json($asistenciasPorMes->pluck('total'));

    const labelsPagos = @json($pagosPorMes->pluck('mes'));
    const dataPagos   = @json($pagosPorMes->pluck('total'));

    // Gráfica de asistencias
    const ctxAsis = document.getElementById('chartAsistencias');
    if (ctxAsis) {
      new Chart(ctxAsis, {
        type: 'line',
        data: {
          labels: labelsAsis,
          datasets: [{
            label: 'Asistencias',
            data: dataAsis,
          }]
        }
      });
    }

    // Gráfica de pagos
    const ctxPagos = document.getElementById('chartPagos');
    if (ctxPagos) {
      new Chart(ctxPagos, {
        type: 'bar',
        data: {
          labels: labelsPagos,
          datasets: [{
            label: 'Pagos (COP)',
            data: dataPagos,
          }]
        }
      });
    }
  </script>
@endpush
@endsection

