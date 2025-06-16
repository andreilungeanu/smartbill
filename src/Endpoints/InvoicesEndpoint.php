<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use AndreiLungeanu\Smartbill\Smartbill;

class InvoicesEndpoint
{
    public function create(array $data): array
    {
        $response = Smartbill::api()
            ->post('/invoice', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function createV2(array $data): array
    {
        $response = Smartbill::api()
            ->post('/invoice/v2', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    // public function getPdf(string $cif, string $seriesName, string $number)
    // {
    //     $response = Smartbill::api()
    //         ->get('/invoice/pdf', [
    //             'cif' => $cif,
    //             'seriesname' => $seriesName,
    //             'number' => $number,
    //         ])
    //         ->throw(fn($response) => throw new SmartbillApiException($response));

    //     return $response;
    // }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        // Use the raw cURL GET request as a temporary fix to handle the API's
        // non-compliant "Entitate atasata" header on error responses.
        $responseArray = Smartbill::api_curl_get('/invoice/pdf', [
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

    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->get('/invoice/paymentstatus', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function reverse(string $cif, string $seriesName, string $number, string $issueDate): array
    {
        $response = Smartbill::api()
            ->post('/invoice/reverse', [
                'companyVatCode' => $cif,
                'seriesName' => $seriesName,
                'number' => $number,
                'issueDate' => $issueDate,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function cancel(string $cif, string $seriesName, string $number): array
    {
        $response = Smartbill::api()
            ->put('/invoice/cancel', [
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
            ->put('/invoice/restore', [
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
            ->delete('/invoice', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
