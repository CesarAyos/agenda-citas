<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\App;

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
    // SOLUCIÓN DEFINITIVA para Inertia + Railway
    if (App::environment('production')) {
        URL::forceScheme('https');
        
        // Configuración específica para Inertia
        request()->server->set('HTTPS', 'on');
        
        // Headers para Inertia
        config(['inertia.ssr.enabled' => false]); // Deshabilitar SSR si no lo usas
    }
}
}
