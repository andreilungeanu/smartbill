<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class DocumentEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function send(array $data): array
    {
        return $this->client
            ->post('/document/send', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
