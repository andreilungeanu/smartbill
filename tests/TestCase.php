<?php

namespace AndreiLungeanu\Smartbill\Tests;

use AndreiLungeanu\Smartbill\SmartbillServiceProvider;
use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @param  Application  $app
     * @return array<int, class-string>
     */
    protected function getPackageProviders($app): array
    {
        return [
            SmartbillServiceProvider::class,
        ];
    }

    /**
     * @param  Application  $app
     */
    public function getEnvironmentSetUp($app): void
    {
        config()->set('smartbill.api_username', 'test-username');
        config()->set('smartbill.api_token', 'test-api-token');
    }
}
