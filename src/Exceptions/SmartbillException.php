<?php

namespace AndreiLungeanu\Smartbill\Exceptions;

use Throwable;

/**
 * Implemented by every exception the package throws, so one catch covers an API
 * failure, a connection failure and a missing configuration alike.
 */
interface SmartbillException extends Throwable {}
