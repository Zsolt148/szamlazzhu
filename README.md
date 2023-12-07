# This is my package szamlazzhu

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zsolt148/szamlazzhu.svg?style=flat-square)](https://packagist.org/packages/zsolt148/szamlazzhu)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/zsolt148/szamlazzhu/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/zsolt148/szamlazzhu/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/zsolt148/szamlazzhu/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/zsolt148/szamlazzhu/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zsolt148/szamlazzhu.svg?style=flat-square)](https://packagist.org/packages/zsolt148/szamlazzhu)

Built on top of https://github.com/zoparga/laravel-szamlazzhu Laravel szamlazzhu as an simple Facade.

## Installation

You can install the package via composer:

```bash
composer require zsolt148/szamlazzhu
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="szamlazzhu-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="szamlazzhu-config"
```

This is the contents of the published config file:

```php
return [

    /**
     * Global disabled/enable function
     */
    'enabled' => env('SZAMLAZZ_HU_ENABLED', false),

    /**
     * Global discount disabled/enable function
     */
    'discount_enabled' => env('SZAMLAZZ_HU_DISCOUNT_ENABLED', false),

    /**
     * Send invoice/receipt notifications
     */
    'send_notifications' => env('SZAMLAZZ_HU_SEND_NOTIFICATIONS', true),

    /**
     * Create/cancel invoice/receipt routes
     */
    'route' => [
        'prefix' => 'szamlazzhu',
        'name' => 'szamlazzhu.',
        'middleware' => ['web'],
    ],

    /*
     * These merchant details will be used by default.
     * You can override these values.
     * */
    'merchant' => [
        'bank_name' => env('SZAMLAZZ_HU_MERCHANT_BANK_NAME'),
        'bank_account_number' => env('SZAMLAZZ_HU_MERCHANT_BANK_ACCOUNT_NUMBER'),
        'reply_email' => env('SZAMLAZZ_HU_MERCHANT_REPLY_EMAIL'),
    ],

    /*
     * Invoice/Receipt prefix
     */
    'prefix' => env('SZAMLAZZ_HU_PREFIX', 'PRE'),

    /*
     * API Client settings
     */
    'client' => [

        /*
         * Authentication credentials.
         * */
        'credentials' => [
            'api_key' => env('SZAMLAZZ_HU_API_KEY'),
            'username' => env('SZAMLAZZ_HU_USERNAME'),
            'password' => env('SZAMLAZZ_HU_PASSWORD'),
        ],

        /*
         * You can enable the certificate based communication.
         * You do not need to provide password if you'll use szamlazz.hu's own certificate
         * */
        'certificate' => [
            'enabled' => false,
            'disk' => 'local',
            'path' => 'szamlazzhu/cacert.pem', // Relative to disk root
        ],

        /*
         * HTTP request timeout (in seconds)
         */
        'timeout' => 30,

        /*
         * Base URI used to reach API
         * */
        'base_uri' => env('SZAMLAZZ_HU_BASE_URI', 'https://www.szamlazz.hu/'),

        /*
         * Client can automatically save / update invoice PDF files if enabled
         * */
        'storage' => [
            'auto_save' => true,
            'disk' => 'public',
            'path' => 'invoices',
        ],
    ],
```

## Usage

#### Using the global helper
```php
// Access the invoice service
szamlazzhu()->invoice();
szamlazzhu('invoice');

// Access the receipt service
szamlazzhu()->receipt();
szamlazzhu('receipt');

// Create invoice/receipt - dispatches an event
szamlazzhu()->invoice()->create($invoiceable);
szamlazzhu()->receipt()->create($invoiceable);

// Create now
szamlazzhu()->invoice()->createNow($invoiceable);
szamlazzhu()->receipt()->createNow($invoiceable);

// Cancel invoice/receipt
szamlazzhu()->invoice()->cancel($invoice);
szamlazzhu()->receipt()->cancel($receipt);

```

#### Using the facade
```php
use Zsolt148\Szamlazzhu\Facades\Szamlazzhu;

// Access the invoice service
Szamlazzhu::invoice();

// Access the receipt service
Szamlazzhu::receipt();

// Create invoice/receipt - dispatches an event
Szamlazzhu::invoice()->create($invoiceable);
Szamlazzhu::receipt()->create($invoiceable);

// Create now
Szamlazzhu::invoice()->createNow($invoiceable);
Szamlazzhu::receipt()->createNow($invoiceable);

// Cancel invoice/receipt
Szamlazzhu::invoice()->cancel($invoice);
Szamlazzhu::receipt()->cancel($receipt);
```

#### The model you want to be invoiceable/receiptable should implement the Invoiceable/Receiptable interface, with HasInvoice/HasReceipt traits.

```php
use Zsolt148\Szamlazzhu\Contracts\Invoiceable;
use Zsolt148\Szamlazzhu\Traits\HasInvoices;

class Order implements Invoiceable 
{
    use HasInvoices;
    ...
}
```

#### The model you want be as invoice/receipt items should implement the ArrayableItem
If the model will has only one item, it can be the Order model as well.

```php
use zoparga\SzamlazzHu\Contracts\ArrayableItem;

class OrderItem implements ArrayableItem
{
    ...
}
```

#### Using the default routes, it should receive a class string and an ID. 
Returns a json response

```js
await axios.post(route('szamlazzhu.create-receipt'), {
    class: this.model.class,
    id: this.model.id,
}).then(resp => {
    console.log(resp.data)
})
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Zsolt148](https://github.com/Zsolt148)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
