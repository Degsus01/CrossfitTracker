@extends('layouts.app')

@section('header')
  <h2 class="font-semibold text-xl leading-tight text-gray-900">Nuevo pago</h2>
@endsection

@section('content')
<div class="max-w-3xl mx-auto"
     x-data='{
        miembros: @json($miembros->map(fn($m)=>[
            "id"=>$m->id,
            "nombre"=>$m->nombre." ".$m->apellido,
            "precio"=>$m->membresia->precio ?? null
        ])),
        setMonto(evt){
          const id = +evt.target.value;
          const m = this.miembros.find(x=>x.id===id);
          if(m && m.precio){ this.$refs.monto.value = m.precio; }
        }
     }'>

  @if ($errors->any())
    <div class="mb-4 rounded-lg bg-red-50 p-4 ring-1 ring-red-200">
      <p class="font-semibold text-sm">Corrige los errores:</p>
      <ul class="list-disc pl-5 mt-1 text-sm space-y-1">
        @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
      </ul>
    </div>
  @endif

  <div class="bg-white rounded-2xl shadow-sm ring-1 ring-gray-200">
    <div class="px-6 py-5 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-900">Datos del pago</h3>
    </div>

    <form action="{{ route('admin.pagos.store') }}" method="POST" class="px-6 pb-6 pt-5">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Miembro --}}
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Miembro *</label>
          <select name="miembro_id" required @change="setMonto"
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="" disabled {{ old('miembro_id') ? '' : 'selected' }}>Seleccione…</option>
            @foreach($miembros as $m)
              <option value="{{ $m->id }}" @selected(old('miembro_id')==$m->id)>
                {{ $m->nombre }} {{ $m->apellido }}
                @if($m->membresia?->nombre) — {{ $m->membresia->nombre }} @endif
              </option>
            @endforeach
          </select>
          @error('miembro_id') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Fecha --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Fecha *</label>
          <input type="date" name="fecha" value="{{ old('fecha', now()->format('Y-m-d')) }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
          @error('fecha') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Monto --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Monto (COP) *</label>
          <input x-ref="monto" type="number" min="0" step="1" name="monto" value="{{ old('monto') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500" required>
          @error('monto') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
          <p class="mt-1 text-xs text-gray-400">Al elegir el miembro intento precargar el precio de su membresía.</p>
        </div>

        {{-- Método --}}
        <div>
          <label class="block text-sm font-medium text-gray-700">Método *</label>
          <select name="metodo" required
                  class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
            <option value="" {{ old('metodo') ? '' : 'selected' }}>Seleccione…</option>
            @foreach($metodos as $m)
              <option value="{{ $m }}" @selected(old('metodo')===$m)>{{ $m }}</option>
            @endforeach
          </select>
          @error('metodo') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Referencia --}}
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Referencia (opcional)</label>
          <input type="text" name="referencia" value="{{ old('referencia') }}"
                 class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                 placeholder="#transacción, comprobante, etc.">
          @error('referencia') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>

        {{-- Notas --}}
        <div class="md:col-span-2">
          <label class="block text-sm font-medium text-gray-700">Notas</label>
          <textarea name="notas" rows="3"
                    class="mt-1 block w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Comentarios del pago, periodos cubiertos, etc.">{{ old('notas') }}</textarea>
          @error('notas') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
        </div>
      </div>

      <div class="mt-7 flex items-center gap-3">
        <button class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-indigo-700">
          Guardar
        </button>
        <a href="{{ route('admin.pagos.index') }}" class="text-sm font-semibold text-gray-700 hover:text-gray-900">Cancelar</a>
      </div>
    </form>
  </div>
</div>
@endsection
