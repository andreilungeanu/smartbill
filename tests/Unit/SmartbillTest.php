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
