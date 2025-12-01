<?php

namespace App\Payments\Strategies;

use App\Models\Pago;
use App\Payments\Contracts\PaymentStrategy;

class DaviplataPayment implements PaymentStrategy
{
    public function label(): string { return 'Daviplata'; }

    public function rules(): array
    {
        return [
            'referencia' => ['required','string','max:100'],
        ];
    }

    public function normalize(array $data): array
    {
        $data['metodo'] = 'Daviplata';
        return $data;
    }

    public function afterCreate(array $data, \App\Models\Pago $pago): void {}
}
