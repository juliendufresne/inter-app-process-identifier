<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Tests\Guzzle;

use GuzzleHttp\HandlerStack;
use JulienDufresne\InterAppRequestIdentifier\Guzzle\ClientFactory;
use JulienDufresne\InterAppRequestIdentifier\Guzzle\RequestIdMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\InterAppRequestIdentifier\Guzzle\ClientFactory
 */
final class ClientFactoryTest extends TestCase
{
    public function testCreate()
    {
        $middlewareMocked = $this->createMock(RequestIdMiddleware::class);

        $object = new ClientFactory($middlewareMocked);

        $result = $object->create();

        $this->assertArrayHasKey('handler', $result->getConfig());
    }

    public function testCreateWithoutHandler()
    {
        $middlewareMocked = $this->createMock(RequestIdMiddleware::class);

        $object = new ClientFactory($middlewareMocked);

        $result = $object->create(['handler' => HandlerStack::create()]);

        $this->assertArrayHasKey('handler', $result->getConfig());
    }
}
