<?php

// Invoices
use Illuminate\Support\Facades\Route;
use Zsolt148\Szamlazzhu\Http\Controllers\InvoiceController;
use Zsolt148\Szamlazzhu\Http\Controllers\ReceiptController;

Route::prefix(config('szamlazz-hu.route.prefix'))
    ->name(config('szamlazz-hu.route.name'))
    ->middleware(config('szamlazz-hu.route.middleware'))
    ->group(function () {
        Route::post('/invoices/create-invoice', [InvoiceController::class, 'createInvoice'])->name('create-invoice');
        Route::post('/invoices/{invoice}/cancel-invoice', [InvoiceController::class, 'cancelInvoice'])->name('cancel-invoice');

        // Receipts
        Route::post('/receipts/create-receipt', [ReceiptController::class, 'createReceipt'])->name('create-receipt');
        Route::post('/receipts/{receipt}/cancel-receipt', [ReceiptController::class, 'cancelReceipt'])->name('cancel-receipt');
    });
