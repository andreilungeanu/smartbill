<?php

namespace AndreiLungeanu\Smartbill;

use AndreiLungeanu\Smartbill\Resources\EstimatesResource;
use AndreiLungeanu\Smartbill\Resources\InvoicesResource;
use AndreiLungeanu\Smartbill\Resources\PaymentsResource;
use AndreiLungeanu\Smartbill\Resources\SeriesResource;
use AndreiLungeanu\Smartbill\Resources\StocksResource;
use AndreiLungeanu\Smartbill\Resources\TaxesResource;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class Smartbill
{
    protected array $config;

    protected ?PendingRequest $request = null;

    public function __construct()
    {
        $this->config = config('smartbill');
    }

    protected function getRequest(): PendingRequest
    {
        if ($this->request === null) {
            $this->validateConfig();

            $this->request = Http::withBasicAuth($this->config['api_username'], $this->config['api_token'])
                ->baseUrl($this->config['api_url'])
                ->acceptJson()
                ->asJson();
        }

        return $this->request;
    }

    protected function validateConfig(): void
    {
        if (empty($this->config['api_username'])) {
            throw new InvalidArgumentException('Smartbill API Username is not configured. Please check your .env file.');
        }

        if (empty($this->config['api_token'])) {
            throw new InvalidArgumentException('Smartbill API token is not configured. Please check your .env file.');
        }
    }

    public function invoices(): InvoicesResource
    {
        return new InvoicesResource($this->getRequest());
    }

    public function estimates(): EstimatesResource
    {
        return new EstimatesResource($this->getRequest());
    }

    public function payments(): PaymentsResource
    {
        return new PaymentsResource($this->getRequest());
    }

    public function taxes(): TaxesResource
    {
        return new TaxesResource($this->getRequest());
    }

    public function series(): SeriesResource
    {
        return new SeriesResource($this->getRequest());
    }

    public function stocks(): StocksResource
    {
        return new StocksResource($this->getRequest());
    }
}
