<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Log;

class SmartbillApiException extends Exception
{
    public function __construct(protected Response $response)
    {
        parent::__construct($this->resolveMessage(), $response->status());
    }

    /**
     * Pick the exception class that matches the shape of the failure.
     */
    public static function from(Response $response): self
    {
        if ($response->status() === 429) {
            return new SmartbillRateLimitException($response);
        }

        if (SmartbillRequestException::matches($response)) {
            return new SmartbillRequestException($response);
        }

        return new self($response);
    }

    /**
     * Smartbill reports the reason in errorText. An empty errorText means the call
     * succeeded, so only a non-empty value carries a failure.
     */
    public function getErrorText(): string
    {
        $body = $this->response->json();

        if (is_array($body) && is_string($body['errorText'] ?? null)) {
            return trim($body['errorText']);
        }

        return '';
    }

    /**
     * Seconds the account is locked out for, when Smartbill reports one.
     */
    public function getCooldown(): ?int
    {
        $body = $this->response->json();

        if (is_array($body) && is_numeric($body['cooldown'] ?? null)) {
            return (int) $body['cooldown'];
        }

        return null;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function report(): void
    {
        Log::error('Smartbill API Error', [
            'status' => $this->response->status(),
            'body' => strip_tags($this->response->body()),
        ]);
    }

    protected function resolveMessage(): string
    {
        $text = $this->getErrorText();

        // Only a non-JSON body (an HTML 500 page, plain text) is worth quoting raw.
        // A JSON body with no usable errorText would just dump the envelope.
        if ($text === '' && ! is_array($this->response->json())) {
            $text = $this->response->body();
        }

        return self::firstSentence($text) ?: 'Smartbill API error';
    }

    /**
     * errorText may carry HTML aimed at the Smartbill Cloud UI: <b> around document
     * names, a hidden <div id="moreErrorDetails"> of help text. The cause is the
     * first sentence, before the first tag; the rest is markup for their interface.
     * The untouched body stays reachable through getResponse().
     */
    protected static function firstSentence(string $text): string
    {
        $cut = strpos($text, '<');

        return trim($cut === false ? $text : substr($text, 0, $cut));
    }
}
