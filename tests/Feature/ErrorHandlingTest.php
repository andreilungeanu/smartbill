<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRateLimitException;
use AndreiLungeanu\Smartbill\Exceptions\SmartbillRequestException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

/**
 * @param  array<string, string>  $headers
 */
function fakeApi(mixed $body, int $status = 200, array $headers = []): void
{
    Http::fake(['https://ws.smartbill.ro/SBORO/api/*' => Http::response($body, $status, $headers)]);
}

describe('errorText decides the outcome', function () {
    it('throws when errorText is populated on a 2xx', function (): void {
        fakeApi(['errorText' => 'Seria nu a fost gasita!', 'documentId' => -1], 200);

        smartbill()->invoices()->createV2(['companyVatCode' => 'RO39521446']);
    })->throws(SmartbillApiException::class, 'Seria nu a fost gasita!');

    it('does not throw when errorText is empty', function (): void {
        fakeApi(['errorText' => '', 'message' => '', 'number' => '3593'], 200);

        expect(smartbill()->invoices()->createV2([]))->toHaveKey('number', '3593');
    });

    it('still throws on a failing status without errorText', function (): void {
        fakeApi('', 500);

        smartbill()->invoices()->createV2([]);
    })->throws(SmartbillApiException::class, 'Smartbill API error');
});

describe('decoding', function () {
    it('returns an array when the body is empty', function (): void {
        fakeApi('', 200);

        expect(smartbill()->invoices()->cancel('c', 's', '1'))->toBe([]);
    });

    it('returns an array when the body is JSON null', function (): void {
        fakeApi('null', 200, ['Content-Type' => 'application/json']);

        expect(smartbill()->invoices()->delete('c', 's', '1'))->toBe([]);
    });
});

describe('invalid_request_error', function () {
    it('surfaces the offending field', function (): void {
        fakeApi([
            'status' => 400,
            'type' => 'invalid_request_error',
            'instance' => '/SBORO/api/invoice/v2',
            'errors' => [['code' => 'json_mapping_error', 'message' => 'Unrecognized property: client.nume.', 'param' => 'client.nume']],
        ], 400);

        try {
            smartbill()->invoices()->createV2(['client' => ['nume' => 'T']]);
            $this->fail('expected SmartbillRequestException');
        } catch (SmartbillRequestException $e) {
            expect($e->getParam())->toBe('client.nume')
                ->and($e->getErrorCode())->toBe('json_mapping_error')
                ->and($e->getMessage())->toContain('client.nume');
        }
    });
});

describe('rate limiting', function () {
    it('exposes the rate limit window', function (): void {
        fakeApi(['errorText' => ''], 429, [
            'X-RateLimit-Limit' => '30',
            'X-RateLimit-Remaining' => '0',
            'X-RateLimit-Reset' => '1787385805',
        ]);

        try {
            smartbill()->taxes()->list('RO39521446');
            $this->fail('expected SmartbillRateLimitException');
        } catch (SmartbillRateLimitException $e) {
            expect($e->getLimit())->toBe(30)
                ->and($e->getRemaining())->toBe(0)
                ->and($e->getResetAt())->toBe(1787385805);
        }
    });
});

describe('html bodies', function () {
    it('does not leak the nginx 502 page from a PDF download', function (): void {
        fakeApi("<html>\n<head><title>502 Bad Gateway</title></head>\n<body><center><h1>502 Bad Gateway</h1></center></body>\n</html>", 502);

        try {
            smartbill()->invoices()->getPdf('RO39521446', 'NOEXIST', '1');
            $this->fail('expected SmartbillApiException');
        } catch (SmartbillApiException $e) {
            expect($e->getMessage())->toBe('Smartbill API error')
                ->and($e->getCode())->toBe(502)
                ->and($e->getResponse()->body())->toContain('502 Bad Gateway');
        }
    });

    it('keeps only the cause from an HTML errorText', function (): void {
        fakeApi(['errorText' => 'Cantitate stoc insuficienta pentru produsul X.<b>FCT 1</b><div id="moreErrorDetails"><p>ajutor</p></div>'], 400);

        try {
            smartbill()->invoices()->createV2([]);
            $this->fail('expected SmartbillApiException');
        } catch (SmartbillApiException $e) {
            expect($e->getMessage())->toBe('Cantitate stoc insuficienta pentru produsul X.');
        }
    });
});

describe('optional query parameters', function () {
    it('keeps a falsy product code', function (): void {
        fakeApi(['errorText' => '', 'list' => []], 200);

        smartbill()->stocks()->list('RO39521446', '2026-08-22', productCode: '0');

        Http::assertSent(fn (Request $request): bool => str_contains($request->url(), 'productCode=0'));
    });

    it('omits a null product code', function (): void {
        fakeApi(['errorText' => '', 'list' => []], 200);

        smartbill()->stocks()->list('RO39521446', '2026-08-22');

        Http::assertSent(fn (Request $request): bool => ! str_contains($request->url(), 'productCode'));
    });
});
