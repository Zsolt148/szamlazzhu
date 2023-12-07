<?php

namespace Zsolt148\Szamlazzhu\Events\Invoice;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zsolt148\Szamlazzhu\Contracts\Invoiceable;

class CreatingInvoice
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Invoiceable $invoiceable;

    public function __construct(Invoiceable $invoiceable)
    {
        $this->invoiceable = $invoiceable;
    }

    public function getInvoiceable(): Invoiceable
    {
        return $this->invoiceable;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
