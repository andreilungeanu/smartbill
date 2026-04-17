<?php

// config for AndreiLungeanu/Smartbill
return [
    'api_username' => env('SMARTBILL_API_USERNAME', ''),
    'api_token' => env('SMARTBILL_API_TOKEN', ''),
    'api_url' => env('SMARTBILL_API_URL', 'https://ws.smartbill.ro/SBORO/api'),
    'timeout' => (int) env('SMARTBILL_TIMEOUT', 30),
];
