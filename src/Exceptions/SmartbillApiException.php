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
        if (SmartbillRateLimitException::matches($response)) {
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
        return self::errorTextIn($this->response);
    }

    /**
     * Read errorText without building an exception — callers check this on every
     * response, and constructing an exception captures a stack trace.
     */
    public static function errorTextIn(Response $response): string
    {
        $body = $response->json();

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

        if ($text === '') {
            $text = $this->nestedStatusMessage();
        }

        // Only a non-JSON body (an HTML 500 page, plain text) is worth quoting raw.
        // A JSON body with no usable errorText would just dump the envelope.
        if ($text === '' && ! is_array($this->response->json())) {
            $text = $this->response->body();
        }

        return self::firstSentence($text) ?: 'Smartbill API error';
    }

    /**
     * /document/send answers with a third envelope of its own:
     * {"status": {"code": 1, "message": "Documentul nu a fost gasit"}} — no errorText.
     * invalid_request_error bodies also carry "status", but as an int, so the array
     * check keeps the two apart.
     */
    protected function nestedStatusMessage(): string
    {
        $status = self::nestedStatus($this->response);

        return is_string($status['message'] ?? null) ? trim($status['message']) : '';
    }

    /**
     * The status.code of the /document/send envelope: 0 on success, 1 on error.
     * Null when the response does not use that envelope.
     */
    public static function statusCodeIn(Response $response): ?int
    {
        $code = self::nestedStatus($response)['code'] ?? null;

        return is_int($code) ? $code : null;
    }

    /**
     * @return array<string, mixed>
     */
    private static function nestedStatus(Response $response): array
    {
        $body = $response->json();
        $status = is_array($body) ? ($body['status'] ?? null) : null;

        return is_array($status) ? $status : [];
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
