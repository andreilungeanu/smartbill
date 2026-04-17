<?php

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use AndreiLungeanu\Smartbill\Smartbill;

it('returns the correct endpoint instance', function (string $method, string $expectedClass) {
    $endpoint = app(Smartbill::class)->{$method}();

    expect($endpoint)->toBeInstanceOf($expectedClass);
})->with([
    'invoices' => ['invoices', InvoicesEndpoint::class],
    'estimates' => ['estimates', EstimatesEndpoint::class],
    'payments' => ['payments', PaymentsEndpoint::class],
    'taxes' => ['taxes', TaxesEndpoint::class],
    'series' => ['series', SeriesEndpoint::class],
    'stocks' => ['stocks', StocksEndpoint::class],
    'document' => ['document', DocumentEndpoint::class],
]);

it('applies the configured timeout to the underlying HTTP client', function () {
    config()->set('smartbill.timeout', 7);
    app()->forgetInstance(Smartbill::class);

    $smartbill = app(Smartbill::class);

    $reflection = new ReflectionClass($smartbill);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);
    $client = $clientProperty->getValue($smartbill);

    $optionsProperty = (new ReflectionClass($client))->getProperty('options');
    $optionsProperty->setAccessible(true);

    expect($optionsProperty->getValue($client))->toMatchArray(['timeout' => 7]);
});

it('defaults the timeout to 30 seconds when no config is set', function () {
    expect(config('smartbill.timeout'))->toBe(30);
});
