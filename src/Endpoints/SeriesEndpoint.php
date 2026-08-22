<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class SeriesEndpoint extends BaseEndpoint
{
    /**
     * Each series in the list carries nextNumber, the next number available.
     *
     * @return array<string, mixed>
     */
    public function list(string $cif, ?string $type = null): array
    {
        $data = ['cif' => $cif];

        if ($type !== null) {
            $data['type'] = $type;
        }

        return $this->decode($this->client->get('/series', $data));
    }
}
