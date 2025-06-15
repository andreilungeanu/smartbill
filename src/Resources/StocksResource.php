<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;

class StocksResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function list(string $cif, string $date, ?string $warehouseName = null, ?string $productName = null, ?string $productCode = null): array
    {
        $data = [
            'cif' => $cif,
            'date' => $date,
        ];

        if ($warehouseName) {
            $data['warehouseName'] = $warehouseName;
        }

        if ($productName) {
            $data['productName'] = $productName;
        }

        if ($productCode) {
            $data['productCode'] = $productCode;
        }

        return $this->sendRequest('GET', '/stocks', $data)->json();
    }
}
