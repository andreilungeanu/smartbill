<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

abstract class AbstractResource
{
    public function __construct(protected PendingRequest $request) {}

    protected function sendRequest(string $method, string $url, array $data = []): Response
    {
        if (strtoupper($method) === 'GET') {
            return $this->request->get($url, $data)->throw();
        }

        return $this->request->send($method, $url, ['json' => $data])->throw();
    }
}
