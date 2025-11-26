<?php

namespace App\Http\Controllers;

use App\Models\Pago;
use App\Models\Miembro;
use Illuminate\Http\Request;

// NUEVO: Requests + Service (Strategy)
use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Services\PaymentService;

class PagoController extends Controller
{
    public function index(Request $request)
    {
        $miembros = Miembro::orderBy('nombre')->get();

        $pagos = Pago::with(['miembro.membresia'])
            ->when($request->miembro_id, fn($q) => $q->where('miembro_id', $request->miembro_id))
            ->orderByDesc('fecha')
            ->orderByDesc('id')
            ->paginate(10);

        return view('pagos.index', compact('pagos','miembros'));
    }

    public function create()
    {
        $miembros = Miembro::with('membresia:id,nombre,precio')->orderBy('nombre')->get();
        // Toma los métodos desde el config de strategies
        $metodos = array_keys(config('payment_methods.map', []));
        return view('pagos.create', compact('miembros','metodos'));
    }

    public function store(StorePagoRequest $request, PaymentService $service)
    {
        // Valida según Strategy + crea usando el servicio
        $service->create($request->validated());
        return redirect()->route('admin.pagos.index')->with('ok', 'Pago registrado.');
    }

    public function edit(Pago $pago)
    {
        $miembros = Miembro::with('membresia:id,nombre,precio')->orderBy('nombre')->get();
        $metodos = array_keys(config('payment_methods.map', []));
        return view('pagos.edit', compact('pago','miembros','metodos'));
    }

    public function update(UpdatePagoRequest $request, Pago $pago, PaymentService $service)
    {
        $service->update($pago, $request->validated());
        return redirect()->route('admin.pagos.index')->with('ok', 'Pago actualizado.');
    }

    public function destroy(Pago $pago)
    {
        $pago->delete();
        return redirect()->route('admin.pagos.index')->with('ok', 'Pago eliminado.');
    }
}
