<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Exceptions\SmartbillConnectionException;
use Closure;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;

abstract class BaseEndpoint
{
    /**
     * @param  PendingRequest|Closure(): PendingRequest  $client
     */
    public function __construct(protected PendingRequest|Closure $client) {}

    /**
     * A fresh client for every request.
     *
     * The service provider hands over a factory, so each call picks up the Http::fake()
     * and preventStrayRequests() state current at that moment. A PendingRequest copies
     * both when it is created: one built before the fake was registered would send the
     * request to the live API. A PendingRequest passed in directly is cloned, so nothing
     * set on it for one request carries into the next.
     */
    protected function client(): PendingRequest
    {
        return $this->client instanceof Closure ? ($this->client)() : clone $this->client;
    }

    /**
     * Send a request whose parameters belong in the query string.
     *
     * @param  array<string, mixed>  $query
     */
    protected function sendQuery(string $method, string $path, array $query): Response
    {
        return $this->sendRequest($method, $path, ['query' => $query]);
    }

    /**
     * Send a request whose parameters belong in a JSON body.
     *
     * @param  array<string, mixed>  $data
     */
    protected function sendJson(string $method, string $path, array $data): Response
    {
        return $this->sendRequest($method, $path, ['json' => $data]);
    }

    /**
     * The cif / seriesname / number triple that identifies a document.
     *
     * @return array<string, string>
     */
    protected function documentQuery(string $cif, string $seriesName, string $number): array
    {
        return [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ];
    }

    /**
     * @param  array<string, mixed>  $options
     */
    protected function sendRequest(string $method, string $path, array $options): Response
    {
        try {
            return $this->client()->send($method, $path, $options);
        } catch (ConnectionException $e) {
            throw SmartbillConnectionException::from($e, $method, $path);
        }
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
