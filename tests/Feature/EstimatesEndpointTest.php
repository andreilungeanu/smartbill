<?php

use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Support\Facades\Http;

it('can create an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['number' => '123']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);

    expect($response['number'])->toBe('123');
});

it('throws an exception when the create estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->create(['client' => ['name' => 'Test Client']]);
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can create a v2 estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/v2' => Http::response(['series' => 'TESTV2']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->createV2(['value' => 100]);

    expect($response['series'])->toBe('TESTV2');
});

it('can get an estimate pdf', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/pdf*' => Http::response('PDF Content'),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->getPdf('test-cif', 'test-series', '123');

    expect($response)->toBe('PDF Content');
});

it('throws an exception when the get estimate pdf request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/pdf*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->getPdf('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can get estimate invoices', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/invoices*' => Http::response(['invoices' => [['number' => 'INV-001']]]),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->getInvoices('test-cif', 'test-series', '123');

    expect($response['invoices'][0]['number'])->toBe('INV-001');
});

it('throws an exception when the get estimate invoices request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/invoices*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->getInvoices('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can cancel an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/cancel' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->cancel('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the cancel estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/cancel' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->cancel('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can restore an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/restore' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->restore('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the restore estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate/restore' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->restore('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);

it('can delete an estimate', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate*' => Http::response(['message' => 'Success']),
    ]);

    $smartbill = app(Smartbill::class);

    $response = $smartbill->estimates()->delete('test-cif', 'test-series', '123');

    expect($response['message'])->toBe('Success');
});

it('throws an exception when the delete estimate request fails', function () {
    Http::fake([
        'https://ws.smartbill.ro/SBORO/api/estimate*' => Http::response(['error' => 'Error'], 500),
    ]);

    $smartbill = app(Smartbill::class);

    $smartbill->estimates()->delete('test-cif', 'test-series', '123');
})->throws(\AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException::class);
