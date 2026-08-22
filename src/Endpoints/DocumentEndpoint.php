<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class DocumentEndpoint extends BaseEndpoint
{
    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function send(array $data): array
    {
        return $this->decode($this->client->post('/document/send', $data));
    }
}
