<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('lists series for a company', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response([
            'list' => [['name' => 'SBINV']],
        ]),
    ]);

    expect(smartbill()->series()->list('test')['list'][0])
        ->toHaveKey('name', 'SBINV');
});

it('lists series filtered by type', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test&type=f' => Http::response([
            'list' => [['name' => 'SBINV']],
        ]),
    ]);

    expect(smartbill()->series()->list('test', 'f')['list'][0])
        ->toHaveKey('name', 'SBINV');
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/series?cif=test' => Http::response(['error' => 'Error'], 500),
    ]);

    smartbill()->series()->list('test');
})->throws(SmartbillApiException::class);
