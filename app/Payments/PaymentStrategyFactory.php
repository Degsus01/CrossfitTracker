<?php

namespace App\Payments;

use App\Payments\Contracts\PaymentStrategy;
use InvalidArgumentException;

class PaymentStrategyFactory
{
    public static function resolve(string $method): PaymentStrategy
    {
        $map = config('payment_methods.map', []);
        $class = $map[$method] ?? null;

        if (!$class || !class_exists($class)) {
            throw new InvalidArgumentException("Método de pago no soportado: {$method}");
        }
        return app($class); // usa IoC
    }
}
