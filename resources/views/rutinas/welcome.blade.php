@extends('layouts.app')

@section('hero')
<section
  aria-label="Banner principal"
  class="relative flex items-center justify-center text-white overflow-hidden"
  style="min-height: calc(100vh - 4rem);"
>
  {{-- Fondo con overlay --}}
  <div
    class="absolute inset-0 bg-cover bg-center"
    style="background-image: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('{{ asset('images/hero-gym.png') }}');">
  </div>

  {{-- Contenido principal --}}
  <div class="relative z-10 text-center px-6 sm:px-8 max-w-4xl mx-auto">
    <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-4 leading-tight">
      Entrena Duro. Registra tu Progreso.
    </h1>

    <p class="text-lg sm:text-xl md:text-2xl text-gray-200 mb-8">
      Optimiza tus rutinas, lleva control de tus resultados y alcanza tus metas con
      <span class="font-semibold text-white">Crossfit Tracker</span> 💪
    </p>

    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      {{-- Asignaciones: visible para usuarios logueados (admin e invitado en tu setup) --}}
      <a href="{{ route('asignaciones.index') }}"
         class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-semibold bg-indigo-600 hover:bg-indigo-500 shadow-lg transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-400">
        Ver asignaciones
      </a>

      {{-- Clases virtuales: admin o entrenador, resolviendo el name correcto por rol --}}
      @auth
        @php
          $cvName = match (auth()->user()->rol) {
            'admin'      => 'admin.clases-virtuales.index',
            'entrenador' => 'entrenador.clases-virtuales.index',
            default      => null,
          };
        @endphp

        @if($cvName && Route::has($cvName))
          <a href="{{ route($cvName) }}"
             class="inline-flex items-center justify-center rounded-lg px-8 py-3 text-lg font-semibold border border-white/80 hover:bg-white/10 backdrop-blur-md transition-all duration-300">
            Clases virtuales
          </a>
        @endif
      @endauth
    </div>
  </div>
</section>
@endsection

@section('content')
  <div class="max-w-7xl mx-auto px-6 sm:px-8 py-10 text-center">
    <h4 class="text-gray-500 text-lg">
      Selecciona una opción del menú superior para empezar
    </h4>
  </div>
@endsection
