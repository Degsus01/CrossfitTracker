<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Payments\PaymentStrategyFactory;

class StorePagoRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        $base = [
            'miembro_id' => ['required','exists:miembros,id'],
            'fecha'      => ['required','date'],
            'monto'      => ['required','numeric','min:0'],
            'metodo'     => ['required','string'], // validamos exactitud contra config en prepare
            'referencia' => ['nullable','string','max:100'],
            'notas'      => ['nullable','string','max:500'],
        ];

        $method = $this->input('metodo');
        $strategy = PaymentStrategyFactory::resolve($method);
        return array_merge($base, $strategy->rules());
    }

    protected function prepareForValidation(): void
    {
        // Opcional: normalizar mayúsculas/acentos del método
        // o rechazar si no está en config:
        $method = $this->input('metodo');
        $allowed = array_keys(config('payment_methods.map', []));
        if ($method && !in_array($method, $allowed, true)) {
            // fuerza un valor imposible para disparar error si quieres
        }
    }
}
