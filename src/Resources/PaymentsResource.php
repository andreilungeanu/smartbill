<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;

class PaymentsResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function create(array $data): array
    {
        return $this->sendRequest('POST', '/payment', $data)->json();
    }

    /**
     * @throws RequestException
     */
    public function getText(string $cif, string $id): array
    {
        return $this->sendRequest('GET', '/payment/text', [
            'cif' => $cif,
            'id' => $id,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function deleteReceipt(string $cif, string $seriesName, string $number): array
    {
        return $this->sendRequest('DELETE', '/payment/chitanta', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ])->json();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $cif, string $paymentType, string $invoiceSeries, string $invoiceNumber): array
    {
        return $this->sendRequest('DELETE', '/payment/v2', [
            'cif' => $cif,
            'paymentType' => $paymentType,
            'invoiceSeries' => $invoiceSeries,
            'invoiceNumber' => $invoiceNumber,
        ])->json();
    }
}
