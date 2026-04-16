<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

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

        if ($warehouseName) {
            $data['warehouseName'] = $warehouseName;
        }

        if ($productName) {
            $data['productName'] = $productName;
        }

        if ($productCode) {
            $data['productCode'] = $productCode;
        }

        return $this->client
            ->get('/stocks', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
