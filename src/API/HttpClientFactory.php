<?php

namespace teamiken\Fotoware\API;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;

class HttpClientFactory
{
    public static function createWithMiddleware($middleware, $baseUri): Client
    {
        $stack = HandlerStack::create();
        $stack->push($middleware());

        return new Client([
            'base_uri' => $baseUri,
            'allow_redirects' => true,
            'handler' => $stack
        ]);
    }

    public static function create($baseUri): Client
    {
        return new Client([
            'base_uri' => $baseUri
        ]);
    }

}
