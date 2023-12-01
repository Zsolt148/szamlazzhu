<?php

namespace Zsolt148\Szamlazzhu\Providers;

use App\Szamlazzhu\Services\InvoiceService;
use App\Szamlazzhu\Services\ReceiptService;
use App\Szamlazzhu\Szamlazzhu;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;

class SzamlazzhuServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('szamlazzhu', function () {
            return new Szamlazzhu(
                new InvoiceService(),
                new ReceiptService()
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function provides()
    {
        return ['szamlazzhu'];
    }
}
