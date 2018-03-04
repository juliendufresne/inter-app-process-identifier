<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Factory\Generator;

use Ramsey\Uuid\Uuid;

/**
 * Generates unique identifier based on the ramsey/uuid lib.
 */
final class RamseyUuidGenerator implements UniqueIdGeneratorInterface
{
    /**
     * Generates an unique identifier each time the function is called.
     *
     * @return string
     */
    public function generateUniqueIdentifier(): string
    {
        if (!class_exists(Uuid::class)) {
            throw new \LogicException(sprintf('%s requires ramsey/uuid library', __CLASS__));
        }

        return Uuid::uuid4()->toString();
    }
}
