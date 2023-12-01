<?php

namespace Zsolt148\Szamlazzhu\Services;

use zoparga\SzamlazzHu\Client\Client;
use zoparga\SzamlazzHu\Contracts\ArrayableItem;
use zoparga\SzamlazzHu\Internal\Support\PaymentMethods;
use zoparga\SzamlazzHu\Receipt;
use Zsolt148\Szamlazzhu\Events\CreateReceiptEvent;
use Zsolt148\Szamlazzhu\Interfaces\Receiptable;
use Zsolt148\Szamlazzhu\Models\Receipt as ReceiptModel;

class ReceiptService
{
    use PaymentMethods;

    public function create(Receiptable $receiptable, bool $event = true): mixed
    {
        if ($event) {
            return CreateReceiptEvent::dispatch($receiptable);
        }

        return $this->createNow($receiptable);
    }

    public function cancel(ReceiptModel $receipt, bool $event = true): mixed
    {
        if ($event) {

        }

        return $this->cancelNow($receipt);
    }

    public function createNow(Receiptable $receiptable): false|ReceiptModel
    {
        if (! $this->enabled()) {
            return false;
        }

        $receiptable->eagerLoad();

        $receipt = new Receipt();
        $receipt->prefix = config('szamlazz-hu.prefix');

        if (app()->environment('production')) {
            $receipt->orderNumber = (string) $receiptable->getKey();
        } else {
            $receipt->orderNumber = (string) ($receiptable->getKey() + date('U'));
        }

        $receipt->currency = $receiptable->currency();
        $receipt->paymentMethod = $this->paymentMethod($receiptable->paymentMethod());
        $receipt->comment = $receiptable->comment();

        // needed due to a bug with exchangeRate
        $receipt->exchangeRateBank = 'MNB';
        $receipt->exchangeRate = 1;

        $receiptable->items()->each(function (ArrayableItem $item) use ($receipt) {
            $receipt->addItem($item);
        });

        if ($receiptable->isDiscount() && config('szamlazz-hu.discount_enabled')) {
            $receiptable->discountItems()->each(function (ArrayableItem $item) use ($receipt) {
                $receipt->addItem($item);
            });
        }

        $receipt->save();

        $model = new ReceiptModel();
        $model->receiptable()->associate($receiptable);
        $model->setReceipt($receipt->receiptNumber);
        $model->save();

        if (config('szamlazz-hu.send_notifications')) {
            $receiptable->sendReceiptNotification($model);
        }

        return $model;
    }

    public function cancelNow(ReceiptModel $model): false|ReceiptModel
    {
        if (! $this->enabled()) {
            return $model;
        }

        $client = app(Client::class);

        $receipt = $client->getReceiptByReceiptNumber($model->receipt_number);

        if (! $receipt) {
            return false;
        }

        $newReceipt = $receipt
            ->cancel()
            ->getCancellationReceipt();

        if (! $newReceipt) {
            return false;
        }

        $model
            ->setReceipt($newReceipt->receiptNumber, isCancel: true)
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
