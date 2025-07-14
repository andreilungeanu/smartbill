<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\PendingRequest;

class StocksEndpoint
{
    public function __construct(protected PendingRequest $client) {}

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

        $response = $this->client
            ->get('/stocks', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
