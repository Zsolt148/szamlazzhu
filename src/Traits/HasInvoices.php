<?php

namespace Zsolt148\Szamlazzhu\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use zoparga\SzamlazzHu\Contracts\ArrayableCustomer;
use Zsolt148\Szamlazzhu\Models\Invoice;

trait HasInvoices
{
    abstract public function items(): Collection;

    abstract public function isInvoiceable(): bool;

    abstract public function sendInvoiceNotification(...$args): void;

    public function invoices(): MorphMany
    {
        return $this->morphMany(Invoice::class, 'model');
    }

    public function invoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'model');
    }

    public function latestInvoice(): MorphOne
    {
        return $this->morphOne(Invoice::class, 'model')->latestOfMany();
    }

    public function customer(): ArrayableCustomer
    {
        if ($this instanceof ArrayableCustomer) {
            return $this;
        }

        throw new \Exception(class_basename($this).' does not implements ArrayableCustomer!');
    }

    public function isPaid(): bool
    {
        return true;
    }

    public function hasInvoice(): bool
    {
        return $this->latestInvoice()->exists();
    }

    public function invoiceLanguage(): string
    {
        return 'hu';
    }

    public function currency(): string
    {
        return 'HUF';
    }

    public function fulfillmentAt(): Carbon
    {
        return $this->created_at;
    }

    public function paymentDeadline(): Carbon
    {
        return $this->created_at;
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
