<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Payments\PaymentStrategyFactory;

class UpdatePagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $base = [
            'miembro_id' => ['required','exists:miembros,id'],
            'fecha'      => ['required','date'],
            'monto'      => ['required','numeric','min:0'],
            'metodo'     => ['required','string'],
            'referencia' => ['nullable','string','max:100'],
            'notas'      => ['nullable','string','max:500'],
        ];
        $strategy = PaymentStrategyFactory::resolve($this->input('metodo'));
        return array_merge($base, $strategy->rules());
    }
}
