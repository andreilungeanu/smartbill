<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Illuminate\Http\Client\ConnectionException;

/**
 * No response arrived: a timeout, a DNS failure or a refused connection.
 *
 * Extends Laravel's ConnectionException, which is what escaped before this class
 * existed, so an application already catching that keeps working.
 *
 * A timeout does not mean the request failed. Smartbill may have created the document
 * and only the answer was lost, so check before retrying a create, or the retry issues
 * a second invoice under the next number.
 */
class SmartbillConnectionException extends ConnectionException implements SmartbillException
{
    public static function from(ConnectionException $previous, string $method, string $path): self
    {
        return new self(
            "Smartbill did not answer {$method} {$path}: {$previous->getMessage()}",
            (int) $previous->getCode(),
            $previous,
        );
    }
}
