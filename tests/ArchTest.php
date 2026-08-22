<?php

arch()->preset()->php();
arch()->preset()->security();
arch()->preset()->laravel();

arch()->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->classes()
    ->toExtend('AndreiLungeanu\Smartbill\Endpoints\BaseEndpoint')
    ->ignoring('AndreiLungeanu\Smartbill\Endpoints\BaseEndpoint');

arch()->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->classes()
    ->toHaveSuffix('Endpoint');

arch()->expect('AndreiLungeanu\Smartbill\Endpoints')
    ->not->toUse(['Illuminate\Support\Facades\Http', 'curl_exec', 'file_get_contents']);

arch()->expect('AndreiLungeanu\Smartbill\Facades')
    ->classes()
    ->toExtend('Illuminate\Support\Facades\Facade');
