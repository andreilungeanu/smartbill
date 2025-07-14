<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;

class InvoicesEndpoint extends BaseEndpoint
{
    public function create(array $data): array
    {
        $response = $this->client
            ->post('/invoice', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function createV2(array $data): array
    {
        $response = $this->client
            ->post('/invoice/v2', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        $response = $this->client
            ->get('/invoice/pdf', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->body();

        return $response;
    }

    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        $response = $this->client
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
        $response = $this->client
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
        $response = $this->client
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
        $response = $this->client
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
        $response = $this->client
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
