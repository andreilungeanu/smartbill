<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\PendingRequest;

class PaymentsEndpoint
{
    public function __construct(protected PendingRequest $client) {}

    public function create(array $data): array
    {
        $response = $this->client
            ->post('/payment', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function getText(string $cif, string $id): array
    {
        $response = $this->client
            ->get('/payment/text', [
                'cif' => $cif,
                'id' => $id,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function deleteReceipt(string $cif, string $seriesName, string $number): array
    {
        $response = $this->client
            ->delete('/payment/chitanta', [
                'cif' => $cif,
                'seriesname' => $seriesName,
                'number' => $number,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function deleteByInvoice(string $cif, string $paymentType, string $invoiceSeries, string $invoiceNumber): array
    {
        $response = $this->client
            ->delete('/payment/v2', [
                'cif' => $cif,
                'paymentType' => $paymentType,
                'invoiceSeries' => $invoiceSeries,
                'invoiceNumber' => $invoiceNumber,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }

    public function deleteByPayment(string $cif, string $paymentType, string $paymentDate, float $paymentValue, string $clientName, string $clientCif): array
    {
        $response = $this->client
            ->delete('/payment/v2', [
                'cif' => $cif,
                'paymentType' => $paymentType,
                'paymentDate' => $paymentDate,
                'paymentValue' => $paymentValue,
                'clientName' => $clientName,
                'clientCif' => $clientCif,
            ])
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
