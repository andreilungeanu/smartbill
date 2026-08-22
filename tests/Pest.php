<?php

use AndreiLungeanu\Smartbill\Smartbill;
use AndreiLungeanu\Smartbill\Tests\TestCase;
use Illuminate\Support\Facades\Http;

pest()->extends(TestCase::class);

pest()->beforeEach(function (): void {
    Http::preventStrayRequests();
});

function smartbill(): Smartbill
{
    return app(Smartbill::class);
}
