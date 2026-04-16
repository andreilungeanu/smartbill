<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class PaymentsEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->client
            ->post('/payment', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function getText(string $cif, string $id): array
    {
        return $this->client
            ->get('/payment/text', [
                'cif' => $cif,
                'id' => $id,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteReceipt(string $cif, string $seriesName, string $number): array
    {
        return $this->client
            ->delete('/payment/chitanta', [
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
    public function deleteByInvoice(string $cif, string $paymentType, string $invoiceSeries, string $invoiceNumber): array
    {
        return $this->client
            ->delete('/payment/v2', [
                'cif' => $cif,
                'paymentType' => $paymentType,
                'invoiceSeries' => $invoiceSeries,
                'invoiceNumber' => $invoiceNumber,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteByPayment(string $cif, string $paymentType, string $paymentDate, float $paymentValue, string $clientName, string $clientCif): array
    {
        return $this->client
            ->delete('/payment/v2', [
                'cif' => $cif,
                'paymentType' => $paymentType,
                'paymentDate' => $paymentDate,
                'paymentValue' => $paymentValue,
                'clientName' => $clientName,
                'clientCif' => $clientCif,
            ])
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
