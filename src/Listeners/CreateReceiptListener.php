<?php

namespace Zsolt148\Szamlazzhu\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Zsolt148\Szamlazzhu\Events\Receipt\CreateReceipt;
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;

class CreateReceiptListener implements ShouldQueue
{
    use InteractsWithQueue;

    public function __construct()
    {
        //
    }

    public function handle(CreateReceipt $event): void
    {
        $receiptable = $event->getReceiptable();

        if (! $receiptable->isReceiptable()) {
            return;
        }

        Szamlazzhu::receipt()->createNow($receiptable);
    }
}
