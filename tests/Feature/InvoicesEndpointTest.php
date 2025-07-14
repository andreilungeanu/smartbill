<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can create an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);

    expect($response['number'])->toBe('123');
});

it('throws an exception when the request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->create(['client' => ['name' => 'Test Client']]);
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can create a v2 invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/v2' => Http::response(['series' => 'TESTV2']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->createV2(['value' => 100]);

    expect($response['series'])->toBe('TESTV2');
});

it('can get an invoice pdf', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/pdf*' => Http::response('PDF Content'),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->getPdf('test-cif', 'test-series', '123');

    expect($response)->toBe('PDF Content');
});

it('throws an exception when the get invoice pdf request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/pdf*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->getPdf('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can get invoice payment status', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus*' => Http::response(['status' => 'Paid']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->getPaymentStatus('test-cif', 'test-series', '123');

    expect($response['status'])->toBe('Paid');
});

it('throws an exception when the get invoice payment status request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/paymentstatus*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->getPaymentStatus('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can reverse an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['number' => 'S123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->reverse('test-cif', 'test-series', '123', '2025-01-01');

    expect($response['number'])->toBe('S123');
});

it('throws an exception when the reverse invoice request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/reverse' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->reverse('test-cif', 'test-series', '123', '2025-01-01');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can cancel an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/cancel' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->cancel('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the cancel invoice request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/cancel' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->cancel('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can restore an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/restore' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->restore('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the restore invoice request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice/restore' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->restore('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can delete an invoice', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice*' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->invoices()->delete('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the delete invoice request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/invoice*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->invoices()->delete('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);
