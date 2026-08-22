<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

describe('create', function () {
    it('returns the payment series', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['series' => 'TEST']),
        ]);

        expect(smartbill()->payments()->create(['value' => 100]))
            ->toHaveKey('series', 'TEST');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->payments()->create(['value' => 100]);
    })->throws(SmartbillApiException::class);
});

describe('getText', function () {
    it('returns the fiscal receipt text', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment/text*' => Http::response(['text' => 'base64']),
        ]);

        expect(smartbill()->payments()->getText('cif', 1384))
            ->toHaveKey('text', 'base64');
    });

    it('throws when the request fails', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment/text*' => Http::response(['error' => 'Error'], 500),
        ]);

        smartbill()->payments()->getText('cif', 1384);
    })->throws(SmartbillApiException::class);
});

describe('deleteReceipt', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment/chitanta*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->payments()->deleteReceipt('cif', 'seriesName', 'number'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
            && str_contains($request->url(), '/payment/chitanta')
            && str_contains($request->url(), 'cif=cif')
            && str_contains($request->url(), 'seriesname=seriesName')
        );
    });
});

describe('deleteByInvoice', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment/v2*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->payments()->deleteByInvoice('cif', 'paymentType', 'invoiceSeries', 'invoiceNumber'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
            && str_contains($request->url(), '/payment/v2')
            && str_contains($request->url(), 'cif=cif')
            && str_contains($request->url(), 'invoiceSeries=invoiceSeries')
        );
    });
});

describe('deleteByPayment', function () {
    it('sends the identifiers as query parameters', function (): void {
        Http::fake([
            'https://ws.smartbill.ro/SBORO/api/payment/v2*' => Http::response(['message' => 'Success']),
        ]);

        expect(smartbill()->payments()->deleteByPayment('cif', 'paymentType', '2025-06-16', 100.0, 'clientName', 'clientCif'))
            ->toHaveKey('message', 'Success');

        Http::assertSent(fn (Request $request): bool => $request->method() === 'DELETE'
            && str_contains($request->url(), '/payment/v2')
            && str_contains($request->url(), 'paymentDate=2025-06-16')
            && str_contains($request->url(), 'paymentValue=100')
        );
    });
});
