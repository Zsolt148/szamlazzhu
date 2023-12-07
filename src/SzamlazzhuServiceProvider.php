<?php

namespace Zsolt148\Szamlazzhu;

use Illuminate\Contracts\Support\DeferrableProvider;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zsolt148\Szamlazzhu\Services\InvoiceService;
use Zsolt148\Szamlazzhu\Services\ReceiptService;

class SzamlazzhuServiceProvider extends PackageServiceProvider implements DeferrableProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('szamlazzhu')
            ->hasConfigFile('szamlazz-hu')
            ->hasMigration('create_szamlazzhu_tables')
            ->publishesServiceProvider('SzamlazzhuServiceProvider')
            ->hasRoute('web');
    }

    public function register()
    {
        parent::register();

        $this->app->bind('szamlazzhu', function () {
            return new Szamlazzhu(
                new InvoiceService(),
                new ReceiptService()
            );
        });
    }

    public function provides()
    {
        return [
            'szamlazzhu',
        ];
    }
}
