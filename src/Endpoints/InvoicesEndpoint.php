<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class InvoicesEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client
            ->post('/invoice', $data)
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
            ->post('/invoice/v2', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        return $this->client
            ->get('/invoice/pdf', [
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
    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->get('/invoice/paymentstatus', [
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
    public function reverse(string $cif, string $seriesName, string $number, string $issueDate): array
    {
        return $this->client
            ->post('/invoice/reverse', [
                'companyVatCode' => $cif,
                'seriesName' => $seriesName,
                'number' => $number,
                'issueDate' => $issueDate,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->sendQuery('PUT', '/invoice/cancel', [
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
        return $this->sendQuery('PUT', '/invoice/restore', [
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
        return $this->sendQuery('DELETE', '/invoice', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
