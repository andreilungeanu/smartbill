<?php

namespace AndreiLungeanu\Smartbill\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \AndreiLungeanu\Smartbill\Smartbill
 */
class Smartbill extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AndreiLungeanu\Smartbill\Smartbill::class;
    }
}
