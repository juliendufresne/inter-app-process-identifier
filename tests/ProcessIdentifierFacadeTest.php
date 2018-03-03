<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Tests;

use JulienDufresne\InterAppRequestIdentifier\RequestIdentifierFacade;
use JulienDufresne\InterAppRequestIdentifier\RequestIdentifierInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\InterAppRequestIdentifier\RequestIdentifierFacade
 */
final class ProcessIdentifierFacadeTest extends TestCase
{
    /** @var RequestIdentifierInterface|MockObject */
    private $requestIdentifierMock;

    public function setUp()/* The :void return type declaration that should be here would cause a BC issue */
    {
        parent::setUp();

        $this->requestIdentifierMock = $this->createMock(RequestIdentifierInterface::class);
        $this->requestIdentifierMock->expects(self::any())
                              ->method('getRootAppRequestId')
                              ->willReturn('A');
        $this->requestIdentifierMock->expects(self::any())
                              ->method('getParentAppRequestId')
                              ->willReturn('B');
        $this->requestIdentifierMock->expects(self::any())
                              ->method('getCurrentAppRequestId')
                              ->willReturn('C');
    }

    public function testCreateEmpty()
    {
        $object = new RequestIdentifierFacade();

        $this->assertEquals('', $object->getRootAppRequestId());
        $this->assertNull($object->getParentAppRequestId());
        $this->assertEquals('', $object->getCurrentAppRequestId());
    }

    public function testCreateWithRequestIdentifier()
    {
        $object = new RequestIdentifierFacade($this->requestIdentifierMock);

        $this->assertEquals('A', $object->getRootAppRequestId());
        $this->assertEquals('B', $object->getParentAppRequestId());
        $this->assertEquals('C', $object->getCurrentAppRequestId());
    }

    public function testInitProcessIdentifier()
    {
        $object = new RequestIdentifierFacade();

        $requestIdentifierMock = $this->createMock(RequestIdentifierInterface::class);

        $object->initRequestIdentifier($requestIdentifierMock);

        $this->expectException(\LogicException::class);
        $object->initRequestIdentifier($requestIdentifierMock);
    }
}
