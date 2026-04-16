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
        $logBody = $body;

        if (is_array($body) && isset($body['errorText'])) {
            $message = $body['errorText'];
        } else {
            $rawBody = $response->body();
            if (! empty($rawBody)) {
                $message = $rawBody;
            }
            $logBody = strip_tags($rawBody);
        }

        parent::__construct($message, $response->status());

        Log::error('Smartbill API Error', [
            'status' => $response->status(),
            'body' => $logBody,
        ]);
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
