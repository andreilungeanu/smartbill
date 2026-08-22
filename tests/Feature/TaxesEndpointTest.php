<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('lists vat rates', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['errorText' => '', 'taxes' => [['name' => 'Normala', 'percentage' => 21.0]]]),
    ]);

    expect(smartbill()->taxes()->list('test')['taxes'][0])
        ->toHaveKey('name', 'Normala')
        ->toHaveKey('percentage', 21.0);
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/tax?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    smartbill()->taxes()->list('test');
})->throws(SmartbillApiException::class);
