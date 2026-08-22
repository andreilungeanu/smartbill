<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('lists stocks', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks*' => Http::response([
            'errorText' => '',
            'list' => [[
                'warehouse' => ['warehouseName' => 'Depozit', 'warehouseType' => 'en gros'],
                'products' => [['productName' => 'Product 1', 'productCode' => 'P1', 'quantity' => 5]],
            ]],
        ]),
    ]);

    expect(smartbill()->stocks()->list('test', '2025-06-14')['list'][0]['products'][0])
        ->toHaveKey('productName', 'Product 1');
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/stocks*' => Http::response(['errorText' => 'Gestiunea nu a fost gasita pe server.'], 400),
    ]);

    smartbill()->stocks()->list('test', '2025-06-14');
})->throws(SmartbillApiException::class, 'Gestiunea nu a fost gasita pe server.');
