<?php

use AndreiLungeanu\Smartbill\Smartbill;

it('returns the matching endpoint', function (string $method, string $expectedClass): void {
    expect(smartbill()->{$method}())->toBeInstanceOf($expectedClass);
})->with('endpoints');

describe('timeout', function () {
    it('applies the configured value to the HTTP client', function (): void {
        config()->set('smartbill.timeout', 7);
        app()->forgetInstance(Smartbill::class);

        $client = (new ReflectionClass(smartbill()))->getProperty('client')->getValue(smartbill());
        $options = (new ReflectionClass($client))->getProperty('options')->getValue($client);

        expect($options)->toMatchArray(['timeout' => 7]);
    });

    it('defaults to 30 seconds', function (): void {
        expect(config('smartbill.timeout'))->toBe(30);
    });
});

describe('credentials', function () {
    it('throws when the username is missing', function (): void {
        config()->set('smartbill.api_username', '');
        app()->forgetInstance(Smartbill::class);

        smartbill();
    })->throws(InvalidArgumentException::class, 'Smartbill API Username is not configured');

    it('throws when the token is missing', function (): void {
        config()->set('smartbill.api_token', '');
        app()->forgetInstance(Smartbill::class);

        smartbill();
    })->throws(InvalidArgumentException::class, 'Smartbill API token is not configured');
});
