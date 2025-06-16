<?php

namespace AndreiLungeanu\Smartbill;

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
        $this->app->singleton(Smartbill::class, function () {
            return new Smartbill;
        });
    }
}
