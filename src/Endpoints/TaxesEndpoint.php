<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class TaxesEndpoint extends BaseEndpoint
{
    /**
     * @return array<string, mixed>
     */
    public function list(string $cif): array
    {
        return $this->client
            ->get('/tax', ['cif' => $cif])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
