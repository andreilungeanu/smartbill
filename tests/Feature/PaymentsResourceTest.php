<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

it('can create a payment', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['series' => 'TEST']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->payments()->create(['value' => 100]);

    expect($response->json('series'))->toBe('TEST');
});

it('throws an exception when the create payment request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->payments()->create(['value' => 100]);
})->throws(RequestException::class);
