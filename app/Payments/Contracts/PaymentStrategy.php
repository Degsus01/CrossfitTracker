<?php

namespace App\Payments\Contracts;

interface PaymentStrategy
{
    /** Nombre legible: ej. “Efectivo” */
    public function label(): string;

    /** Reglas de validación adicionales/condicionales para este método */
    public function rules(): array;

    /**
     * Normaliza/auto-completa datos antes de crear/actualizar.
     * Debe devolver el array final que irá a Eloquent.
     */
    public function normalize(array $data): array;

    /** Hook posterior por si quieres emitir recibo, logs, etc. */
    public function afterCreate(array $data, \App\Models\Pago $pago): void;
}
