@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl text-gray-900">Panel del Entrenador</h2>
@endsection

@section('content')
  <div class="max-w-6xl mx-auto space-y-4">
    <div class="bg-white rounded-xl p-6 shadow ring-1 ring-gray-200">
      <p class="text-gray-700">Accesos rápidos:</p>
      <div class="mt-3 flex gap-3">
        <a href="{{ route('asistencias.index') }}" class="btn btn-primary">Registrar asistencias</a>
        <a href="{{ route('asignaciones.create') }}" class="btn btn-soft">Asignar rutina</a>
        <a href="{{ route('clases-virtuales.index') }}" class="btn btn-soft">Clases virtuales</a>
      </div>
    </div>
  </div>
@endsection
