@extends('layouts.app')

@section('hero')
  <section class="relative">
    <img src="{{ asset('images/banner-miembros.png') }}"
     alt="Banner Miembros"
     class="w-full h-[450px] object-center object-cover brightness-75">

    <div class="absolute inset-0 flex items-center justify-center">
      <div class="text-center text-white">
        <h1 class="text-8xl font-extrabold tracking-tight drop-shadow-lg">
          Nuestros Miembros
        </h1>
        <p class="text-lg mt-6 text-gray-300">
          Entrena, progresa y supera tus límites cada día.
        </p>
      </div>
    </div>
  </section>
@endsection

@section('content')
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

  {{-- Encabezado --}}
  <header class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-800 mb-2">
      Gestión de Miembros
    </h2>
    <p class="text-gray-600">
      Administra registros, planes y progreso de cada miembro del gimnasio.
    </p>
  </header>

  {{-- Botones principales --}}
  <div class="flex flex-wrap items-center gap-3 mb-4">
    @auth
      @if(auth()->user()->rol === 'admin')
        @auth
          @if(optional(auth()->user()->role)->slug === 'admin')
              <a href="{{ route('admin.miembros.create') }}" class="btn btn-primary">
                  + Nuevo Miembro
              </a>
          @endif
        @endauth
      @endif
    @endauth

    {{-- Este botón lo ven todos --}}
    <a href="{{ route('miembros.index') }}" class="btn btn-soft">Ver Todos</a>
  </div>

  {{-- Tabla --}}
  <div class="bg-white shadow-sm rounded-xl border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="th">ID</th>
          <th class="th">Nombre</th>
          <th class="th">Correo</th>
          <th class="th">Teléfono</th>
          <th class="th">Membresía</th>
          <th class="th">Rutinas</th>
          <th class="th">Acciones</th>
        </tr>
      </thead>

      <tbody class="divide-y divide-gray-100">
        @forelse ($miembros as $m)
          <tr class="hover:bg-gray-50">
            <td class="td">{{ $m->id }}</td>
            <td class="td font-medium">{{ $m->nombre }}</td>
            <td class="td">{{ $m->correo }}</td>
            <td class="td">{{ $m->telefono ?? '—' }}</td>
            <td class="td">{{ $m->membresia?->nombre ?? '—' }}</td>
            <td class="td">
              <a href="{{ route('rutinas.index', ['miembro' => $m->id]) }}" class="link">Ver</a>
            </td>

            {{-- Botones admin --}}
            <td class="td">
              @auth
                @if(auth()->user()->rol === 'admin')
                  <div class="flex items-center gap-2">

                    {{-- CORREGIDO --}}
                    <a href="{{ route('admin.miembros.edit', $m) }}" class="btn-ghost">Editar</a>

                    {{-- CORREGIDO --}}
                    <form method="POST"
                          action="{{ route('admin.miembros.destroy', $m) }}"
                          onsubmit="return confirm('¿Eliminar miembro?')">
                      @csrf
                      @method('DELETE')
                      <button class="btn-danger-ghost">Eliminar</button>
                    </form>

                  </div>
                @endif
              @endauth
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
              No hay miembros registrados.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>
@endsection
