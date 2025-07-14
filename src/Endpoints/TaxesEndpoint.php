<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;

class TaxesEndpoint extends BaseEndpoint
{
    public function list(string $cif): array
    {
        $response = $this->client
            ->get('/tax', ['cif' => $cif])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
