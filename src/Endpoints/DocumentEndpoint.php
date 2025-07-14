<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\PendingRequest;

class DocumentEndpoint
{
    public function __construct(protected PendingRequest $client) {}

    public function send(array $data): array
    {
        $response = $this->client
            ->post('/document/send', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
