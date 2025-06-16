<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can get document series', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response([
            'list' => [['name' => 'SBINV']],
        ]),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->series()->list('test');

    expect($response['list'][0]['name'])->toBe('SBINV');
});

it('throws an exception when the get document series request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->series()->list('test');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);
