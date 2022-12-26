<?php

namespace FT\RequestResponse\Tests;

use Creativestyle\AppHttpServerMock\Server;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LocalServer extends Server
{
    protected function registerRequestHandlers() {
        $this->registerRequestHandler('GET', '/', function (Request $request) {
            return new Response(json_encode($_SERVER), 200, [
                'Content-Type' => 'application/json'
            ]);
        });

        $this->registerRequestHandler('GET', '/foos/bar', function (Request $request) {
            return new Response(json_encode($_SERVER), 200, [
                'Content-Type' => 'application/json'
            ]);
        });
    }
}
