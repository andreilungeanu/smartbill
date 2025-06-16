<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Smartbill;

class EstimatesEndpoint
{
    public function create(array $data): array
    {
        $response = Smartbill::api()
            ->post('/estimate', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function createV2(array $data): array
    {
        $response = Smartbill::api()
            ->post('/estimate/v2', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    // public function getPdf(string $cif, string $seriesName, string $number)
    // {
    //     $response = Smartbill::api()
    //         ->get('/estimate/pdf', [
    //             'cif' => $cif,
    //             'seriesname' => $seriesName,
    //             'number' => $number,
    //         ])
    //         ->throw(fn($response) => throw new SmartbillApiException($response))
    //         ->body();

    //     return $response;
    // }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        // Use the raw cURL GET request as a temporary fix to handle the API's
        // non-compliant "Entitate atasata" header on error responses.
        $responseArray = Smartbill::api_curl_get('/estimate/pdf', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]);

        if ($responseArray['code'] >= 400) {
            throw new SmartbillApiException(
                $responseArray['body'],
                $responseArray['code']
            );
        }

        return $responseArray['body'];
    }

    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->get('/estimate/invoices', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function cancel(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->put('/estimate/cancel', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function restore(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->put('/estimate/restore', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function delete(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->delete('/estimate', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
