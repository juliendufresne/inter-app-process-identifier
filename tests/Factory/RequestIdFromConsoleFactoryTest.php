<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Tests\Factory;

use JulienDufresne\RequestId\Factory\Generator\UniqueIdGeneratorInterface;
use JulienDufresne\RequestId\Factory\RequestIdFromConsoleFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\RequestId\Factory\RequestIdFromConsoleFactory
 */
final class RequestIdFromConsoleFactoryTest extends TestCase
{
    public function testCreate()
    {
        $generator = $this->createMock(UniqueIdGeneratorInterface::class);
        $generator->expects(self::once())
            ->method('generateUniqueIdentifier')
            ->willReturn('foo');

        $object = new RequestIdFromConsoleFactory($generator);
        $result = $object->create('bar', 'baz');

        $this->assertEquals('foo', $result->getCurrentAppRequestId());
        $this->assertEquals('bar', $result->getParentAppRequestId());
        $this->assertEquals('baz', $result->getRootAppRequestId());
    }
}
