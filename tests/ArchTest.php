<?php

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch('endpoints extend BaseEndpoint')
    ->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->classes()
    ->toExtend('AndreiLungeanu\Smartbill\Endpoints\BaseEndpoint')
    ->ignoring('AndreiLungeanu\Smartbill\Endpoints\BaseEndpoint');

arch('endpoints use the Endpoint suffix')
    ->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->classes()
    ->toHaveSuffix('Endpoint');

arch('endpoints do not use the Http facade')
    ->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->not->toUse(['Illuminate\Support\Facades\Http', 'curl_exec', 'file_get_contents']);

arch('facades extend Laravel Facade base class')
    ->expect('AndreiLungeanu\Smartbill\Facades')
    ->classes()
    ->toExtend('Illuminate\Support\Facades\Facade');
