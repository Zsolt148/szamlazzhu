<?php

namespace Zsolt148\Szamlazzhu\Traits;

use Zsolt148\Szamlazzhu\Models\Receipt;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

trait HasReceipts
{
    abstract public function items(): Collection;

    abstract public function isReceiptable(): bool;

    abstract public function sendReceiptNotification(...$args): void;

    public function receipts(): MorphMany
    {
        return $this->morphMany(Receipt::class, 'model');
    }

    public function receipt(): MorphOne
    {
        return $this->morphOne(Receipt::class, 'model');
    }

    public function latestReceipt(): MorphOne
    {
        return $this->morphOne(Receipt::class, 'model')->latestOfMany();
    }

    public function hasReceipt(): bool
    {
        return $this->latestReceipt()->exists();
    }

    public function currency(): string
    {
        return 'HUF';
    }

    public function comment(): string
    {
        return '';
    }

    public function paymentMethod(): string
    {
        return 'cash';
    }

    public function isDiscount(): bool
    {
        return false;
    }

    public function discountItems(): Collection
    {
        return collect();
    }
}
