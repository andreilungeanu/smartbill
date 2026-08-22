<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

abstract class BaseEndpoint
{
    public function __construct(protected PendingRequest $client) {}

    /**
     * Send a request whose parameters belong in the query string.
     *
     * Do not call withQueryParameters() on $this->client — the HTTP client is a
     * singleton and that method mutates it for every later request.
     *
     * @param  array<string, mixed>  $query
     */
    protected function sendQuery(string $method, string $path, array $query): Response
    {
        return $this->client->send($method, $path, ['query' => $query]);
    }
}
