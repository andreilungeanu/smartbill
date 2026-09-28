<?php

namespace AndreiLungeanu\Smartbill;

use AndreiLungeanu\Smartbill\Exceptions\SmartbillConfigurationException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class SmartbillServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('smartbill')
            ->hasConfigFile();
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(Smartbill::class, function (Application $app) {
            $config = $app['config']['smartbill'];

            if (empty($config['api_username'])) {
                throw SmartbillConfigurationException::missing('SMARTBILL_API_USERNAME');
            }

            if (empty($config['api_token'])) {
                throw SmartbillConfigurationException::missing('SMARTBILL_API_TOKEN');
            }

            // A factory, not a built client: the singleton outlives any one test, and a
            // client built before Http::fake() would never see the fake.
            return new Smartbill(fn (): PendingRequest => Http::withBasicAuth($config['api_username'], $config['api_token'])
                ->baseUrl($config['api_url'])
                ->timeout($config['timeout'])
                ->acceptJson());
        });
    }
}
