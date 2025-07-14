<?php

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use AndreiLungeanu\Smartbill\Smartbill;
use Illuminate\Http\Client\PendingRequest;

beforeEach(function () {
    $this->client = mock(PendingRequest::class);
    $this->smartbill = new Smartbill($this->client);
});

it('returns the correct endpoint instance with the client injected', function (string $method, string $expectedClass) {
    $endpoint = $this->smartbill->{$method}();

    expect($endpoint)->toBeA($expectedClass)->withClient($this->client);
})->with([
    'invoices' => ['invoices', InvoicesEndpoint::class],
    'estimates' => ['estimates', EstimatesEndpoint::class],
    'payments' => ['payments', PaymentsEndpoint::class],
    'taxes' => ['taxes', TaxesEndpoint::class],
    'series' => ['series', SeriesEndpoint::class],
    'stocks' => ['stocks', StocksEndpoint::class],
    'document' => ['document', DocumentEndpoint::class],
]);
