<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Tests;

use JulienDufresne\RequestId\RequestId;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\RequestId\RequestId
 */
final class RequestIdTest extends TestCase
{
    public function testCreate()
    {
        $object = new RequestId('foo', 'bar', 'baz');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('bar', $object->getParentAppRequestId());
        $this->assertEquals('baz', $object->getRootAppRequestId());
    }

    public function testCreateWithParent()
    {
        $object = new RequestId('foo', 'bar');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('bar', $object->getParentAppRequestId());
        $this->assertEquals('bar', $object->getRootAppRequestId());
    }

    public function testCreateWithDefault()
    {
        $object = new RequestId('foo');

        $this->assertEquals('foo', $object->getCurrentAppRequestId());
        $this->assertEquals('foo', $object->getRootAppRequestId());
        $this->assertNull($object->getParentAppRequestId());
    }
}
