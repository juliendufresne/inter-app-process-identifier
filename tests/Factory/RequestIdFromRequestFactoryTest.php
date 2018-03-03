<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Tests\Factory;

use JulienDufresne\InterAppRequestIdentifier\Factory\Generator\UniqueIdGeneratorInterface;
use JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromRequestFactory;
use PHPUnit\Framework\TestCase;

/**
 * @covers \JulienDufresne\InterAppRequestIdentifier\Factory\RequestIdFromRequestFactory
 */
final class RequestIdFromRequestFactoryTest extends TestCase
{
    /**
     * @dataProvider provideHeaders
     *
     * @param array       $headers
     * @param string      $expectedCurrentRequestId
     * @param string|null $expectedParentRequestId
     * @param string|null $expectedRootRequestId
     *
     * @throws \ReflectionException
     */
    public function testCreate(
        array $headers,
        string $expectedCurrentRequestId,
        ?string $expectedParentRequestId,
        ?string $expectedRootRequestId
    ) {
        $generator = $this->createMock(UniqueIdGeneratorInterface::class);
        $generator->expects(self::once())
                  ->method('generateUniqueIdentifier')
                  ->willReturn($expectedCurrentRequestId);

        $object = new RequestIdFromRequestFactory($generator, 'X-Root-Request-Id', 'X-Parent-Request-Id');
        $result = $object->create($headers);

        $this->assertEquals($expectedCurrentRequestId, $result->getCurrentAppRequestId());
        $this->assertEquals($expectedParentRequestId, $result->getParentAppRequestId());
        $this->assertEquals($expectedRootRequestId, $result->getRootAppRequestId());
    }

    public function provideHeaders()
    {
        return [
            'nothing in headers' => [
                'headers' => [
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'foo',
                null,
                'foo', // this should be the same as the second argument
            ],
            'only parent header is found' => [
                'headers' => [
                    'X-Parent-Request-Id' => 'baz',
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'foo',
                'baz',
                'baz',
            ],
            'only root header is found' => [
                'headers' => [
                    'X-Root-Request-Id' => 'baz',
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'foo',
                'baz',
                'baz',
            ],
            'both header are found' => [
                'headers' => [
                    'X-Parent-Request-Id' => 'bar',
                    'X-Root-Request-Id' => 'baz',
                    'Accept' => '*/*',
                    'Accept-Language' => 'en-us',
                    'Accept-Encoding' => 'gzip, deflate',
                    'User-Agent' => 'Mozilla/4.0',
                    'Host' => 'www.example.com',
                    'Connection' => 'Keep-Alive',
                ],
                'foo',
                'bar',
                'baz',
            ],
        ];
    }
}
