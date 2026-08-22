<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

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

        return $this->decode($this->client->get('/series', $data));
    }
}
