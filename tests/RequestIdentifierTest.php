<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Tests;

use JulienDufresne\InterAppRequestIdentifier\RequestIdentifier;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\InterAppRequestIdentifier\RequestIdentifier
 */
final class RequestIdentifierTest extends TestCase
{
    public function testCreate()
    {
        $object = new RequestIdentifier('foo', 'bar', 'baz');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('bar', $object->getParentAppRequestId());
        $this->assertEquals('baz', $object->getRootAppRequestId());
    }

    public function testCreateWithParent()
    {
        $object = new RequestIdentifier('foo', 'bar');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('bar', $object->getParentAppRequestId());
        $this->assertEquals('bar', $object->getRootAppRequestId());
    }

    public function testCreateWithDefault()
    {
        $object = new RequestIdentifier('foo');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('foo', $object->getRootAppRequestId());
        $this->assertNull($object->getParentAppRequestId());
    }
}
