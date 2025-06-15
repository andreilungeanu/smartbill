<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;

class SeriesResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function list(string $cif, ?string $type = null): array
    {
        $data = ['cif' => $cif];

        if ($type) {
            $data['type'] = $type;
        }

        return $this->sendRequest('GET', '/series', $data)->json();
    }
}
