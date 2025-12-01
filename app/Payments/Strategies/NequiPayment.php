<?php

namespace App\Payments\Strategies;

use App\Models\Pago;
use App\Payments\Contracts\PaymentStrategy;

class NequiPayment implements PaymentStrategy
{
    public function label(): string { return 'Nequi'; }

    public function rules(): array
    {
        return [
            'referencia' => ['required','string','max:100'],
        ];
    }

    public function normalize(array $data): array
    {
        $data['metodo'] = 'Nequi';
        return $data;
    }

    public function afterCreate(array $data, Pago $pago): void {}
}
