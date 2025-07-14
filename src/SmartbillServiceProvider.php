<?php

namespace AndreiLungeanu\Smartbill;

use Illuminate\Support\Facades\Http;
use InvalidArgumentException;
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
        $this->app->singleton(Smartbill::class, function ($app) {
            $config = $app['config']['smartbill'];

            if (empty($config['api_username'])) {
                throw new InvalidArgumentException('Smartbill API Username is not configured. Please check your .env file.');
            }

            if (empty($config['api_token'])) {
                throw new InvalidArgumentException('Smartbill API token is not configured. Please check your .env file.');
            }

            $client = Http::withBasicAuth($config['api_username'], $config['api_token'])
                ->baseUrl($config['api_url'])
                ->acceptJson();

            return new Smartbill($client);
        });
    }
}
