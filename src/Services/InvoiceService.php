<?php

namespace Zsolt148\Szamlazzhu\Services;

use zoparga\SzamlazzHu\Client\Client;
use zoparga\SzamlazzHu\Contracts\ArrayableItem;
use zoparga\SzamlazzHu\Invoice;
use Zsolt148\Szamlazzhu\Events\CreateInvoiceEvent;
use Zsolt148\Szamlazzhu\Interfaces\Invoiceable;
use Zsolt148\Szamlazzhu\Models\Invoice as InvoiceModel;

class InvoiceService extends Service
{
    public function create(Invoiceable $invoiceable, ...$args): mixed
    {
        return CreateInvoiceEvent::dispatch($invoiceable, ...$args);
    }

    public function cancel(InvoiceModel $invoice): false|InvoiceModel
    {
        return $this->cancelNow($invoice);
    }

    public function createNow(Invoiceable $invoiceable): false|InvoiceModel
    {
        if (! $this->enabled()) {
            return false;
        }

        $invoiceable->eagerLoad();

        $invoice = new Invoice();
        $invoice->isPaid = $invoiceable->isPaid();

        if (app()->environment('production')) {
            $invoice->orderNumber = (string) $invoiceable->getKey();
        } else {
            $invoice->orderNumber = (string) ($invoiceable->getKey() + date('U'));
        }

        $invoice->invoiceLanguage = $invoiceable->invoiceLanguage();
        $invoice->currency = $invoiceable->currency();
        $invoice->fulfillmentAt = $invoiceable->fulfillmentAt();
        $invoice->paymentDeadline = $invoiceable->paymentDeadline();
        $invoice->paymentMethod = $this->paymentMethod($invoiceable->paymentMethod());
        $invoice->comment = $invoiceable->comment();

        // needed due to a bug with exchangeRate
        $invoice->exchangeRateBank = 'MNB';
        $invoice->exchangeRate = 1;

        $invoice->setCustomer($invoiceable->customer());

        $invoiceable->items()->each(function (ArrayableItem $item) use ($invoice) {
            $invoice->addItem($item);
        });

        if ($invoiceable->isDiscount() && config('szamlazz-hu.discount_enabled')) {
            $invoiceable->discountItems()->each(function (array $item) use ($invoice) {
                $invoice->addItem($item);
            });
        }

        $invoice->save();

        $model = new InvoiceModel();
        $model->model()->associate($invoiceable);
        $model->setInvoice($invoice->invoiceNumber);
        $model->save();

        if ($this->sendNotifications()) {
            $invoiceable->sendInvoiceNotification($invoice);
        }

        return $model;
    }

    public function cancelNow(InvoiceModel $model): false|InvoiceModel
    {
        if (! $this->enabled()) {
            return $model;
        }

        $client = app(Client::class);

        $invoice = $client->getInvoice($model->invoice_number);

        if (! $invoice) {
            return false;
        }

        $newInvoice = $invoice
            ->cancel()
            ->getCancellationInvoice();

        if (! $newInvoice) {
            return false;
        }

        $model
            ->setInvoice($newInvoice->invoiceNumber, isCancel: true)
            ->save();

        // TODO send cancel notification
        if ($this->sendNotifications()) {

        }

        return $model;
    }
}
