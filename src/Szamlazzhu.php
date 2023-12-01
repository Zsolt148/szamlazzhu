<?php

namespace Zsolt148\Szamlazzhu;

use Zsolt148\Szamlazzhu\Services\InvoiceService;
use Zsolt148\Szamlazzhu\Services\ReceiptService;

class Szamlazzhu
{
    protected InvoiceService $invoiceService;

    protected ReceiptService $receiptService;

    public function __construct(InvoiceService $invoiceService, ReceiptService $receiptService)
    {
        $this->invoiceService = $invoiceService;
        $this->receiptService = $receiptService;
    }

    public function invoice(): InvoiceService
    {
        return $this->invoiceService;
    }

    public function receipt(): ReceiptService
    {
        return $this->receiptService;
    }
}
