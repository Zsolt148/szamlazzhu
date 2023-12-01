<?php

namespace Zsolt148\Szamlazzhu\Services;

use zoparga\SzamlazzHu\Internal\Support\PaymentMethods;

abstract class Service
{
    use PaymentMethods;

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

    protected function sendNotifications(): bool
    {
        return (bool) config('szamlazz-hu.send_notifications');
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
