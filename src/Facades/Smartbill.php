<?php

namespace AndreiLungeanu\Smartbill\Facades;

use AndreiLungeanu\Smartbill\Endpoints\DocumentEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\EstimatesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\InvoicesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\PaymentsEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\SeriesEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\StocksEndpoint;
use AndreiLungeanu\Smartbill\Endpoints\TaxesEndpoint;
use Illuminate\Support\Facades\Facade;

/**
 * @see \AndreiLungeanu\Smartbill\Smartbill
 *
 * @method static InvoicesEndpoint invoices()
 * @method static EstimatesEndpoint estimates()
 * @method static PaymentsEndpoint payments()
 * @method static TaxesEndpoint taxes()
 * @method static SeriesEndpoint series()
 * @method static StocksEndpoint stocks()
 * @method static DocumentEndpoint document()
 */
class Smartbill extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \AndreiLungeanu\Smartbill\Smartbill::class;
    }
}
