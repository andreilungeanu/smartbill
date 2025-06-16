<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can send a document', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/document/send' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->document()->send(['to' => 'test@example.com']);

    expect($response['message'])->toBe('Success');
});
