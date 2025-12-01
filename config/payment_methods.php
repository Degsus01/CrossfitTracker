<?php

return [
    // valor que llega desde el <select name="metodo">
    'map' => [
        'Efectivo'     => \App\Payments\Strategies\CashPayment::class,
        'Transferencia'=> \App\Payments\Strategies\TransferPayment::class,
        'Tarjeta'      => \App\Payments\Strategies\CardPayment::class,
        'Nequi'        => \App\Payments\Strategies\NequiPayment::class,
        'Daviplata'    => \App\Payments\Strategies\DaviplataPayment::class,
    ],
];
