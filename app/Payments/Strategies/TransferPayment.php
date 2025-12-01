<?php

namespace App\Payments\Strategies;

use App\Models\Pago;
use App\Payments\Contracts\PaymentStrategy;

class TransferPayment implements PaymentStrategy
{
    public function label(): string { return 'Transferencia'; }

    public function rules(): array
    {
        return [
            'referencia' => ['required','string','max:100'],
        ];
    }

    public function normalize(array $data): array
    {
        $data['metodo'] = 'Transferencia';
        return $data;
    }

    public function afterCreate(array $data, Pago $pago): void
    {
        // opcional: notificar recepción, adjuntar comprobante, etc.
    }
}
