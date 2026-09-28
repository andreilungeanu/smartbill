<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Illuminate\Http\Client\Response;

/**
 * Smartbill answered 401. Either the username or token is wrong, or — observed live —
 * the cif does not belong to the account. Retrying with the same values cannot help.
 */
class SmartbillAuthenticationException extends SmartbillApiException
{
    public static function matches(Response $response): bool
    {
        return $response->status() === 401;
    }

    protected function defaultMessage(): string
    {
        return 'Smartbill rejected the credentials or the cif (HTTP 401)';
    }
}
