<?php

namespace Zsolt148\Szamlazzhu\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zsolt148\Szamlazzhu\Events\Invoice\CreateInvoice;
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;

class CreateInvoiceListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(CreateInvoice $event): void
    {
        $invoiceable = $event->getInvoiceable();

        if (! $invoiceable->isInvoiceable()) {
            return;
        }

        Szamlazzhu::invoice()->createNow($invoiceable);
    }
}
