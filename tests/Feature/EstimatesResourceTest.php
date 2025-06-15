<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

it('can create an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);

    expect($response->json('number'))->toBe('123');
});

it('throws an exception when the create estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);
})->throws(RequestException::class);
