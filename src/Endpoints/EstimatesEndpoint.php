<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class EstimatesEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        return $this->decode($this->client->post('/estimate', $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createV2(array $data): array
    {
        return $this->decode($this->client->post('/estimate/v2', $data));
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        return $this->download($this->client->get('/estimate/pdf', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->client->get('/estimate/invoices', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('PUT', '/estimate/cancel', [
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
        return $this->decode($this->sendQuery('PUT', '/estimate/restore', [
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
        return $this->decode($this->sendQuery('DELETE', '/estimate', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]));
    }
}
