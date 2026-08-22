<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

describe('create', function () {
    it('returns the estimate number', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['number' => '123']),
        ]);

        expect(smartbill()->estimates()->create(['client' => ['name' => 'Test Client']]))
            ->toHaveKey('number', '123');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->create(['client' => ['name' => 'Test Client']]);
    })->throws(SmartbillApiException::class);
});

describe('createV2', function () {
    it('returns the series', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/v2' => Http::response(['series' => 'TESTV2']),
        ]);

        expect(smartbill()->estimates()->createV2(['value' => 100]))
            ->toHaveKey('series', 'TESTV2');
    });
});

describe('getPdf', function () {
    it('returns the PDF body', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/pdf*' => Http::response('PDF Content'),
        ]);

        expect(smartbill()->estimates()->getPdf('test-cif', 'test-series', '123'))
            ->toBe('PDF Content');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/pdf*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->getPdf('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('getInvoices', function () {
    it('returns invoices issued from the estimate', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/invoices*' => Http::response(['invoices' => [['number' => 'INV-001']]]),
        ]);

        expect(smartbill()->estimates()->getInvoices('test-cif', 'test-series', '123')['invoices'][0])
            ->toHaveKey('number', 'INV-001');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/invoices*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->getInvoices('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('cancel', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/cancel*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->estimates()->cancel('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
            && str_contains($request->url(), '/estimate/cancel')
            && str_contains($request->url(), 'cif=test-cif')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/cancel*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->cancel('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('restore', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/restore*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->estimates()->restore('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'PUT'
            && str_contains($request->url(), '/estimate/restore')
            && str_contains($request->url(), 'cif=test-cif')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate/restore*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->restore('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});

describe('delete', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->estimates()->delete('test-cif', 'test-series', '123'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
            && str_contains($request->url(), '/estimate')
            && str_contains($request->url(), 'cif=test-cif')
        );
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/estimate*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->estimates()->delete('test-cif', 'test-series', '123');
    })->throws(SmartbillApiException::class);
});
