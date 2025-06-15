<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;

class InvoicesResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function create(array $data): array
    {
        return $this->sendRequest('POST', '/invoice', $data)->json();
    }

    /**
     * @throws RequestException
     */
    public function getPdf(string $cif, string $seriesName, string $number): Response
    {
        return $this->sendRequest('GET', '/invoice/pdf', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]);
    }

    /**
     * @throws RequestException
     */
    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('GET', '/invoice/paymentstatus', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function reverse(string $cif, string $seriesName, string $number, string $issueDate): array
    {
        return $this->sendRequest('POST', '/invoice/reverse', [
            'companyVatCode' => $cif,
            'seriesName' => $seriesName,
            'number' => $number,
            'issueDate' => $issueDate,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('PUT', '/invoice/cancel', [
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
        return $this->sendRequest('PUT', '/invoice/restore', [
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
        return $this->sendRequest('DELETE', '/invoice', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }
}
