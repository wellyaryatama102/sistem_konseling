<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Services\WhatsApp\WhatsAppGatewayInterface::class, function ($app) {
            $enabled = config('services.whatsapp.enabled', true);
            $provider = config('services.whatsapp.provider', 'fonnte');

            if ($enabled && $provider === 'fonnte') {
                return new \App\Services\WhatsApp\FonnteWhatsAppGateway();
            }

            return new \App\Services\WhatsApp\LogWhatsAppGateway();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
