<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Factory;

use JulienDufresne\RequestId\RequestId;
use JulienDufresne\RequestId\RequestIdInterface;

final class RequestIdFromConsoleFactory extends AbstractRequestIdFactory
{
    public function create(?string $parentRequestId = null, ?string $rootRequestId = null): RequestIdInterface
    {
        $current = $this->uniqueIdentifierGenerator->generateUniqueIdentifier();

        return new RequestId($current, $parentRequestId, $rootRequestId);
    }
}
