<?php

namespace AndreiLungeanu\Smartbill\Tests;

use AndreiLungeanu\Smartbill\SmartbillServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            SmartbillServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app): void
    {
        config()->set('smartbill.api_username', 'test-username');
        config()->set('smartbill.api_token', 'test-api-token');
    }
}
