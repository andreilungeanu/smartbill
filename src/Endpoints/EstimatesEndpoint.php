<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class EstimatesEndpoint extends BaseEndpoint
{
    /**
     * @deprecated Use createV2(). /estimate is undocumented in the SmartBill OpenAPI
     *             spec and answers with a smaller envelope.
     *
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
     * Check areInvoicesCreated in the response to learn whether the estimate has
     * already been invoiced.
     *
     * An estimate that exists but has not been invoiced answers HTTP 200 with a
     * populated errorText. That is a normal state, not a failure, so errorText is
     * not treated as one here. A missing estimate still answers 410 and throws.
     *
     * @return array<string, mixed>
     */
    public function getInvoices(string $cif, string $seriesName, string $number): array
    {
        $response = $this->client->get('/estimate/invoices', [
            'cif' => $cif,
            'seriesname' => $seriesName,
            'number' => $number,
        ]);

        // Suppress errorText only alongside areInvoicesCreated, which marks the known
        // state. Any other populated errorText on a 2xx is still a real failure.
        $body = $response->json();
        $known = is_array($body) && array_key_exists('areInvoicesCreated', $body);

        return $this->decode($response, errorTextIsFailure: ! $known);
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
