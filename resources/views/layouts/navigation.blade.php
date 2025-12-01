<nav x-data="{ open:false }"
     class="sticky top-0 z-50 bg-gray-900/95 backdrop-blur supports-[backdrop-filter]:bg-gray-900/70 border-b border-gray-800 shadow-sm">

  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex h-16 items-center justify-between">

      {{-- Marca --}}
      <div class="flex items-center gap-6">
        <a href="{{ route('home') }}" class="flex items-center gap-3">
          <img src="{{ asset('images/logo.png') }}" alt="Crossfit Tracker" class="h-9 w-9 rounded-full">
          <span class="hidden sm:inline font-semibold text-gray-100">Crossfit Tracker</span>
        </a>

        {{-- Links Desktop --}}
        <div class="hidden sm:flex items-center gap-5">

          {{-- Obtener rol real --}}
          @auth
            @php
              $rol = strtolower((string) (auth()->user()->role->slug ?? ''));
            @endphp
          @endauth

          <a href="{{ route('home') }}"
             class="text-sm font-medium transition {{ request()->routeIs('home') ? 'text-white' : 'text-gray-300 hover:text-white' }}">
            Inicio
          </a>

          {{-- Miembros: disponible para admin e invitado --}}
          @auth
            @if(in_array($rol, ['admin','invitado']))
              <a href="{{ route('miembros.index') }}"
                 class="text-sm font-medium transition {{ request()->is('miembros*') ? 'text-white' : 'text-gray-300 hover:text-white' }}">
                Miembros
              </a>
            @endif
          @endauth

          {{-- Asistencias: admin + entrenador --}}
          @auth
            @if(in_array($rol, ['admin','entrenador']))
              <a href="{{ route('asistencias.index') }}"
                 class="text-sm font-medium transition {{ request()->is('asistencias*') ? 'text-white':'text-gray-300 hover:text-white' }}">
                Asistencias
              </a>
            @endif
          @endauth

          {{-- Rutinas (lectura común) --}}
          <a href="{{ route('rutinas.index') }}"
             class="text-sm font-medium transition {{ request()->is('rutinas*') ? 'text-white':'text-gray-300 hover:text-white' }}">
            Rutinas
          </a>

          {{-- Pagos --}}
          @auth
            @if($rol === 'admin')
              <a href="{{ route('admin.pagos.index') }}"
                 class="text-sm font-medium transition {{ request()->is('admin/pagos*') ? 'text-white':'text-gray-300 hover:text-white' }}">
                Pagos
              </a>
            @elseif($rol === 'miembro')
              <a href="{{ route('mi.pagos') }}"
                 class="text-sm font-medium transition {{ request()->is('mi/pagos*') ? 'text-white':'text-gray-300 hover:text-white' }}">
                Mis pagos
              </a>
            @endif
          @endauth

          {{-- Clases virtuales --}}
          @auth
            @if(in_array($rol,['admin','entrenador']))
              <a href="{{ route('clases-virtuales.index') }}"
                 class="text-sm font-medium transition {{ request()->is('clases-virtuales*') ? 'text-white':'text-gray-300 hover:text-white' }}">
                Clases virtuales
              </a>
            @elseif($rol === 'miembro')
              <a href="{{ route('mi.clases') }}"
                 class="text-sm font-medium transition {{ request()->is('mi/clases-virtuales*') ? 'text-white':'text-gray-300 hover:text-white' }}">
                Clases virtuales
              </a>
            @endif
          @endauth

          {{-- Asignaciones (lectura para todos los roles excepto invitado no autenticado) --}}
          @auth
            <a href="{{ route('asignaciones.index') }}"
               class="text-sm font-medium transition {{ request()->is('asignaciones*') ? 'text-white':'text-gray-300 hover:text-white' }}">
              Asignaciones
            </a>
          @endauth

          {{-- Panel según rol --}}
          @auth
            @if($rol === 'admin')
              <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                Panel Admin
              </a>
            @elseif($rol === 'entrenador')
              <a href="{{ route('entrenador.dashboard') }}" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                Panel Entrenador
              </a>
            @elseif($rol === 'miembro')
              <a href="{{ route('mi.dashboard') }}" class="text-sm font-semibold text-indigo-400 hover:text-indigo-300">
                Mi Panel
              </a>
            @endif
          @endauth

        </div>
      </div>

      {{-- Usuario (login/logout) --}}
      <div class="hidden sm:flex sm:items-center justify-end">
        <x-dropdown align="right" width="48">
          <x-slot name="trigger">
            <button class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md text-gray-200 hover:text-white">
              {{ auth()->check() ? auth()->user()->name : 'Invitado' }}
              <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 011.08 1.04l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.27z"/></svg>
            </button>
          </x-slot>

          <x-slot name="content">
            @auth
              <x-dropdown-link :href="route('profile.edit')">Perfil</x-dropdown-link>

              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                  Cerrar sesión
                </x-dropdown-link>
              </form>
            @else
              <x-dropdown-link :href="route('login')">Iniciar sesión</x-dropdown-link>
              @if(Route::has('register'))
                <x-dropdown-link :href="route('register')">Registrarme</x-dropdown-link>
              @endif
            @endauth
          </x-slot>

        </x-dropdown>
      </div>

      {{-- Hamburguesa mobile --}}
      <div class="md:hidden">
        <button @click="open=!open" class="p-2 text-gray-300 hover:text-white hover:bg-gray-800 rounded-md">
          <svg class="h-6 w-6" fill="none" stroke="currentColor">
            <path :class="{'hidden':open,'inline-flex':!open}" stroke-linecap="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            <path :class="{'hidden':!open,'inline-flex':open}" stroke-linecap="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

    </div>
  </div>
</nav>
