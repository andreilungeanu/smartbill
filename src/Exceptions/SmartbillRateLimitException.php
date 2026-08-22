<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * V1 allows 30 calls per 10 seconds per token; going over locks the token out for
 * ten minutes. Do not retry blindly — a retry storm turns a ten second wait into a
 * ten minute outage.
 *
 * The documentation says the breach answers 429. It does not: observed live, it is a
 * 403 whose errorText names the limit, and that response carries no X-RateLimit
 * headers at all. The accessors below therefore return null on the blocking response
 * and only report a real window on ordinary responses.
 */
class SmartbillRateLimitException extends SmartbillApiException
{
    public static function matches(Response $response): bool
    {
        if ($response->status() === 429) {
            return true;
        }

        // 403 alone is not enough: Smartbill also answers 403 for "invoice is not the
        // last one in its series". Only the blocking response names the limit.
        return $response->status() === 403
            && str_contains(mb_strtolower(self::errorTextIn($response)), 'limita maxima de requesturi');
    }

    public function getLimit(): ?int
    {
        return $this->intHeader('X-RateLimit-Limit');
    }

    public function getRemaining(): ?int
    {
        return $this->intHeader('X-RateLimit-Remaining');
    }

    /**
     * Unix timestamp at which the next window opens.
     */
    public function getResetAt(): ?int
    {
        return $this->intHeader('X-RateLimit-Reset');
    }

    protected function resolveMessage(): string
    {
        $message = parent::resolveMessage();

        return $message === 'Smartbill API error' ? 'Smartbill API rate limit exceeded' : $message;
    }

    protected function intHeader(string $name): ?int
    {
        $value = $this->response->header($name);

        return is_numeric($value) ? (int) $value : null;
    }
}
