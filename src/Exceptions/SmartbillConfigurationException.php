<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use InvalidArgumentException;

/**
 * The package is installed but not configured. Thrown when the container resolves
 * Smartbill::class, so the failure lands at the first use rather than at boot.
 *
 * Extends InvalidArgumentException because the service provider threw that directly
 * before this class existed — an application already catching it keeps working.
 */
class SmartbillConfigurationException extends InvalidArgumentException
{
    public static function missing(string $variable): self
    {
        return new self(
            "Smartbill is not configured: set {$variable} in your .env file, ".
            'then run `php artisan config:clear` if the config is cached.'
        );
    }
}
