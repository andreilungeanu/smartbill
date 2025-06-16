<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can create a payment', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['series' => 'TEST']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->payments()->create(['value' => 100]);

    expect($response['series'])->toBe('TEST');
});

it('throws an exception when the create payment request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->payments()->create(['value' => 100]);
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can delete a payment by invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment/v2*' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->payments()->deleteByInvoice('cif', 'paymentType', 'invoiceSeries', 'invoiceNumber');

    expect($response['message'])->toBe('Success');
});

it('can delete a payment by payment details', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment/v2*' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->payments()->deleteByPayment('cif', 'paymentType', '2025-06-16', 100.0, 'clientName', 'clientCif');

    expect($response['message'])->toBe('Success');
});

it('can delete a receipt', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment/chitanta*' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->payments()->deleteReceipt('cif', 'seriesName', 'number');

    expect($response['message'])->toBe('Success');
});
