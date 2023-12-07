<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Zsolt148\Szamlazzhu\Events\Invoice\CreatedCancelInvoice;
use Zsolt148\Szamlazzhu\Events\Invoice\CreatedInvoice;
use Zsolt148\Szamlazzhu\Events\Invoice\CreateInvoice;
use Zsolt148\Szamlazzhu\Events\Invoice\CreatingCancelInvoice;
use Zsolt148\Szamlazzhu\Events\Invoice\CreatingInvoice;
use Zsolt148\Szamlazzhu\Events\Receipt\CreatedCancelReceipt;
use Zsolt148\Szamlazzhu\Events\Receipt\CreatedReceipt;
use Zsolt148\Szamlazzhu\Events\Receipt\CreateReceipt;
use Zsolt148\Szamlazzhu\Events\Receipt\CreatingCancelReceipt;
use Zsolt148\Szamlazzhu\Events\Receipt\CreatingReceipt;
use Zsolt148\Szamlazzhu\Listeners\CreateInvoiceListener;
use Zsolt148\Szamlazzhu\Listeners\CreateReceiptListener;

class SzamlazzhuServiceProvider extends ServiceProvider
{
    public function events(): array
    {
        return [
            // Invoice
            CreatingInvoice::class => [],
            CreateInvoice::class => [
                CreateInvoiceListener::class,
            ],
            CreatedInvoice::class => [],
            CreatingCancelInvoice::class => [],
            CreatedCancelInvoice::class => [],

            // Receipt
            CreatingReceipt::class => [],
            CreateReceipt::class => [
                CreateReceiptListener::class,
            ],
            CreatedReceipt::class => [],
            CreatingCancelReceipt::class => [],
            CreatedCancelReceipt::class => [],

        ];
    }

    public function register()
    {
        $this->booting(function () {
            foreach ($this->events() as $event => $listeners) {
                foreach (array_unique($listeners, SORT_REGULAR) as $listener) {
                    Event::listen($event, $listener);
                }
            }
        });
    }

    public function boot()
    {
        //
    }
}
