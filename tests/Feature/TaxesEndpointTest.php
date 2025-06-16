<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can get vat rates', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['taxes' => [['name' => 'Normala', 'value' => 19]]]),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->taxes()->list('test');

    expect($response['taxes'][0]['name'])->toBe('Normala');
});

it('throws an exception when the get vat rates request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->taxes()->list('test');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);
