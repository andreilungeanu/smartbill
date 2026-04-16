<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

it('builds from a Response with errorText JSON', function () {
    Http::fake([
        'https://example.com/*' => Http::response(['errorText' => 'Invalid CIF'], 400),
    ]);

    $response = Http::get('https://example.com/test');

    $exception = new SmartbillApiException($response);

    expect($exception->getMessage())->toBe('Invalid CIF');
    expect($exception->getCode())->toBe(400);
    expect($exception->getResponse())->toBeInstanceOf(Response::class);
});

it('falls back to raw body when no errorText key', function () {
    Http::fake([
        'https://example.com/*' => Http::response('<p>Server exploded</p>', 500),
    ]);

    $response = Http::get('https://example.com/test');

    $exception = new SmartbillApiException($response);

    expect($exception->getMessage())->toBe('<p>Server exploded</p>');
    expect($exception->getCode())->toBe(500);
});

it('uses default message when body is empty', function () {
    Http::fake([
        'https://example.com/*' => Http::response('', 503),
    ]);

    $response = Http::get('https://example.com/test');

    $exception = new SmartbillApiException($response);

    expect($exception->getMessage())->toBe('Smartbill API error');
    expect($exception->getCode())->toBe(503);
});
