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
        $this->parentRequestIdHeaderName = $parentRequestIdHeaderName;
        $this->rootRequestIdHeaderName = $rootRequestIdHeaderName;
    }

    /**
     * @param string[] $requestHeaders list of all your request headers
     *
     * @return RequestIdentifierInterface
     */
    public function create(array $requestHeaders): RequestIdentifierInterface
    {
        $current = $this->uniqueIdentifierGenerator->generateUniqueIdentifier();

        return new RequestIdentifier(
            $current,
            $requestHeaders[$this->parentRequestIdHeaderName] ?? null,
            $requestHeaders[$this->rootRequestIdHeaderName] ?? null
        );
    }
}
