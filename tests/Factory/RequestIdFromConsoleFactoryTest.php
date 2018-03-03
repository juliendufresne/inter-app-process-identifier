<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Tests\Factory;

use JulienDufresne\InterAppRequestIdentifier\Factory\Generator\UniqueIdGeneratorInterface;
use JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromConsoleFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromConsoleFactory
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
