<?php

namespace Zsolt148\Szamlazzhu\Interfaces;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use zoparga\SzamlazzHu\Contracts\ArrayableCustomer;

interface Invoiceable
{
    // Model key
    public function getKey();

    // Eager load the needed relations
    public function eagerLoad(): void;

    // Relations
    public function invoices(): MorphMany;

    public function invoice(): MorphOne;

    public function latestInvoice(): MorphOne;

    public function customer(): ArrayableCustomer;

    public function isPaid(): bool;

    public function hasInvoice(): bool;

    public function isInvoiceable(): bool;

    // Misc
    public function invoiceLanguage(): string;

    public function currency(): string;

    public function fulfillmentAt(): Carbon;

    public function paymentDeadline(): Carbon;

    public function comment(): string;

    // Payment method
    public function paymentMethod(): string;

    // ArrayableItems collection
    public function items(): Collection;

    // Discount + ArrayableItems collection
    public function isDiscount(): bool;

    public function discountItems(): Collection;

    public function sendInvoiceNotification(...$args): void;
}
