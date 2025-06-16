<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can create an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);

    expect($response['number'])->toBe('123');
});

it('throws an exception when the create estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can create a v2 estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/v2' => Http::response(['series' => 'TESTV2']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->createV2(['value' => 100]);

    expect($response['series'])->toBe('TESTV2');
});
