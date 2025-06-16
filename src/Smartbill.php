<?php

namespace AndreiLungeanu\Smartbill;

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use InvalidArgumentException;

class Smartbill
{
    public static function api(): PendingRequest
    {
        $config = config('smartbill');

        if (empty($config['api_username'])) {
            throw new InvalidArgumentException('Smartbill API Username is not configured. Please check your .env file.');
        }

        if (empty($config['api_token'])) {
            throw new InvalidArgumentException('Smartbill API token is not configured. Please check your .env file.');
        }

        return Http::withBasicAuth($config['api_username'], $config['api_token'])
            ->baseUrl($config['api_url'])
            ->acceptJson();
    }

    /**
     * =========================================================================
     * TEMPORARY WORKAROUND for non-compliant API
     *
     * This method uses raw cURL to bypass Guzzle's strict header parsing.
     * Guzzle throws an InvalidArgumentException when the Smartbill API returns
     * malformed HTTP headers—specifically headers with spaces in their names
     * (e.g., "Entitate atasata")—which violate the HTTP/1.1 specification.
     *
     * According to RFC 7230 section 3.2 (https://datatracker.ietf.org/doc/html/rfc7230#section-3.2),
     * header field names must be valid tokens without spaces or control characters.
     * Headers with spaces in their names are non-compliant and considered malformed.
     *
     * Malformed headers can cause interoperability issues, unexpected errors in HTTP clients,
     * and potentially introduce security risks by breaking assumptions in header parsing.
     * Bypassing client validation by using raw cURL may enable handling these responses,
     * but it is generally a bad practice and should be avoided or reported to the API provider
     * to fix the server-side header formatting.
     * =========================================================================
     */
    public static function api_curl_get(string $endpoint, array $queryParams = []): array
    {
        $config = config('smartbill');

        if (empty($config['api_username'])) {
            throw new InvalidArgumentException('Smartbill API Username is not configured. Please check your .env file.');
        }

        if (empty($config['api_token'])) {
            throw new InvalidArgumentException('Smartbill API token is not configured. Please check your .env file.');
        }

        $fullApiUrl = $config['api_url'].$endpoint.'?'.http_build_query($queryParams);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $fullApiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $config['api_username'].':'.$config['api_token']);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);

        $rawResponse = curl_exec($ch);
        $headerSize = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($rawResponse === false) {
            throw new \Exception('cURL Error: '.$curlError);
        }

        // 5. Manually separate headers from the body
        $headerString = substr($rawResponse, 0, $headerSize);
        $bodyString = substr($rawResponse, $headerSize);

        return [
            'body' => $bodyString,
            'code' => $httpCode,
            'headers' => $headerString,
        ];
    }

    public function invoices(): InvoicesEndpoint
    {
        return new InvoicesEndpoint;
    }

    public function estimates(): EstimatesEndpoint
    {
        return new EstimatesEndpoint;
    }

    public function payments(): PaymentsEndpoint
    {
        return new PaymentsEndpoint;
    }

    public function taxes(): TaxesEndpoint
    {
        return new TaxesEndpoint;
    }

    public function series(): SeriesEndpoint
    {
        return new SeriesEndpoint;
    }

    public function stocks(): StocksEndpoint
    {
        return new StocksEndpoint;
    }

    public function document(): DocumentEndpoint
    {
        return new DocumentEndpoint;
    }
}
