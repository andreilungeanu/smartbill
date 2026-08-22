<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('lists vat rates', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['taxes' => [['name' => 'Normala', 'value' => 19]]]),
    ]);

    expect(smartbill()->taxes()->list('test')['taxes'][0])
        ->toHaveKey('name', 'Normala');
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    smartbill()->taxes()->list('test');
})->throws(SmartbillApiException::class);
