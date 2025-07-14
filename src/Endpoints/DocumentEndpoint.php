<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;

class DocumentEndpoint extends BaseEndpoint
{
    public function send(array $data): array
    {
        $response = $this->client
            ->post('/document/send', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
