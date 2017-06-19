<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default App Token
    |--------------------------------------------------------------------------
    |
    | The default app token received from e-conomic when making developer
    | account.
    |
    */
    'appToken' => env('ECONOMIC_APP_TOKEN', ''),

    /*
    |--------------------------------------------------------------------------
    | Default Grant Token
    |--------------------------------------------------------------------------
    |
    | The default gratn token received from e-conomic when linking with
    | developer app.
    |
    */
    'grantToken' => env('ECONOMIC_GRANT_TOKEN', ''),
    /*

    |--------------------------------------------------------------------------
    | Developer app urs
    |--------------------------------------------------------------------------
    |
    | The default app url for getting grantToken
    |
    */

    'devAppUrl' => env('ECONOMIC_REQUEST_URL', ''),
];
