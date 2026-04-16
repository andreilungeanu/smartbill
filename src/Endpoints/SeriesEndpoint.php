<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillApiException;
use Illuminate\Http\Client\Response;

class SeriesEndpoint extends BaseEndpoint
{
    /**
     * @return array<string, mixed>
     */
    public function list(string $cif, ?string $type = null): array
    {
        $data = ['cif' => $cif];

        if ($type) {
            $data['type'] = $type;
        }

        return $this->client
            ->get('/series', $data)
            ->throw(fn (Response $response) => throw new SmartbillApiException($response))
            ->json();
    }
}
