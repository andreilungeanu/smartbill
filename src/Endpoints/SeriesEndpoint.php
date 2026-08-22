<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class SeriesEndpoint extends BaseEndpoint
{
    /**
     * Each series in the list carries nextNumber, the next number available.
     *
     * $type accepts only 'f' (invoice), 'p' (estimate) or 'c' (receipt); anything else
     * is rejected with a 400. Series for delivery notes (`aviz`) and fiscal receipts are
     * not listable at all, filtered or not, so those names have to come from the user.
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
