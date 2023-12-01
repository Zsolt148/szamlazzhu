<?php

namespace Zsolt148\Szamlazzhu\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Collection;

interface Receiptable
{
    // Model key
    public function getKey();

    // Eager load the needed relations
    public function eagerLoad(): void;

    // Relations to model
    public function receipts(): MorphMany;

    public function receipt(): MorphOne;

    public function latestReceipt(): MorphOne;

    public function hasReceipt(): bool;

    public function isReceiptable(): bool;

    // Misc
    public function currency(): string;

    public function comment(): string;

    // Payment method
    public function paymentMethod(): string;

    // ArrayableItems collection
    public function items(): Collection;

    // Discount + ArrayableItems collection
    public function isDiscount(): bool;

    public function discountItems(): Collection;

    public function sendReceiptNotification(...$args): void;
}
