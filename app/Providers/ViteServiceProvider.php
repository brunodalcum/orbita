<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ViteServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Registrar diretiva Blade personalizada para Vite com fallback
        Blade::directive('viteWithFallback', function ($expression) {
            return "<?php echo app('App\\Services\\ViteService')->renderAssets($expression); ?>";
        });
    }
}
