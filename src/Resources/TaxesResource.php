<?php

namespace AndreiLungeanu\Smartbill\Resources;

use Illuminate\Http\Client\RequestException;

class TaxesResource extends AbstractResource
{
    /**
     * @throws RequestException
     */
    public function list(string $cif): array
    {
        return $this->sendRequest('GET', '/tax', ['cif' => $cif])->json();
    }
}
