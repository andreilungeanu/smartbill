<?php

namespace AndreiLungeanu\Smartbill;

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use Closure;
use Illuminate\Http\Client\PendingRequest;

class Smartbill
{
    /**
     * Pass a factory rather than a built client where possible: a PendingRequest copies
     * Http::fake() and preventStrayRequests() when it is created, so one built ahead of
     * a fake sends its requests to the live API.
     *
     * @param  PendingRequest|Closure(): PendingRequest  $client
     */
    public function __construct(protected PendingRequest|Closure $client) {}

    public function invoices(): InvoicesEndpoint
    {
        return new InvoicesEndpoint($this->client);
    }

    public function estimates(): EstimatesEndpoint
    {
        return new EstimatesEndpoint($this->client);
    }

    public function payments(): PaymentsEndpoint
    {
        return new PaymentsEndpoint($this->client);
    }

    public function taxes(): TaxesEndpoint
    {
        return new TaxesEndpoint($this->client);
    }

    public function series(): SeriesEndpoint
    {
        return new SeriesEndpoint($this->client);
    }

    public function stocks(): StocksEndpoint
    {
        return new StocksEndpoint($this->client);
    }

    public function document(): DocumentEndpoint
    {
        return new DocumentEndpoint($this->client);
    }
}
