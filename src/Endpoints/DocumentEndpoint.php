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

    /**
     * send() with subject and bodyText encoded on the way out, for callers holding plain
     * text. Only non-empty strings are touched, so a payload that omits either key — and
     * therefore falls back to the template configured in the Smartbill account — is
     * passed through unchanged. Do not mix this with values you already encoded.
     *
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    public function sendEncoded(array $data): array
    {
        foreach (['subject', 'bodyText'] as $key) {
            $value = $data[$key] ?? null;

            if (is_string($value) && $value !== '') {
                $data[$key] = base64_encode($value);
            }
        }

        return $this->send($data);
    }
}
