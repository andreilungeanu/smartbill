<?php

namespace AndreiLungeanu\Smartbill\Endpoints;

use Illuminate\Http\Client\PendingRequest;

abstract class BaseEndpoint
{
    public function __construct(protected PendingRequest $client) {}
}
