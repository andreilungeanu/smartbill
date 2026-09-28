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
        return $this->decode($this->sendJson('POST', '/invoice', $data));
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function createV2(array $data): array
    {
        return $this->decode($this->sendJson('POST', '/invoice/v2', $data));
    }

    public function getPdf(string $cif, string $seriesName, string $number): string
    {
        return $this->download($this->sendQuery('GET', '/invoice/pdf', $this->documentQuery($cif, $seriesName, $number)));
    }

    /**
     * @return array<string, mixed>
     */
    public function getPaymentStatus(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('GET', '/invoice/paymentstatus', $this->documentQuery($cif, $seriesName, $number)));
    }

    /**
     * issueDate is optional — confirmed live: omitted, empty or set all return 200 and
     * create the storno. Only companyVatCode, seriesName and number are required.
     *
     * @return array<string, mixed>
     */
    public function reverse(string $cif, string $seriesName, string $number, ?string $issueDate = null): array
    {
        $data = [
            'companyVatCode' => $cif,
            'seriesName' => $seriesName,
            'number' => $number,
        ];

        if ($issueDate !== null) {
            $data['issueDate'] = $issueDate;
        }

        return $this->decode($this->sendJson('POST', '/invoice/reverse', $data));
    }

    /**
     * @return array<string, mixed>
     */
    public function cancel(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('PUT', '/invoice/cancel', $this->documentQuery($cif, $seriesName, $number)));
    }

    /**
     * @return array<string, mixed>
     */
    public function restore(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('PUT', '/invoice/restore', $this->documentQuery($cif, $seriesName, $number)));
    }

    /**
     * @return array<string, mixed>
     */
    public function delete(string $cif, string $seriesName, string $number): array
    {
        return $this->decode($this->sendQuery('DELETE', '/invoice', $this->documentQuery($cif, $seriesName, $number)));
    }
}
