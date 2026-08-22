<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

describe('create', function () {
    it('returns the invoice number', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['number' => '123']),
        ]);

        expect(smartbill()->invoices()->create(['client' => ['name' => 'Test Client']]))
            ->toHaveKey('number', '123');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->create(['client' => ['name' => 'Test Client']]);
    })->throws(SmartbillApiException::class);
});

describe('createV2', function () {
    it('returns the series', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/v2' => Http::response(['series' => 'TESTV2']),
        ]);

        expect(smartbill()->invoices()->createV2(['value' => 100]))
            ->toHaveKey('series', 'TESTV2');
    });
});

describe('getPdf', function () {
    it('returns the PDF body', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/pdf*' => Http::response('PDF Content'),
        ]);

        expect(smartbill()->invoices()->getPdf('test-cif', 'test-series', '123'))
            ->toBe('PDF Content');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/pdf*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->getPdf('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('getPaymentStatus', function () {
    it('returns the payment status', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus*' => Http::response(['status' => 'Paid']),
        ]);

        expect(smartbill()->invoices()->getPaymentStatus('test-cif', 'test-series', '123'))
            ->toHaveKey('status', 'Paid');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->getPaymentStatus('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('reverse', function () {
    it('returns the storno number', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['number' => 'S123']),
        ]);

        expect(smartbill()->invoices()->reverse('test-cif', 'test-series', '123', '2025-01-01'))
            ->toHaveKey('number', 'S123');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->reverse('test-cif', 'test-series', '123', '2025-01-01');
    })->throws(SmartbillApiException::class);

    it('omits issueDate when it is not given', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['errorText' => '', 'number' => 'S123']),
        ]);

        smartbill()->invoices()->reverse('test-cif', 'test-series', '123');

        Http::assertSent(fn (Request $request): bool => ! array_key_exists('issueDate', (array) $request->data()));
    });

    it('sends issueDate when it is given', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['errorText' => '', 'number' => 'S123']),
        ]);

        smartbill()->invoices()->reverse('test-cif', 'test-series', '123', '2025-01-01');

        Http::assertSent(fn (Request $request): bool => $request->data()['issueDate'] === '2025-01-01');
    });
});

describe('cancel', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/cancel*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->invoices()->cancel('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
            && str_contains($request->url(), '/invoice/cancel')
            && str_contains($request->url(), 'cif=test-cif')
            && str_contains($request->url(), 'seriesname=test-series')
            && str_contains($request->url(), 'number=123')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/cancel*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->cancel('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('restore', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/restore*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->invoices()->restore('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
            && str_contains($request->url(), '/invoice/restore')
            && str_contains($request->url(), 'cif=test-cif')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice/restore*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->restore('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('delete', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->invoices()->delete('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
            && str_contains($request->url(), '/invoice')
            && str_contains($request->url(), 'cif=test-cif')
            && str_contains($request->url(), 'seriesname=test-series')
            && str_contains($request->url(), 'number=123')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/invoice*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->invoices()->delete('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});
