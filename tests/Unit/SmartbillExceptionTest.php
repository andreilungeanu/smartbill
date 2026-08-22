<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

describe('message', function () {
    it('uses errorText from JSON', function (): void {
        Http::fake([
            'https://example.com/*' => Http::response(['errorText' => 'Invalid CIF'], 400),
        ]);

        $exception = new SmartbillApiException(Http::get('https://example.com/test'));

        expect($exception->getMessage())->toBe('Invalid CIF')
            ->and($exception->getCode())->toBe(400);
    });

    it('falls back to the raw body', function (): void {
        Http::fake([
            'https://example.com/*' => Http::response('<p>Server exploded</p>', 500),
        ]);

        $exception = new SmartbillApiException(Http::get('https://example.com/test'));

        expect($exception->getMessage())->toBe('<p>Server exploded</p>')
            ->and($exception->getCode())->toBe(500);
    });

    it('uses the default when the body is empty', function (): void {
        Http::fake([
            'https://example.com/*' => Http::response('', 503),
        ]);

        $exception = new SmartbillApiException(Http::get('https://example.com/test'));

        expect($exception->getMessage())->toBe('Smartbill API error')
            ->and($exception->getCode())->toBe(503);
    });
});

describe('report', function () {
    it('does not log on construct', function (): void {
        $spy = Log::spy();

        Http::fake([
            'https://example.com/*' => Http::response(['errorText' => 'Invalid CIF'], 400),
        ]);

        new SmartbillApiException(Http::get('https://example.com/test'));

        $spy->shouldNotHaveReceived('error');
    });

    it('logs when report() is called', function (): void {
        $spy = Log::spy();

        Http::fake([
            'https://example.com/*' => Http::response(['errorText' => 'Invalid CIF'], 400),
        ]);

        (new SmartbillApiException(Http::get('https://example.com/test')))->report();

        $spy->shouldHaveReceived('error', [
            'Smartbill API Error',
            Mockery::on(fn (array $ctx): bool => $ctx['status'] === 400
                && str_contains($ctx['body'], 'Invalid CIF')
            ),
        ]);
    });
});
