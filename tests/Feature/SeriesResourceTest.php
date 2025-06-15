<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;

it('can get document series', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response(['series' => [['name' => 'TEST']]]),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->series()->list('test');

    expect($response->json('series.0.name'))->toBe('TEST');
});

it('throws an exception when the get document series request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->series()->list('test');
})->throws(RequestException::class);
