<?php

use AndreiLungeanu\Smartbill\Tests\TestCase;
use Illuminate\Http\Client\PendingRequest;

uses(TestCase::class)->in(__DIR__);

expect()->extend('toBeA', function (string $class) {
    return $this->toBeInstanceOf($class);
});

expect()->extend('withClient', function (PendingRequest $client) {
    $reflection = new ReflectionClass($this->value);
    $clientProperty = $reflection->getProperty('client');
    $clientProperty->setAccessible(true);

    return $this->and($clientProperty->getValue($this->value))->toBe($client);
});
