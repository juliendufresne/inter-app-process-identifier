<?php

declare(strict_types=1);

namespace JulienDufresne\RequestId\Factory\Generator;

/**
 * Interface that your generator must implement to provide a way to generate unique identifier.
 * It is used to generate an unique identifier for the current application execution.
 *
 * The generated string must be unique across every application.
 */
interface UniqueIdGeneratorInterface
{
    /**
     * Generates an unique identifier each time the function is called.
     *
     * @return string
     */
    public function generateUniqueIdentifier(): string;
}
