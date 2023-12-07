<?php

namespace Zsolt148\Szamlazzhu\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Zsolt148\Szamlazzhu\Contracts\Receiptable;

class CreateReceiptEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected Receiptable $receiptable;

    public function __construct(Receiptable $receiptable)
    {
        $this->receiptable = $receiptable;
    }

    public function getReceiptable(): Receiptable
    {
        return $this->receiptable;
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('channel-name'),
        ];
    }
}
