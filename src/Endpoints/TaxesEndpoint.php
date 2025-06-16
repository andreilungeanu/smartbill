<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Smartbill;

class TaxesEndpoint
{
    public function list(string $cif): array
    {
        $response = Smartbill::api()
            ->get('/tax', ['cif' => $cif])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
