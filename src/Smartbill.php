<?php

namespace AndreiLungeanu\Smartbill;

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class Smartbill
{
    public static function api(): PendingRequest
    {
        $config = config('smartbill');

        if (empty($config['api_username'])) {
            throw new InvalidArgumentException('Smartbill API Username is not configured. Please check your .env file.');
        }

        if (empty($config['api_token'])) {
            throw new InvalidArgumentException('Smartbill API token is not configured. Please check your .env file.');
        }

        return Http::withBasicAuth($config['api_username'], $config['api_token'])
            ->baseUrl($config['api_url'])
            ->acceptJson();
    }


    public function invoices(): InvoicesEndpoint
    {
        return new InvoicesEndpoint;
    }

    public function estimates(): EstimatesEndpoint
    {
        return new EstimatesEndpoint;
    }

    public function payments(): PaymentsEndpoint
    {
        return new PaymentsEndpoint;
    }

    public function taxes(): TaxesEndpoint
    {
        return new TaxesEndpoint;
    }

    public function series(): SeriesEndpoint
    {
        return new SeriesEndpoint;
    }

    public function stocks(): StocksEndpoint
    {
        return new StocksEndpoint;
    }

    public function document(): DocumentEndpoint
    {
        return new DocumentEndpoint;
    }
}
