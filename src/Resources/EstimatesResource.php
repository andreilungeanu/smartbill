<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class EstimatesResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function create(array $data): array
    {
        return $this->sendRequest('POST', '/estimate', $data)->json();
    }

    /**
     * @throws RequestException
     */
    public function getPdf(string $cif, string $seriesName, string $number): Response
    {
        return $this->sendRequest('GET', '/estimate/pdf', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('GET', '/estimate/invoices', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('PUT', '/estimate/cancel', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function restore(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('PUT', '/estimate/restore', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('DELETE', '/estimate', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }
}
