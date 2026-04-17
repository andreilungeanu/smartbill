<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class SmartbillApiException extends Exception
{
    public function __construct(protected Response $response)
    {
        $body = $response->json();
        $message = 'Smartbill API error';

        if (is_array($body) && isset($body['errorText'])) {
            $message = $body['errorText'];
        } else {
            $rawBody = $response->body();
            if (! empty($rawBody)) {
                $message = $rawBody;
            }
        }

        parent::__construct($message, $response->status());
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function report(): void
    {
        Log::error('Smartbill API Error', [
            'status' => $this->response->status(),
            'body' => strip_tags((string) $this->response->body()),
        ]);
    }
}
