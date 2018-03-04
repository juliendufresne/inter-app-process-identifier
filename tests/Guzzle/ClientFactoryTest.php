<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Tests\Guzzle;

use GuzzleHttp\HandlerStack;
use JulienDufresne\RequestId\Guzzle\ClientFactory;
use JulienDufresne\RequestId\Guzzle\RequestIdMiddleware;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\RequestId\Guzzle\ClientFactory
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
