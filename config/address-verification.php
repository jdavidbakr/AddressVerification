<?php

return [
    'intelligentsearch' => [
        'username' => env('INTELLIGENTSEARCH_USERNAME'),
        'password' => env('INTELLIGENTSEARCH_PASSWORD'),
        'base_uri' => 'http://www.intelligentsearch.com/CorrectAddressWS/CorrectAddressWebService.asmx/',
        'uri' => 'wsCorrectA',
    ],
    // cache_time is in days. Set to 0 to not cache our results.
    // This is to reduce the number of requests especially if you submit the same address
    // in a form multiple times.
    'cache_time' => 90,
];
