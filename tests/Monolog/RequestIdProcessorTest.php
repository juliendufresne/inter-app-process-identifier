<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Tests\Monolog;

use JulienDufresne\RequestId\Monolog\RequestIdProcessor;
use JulienDufresne\RequestId\RequestIdInterface;
use Monolog\Logger;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\RequestId\Monolog\RequestIdProcessor
 */
final class RequestIdProcessorTest extends TestCase
{
    public function testProcessor()
    {
        $values = [
            'current' => 'A',
            'root' => 'B',
            'parent' => 'C',
        ];

        $requestIdentifierMock = $this->createMock(RequestIdInterface::class);
        $requestIdentifierMock->expects(self::once())
                                    ->method('getRootAppRequestId')
                                    ->willReturn($values['root']);
        $requestIdentifierMock->expects(self::once())
                                    ->method('getParentAppRequestId')
                                    ->willReturn($values['parent']);
        $requestIdentifierMock->expects(self::once())
                                    ->method('getCurrentAppRequestId')
                                    ->willReturn($values['current']);

        $processor = new RequestIdProcessor($requestIdentifierMock);
        $record = $processor(
            [
                'message' => 'test',
                'context' => [],
                'level' => Logger::WARNING,
                'level_name' => Logger::getLevelName(Logger::WARNING),
                'channel' => 'test',
                'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
                'extra' => [],
            ]
        );

        $this->assertArrayHasKey('request_id', $record['extra']);
        $this->assertEquals($values['current'], $record['extra']['request_id']['current']);
        $this->assertEquals($values['root'], $record['extra']['request_id']['root']);
        $this->assertEquals($values['parent'], $record['extra']['request_id']['parent']);
    }

    public function testProcessorDoNothingIfNoRequestIdentifier()
    {
        $values = [
            'current' => '',
            'root' => '',
            'parent' => null,
        ];

        $requestIdentifierFacadeMock = $this->createMock(RequestIdInterface::class);
        $requestIdentifierFacadeMock->expects(self::once())
                                    ->method('getRootAppRequestId')
                                    ->willReturn($values['root']);
        $requestIdentifierFacadeMock->expects(self::once())
                                    ->method('getParentAppRequestId')
                                    ->willReturn($values['parent']);
        $requestIdentifierFacadeMock->expects(self::once())
                                    ->method('getCurrentAppRequestId')
                                    ->willReturn($values['current']);

        $processor = new RequestIdProcessor($requestIdentifierFacadeMock);
        $record = $processor(
            [
                'message' => 'test',
                'context' => [],
                'level' => Logger::WARNING,
                'level_name' => Logger::getLevelName(Logger::WARNING),
                'channel' => 'test',
                'datetime' => \DateTime::createFromFormat('U.u', sprintf('%.6F', microtime(true))),
                'extra' => [],
            ]
        );

        $this->assertArrayNotHasKey('request_id', $record['extra']);
    }
}
