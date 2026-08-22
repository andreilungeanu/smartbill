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
     * The spec types this query parameter as an integer, but POST /payment hands the
     * id back as a string, so both are accepted rather than forcing callers to cast.
     *
     * @return array<string, mixed>
     */
    public function getText(string $cif, int|string $id): array
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
     * Smartbill identifies the payment by exact match, so the value is not narrowed to
     * float: PHP renders 100.00 as "100" and 55.10 as "55.1" on the query string. Pass
     * a string to send the amount exactly as Smartbill stored it.
     *
     * @return array<string, mixed>
     */
    public function deleteByPayment(string $cif, string $paymentType, string $paymentDate, int|float|string $paymentValue, string $clientName, string $clientCif): array
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
