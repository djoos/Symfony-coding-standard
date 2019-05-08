<?php

return [

    'api_domain' => env('API_DOMAIN', 'api.example.com'),

    'app_id' => env('APP_ID'),

    'client_id' => env('CLIENT_ID'),
    'client_secret' => env('CLIENT_SECRET'),

];

function foo(){
    if ($a === $b) {
        return false;
    } else {
        return true;
    }
}

function bar()
{
    if ($a == $b) {
        return false;
    }

    return true;
}

function baz()
{
    if ($a == $b) {
        $a = function () {
            return false;
        };
    } else {
        return true;
    }
}

function caseFoo(){
    switch ($a === $b) {
    case true:
        return false;
        break;
    case false:
        return true;
    default:
        return null;
    }
}
