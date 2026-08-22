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
     * For binary payloads. /invoice/pdf answers 502 with an nginx HTML body when a
     * parameter is missing or the document is unknown, while /estimate/pdf answers a
     * normal 400 with errorText. The status covers both.
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
     * /document/send carries no errorText at all — it reports through status.code,
     * where 0 is success — so that is checked too.
     *
     * Pass $errorTextIsFailure = false for the endpoints where a populated errorText
     * on a 2xx describes a normal state rather than a failure.
     */
    protected function guard(Response $response, bool $errorTextIsFailure = true): void
    {
        $statusCode = SmartbillApiException::statusCodeIn($response);

        if (! $response->successful()
            || ($errorTextIsFailure && SmartbillApiException::errorTextIn($response) !== '')
            || ($statusCode !== null && $statusCode !== 0)
        ) {
            throw SmartbillApiException::from($response);
        }
    }
}
