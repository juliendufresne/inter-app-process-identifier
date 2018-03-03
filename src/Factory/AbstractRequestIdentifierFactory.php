<?php

declare(strict_types=1);

namespace JulienDufresne\InterAppRequestIdentifier\Factory;

use JulienDufresne\InterAppRequestIdentifier\Factory\Generator\UniqueIdGeneratorInterface;

abstract class AbstractRequestIdentifierFactory
{
    /** @var UniqueIdGeneratorInterface */
    protected $uniqueIdentifierGenerator;

    public function __construct(UniqueIdGeneratorInterface $uniqueIdentifierGenerator)
    {
        $this->uniqueIdentifierGenerator = $uniqueIdentifierGenerator;
    }
}
