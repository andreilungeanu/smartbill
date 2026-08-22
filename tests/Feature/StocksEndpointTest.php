<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('lists stocks', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks?cif=test&date=2025-06-14' => Http::response(['stocks' => [['name' => 'Product 1']]]),
    ]);

    expect(smartbill()->stocks()->list('test', '2025-06-14')['stocks'][0])
        ->toHaveKey('name', 'Product 1');
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks?cif=test&date=2025-06-14' => Http::response(['error' => 'Error'], 500),
    ]);

    smartbill()->stocks()->list('test', '2025-06-14');
})->throws(SmartbillApiException::class);
