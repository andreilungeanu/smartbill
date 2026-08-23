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
    })->throws(SmartbillApiException::class, 'Smartbill API error (HTTP 500)');
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
            expect($e->getMessage())->toBe('Smartbill API error (HTTP 502)')
                ->and($e->getCode())->toBe(502)
                ->and($e->getResponse()->body())->toContain('502 Bad Gateway');
        }
    });

    it('keeps the inline detail but drops the help markup', function (): void {
        fakeApi(['errorText' => 'Cantitate stoc insuficienta la <b>FCT 1</b> pentru produsul <b>Mere</b>.<div id="moreErrorDetails"><p>ajutor</p></div>'], 400);

        try {
            smartbill()->invoices()->createV2([]);
            $this->fail('expected SmartbillApiException');
        } catch (SmartbillApiException $e) {
            expect($e->getMessage())->toBe('Cantitate stoc insuficienta la FCT 1 pentru produsul Mere.');
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

describe('the document/send envelope', function () {
    it('reads the cause out of a nested status object', function (): void {
        fakeApi(['status' => ['code' => 1, 'message' => 'Documentul nu a fost gasit']], 400);

        smartbill()->document()->send(['companyVatCode' => 'RO39521446']);
    })->throws(SmartbillApiException::class, 'Documentul nu a fost gasit');

    it('does not mistake the integer status of invalid_request_error for it', function (): void {
        fakeApi([
            'status' => 400,
            'type' => 'invalid_request_error',
            'errors' => [['code' => 'json_mapping_error', 'message' => 'Unrecognized property: zzz.', 'param' => 'zzz']],
        ], 400);

        smartbill()->document()->send([]);
    })->throws(SmartbillRequestException::class, 'Unrecognized property: zzz. (zzz)');
});

describe('the shared client', function () {
    it('does not carry query parameters into the next call', function (): void {
        // The whole reason sendQuery() exists: the client is a singleton, so a mutating
        // withQueryParameters() would leak into every later request.
        fakeApi(['errorText' => '', 'message' => 'ok'], 200);

        smartbill()->invoices()->delete('RO39521446', 'TE', '0001');
        smartbill()->taxes()->list('RO39521446');

        // assertNotSent, not assertSent: assertSent passes as soon as any one request
        // matches, which the DELETE would do on its own and hide a leak entirely.
        Http::assertNotSent(fn (Request $request): bool => str_contains($request->url(), '/tax')
            && (str_contains($request->url(), 'seriesname') || str_contains($request->url(), 'number=')));

        Http::assertSent(fn (Request $request): bool => $request->url() === 'https://ws.smartbill.ro/SBORO/api/tax?cif=RO39521446');
    });

    it('sends no body on query verbs', function (): void {
        fakeApi(['errorText' => '', 'message' => 'ok'], 200);

        smartbill()->invoices()->cancel('RO39521446', 'TE', '0001');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT' && $request->body() === '');
    });
});

describe('pdf failures differ by endpoint', function () {
    it('surfaces the JSON errorText of an estimate PDF', function (): void {
        // /estimate/pdf answers a normal 400 with errorText, unlike /invoice/pdf's 502.
        fakeApi(['errorText' => 'Proforma cu seria si numarul TP0001 nu a fost gasita!'], 400);

        smartbill()->estimates()->getPdf('RO39521446', 'TP', '0001');
    })->throws(SmartbillApiException::class, 'Proforma cu seria si numarul TP0001 nu a fost gasita!');
});

describe('rate limiting through an endpoint', function () {
    it('raises the rate limit exception on the live 403 shape', function (): void {
        // The live blocking response: 403, Romanian errorText, no X-RateLimit headers.
        fakeApi(['errorText' => 'Ai depasit limita maxima de requesturi admisa. Vei putea executa alte requesturi dupa 10 min de la momentul blocarii 22/08/2026 11:40:35'], 403);

        try {
            smartbill()->taxes()->list('RO39521446');
            $this->fail('expected SmartbillRateLimitException');
        } catch (SmartbillRateLimitException $e) {
            expect($e->getCode())->toBe(403)
                ->and($e->getRemaining())->toBeNull()
                ->and($e->getMessage())->toContain('limita maxima de requesturi');
        }
    });

    it('leaves an ordinary 403 as the base exception', function (): void {
        fakeApi(['errorText' => 'Factura nu este ultima din serie si nu poate fi stearsa.'], 403);

        try {
            smartbill()->invoices()->delete('RO39521446', 'TE', '0001');
            $this->fail('expected SmartbillApiException');
        } catch (SmartbillApiException $e) {
            expect($e::class)->toBe(SmartbillApiException::class);
        }
    });
});
