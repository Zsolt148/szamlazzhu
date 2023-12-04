<?php

namespace Zsolt148\Szamlazzhu\Services;

use zoparga\SzamlazzHu\Client\Client;
use zoparga\SzamlazzHu\Contracts\ArrayableItem;
use zoparga\SzamlazzHu\Internal\Support\PaymentMethods;
use zoparga\SzamlazzHu\Receipt;
use Zsolt148\Szamlazzhu\Events\CreateReceiptEvent;
use Zsolt148\Szamlazzhu\Interfaces\Receiptable;
use Zsolt148\Szamlazzhu\Models\Receipt as ReceiptModel;

class ReceiptService extends Service
{
    use PaymentMethods;

    public function create(Receiptable $receiptable, ...$args): mixed
    {
        return CreateReceiptEvent::dispatch($receiptable, ...$args);
    }

    public function cancel(ReceiptModel $receipt): false|ReceiptModel
    {
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
        $model->model()->associate($receiptable);
        $model->setReceipt($receipt->receiptNumber);
        $model->save();

        if ($this->sendNotifications()) {
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
        if ($this->sendNotifications()) {

        }

        return $model;
    }
}
