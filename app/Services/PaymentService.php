<?php

namespace App\Services;

use App\Models\Pago;
use App\Models\Miembro;
use App\Payments\PaymentStrategyFactory;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PaymentService
{
    public function create(array $data): Pago
    {
        return DB::transaction(function () use ($data) {
            $strategy = PaymentStrategyFactory::resolve($data['metodo']);
            $payload  = $strategy->normalize($data);

            // 1) Crear el pago normal (tu lógica)
            $pago = Pago::create($payload);

            // 2) Hook propio de la strategy (tu lógica)
            $strategy->afterCreate($payload, $pago);

            // 3) 🔥 Actualizar membresía del miembro según el pago
            $this->syncMembershipFromPayment($pago);

            return $pago;
        });
    }

    public function update(Pago $pago, array $data): Pago
    {
        return DB::transaction(function () use ($pago, $data) {
            $strategy = PaymentStrategyFactory::resolve($data['metodo']);
            $payload  = $strategy->normalize($data);

            // 1) Actualizar pago
            $pago->update($payload);

            // 2) Hook de strategy
            $strategy->afterCreate($payload, $pago); // si quieres, luego lo cambias a afterUpdate

            // 3) 🔥 Volver a recalcular vencimiento con la info nueva
            $this->syncMembershipFromPayment($pago);

            return $pago;
        });
    }

    /**
     * 🔥 Sincroniza la fecha de vencimiento de la membresía
     * a partir del pago realizado.
     *
     * Regla básica:
     *  - Toma la membresía del miembro (miembros.membresia_id → membresias.duracion_dias)
     *  - Usa la fecha del pago como inicio
     *  - membresia_expira_at = fecha_pago + duracion_dias
     */
    protected function syncMembershipFromPayment(Pago $pago): void
    {
        // Nos aseguramos de tener miembro + membresía cargados
        $miembro = $pago->miembro()->with('membresia')->first();

        if (!$miembro || !$miembro->membresia) {
            return; // no hay nada que actualizar
        }

        // Fecha base: la fecha del pago o hoy si viniera null
        $fechaBase = $pago->fecha ? Carbon::parse($pago->fecha) : Carbon::today();

        $duracion = (int) ($miembro->membresia->duracion_dias ?? 0);
        if ($duracion <= 0) {
            return; // membresía sin duración definida, no tocamos nada
        }

        // Nuevo vencimiento
        $nuevoVence = $fechaBase->clone()->addDays($duracion);

        // Actualizamos solo el campo de vencimiento
        $miembro->update([
            'membresia_expira_at' => $nuevoVence,
        ]);
    }
}
