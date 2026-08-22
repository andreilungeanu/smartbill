<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
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

    /**
     * @return array<string, mixed>
     */
    protected function decode(Response $response, bool $errorTextIsFailure = true): array
    {
        $this->guard($response, $errorTextIsFailure);

        $body = $response->json();

        return is_array($body) ? $body : [];
    }

    /**
     * For binary payloads. The PDF endpoints answer 502 with an HTML body when a
     * parameter is missing, so only the status is meaningful here.
     */
    protected function download(Response $response): string
    {
        $this->guard($response);

        return $response->body();
    }

    /**
     * A 2xx alone does not mean success: Smartbill reports functional failures with
     * HTTP 200 and a populated errorText. An empty errorText is the success signal.
     *
     * Pass $errorTextIsFailure = false for the endpoints where a populated errorText
     * on a 2xx describes a normal state rather than a failure.
     */
    protected function guard(Response $response, bool $errorTextIsFailure = true): void
    {
        if ($response->failed() || ($errorTextIsFailure && SmartbillApiException::errorTextIn($response) !== '')) {
            throw SmartbillApiException::from($response);
        }
    }
}
