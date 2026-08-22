<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

/**
 * V1 allows 30 calls per 10 seconds per token; going over locks the token out for
 * ten minutes. Do not retry blindly — a retry storm turns a ten second wait into a
 * ten minute outage. Back off until getResetAt().
 */
class SmartbillRateLimitException extends SmartbillApiException
{
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
