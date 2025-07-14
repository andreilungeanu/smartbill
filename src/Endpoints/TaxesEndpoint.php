<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\PendingRequest;

class TaxesEndpoint
{
    public function __construct(protected PendingRequest $client) {}

    public function list(string $cif): array
    {
        $response = $this->client
            ->get('/tax', ['cif' => $cif])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
