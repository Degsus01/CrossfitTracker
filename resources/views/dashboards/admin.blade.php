@extends('layouts.app')

@section('content')

{{-- 🔥 BANNER SUPERIOR --}}
<div class="relative w-full h-64 md:h-80 lg:h-96 overflow-hidden">
    <img src="{{ asset('images/gym-banner.jpg') }}" 
         class="w-full h-full object-cover brightness-75" alt="Banner Gimnasio">

    <div class="absolute inset-0 flex flex-col justify-center px-10 md:px-20 text-white">
        <h1 class="text-4xl md:text-5xl font-extrabold drop-shadow-lg">
            Panel de Administrador
        </h1>
        <p class="mt-3 text-lg md:text-xl text-gray-200 drop-shadow">
            Bienvenida, {{ auth()->user()->name }}. Gestiona todo el sistema desde aquí.
        </p>
    </div>
</div>

{{-- 🔥 CARDS RESUMEN --}}
<div class="max-w-7xl mx-auto mt-10 px-6 grid md:grid-cols-3 gap-6">

    {{-- Miembros activos --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <p class="text-gray-600 text-sm font-medium">Miembros activos</p>
        <p class="mt-3 text-4xl font-bold text-green-600">{{ $miembrosActivos }}</p>
    </div>

    {{-- Membresía vencida --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <p class="text-gray-600 text-sm font-medium">Membresías vencidas</p>
        <p class="mt-3 text-4xl font-bold text-red-500">{{ $membresiasVencidas }}</p>
    </div>

    {{-- Total miembros --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <p class="text-gray-600 text-sm font-medium">Total de miembros</p>
        <p class="mt-3 text-4xl font-bold text-indigo-600">{{ $totalMiembros }}</p>
    </div>

</div>

{{-- 🔥 ENLACES A REPORTES --}}
<div class="max-w-6xl mx-auto px-6 mt-10">
    <h2 class="text-xl font-semibold mb-4">Reportes</h2>

    <div class="grid md:grid-cols-2 gap-4">

        {{-- CARD + BOTÓN REPORTE DE ASISTENCIAS --}}
        <div>
            <a href="{{ route('admin.reportes.asistencias') }}"
               class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center gap-4">
                <svg class="w-10 h-10 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M3 3h12M3 7h9m-9 4h6m4 5h8m-4-4v8"></path>
                </svg>
                <div>
                    <p class="text-lg font-semibold">Reporte de asistencias</p>
                    <p class="text-gray-500 text-sm">Resumen completo de asistencia por miembro.</p>
                </div>
            </a>

            <form action="{{ route('admin.reportes.asistencias.generar') }}" method="POST" class="mt-2">
                @csrf
                <button class="bg-indigo-600 text-white px-3 py-1 rounded-lg">
                    Generar reporte de asistencias
                </button>
            </form>
        </div>

        {{-- CARD + BOTÓN REPORTE FINANCIERO --}}
        <div>
            <a href="{{ route('admin.reportes.finanzas') }}"
               class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 hover:shadow-md transition flex items-center gap-4">
                <svg class="w-10 h-10 text-green-500" fill="none" stroke="currentColor" stroke-width="1.5"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" d="M12 8v8m-4-4h8m1 9H7a3 3 0 01-3-3V4a3 3 0 013-3h10a3 3 0 013 3v14a3 3 0 01-3 3z"></path>
                </svg>
                <div>
                    <p class="text-lg font-semibold">Reporte financiero</p>
                    <p class="text-gray-500 text-sm">Ingresos, pagos y resúmenes económicos.</p>
                </div>
            </a>

            <form action="{{ route('admin.reportes.finanzas.generar') }}" method="POST" class="mt-2">
                @csrf
                <button class="bg-green-600 text-white px-3 py-1 rounded-lg">
                    Generar reporte financiero
                </button>
            </form>
        </div>

    </div>
</div>

@endsection
