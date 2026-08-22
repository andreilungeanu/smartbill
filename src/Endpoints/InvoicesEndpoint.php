<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class InvoicesEndpoint extends BaseEndpoint
{
    /**
     * @deprecated Use createV2(). /invoice is undocumented in the SmartBill OpenAPI
     *             spec and answers with a smaller envelope: no documentUrl,
     *             documentId or documentViewUrl.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->decode($this->client->post('/invoice', $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createV2(array $data): array
    {
        return $this->decode($this->client->post('/invoice/v2', $data));
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        return $this->download($this->client->get('/invoice/pdf', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->client->get('/invoice/paymentstatus', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function reverse(string $cif, string $seriesName, string $number, string $issueDate): array
    {
        return $this->decode($this->client->post('/invoice/reverse', [
            'companyVatCode' => $cif,
            'seriesName' => $seriesName,
            'number' => $number,
            'issueDate' => $issueDate,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('PUT', '/invoice/cancel', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function restore(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('PUT', '/invoice/restore', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('DELETE', '/invoice', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }
}
