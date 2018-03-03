<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Factory;

use JulienDufresne\InterAppRequestIdentifier\RequestIdentifier;
use JulienDufresne\InterAppRequestIdentifier\RequestIdentifierInterface;

final class RequestIdFromConsoleFactory extends AbstractRequestIdentifierFactory
{
    public function create(?string $parentRequestId = null, ?string $rootRequestId = null): RequestIdentifierInterface
    {
        $current = $this->uniqueIdentifierGenerator->generateUniqueIdentifier();

        return new RequestIdentifier($current, $parentRequestId, $rootRequestId);
    }
}
