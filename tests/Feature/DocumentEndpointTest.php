<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;

it('sends the document', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response(['message' => 'Success']),
    ]);

    expect(smartbill()->document()->send(['to' => 'test@example.com']))
        ->toHaveKey('message', 'Success');
});

it('throws when the request fails', function (): void {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response(['error' => 'Error'], 500),
    ]);

    smartbill()->document()->send(['to' => 'test@example.com']);
})->throws(SmartbillApiException::class);
