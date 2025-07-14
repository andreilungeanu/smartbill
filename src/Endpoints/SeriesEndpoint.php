<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;

class SeriesEndpoint extends BaseEndpoint
{
    public function list(string $cif, ?string $type = null): array
    {
        $data = ['cif' => $cif];

        if ($type) {
            $data['type'] = $type;
        }

        $response = $this->client
            ->get('/series', $data)
            ->throw(fn ($response) => throw new SmartbillApiException($response))
            ->json();

        return $response;
    }
}
