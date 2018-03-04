<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Factory;

use JulienDufresne\RequestId\Factory\Generator\UniqueIdGeneratorInterface;

abstract class AbstractRequestIdFactory
{
    /** @var UniqueIdGeneratorInterface */
    protected $uniqueIdentifierGenerator;

    public function __construct(UniqueIdGeneratorInterface $uniqueIdentifierGenerator)
    {
        $this->uniqueIdentifierGenerator = $uniqueIdentifierGenerator;
    }
}
