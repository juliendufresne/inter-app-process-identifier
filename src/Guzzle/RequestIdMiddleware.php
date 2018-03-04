<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Guzzle;

use JulienDufresne\RequestId\RequestIdInterface;
use Psr\Http\Message\RequestInterface;

/*final */class RequestIdMiddleware
{
    const DEFAULT_REQUEST_HEADER_NAME_ROOT = 'X-Root-Request-Id';
    const DEFAULT_REQUEST_HEADER_NAME_PARENT = 'X-Parent-Request-Id';

    /** @var RequestIdInterface */
    private $requestIdentifier;
    /** @var string */
    private $parentAppRequestHeaderName;
    /** @var string */
    private $rootAppRequestHeaderName;

    public function __construct(
        RequestIdInterface $requestIdentifier,
        string $rootAppRequestHeaderName = self::DEFAULT_REQUEST_HEADER_NAME_ROOT,
        string $parentAppRequestHeaderName = self::DEFAULT_REQUEST_HEADER_NAME_PARENT
    ) {
        $this->requestIdentifier = $requestIdentifier;
        $this->rootAppRequestHeaderName = $rootAppRequestHeaderName;
        $this->parentAppRequestHeaderName = $parentAppRequestHeaderName;
    }

    public function __invoke(RequestInterface $request)
    {
        $headers = array_filter(
            [
                $this->rootAppRequestHeaderName => $this->requestIdentifier->getRootAppRequestId(),
                $this->parentAppRequestHeaderName => $this->requestIdentifier->getCurrentAppRequestId(),
            ]
        );

        foreach ($headers as $headerName => $headerValue) {
            $request = $request->withHeader($headerName, $headerValue);
        }

        return $request;
    }
}
