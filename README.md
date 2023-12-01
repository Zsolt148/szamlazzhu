# This is my package szamlazzhu

[![Latest Version on Packagist](https://img.shields.io/packagist/v/zsolt148/szamlazzhu.svg?style=flat-square)](https://packagist.org/packages/zsolt148/szamlazzhu)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/zsolt148/szamlazzhu/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/zsolt148/szamlazzhu/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/zsolt148/szamlazzhu/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/zsolt148/szamlazzhu/actions?query=workflow%3A"Fix+PHP+code+style+issues"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/zsolt148/szamlazzhu.svg?style=flat-square)](https://packagist.org/packages/zsolt148/szamlazzhu)

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Support us

[<img src="https://github-ads.s3.eu-central-1.amazonaws.com/szamlazzhu.jpg?t=1" width="419px" />](https://spatie.be/github-ad-click/szamlazzhu)

We invest a lot of resources into creating [best in class open source packages](https://spatie.be/open-source). You can support us by [buying one of our paid products](https://spatie.be/open-source/support-us).

We highly appreciate you sending us a postcard from your hometown, mentioning which of our package(s) you are using. You'll find our address on [our contact page](https://spatie.be/about-us). We publish all received postcards on [our virtual postcard wall](https://spatie.be/open-source/postcards).

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

    'enabled' => env('SZAMLAZZ_HU_ENABLED', false),

    'discount_enabled' => env('SZAMLAZZ_HU_DISCOUNT_ENABLED', false),

    'send_notifications' => env('SZAMLAZZ_HU_SEND_NOTIFICATIONS', true),

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

    ];
```

## Usage

```php

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
