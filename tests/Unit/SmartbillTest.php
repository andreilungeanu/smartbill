<?php

use AndreiLungeanu\Smartbill\Exceptions\SmartbillConfigurationException;
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
    it('names the username variable when it is missing', function (): void {
        config()->set('smartbill.api_username', '');
        app()->forgetInstance(Smartbill::class);

        smartbill();
    })->throws(SmartbillConfigurationException::class, 'set SMARTBILL_API_USERNAME in your .env file');

    it('names the token variable when it is missing', function (): void {
        config()->set('smartbill.api_token', '');
        app()->forgetInstance(Smartbill::class);

        smartbill();
    })->throws(SmartbillConfigurationException::class, 'set SMARTBILL_API_TOKEN in your .env file');

    it('stays catchable as InvalidArgumentException', function (): void {
        config()->set('smartbill.api_token', '');
        app()->forgetInstance(Smartbill::class);

        expect(fn () => smartbill())->toThrow(InvalidArgumentException::class);
    });
});
