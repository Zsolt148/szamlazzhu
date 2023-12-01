<?php

namespace Zsolt148\Szamlazzhu\Services;

use zoparga\SzamlazzHu\Client\Client;
use zoparga\SzamlazzHu\Contracts\ArrayableItem;
use zoparga\SzamlazzHu\Internal\Support\PaymentMethods;
use zoparga\SzamlazzHu\Invoice;
use Zsolt148\Szamlazzhu\Events\CreateInvoiceEvent;
use Zsolt148\Szamlazzhu\Interfaces\Invoiceable;
use Zsolt148\Szamlazzhu\Models\Invoice as InvoiceModel;

class InvoiceService
{
    use PaymentMethods;

    public function dispatchCreate(Invoiceable $invoiceable, ...$args): mixed
    {
        return CreateInvoiceEvent::dispatch($invoiceable, ...$args);
    }

    public function create(Invoiceable $invoiceable, bool $event = true): mixed
    {
        if ($event) {
            return CreateInvoiceEvent::dispatch($invoiceable);
        }

        return $this->createNow($invoiceable);
    }

    public function cancel(InvoiceModel $invoice, bool $event = true): mixed
    {
        if ($event) {

        }

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
            $invoice->orderNumber = $invoiceable->getKey();
        } else {
            $invoice->orderNumber = (int) ($invoiceable->getKey() + date('U'));
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

        $invoice->setCustomer($invoiceable);

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
        $model->invoiceable()->associate($invoiceable);
        $model->setInvoice($invoice->invoiceNumber);
        $model->save();

        if (config('szamlazz-hu.send_notifications')) {
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
        if (config('szamlazz-hu.send_notifications')) {

        }

        return $model;
    }

    protected function enabled(): bool
    {
        if (config('szamlazz-hu.enabled')) {

            throw_if(
                config('szamlazz-hu.client.credentials.api_key') == null,
                'RuntimeException',
                'Szamlazzhu API key is missing!'
            );

            return true;
        }

        return false;
    }

    protected function paymentMethod(string $paymentMethod)
    {
        switch ($paymentMethod) {
            case 'cash':
                return $this->getPaymentMethod('cash');
            case 'simplepay':
                return $this->getPaymentMethod('otp_simple');
            case 'szep':
                return $this->getPaymentMethod('szép_card');
        }

        throw new \InvalidArgumentException("Unsupported gateway [$paymentMethod]!");
    }
}
