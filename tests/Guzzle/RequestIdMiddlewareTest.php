<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Tests\Guzzle;

use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Promise\PromiseInterface;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use JulienDufresne\RequestId\Guzzle\RequestIdMiddleware;
use JulienDufresne\RequestId\RequestIdInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;

final class RequestIdMiddlewareTest extends TestCase
{
    /**
     * @dataProvider provider
     *
     * @param string|null $rootHeaderValue
     * @param string|null $parentHeaderValue
     *
     * @throws \ReflectionException
     */
    public function testAddRequestIdentifier(?string $rootHeaderValue, ?string $parentHeaderValue)
    {
        $rootHeaderName = 'X-root';
        $parentHeaderName = 'X-parent';

        $h = new MockHandler(
            [
                function (RequestInterface $request) use ($rootHeaderValue, $rootHeaderName, $parentHeaderName, $parentHeaderValue) {
                    if ($rootHeaderValue) {
                        $this->assertEquals($rootHeaderValue, $request->getHeaderLine($rootHeaderName));
                    } else {
                        $this->assertFalse($request->hasHeader($rootHeaderName));
                    }
                    if ($parentHeaderValue) {
                        $this->assertEquals($parentHeaderValue, $request->getHeaderLine($parentHeaderName));
                    } else {
                        $this->assertFalse($request->hasHeader($parentHeaderName));
                    }

                    return new Response(200);
                },
            ]
        );
        $requestIdentifierMock = $this->createMock(RequestIdInterface::class);
        $requestIdentifierMock->expects(self::once())
                                    ->method('getRootAppRequestId')
                                    ->willReturn($rootHeaderValue);
        $requestIdentifierMock->expects(self::once())
                                    ->method('getCurrentAppRequestId')
                                    ->willReturn($parentHeaderValue);

        $m = new RequestIdMiddleware($requestIdentifierMock, $rootHeaderName, $parentHeaderName);
        $stack = new HandlerStack($h);
        $stack->push(Middleware::mapRequest($m));
        $comp = $stack->resolve();
        /** @var PromiseInterface $p */
        $p = $comp(new Request('GET', 'http://www.google.com'), []);
        $this->assertInstanceOf(PromiseInterface::class, $p);
        $response = $p->wait();
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function provider(): array
    {
        return [
            'no header' => [
                '', '',
            ],
            'parent header' => [
                '', 'foo',
            ],
            'root header' => [
                'foo',
                '',
            ],
            'every headers' => [
                'foo',
                'bar',
            ],
        ];
    }
}
