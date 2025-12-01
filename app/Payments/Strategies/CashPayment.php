<?php

namespace App\Payments\Strategies;

use App\Models\Pago;
use App\Payments\Contracts\PaymentStrategy;

class CashPayment implements PaymentStrategy
{
    public function label(): string { return 'Efectivo'; }

    public function rules(): array
    {
        return [
            'referencia' => ['nullable','string','max:100'],
        ];
    }

    public function normalize(array $data): array
    {
        $data['metodo'] = 'Efectivo';
        // Puedes forzar redondeo de monto, etc:
        $data['monto'] = (int) $data['monto'];
        return $data;
    }

    public function afterCreate(array $data, Pago $pago): void
    {
        // opcional: imprimir recibo, event(), log, etc.
    }
}
