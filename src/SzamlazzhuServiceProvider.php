<?php

namespace Zsolt148\Szamlazzhu;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Zsolt148\Szamlazzhu\Services\InvoiceService;
use Zsolt148\Szamlazzhu\Services\ReceiptService;

class SzamlazzhuServiceProvider extends PackageServiceProvider
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
            ->hasViews()
            ->hasMigration('create_szamlazzhu_tables')
            ->hasRoute('web');
    }

    //    public function register()
    //    {
    //        parent::register();
    //
    //        $this->app->bind('szamlazzhu', function () {
    //            return new Szamlazzhu(
    //                new InvoiceService(),
    //                new ReceiptService()
    //            );
    //        });
    //    }
}
