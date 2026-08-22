<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('sends the document', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response([
            'status' => ['code' => 0, 'message' => 'Documentul a fost trimis cu succes.'],
        ]),
    ]);

    expect(smartbill()->document()->send(['companyVatCode' => 'RO39521446'])['status'])
        ->toHaveKey('code', 0);
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response([
            'status' => ['code' => 1, 'message' => 'Documentul nu a fost gasit'],
        ], 400),
    ]);

    smartbill()->document()->send(['companyVatCode' => 'RO39521446']);
})->throws(SmartbillApiException::class, 'Documentul nu a fost gasit');

it('throws on a 2xx carrying a non-zero status code', function (): void {
    // The envelope reports through status.code, so a 200 can still be a failure.
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response([
            'status' => ['code' => 1, 'message' => 'Server-ul de email nu a fost configurat.'],
        ], 200),
    ]);

    smartbill()->document()->send(['companyVatCode' => 'RO39521446']);
})->throws(SmartbillApiException::class, 'Server-ul de email nu a fost configurat.');
