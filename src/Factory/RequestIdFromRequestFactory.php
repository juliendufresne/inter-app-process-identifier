<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Factory;

use JulienDufresne\RequestId\Factory\Generator\UniqueIdGeneratorInterface;
use JulienDufresne\RequestId\RequestId;
use JulienDufresne\RequestId\RequestIdInterface;

final class RequestIdFromRequestFactory extends AbstractRequestIdFactory
{
    /** @var string */
    private $parentRequestIdHeaderName;
    /** @var string */
    private $rootRequestIdHeaderName;

    public function __construct(
        UniqueIdGeneratorInterface $uniqueIdentifierGenerator,
        string $rootRequestIdHeaderName = 'X-Root-Request-Id',
        string $parentRequestIdHeaderName = 'X-Parent-Request-Id'
    ) {
        parent::__construct($uniqueIdentifierGenerator);
        $this->parentRequestIdHeaderName = $this->sanitizeHeaderKey($parentRequestIdHeaderName);
        $this->rootRequestIdHeaderName = $this->sanitizeHeaderKey($rootRequestIdHeaderName);
    }

    /**
     * @param string[] $requestHeaders list of all your request headers
     *
     * @return RequestIdInterface
     */
    public function create(array $requestHeaders): RequestIdInterface
    {
        $current = $this->uniqueIdentifierGenerator->generateUniqueIdentifier();
        $requestHeaders = $this->sanitizeHeaderKeys($requestHeaders);

        return new RequestId(
            $current,
            $this->extractHeader($requestHeaders, $this->parentRequestIdHeaderName),
            $this->extractHeader($requestHeaders, $this->rootRequestIdHeaderName)
        );
    }

    private function sanitizeHeaderKeys(array $requestHeaders): array
    {
        $newRequestHeaders = [];
        foreach ($requestHeaders as $key => $value) {
            $newRequestHeaders[$this->sanitizeHeaderKey($key)] = $value;
        }

        return $newRequestHeaders;
    }

    private function sanitizeHeaderKey(string $key): string
    {
        // an header is case insensitive
        return mb_strtolower($key);
    }

    private function extractHeader(array $requestHeaders, string $headerName)
    {
        if (!array_key_exists($headerName, $requestHeaders)) {
            return null;
        }

        $values = $requestHeaders[$headerName];

        return is_array($values) ? implode(',', $values) : $values;
    }
}
