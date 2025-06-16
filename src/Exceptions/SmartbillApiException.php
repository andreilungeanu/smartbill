<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class SmartbillApiException extends Exception
{
    protected $response;

    public function __construct($responseOrMessage, ?int $code = 0)
    {
        if ($responseOrMessage instanceof Response) {
            // Handle the original constructor logic with a Response object
            $this->response = $responseOrMessage;
            $body = $this->response->json();
            $message = 'Smartbill API error';
            $logBody = $body;

            if (is_array($body) && isset($body['errorText'])) {
                $message = $body['errorText'];
            } else {
                $rawBody = $this->response->body();
                if (! empty($rawBody)) {
                    $message = $rawBody;
                }
                $logBody = strip_tags($rawBody);
            }

            parent::__construct($message, $this->response->status());

            Log::error('Smartbill API Error', [
                'status' => $this->response->status(),
                'body' => $logBody,
            ]);
        } else {
            // Handle the new case with a message and a code
            $message = $responseOrMessage;
            parent::__construct($message, $code);

            Log::error('Smartbill API Error', [
                'status' => $code,
                'body' => $message,
            ]);
        }
    }

    public function getResponse(): Response
    {
        return $this->response;
    }
}
