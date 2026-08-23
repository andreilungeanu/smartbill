<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRateLimitException;
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * @param  array<string, string>  $headers
 */
function failing(mixed $body, int $status = 400, array $headers = []): Response
{
    Http::fake(['https://example.com/*' => Http::response($body, $status, $headers)]);

    return Http::get('https://example.com/test');
}

describe('message', function () {
    it('uses errorText from JSON', function (): void {
        $exception = new SmartbillApiException(failing(['errorText' => 'Invalid CIF']));

        expect($exception->getMessage())->toBe('Invalid CIF')
            ->and($exception->getCode())->toBe(400)
            ->and($exception->getErrorText())->toBe('Invalid CIF');
    });

    it('falls back to the raw body', function (): void {
        $exception = new SmartbillApiException(failing('Server exploded', 500));

        expect($exception->getMessage())->toBe('Server exploded')
            ->and($exception->getCode())->toBe(500);
    });

    it('uses the default when the body is empty', function (): void {
        expect((new SmartbillApiException(failing('', 503)))->getMessage())
            ->toBe('Smartbill API error (HTTP 503)');
    });

    it('ignores an empty errorText, which means success', function (): void {
        expect((new SmartbillApiException(failing(['errorText' => '', 'message' => 'x'])))->getMessage())
            ->toBe('Smartbill API error (HTTP 400)');
    });

    it('keeps the cause when <b> sits inside the sentence', function (): void {
        // The real Smartbill message wraps the document and product in <b>. Cutting at
        // the first tag would leave only "Cantitate stoc insuficienta la".
        $exception = new SmartbillApiException(failing([
            'errorText' => 'Cantitate stoc insuficienta la <b>FCT 123 / 22.08.2026</b> pentru produsul <b>Mere Golden</b>.',
        ]));

        expect($exception->getMessage())
            ->toBe('Cantitate stoc insuficienta la FCT 123 / 22.08.2026 pentru produsul Mere Golden.');
    });

    it('drops the hidden help block but keeps the cause', function (): void {
        $exception = new SmartbillApiException(failing([
            'errorText' => 'Unitatea de masura <b>buc</b> a produsului <b>X</b> nu are factor de conversie setat.<div id="moreErrorDetails" style="display:none"><p>ajutor</p></div>',
        ]));

        expect($exception->getMessage())
            ->toBe('Unitatea de masura buc a produsului X nu are factor de conversie setat.')
            ->and($exception->getResponse()->body())->toContain('moreErrorDetails');
    });

    it('drops the suggestion appended after a line break', function (): void {
        $exception = new SmartbillApiException(failing([
            'errorText' => 'Nu ai facut nicio achizitie pentru produsul Mere.<br/>Verifica gestiunea.',
        ]));

        expect($exception->getMessage())->toBe('Nu ai facut nicio achizitie pentru produsul Mere.');
    });

    it('does not leak an HTML error page into the message', function (): void {
        $exception = new SmartbillApiException(failing('<html><body><h1>HTTP 500</h1></body></html>', 500));

        expect($exception->getMessage())->toBe('Smartbill API error (HTTP 500)');
    });

    it('reads the cooldown when present', function (): void {
        expect((new SmartbillApiException(failing(['errorText' => 'Blocat', 'cooldown' => 600], 401)))->getCooldown())
            ->toBe(600);
    });
});

describe('from', function () {
    it('picks the request exception for invalid_request_error', function (): void {
        expect(SmartbillApiException::from(failing(['type' => 'invalid_request_error', 'errors' => []]))::class)
            ->toBe(SmartbillRequestException::class);
    });

    it('picks the rate limit exception on 429', function (): void {
        expect(SmartbillApiException::from(failing(['errorText' => ''], 429))::class)
            ->toBe(SmartbillRateLimitException::class);
    });

    it('picks the rate limit exception on the 403 that names the limit', function (): void {
        $body = ['errorText' => 'Ai depasit limita maxima de requesturi admisa. Vei putea executa alte requesturi dupa 10 min de la momentul blocarii 22/08/2026 11:40:35'];

        expect(SmartbillApiException::from(failing($body, 403))::class)
            ->toBe(SmartbillRateLimitException::class);
    });

    it('leaves an ordinary 403 alone', function (): void {
        $body = ['errorText' => 'Factura nu este ultima din serie si nu poate fi stearsa.'];

        expect(SmartbillApiException::from(failing($body, 403))::class)
            ->toBe(SmartbillApiException::class);
    });

    it('picks the base exception otherwise', function (): void {
        expect(SmartbillApiException::from(failing(['errorText' => 'Seria nu a fost gasita!']))::class)
            ->toBe(SmartbillApiException::class);
    });
});

describe('invalid_request_error', function () {
    it('names the offending field', function (): void {
        $exception = new SmartbillRequestException(failing([
            'status' => 400,
            'type' => 'invalid_request_error',
            'errors' => [['code' => 'json_mapping_error', 'message' => 'Unrecognized property: zzz.', 'param' => 'zzz']],
        ]));

        expect($exception->getMessage())->toBe('Unrecognized property: zzz. (zzz)')
            ->and($exception->getParam())->toBe('zzz')
            ->and($exception->getErrorCode())->toBe('json_mapping_error')
            ->and($exception->getErrors())->toHaveCount(1);
    });

    it('names a field inside a list', function (): void {
        $exception = new SmartbillRequestException(failing([
            'type' => 'invalid_request_error',
            'errors' => [['code' => 'json_mapping_error', 'message' => 'Could not map property.', 'param' => 'products[0].quantity']],
        ]));

        expect($exception->getParam())->toBe('products[0].quantity');
    });

    it('survives an empty errors list', function (): void {
        $exception = new SmartbillRequestException(failing(['type' => 'invalid_request_error']));

        expect($exception->getMessage())->toBe('Smartbill rejected the request')
            ->and($exception->getParam())->toBeNull();
    });
});

describe('rate limit', function () {
    it('exposes the rate limit headers', function (): void {
        $exception = new SmartbillRateLimitException(failing(['errorText' => ''], 429, [
            'X-RateLimit-Limit' => '30',
            'X-RateLimit-Remaining' => '0',
            'X-RateLimit-Reset' => '1787384999',
        ]));

        expect($exception->getMessage())->toBe('Smartbill API rate limit exceeded')
            ->and($exception->getLimit())->toBe(30)
            ->and($exception->getRemaining())->toBe(0)
            ->and($exception->getResetAt())->toBe(1787384999);
    });

    it('returns null when the headers are absent', function (): void {
        $exception = new SmartbillRateLimitException(failing(['errorText' => ''], 429));

        expect($exception->getLimit())->toBeNull()
            ->and($exception->getResetAt())->toBeNull();
    });

    it('keeps the blocking message and reports no window', function (): void {
        // The live blocking response carries no X-RateLimit headers at all.
        $exception = new SmartbillRateLimitException(failing([
            'errorText' => 'Ai depasit limita maxima de requesturi admisa. Vei putea executa alte requesturi dupa 10 min de la momentul blocarii 22/08/2026 11:40:35',
        ], 403));

        expect($exception->getMessage())->toContain('limita maxima de requesturi')
            ->and($exception->getCode())->toBe(403)
            ->and($exception->getRemaining())->toBeNull();
    });
});

describe('context', function () {
    it('does not log on construct', function (): void {
        $spy = Log::spy();

        new SmartbillApiException(failing(['errorText' => 'Invalid CIF']));

        $spy->shouldNotHaveReceived('error');
    });

    it('exposes the status and body for the framework log entry', function (): void {
        $exception = new SmartbillApiException(failing(['errorText' => 'Invalid CIF']));

        expect($exception->context())
            ->toHaveKey('smartbill_status', 400)
            ->and($exception->context()['smartbill_body'])->toContain('Invalid CIF');
    });

    it('truncates a long body', function (): void {
        $exception = new SmartbillApiException(failing(str_repeat('x', 2000), 500));

        expect(strlen((string) $exception->context()['smartbill_body']))->toBeLessThan(600);
    });

    it('does not define report(), so Laravel still logs the trace', function (): void {
        // A report() returning anything but false makes Laravel skip its own reporting.
        expect(method_exists(SmartbillApiException::class, 'report'))->toBeFalse();
    });
});
