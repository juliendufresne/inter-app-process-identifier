<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Factory;

use JulienDufresne\InterAppRequestIdentifier\Factory\Generator\UniqueIdGeneratorInterface;
use JulienDufresne\InterAppRequestIdentifier\RequestIdentifier;
use JulienDufresne\InterAppRequestIdentifier\RequestIdentifierInterface;

final class RequestIdFromRequestFactory extends AbstractRequestIdentifierFactory
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
     * @return RequestIdentifierInterface
     */
    public function create(array $requestHeaders): RequestIdentifierInterface
    {
        $current = $this->uniqueIdentifierGenerator->generateUniqueIdentifier();
        $requestHeaders = $this->sanitizeHeaderKeys($requestHeaders);

        return new RequestIdentifier(
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
