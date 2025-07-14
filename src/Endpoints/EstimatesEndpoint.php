<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
class EstimatesEndpoint extends BaseEndpoint
{
    public function create(array $data): array
    {
        $response = $this->client
            ->post('/estimate', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function createV2(array $data): array
    {
        $response = $this->client
            ->post('/estimate/v2', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        $response = $this->client
            ->get('/estimate/pdf', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->body();

        return $response;
    }

    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        $response = $this->client
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
        $response = $this->client
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
        $response = $this->client
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
        $response = $this->client
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
