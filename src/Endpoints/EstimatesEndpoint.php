<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class EstimatesEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client
            ->post('/estimate', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createV2(array $data): array
    {
        return $this->client
            ->post('/estimate/v2', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        return $this->client
            ->get('/estimate/pdf', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->body();
    }

    /**
     * @return array<string, mixed>
     */
    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->get('/estimate/invoices', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->put('/estimate/cancel', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function restore(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->put('/estimate/restore', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->delete('/estimate', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
