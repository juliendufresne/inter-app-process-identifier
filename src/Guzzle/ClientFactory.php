<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Guzzle;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;

final class ClientFactory
{
    /** @var RequestIdMiddleware */
    private $middleware;

    public function __construct(RequestIdMiddleware $middleware)
    {
        $this->middleware = $middleware;
    }

    public function create(array $config = []): Client
    {
        $config['handler'] = $this->injectMiddleware($config['handler'] ?? HandlerStack::create());

        return new Client($config);
    }

    private function injectMiddleware(HandlerStack $handler): HandlerStack
    {
        $handler->push(Middleware::mapRequest($this->middleware));

        return $handler;
    }
}
