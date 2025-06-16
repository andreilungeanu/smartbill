<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can create an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);

    expect($response['number'])->toBe('123');
});

it('throws an exception when the request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can create a v2 invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/v2' => Http::response(['series' => 'TESTV2']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->createV2(['value' => 100]);

    expect($response['series'])->toBe('TESTV2');
});
