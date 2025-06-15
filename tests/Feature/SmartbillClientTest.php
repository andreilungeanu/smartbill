<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

it('can create an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);

    expect($response->json('number'))->toBe('123');
});

it('throws an exception when the request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);
})->throws(RequestException::class);
