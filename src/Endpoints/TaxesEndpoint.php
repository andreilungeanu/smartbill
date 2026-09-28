<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class TaxesEndpoint extends BaseEndpoint
{
    /**
     * @return array<string, mixed>
     */
    public function list(string $cif): array
    {
        return $this->decode($this->sendQuery('GET', '/tax', ['cif' => $cif]));
    }
}
