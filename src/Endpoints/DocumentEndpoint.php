<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

class DocumentEndpoint extends BaseEndpoint
{
    /**
     * Both subject and bodyText must arrive Base64 encoded — Smartbill rejects plain
     * text with "Subiectul emailului trebuie sa fie codat folosind Base64." Encoding
     * is left to the caller so an already encoded value is not encoded twice.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function send(array $data): array
    {
        return $this->decode($this->client->post('/document/send', $data));
    }
}
