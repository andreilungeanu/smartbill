<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Str;

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

    /**
     * Attached to the framework's own log entry rather than replacing it: a report()
     * returning anything but false makes Laravel skip its default reporting, which
     * would drop the stack trace and the call site. Caught exceptions stay silent
     * either way, since context() is only read when the exception is reported.
     *
     * The body is truncated — Smartbill error bodies can carry client and document data.
     *
     * @return array<string, mixed>
     */
    public function context(): array
    {
        return [
            'smartbill_status' => $this->response->status(),
            'smartbill_body' => Str::limit(strip_tags($this->response->body()), 500),
        ];
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

        return self::sanitize($text) ?: $this->defaultMessage();
    }

    /**
     * Used when the body carries nothing quotable — an nginx or Tomcat HTML page, or an
     * empty body. The status is then the only fact worth reporting, and without it the
     * message cannot tell a 502 from a 401.
     */
    protected function defaultMessage(): string
    {
        return 'Smartbill API error (HTTP '.$this->response->status().')';
    }

    /**
     * /document/send answers with a third envelope of its own:
     * {"status": {"code": 1, "message": "Documentul nu a fost gasit"}} — no errorText.
     * invalid_request_error bodies also carry "status", but as an int, so the array
     * check keeps the two apart.
     */
    protected function nestedStatusMessage(): string
    {
        // Only when the envelope says it failed: a message sitting next to code 0 is a
        // success notice and would read as the cause of an error it did not describe.
        if (self::statusCodeIn($this->response) === 0) {
            return '';
        }

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
     * errorText may carry HTML aimed at the Smartbill Cloud UI. Two kinds appear, and
     * they need opposite treatment:
     *
     *  - help markup appended after the cause: a `<br/>` followed by a suggestion, or a
     *    hidden `<div id="moreErrorDetails">` block. Both are dropped.
     *  - `<b>` wrapped *inside* the sentence, around the document, date or product name.
     *    Truncating at the first tag would throw the cause away, so tags are stripped
     *    and their contents kept.
     *
     * A response body that is an HTML page rather than a message yields nothing.
     * The untouched body always stays reachable through getResponse().
     */
    protected static function sanitize(string $text): string
    {
        if (preg_match('#^\s*<(!doctype|html)#i', $text) === 1) {
            return '';
        }

        $text = (string) preg_replace('#<div[^>]*id="moreErrorDetails".*#is', '', $text);
        $text = (string) preg_replace('#<br\s*/?>.*#is', '', $text);

        return trim((string) preg_replace('/\s+/', ' ', strip_tags($text)));
    }
}
