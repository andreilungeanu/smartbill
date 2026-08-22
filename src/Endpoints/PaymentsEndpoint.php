<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class PaymentsEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->decode($this->client->post('/payment', $data));
    }

    /**
     * The receipt text arrives in the message key, Base64 encoded.
     *
     * @return array<string, mixed>
     */
    public function getText(string $cif, int $id): array
    {
        return $this->decode($this->client->get('/payment/text', [
            'cif' => $cif,
            'id' => $id,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteReceipt(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('DELETE', '/payment/chitanta', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteByInvoice(string $cif, string $paymentType, string $invoiceSeries, string $invoiceNumber): array
    {
        return $this->decode($this->sendQuery('DELETE', '/payment/v2', [
            'cif' => $cif,
            'paymentType' => $paymentType,
            'invoiceSeries' => $invoiceSeries,
            'invoiceNumber' => $invoiceNumber,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function deleteByPayment(string $cif, string $paymentType, string $paymentDate, float $paymentValue, string $clientName, string $clientCif): array
    {
        return $this->decode($this->sendQuery('DELETE', '/payment/v2', [
            'cif' => $cif,
            'paymentType' => $paymentType,
            'paymentDate' => $paymentDate,
            'paymentValue' => $paymentValue,
            'clientName' => $clientName,
            'clientCif' => $clientCif,
        ]));
    }
}
