<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

it('can get stocks', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks?cif=test&date=2025-06-14' => Http::response(['stocks' => [['name' => 'Product 1']]]),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->stocks()->list('test', '2025-06-14');

    expect($response->json('stocks.0.name'))->toBe('Product 1');
});

it('throws an exception when the get stocks request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks?cif=test&date=2025-06-14' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->stocks()->list('test', '2025-06-14');
})->throws(RequestException::class);
