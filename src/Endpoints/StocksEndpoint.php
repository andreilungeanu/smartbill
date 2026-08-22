<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class StocksEndpoint extends BaseEndpoint
{
    /**
     * @return array<string, mixed>
     */
    public function list(string $cif, string $date, ?string $warehouseName = null, ?string $productName = null, ?string $productCode = null): array
    {
        $data = [
            'cif' => $cif,
            'date' => $date,
        ];

        // Compare against null, not truthiness: "0" is a valid product code.
        if ($warehouseName !== null) {
            $data['warehouseName'] = $warehouseName;
        }

        if ($productName !== null) {
            $data['productName'] = $productName;
        }

        if ($productCode !== null) {
            $data['productCode'] = $productCode;
        }

        return $this->decode($this->client->get('/stocks', $data));
    }
}
