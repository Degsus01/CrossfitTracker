<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator; // 👈 importante
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void

    {
        {
    // ✅ Directiva Blade personalizada: @admin
    Blade::if('admin', function () {
        return auth()->check() && auth()->user()->rol === 'admin';
    });
}
        
        // Esto hará que la paginación use el estilo de Bootstrap 5
        Paginator::useBootstrapFive();
    }
}

