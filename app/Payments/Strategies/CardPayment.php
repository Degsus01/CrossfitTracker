<?php

namespace App\Payments\Strategies;

use App\Models\Pago;
use App\Payments\Contracts\PaymentStrategy;

class CardPayment implements PaymentStrategy
{
    public function label(): string { return 'Tarjeta'; }

    public function rules(): array
    {
        return [
            'referencia' => ['required','string','max:100'], // voucher/ID transacción
        ];
    }

    public function normalize(array $data): array
    {
        $data['metodo'] = 'Tarjeta';
        return $data;
    }

    public function afterCreate(array $data, Pago $pago): void {}
}
