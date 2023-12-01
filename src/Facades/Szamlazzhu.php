<?php

namespace Zsolt148\Szamlazzhu\Facades;

use Illuminate\Support\Facades\Facade;
use Zsolt148\Szamlazzhu\Services\InvoiceService;
use Zsolt148\Szamlazzhu\Services\ReceiptService;

/**
 * @method static InvoiceService invoice()
 * @method static ReceiptService receipt()
 *
 * @see \Zsolt148\Szamlazzhu\Szamlazzhu
 */
class Szamlazzhu extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Zsolt148\Szamlazzhu\Szamlazzhu::class;
    }
}
